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

/* themes/contrib/belgrade/templates/navigation/menu.html.twig */
class __TwigTemplate_78e34bb2bb2c5407c393947ed1bccf04 extends Template
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
        // line 21
        $macros["menus"] = $this->macros["menus"] = $this;
        // line 22
        yield "
";
        // line 27
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(CoreExtension::callMacro($macros["menus"], "macro_menu_links", [($context["items"] ?? null), ($context["attributes"] ?? null), 0], 27, $context, $this->getSourceContext()));
        yield "

";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["_self", "items", "attributes", "menu_level"]);        yield from [];
    }

    // line 29
    public function macro_menu_links($__items__ = null, $__attributes__ = null, $__menu_level__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = [
            "items" => $__items__,
            "attributes" => $__attributes__,
            "menu_level" => $__menu_level__,
            "varargs" => $__varargs__,
        ] + $this->env->getGlobals();

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 30
            yield "  ";
            $macros["menus"] = $this;
            // line 31
            yield "  ";
            if (($context["items"] ?? null)) {
                // line 32
                yield "    ";
                if ((($context["menu_level"] ?? null) == 0)) {
                    // line 33
                    yield "      <ul";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", ["nav"], "method", false, false, true, 33), 33, $this->source), "html", null, true);
                    yield ">
    ";
                } else {
                    // line 35
                    yield "      <ul class=\"dropdown-menu\">
    ";
                }
                // line 37
                yield "    ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
                $context['_iterated'] = false;
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 38
                    yield "      ";
                    // line 39
                    $context["classes"] = ["nav-item", ((CoreExtension::getAttribute($this->env, $this->source,                     // line 41
$context["item"], "below", [], "any", false, false, true, 41)) ? ("dropdown") : ("")), ((CoreExtension::getAttribute($this->env, $this->source,                     // line 42
$context["item"], "is_expanded", [], "any", false, false, true, 42)) ? ("nav-item--expanded") : ("")), ((CoreExtension::getAttribute($this->env, $this->source,                     // line 43
$context["item"], "is_collapsed", [], "any", false, false, true, 43)) ? ("nav-item--collapsed") : ("")), ((CoreExtension::getAttribute($this->env, $this->source,                     // line 44
$context["item"], "in_active_trail", [], "any", false, false, true, 44)) ? ("nav-item--active-trail") : (""))];
                    // line 47
                    yield "      ";
                    // line 48
                    $context["link_classes"] = ["nav-link", ((CoreExtension::getAttribute($this->env, $this->source,                     // line 50
$context["item"], "in_active_trail", [], "any", false, false, true, 50)) ? ("active") : ("")), ((CoreExtension::getAttribute($this->env, $this->source,                     // line 51
$context["item"], "below", [], "any", false, false, true, 51)) ? ("dropdown-toggle") : (""))];
                    // line 54
                    yield "      <li";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 54), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 54), 54, $this->source), "html", null, true);
                    yield ">
        ";
                    // line 55
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 55)) {
                        // line 56
                        yield "          ";
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 56), 56, $this->source), $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 56), 56, $this->source), ["class" => ($context["link_classes"] ?? null), "data-toggle" => "dropdown", "title" => ((t("Expand menu") . " ") . $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 56), 56, $this->source))]), "html", null, true);
                        yield "
          ";
                        // line 57
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(CoreExtension::callMacro($macros["menus"], "macro_menu_links", [CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 57), ($context["attributes"] ?? null), (($context["menu_level"] ?? null) + 1)], 57, $context, $this->getSourceContext()));
                        yield "
        ";
                    } else {
                        // line 59
                        yield "          ";
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 59), 59, $this->source), $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 59), 59, $this->source), $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 59), "removeClass", [($context["classes"] ?? null)], "method", false, false, true, 59), "addClass", [($context["link_classes"] ?? null)], "method", false, false, true, 59), 59, $this->source)), "html", null, true);
                        yield "
        ";
                    }
                    // line 61
                    yield "    ";
                    $context['_iterated'] = true;
                }
                if (!$context['_iterated']) {
                    // line 62
                    yield "      </li>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['_iterated']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 64
                yield "    </ul>
  ";
            }
            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/navigation/menu.html.twig";
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
        return array (  146 => 64,  139 => 62,  134 => 61,  128 => 59,  123 => 57,  118 => 56,  116 => 55,  111 => 54,  109 => 51,  108 => 50,  107 => 48,  105 => 47,  103 => 44,  102 => 43,  101 => 42,  100 => 41,  99 => 39,  97 => 38,  91 => 37,  87 => 35,  81 => 33,  78 => 32,  75 => 31,  72 => 30,  58 => 29,  49 => 27,  46 => 22,  44 => 21,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/navigation/menu.html.twig", "/Applications/MAMP/htdocs/kickstart/web/themes/contrib/belgrade/templates/navigation/menu.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 21, "macro" => 29, "if" => 31, "for" => 37, "set" => 39);
        static $filters = array("escape" => 33, "t" => 56);
        static $functions = array("link" => 56);

        try {
            $this->sandbox->checkSecurity(
                ['import', 'macro', 'if', 'for', 'set'],
                ['escape', 't'],
                ['link'],
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
