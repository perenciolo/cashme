# Create file dall-irs.
echo "Creating dirs and symlinks."
cd $LANDO_MOUNT
mkdir -p -m 777 web/sites/default/files
mkdir -p -m 777 web/sites/default/files/phpunit
mkdir -p -m 777 web/sites/simpletest
mkdir -p -m 777 files/private
mkdir -p -m 777 files/tmp
mkdir -p -m 777 files/sync
rm $LANDO_MOUNT/web/sites/default/settings.php

# Symlink the settings and public file dir.
if [ ! -e "$LANDO_MOUNT/web/sites/default/settings.php" ]; then
    ln -s $LANDO_MOUNT/config/sites.default.settings.php $LANDO_MOUNT/web/sites/default/settings.php
fi
if [ ! -L "$LANDO_MOUNT/files/public" ]; then
    ln -s $LANDO_APP_ROOT_BIND/web/sites/default/files $LANDO_MOUNT/files/public
fi
if [ ! -L "$LANDO_MOUNT/files/simpletest" ]; then
    ln -s $LANDO_APP_ROOT_BIND/web/sites/simpletest $LANDO_MOUNT/files/simpletest
fi

cp -Rv $LANDO_MOUNT/web/profiles/contrib/contenta_jsonapi/config/sync/* $LANDO_MOUNT/files/sync