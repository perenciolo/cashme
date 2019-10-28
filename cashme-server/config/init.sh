#!/bin/bash

# Install Drupal.
cd $LANDO_MOUNT
if [ -d 'contentacms' ]; then
    echo "Web folder already exists. No git clone executed."
    FIRST_RUN=0
else
    # Do a git checkout of the current D8 core.
    echo "Cloning drupal core."

    php -r "readfile('https://raw.githubusercontent.com/contentacms/contenta_jsonapi_project/8.x-1.x/scripts/download.sh');" > download-contentacms.sh
    chmod a+x download-contentacms.sh
    ./download-contentacms.sh $LANDO_MOUNT/contentacms
    FIRST_RUN=1
fi

echo "Composer installing drupal core."
cd $LANDO_MOUNT/contentacms
composer install

if [ $FIRST_RUN ]; then
    cd $LANDO_MOUNT/contentacms
    # Upgrade PHPUnit to work with PHP 7, add drush, console, selenium
    composer require --update-with-all-dependencies "phpunit/phpunit ^6.0" "drush/drush" "joomla-projects/selenium-server-standalone"
fi

# Create file dall-irs.
echo "Creating dirs and symlinks."
cd $LANDO_MOUNT/contentacms
mkdir -p -m 777 web/sites/default/files
mkdir -p -m 777 web/sites/default/files/phpunit
mkdir -p -m 777 web/sites/simpletest
mkdir -p -m 777 files/private
mkdir -p -m 777 files/tmp
mkdir -p -m 777 files/sync
rm $LANDO_MOUNT/contentacms/web/sites/default/settings.php

# Symlink the settings and public file dir.
if [ ! -e "$LANDO_MOUNT/contentacms/web/sites/default/settings.php" ]; then
    ln -s $LANDO_MOUNT/config/sites.default.settings.php $LANDO_MOUNT/contentacms/web/sites/default/settings.php
fi
if [ ! -L "$LANDO_MOUNT/files/public" ]; then
    ln -s $LANDO_APP_ROOT_BIND/contentacms/web/sites/default/files $LANDO_MOUNT/contentacms/files/public
fi
if [ ! -L "$LANDO_MOUNT/files/simpletest" ]; then
    ln -s $LANDO_APP_ROOT_BIND/contentacms/web/sites/simpletest $LANDO_MOUNT/contentacms/files/simpletest
fi

if [ $FIRST_RUN ]; then
    echo "Installing default site."
    cd $LANDO_MOUNT/contentacms
    #cp .env.example .env
    cp $LANDO_MOUNT/config/.env.local $LANDO_MOUNT/contentacms/.env.local
    cp -Rv $LANDO_MOUNT/contentacms/web/profiles/contrib/contenta_jsonapi/config/sync/* $LANDO_MOUNT/contentacms/files/sync
    composer run-script install:with-mysql
fi

if [ ! -f $LANDO_MOUNT/contentacms/web/.gitignore ]; then
    # Ignore changed core files
    echo "composer.json
composer.lock
vendor
sites/default/settings.php
sites/default/files
sites/simpletest
" > $LANDO_MOUNT/contentacms/web/.gitignore
fi

# Create phpunit.xml and configure.
if [ ! -f $LANDO_MOUNT/contentacms/web/core/phpunit.xml ]; then
    echo 'Creating phpunit.xml.'
    cd $LANDO_MOUNT/contentacms/web/core
    cp phpunit.xml.dist phpunit.xml
    sed -i 's/SIMPLETEST_DB" value=""/SIMPLETEST_DB" value="sqlite:\/\/localhost\/\'$LANDO_MOUNT'\/contentacms\/web\/sites\/default\/files\/test.sqlite"/' phpunit.xml
    sed -i 's/SIMPLETEST_BASE_URL" value=""/SIMPLETEST_BASE_URL" value="http:\/\/\'$LANDO_APP_NAME'.'$LANDO_DOMAIN'"/' phpunit.xml
    sed -i 's/BROWSERTEST_OUTPUT_DIRECTORY" value=""/BROWSERTEST_OUTPUT_DIRECTORY" value="\'$LANDO_MOUNT'\/contentacms\/web\/sites\/default\/files\/phpunit"/' phpunit.xml
    sed -i 's/beStrictAboutOutputDuringTests="true"/beStrictAboutOutputDuringTests="false" verbose="true" printerClass="\Drupal\Tests\Listeners\HtmlOutputPrinter"/' phpunit.xml
    sed -i 's/<\/phpunit>/<logging><log type="testdox-text" target="\'$LANDO_MOUNT'\/contentacms\/web\/sites\/default\/files\/testdox.txt"\/><\/logging><\/phpunit>/' phpunit.xml
fi
