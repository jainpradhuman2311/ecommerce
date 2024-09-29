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

/* themes/contrib/belgrade/templates/commerce/commerce-order-total-summary.html.twig */
class __TwigTemplate_c66276d955e271c93b24d3e8114d6da9 extends Template
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
        yield "
<div";
        // line 22
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", ["text-align-right"], "method", false, false, true, 22), 22, $this->source), "html", null, true);
        yield ">
  <div class=\"order-total-line order-total-line__subtotal\">
    <span class=\"order-total-line-label\">";
        // line 24
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Subtotal:"));
        yield " </span><span class=\"order-total-line-value\">";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["totals"] ?? null), "subtotal", [], "any", false, false, true, 24), 24, $this->source)), "html", null, true);
        yield "</span>
  </div>
  ";
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["totals"] ?? null), "adjustments", [], "any", false, false, true, 26));
        foreach ($context['_seq'] as $context["_key"] => $context["adjustment"]) {
            // line 27
            yield "    <div class=\"order-total-line order-total-line__adjustment order-total-line__adjustment--";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["adjustment"], "type", [], "any", false, false, true, 27), 27, $this->source)), "html", null, true);
            yield "\">
      <span class=\"order-total-line-label\">";
            // line 28
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["adjustment"], "label", [], "any", false, false, true, 28), 28, $this->source), "html", null, true);
            yield " </span><span class=\"order-total-line-value\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["adjustment"], "amount", [], "any", false, false, true, 28), 28, $this->source)), "html", null, true);
            yield "</span>
    </div>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['adjustment'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        yield "  <div class=\"order-total-line order-total-line__total fw-bold fs-4\">
    <span class=\"order-total-line-label\">";
        // line 32
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Total:"));
        yield " </span><span class=\"order-total-line-value\">";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["totals"] ?? null), "total", [], "any", false, false, true, 32), 32, $this->source)), "html", null, true);
        yield "</span>
  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "totals"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/commerce/commerce-order-total-summary.html.twig";
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
        return array (  82 => 32,  79 => 31,  68 => 28,  63 => 27,  59 => 26,  52 => 24,  47 => 22,  44 => 21,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/commerce/commerce-order-total-summary.html.twig", "/Applications/MAMP/htdocs/kickstart/web/themes/contrib/belgrade/templates/commerce/commerce-order-total-summary.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 26);
        static $filters = array("escape" => 22, "t" => 24, "commerce_price_format" => 24, "clean_class" => 27);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for'],
                ['escape', 't', 'commerce_price_format', 'clean_class'],
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
