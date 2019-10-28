<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/contrib/material_admin/templates/layout/page--portal-login.html.twig */
class __TwigTemplate_89e23ee7c2bd4ec841ffef5f462457fcf422a8234641ba58c32f8a81e6fac8be extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["include" => 31, "if" => 36];
        $filters = ["escape" => 45];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['include', 'if'],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 30
        echo "<div class=\"layout-container\">
  ";
        // line 31
        $this->loadTemplate("@material_admin/misc/notification-drawer.html.twig", "themes/contrib/material_admin/templates/layout/page--portal-login.html.twig", 31)->display($context);
        // line 32
        echo "  <header role=\"banner\">
    <div class=\"row material-container\">
      <div class=\"s12 col portal-login-notifications\">
      ";
        // line 35
        $this->loadTemplate("@material_admin/misc/notification-trigger.html.twig", "themes/contrib/material_admin/templates/layout/page--portal-login.html.twig", 35)->display($context);
        // line 36
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "status", [])) {
            // line 37
            echo "        </div>
      ";
        }
        // line 39
        echo "    </div>
  </header>
  <main role=\"main-wrapper\">
    <div class=\"row material-container fixed-width-row\">
      <div class=\"s12 m8 l6 push-l3 push-m2 col\">
        <div class=\"login-header center grey-text\">
          ";
        // line 45
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "header", [])), "html", null, true);
        echo "
          </div>
     <div class=\"card white\">
        <div class=\"card-content\">
          <a id=\"main-content\" tabindex=\"-1\"></a>";
        // line 50
        echo "           <span class=\"card-title grey-text blue text-darken-4\"></span>
          <p> ";
        // line 51
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "content", [])), "html", null, true);
        echo "</p>
        </div>
      </div>
    </div>
    </div>
  </main>
  ";
        // line 57
        if ($this->getAttribute(($context["page"] ?? null), "footer", [])) {
            // line 58
            echo "  <footer role=\"contentinfo\">
    <div clas=\"row material-container\">
      ";
            // line 60
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer", [])), "html", null, true);
            echo "
    </div>
  </footer>
  ";
        }
        // line 64
        echo "</div>";
        // line 65
        echo "
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/material_admin/templates/layout/page--portal-login.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 65,  114 => 64,  107 => 60,  103 => 58,  101 => 57,  92 => 51,  89 => 50,  82 => 45,  74 => 39,  70 => 37,  67 => 36,  65 => 35,  60 => 32,  58 => 31,  55 => 30,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/material_admin/templates/layout/page--portal-login.html.twig", "/app/web/themes/contrib/material_admin/templates/layout/page--portal-login.html.twig");
    }
}
