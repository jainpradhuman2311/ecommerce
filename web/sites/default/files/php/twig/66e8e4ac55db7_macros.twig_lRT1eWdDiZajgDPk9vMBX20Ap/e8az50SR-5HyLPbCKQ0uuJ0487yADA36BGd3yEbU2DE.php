<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* @belgrade/macros.twig */
class __TwigTemplate_51b1e2c4a333b4f8d79b965e9e7b4bff extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 2
        yield "
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["icon", "classes", "width", "height", "path", "desc"]);        yield from [];
    }

    // line 3
    public function macro_getIcon($__icon__ = null, $__width__ = null, $__height__ = null, $__classes__ = null, $__path__ = null, $__title__ = null, $__desc__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = [
            "icon" => $__icon__,
            "width" => $__width__,
            "height" => $__height__,
            "classes" => $__classes__,
            "path" => $__path__,
            "title" => $__title__,
            "desc" => $__desc__,
            "varargs" => $__varargs__,
        ] + $this->env->getGlobals();

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 4
            yield "
  ";
            // line 5
            $context["title"] = ((($context["title"] ?? null)) ? (($context["title"] ?? null)) : (Twig\Extension\CoreExtension::capitalize($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 5, $this->source))));
            // line 6
            yield "
  ";
            // line 12
            yield "
  <svg class=\"beo beo-";
            // line 13
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 13, $this->source), "html", null, true);
            yield " ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["classes"] ?? null), 13, $this->source), "html", null, true);
            yield "\"
       width=\"";
            // line 14
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((array_key_exists("width", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["width"] ?? null), 14, $this->source), 16)) : (16)), "html", null, true);
            yield "\"
       height=\"";
            // line 15
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((array_key_exists("height", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["height"] ?? null), 15, $this->source), 16)) : (16)), "html", null, true);
            yield "\"
       fill=\"currentColor\"
       aria-hidden=\"true\"
       viewBox=\"0 0 16 16\"
       role=\"img\">
    <title>";
            // line 20
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 20, $this->source), "html", null, true);
            yield "</title>

    ";
            // line 25
            yield "    <use xlink:href=\"";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((array_key_exists("path", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["path"] ?? null), 25, $this->source), ((("/" . $this->extensions['Drupal\Core\Template\TwigExtension']->getActiveThemePath()) . "/images/belgrade-icons.svg#") . $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 25, $this->source)))) : (((("/" . $this->extensions['Drupal\Core\Template\TwigExtension']->getActiveThemePath()) . "/images/belgrade-icons.svg#") . $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 25, $this->source)))), "html", null, true);
            yield "\"/>

    ";
            // line 31
            yield "
    ";
            // line 32
            if (($context["desc"] ?? null)) {
                // line 33
                yield "      <desc>";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["desc"] ?? null), 33, $this->source), "html", null, true);
                yield "</desc>
    ";
            }
            // line 35
            yield "  </svg>

";
            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@belgrade/macros.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  120 => 35,  114 => 33,  112 => 32,  109 => 31,  103 => 25,  98 => 20,  90 => 15,  86 => 14,  80 => 13,  77 => 12,  74 => 6,  72 => 5,  69 => 4,  51 => 3,  44 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "@belgrade/macros.twig", "/Applications/MAMP/htdocs/kickstart/web/themes/contrib/belgrade/templates/macros.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("macro" => 3, "set" => 5, "if" => 32);
        static $filters = array("capitalize" => 5, "escape" => 13, "default" => 14);
        static $functions = array("active_theme_path" => 25);

        try {
            $this->sandbox->checkSecurity(
                ['macro', 'set', 'if'],
                ['capitalize', 'escape', 'default'],
                ['active_theme_path'],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

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
}
