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

/* themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-progress.html.twig */
class __TwigTemplate_8c758e5459d724a124d65e4d0631a136 extends Template
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
        // line 1
        $macros["svg"] = $this->macros["svg"] = $this->loadTemplate("@belgrade/macros.twig", "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-progress.html.twig", 1)->unwrap();
        // line 2
        yield "
";
        // line 17
        yield "<div class=\"checkout-progress row justify-content-center text-align-center text-primary my-4\">
  ";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["steps"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["step"]) {
            // line 19
            yield "
    ";
            // line 20
            $context["stepDefaultIcon"] = "basket";
            // line 21
            yield "
    ";
            // line 22
            $context["stepIcon"] = (((CoreExtension::getAttribute($this->env, $this->source,             // line 23
$context["step"], "id", [], "any", false, false, true, 23) == "login")) ? ("info-circle") : ((((CoreExtension::getAttribute($this->env, $this->source,             // line 24
$context["step"], "id", [], "any", false, false, true, 24) == "order_information")) ? ("clipboard-data") : ((((CoreExtension::getAttribute($this->env, $this->source,             // line 25
$context["step"], "id", [], "any", false, false, true, 25) == "review")) ? ("clipboard-check") : ((((CoreExtension::getAttribute($this->env, $this->source,             // line 26
$context["step"], "id", [], "any", false, false, true, 26) == "complete")) ? ("gift") : (($context["stepDefaultIcon"] ?? null)))))))));
            // line 28
            yield "
    <div class=\"checkout-progress--step checkout-progress--step__";
            // line 29
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["step"], "position", [], "any", false, false, true, 29), 29, $this->source), "html", null, true);
            yield " position-relative col col-md-3 col-lg-2 mb-3\">
      <div class=\"mb-2 pb-1 position-relative\">
        ";
            // line 31
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(CoreExtension::callMacro($macros["svg"], "macro_getIcon", [($context["stepIcon"] ?? null), 42, 42], 31, $context, $this->getSourceContext()));
            yield "
        ";
            // line 32
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["step"], "position", [], "any", false, false, true, 32) == "previous")) {
                // line 33
                yield "          ";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(CoreExtension::callMacro($macros["svg"], "macro_getIcon", ["check-circle-fill", 16, 16, "position-absolute bottom-0"], 33, $context, $this->getSourceContext()));
                yield "
        ";
            }
            // line 35
            yield "      </div>
      <div class=\"d-none d-sm-block fw-bold\">";
            // line 37
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["step"], "label", [], "any", false, false, true, 37), 37, $this->source), "html", null, true);
            // line 38
            yield "</div>
    </div>

  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['step'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["steps"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-progress.html.twig";
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
        return array (  104 => 42,  95 => 38,  93 => 37,  90 => 35,  84 => 33,  82 => 32,  78 => 31,  73 => 29,  70 => 28,  68 => 26,  67 => 25,  66 => 24,  65 => 23,  64 => 22,  61 => 21,  59 => 20,  56 => 19,  52 => 18,  49 => 17,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-progress.html.twig", "/Applications/MAMP/htdocs/kickstart/web/themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-progress.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 1, "for" => 18, "set" => 20, "if" => 32);
        static $filters = array("escape" => 29);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['import', 'for', 'set', 'if'],
                ['escape'],
                [],
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
