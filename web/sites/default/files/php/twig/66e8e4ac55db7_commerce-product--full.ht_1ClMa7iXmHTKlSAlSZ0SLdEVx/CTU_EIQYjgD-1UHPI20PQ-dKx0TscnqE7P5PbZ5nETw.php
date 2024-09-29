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

/* themes/contrib/belgrade/templates/commerce/product/commerce-product--full.html.twig */
class __TwigTemplate_0097c1314a983ba81c79586b130987ae extends Template
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
        // line 22
        yield "
";
        // line 24
        $context["classes"] = ["pb-4", "mb-5", "overflow-hidden"];
        // line 30
        yield "
<article";
        // line 31
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 31), 31, $this->source), "html", null, true);
        yield ">
  <div class=\"row g-5\">
    <div class=\"col-md-7 order-md-last\">
      <div class=\"mb-2\">";
        // line 35
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "product_categories", [], "any", false, false, true, 35), 35, $this->source), "html", null, true);
        // line 36
        yield "</div>";
        // line 37
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "title", [], "any", false, false, true, 37), 37, $this->source), "html", null, true);
        // line 38
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "brand", [], "any", false, false, true, 38), 38, $this->source), "html", null, true);
        // line 39
        yield "<div class=\"fs-4\">";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "variation_price", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
        yield "</div>
      <hr/>
      ";
        // line 41
        if ( !Twig\Extension\CoreExtension::testEmpty($this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "special_categories", [], "any", false, false, true, 41)))) {
            // line 42
            yield "        <div class=\"mb-3\">";
            // line 43
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "special_categories", [], "any", false, false, true, 43), 43, $this->source), "html", null, true);
            // line 44
            yield "</div>
      ";
        }
        // line 46
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["product"] ?? null), 46, $this->source), "variation_attributes", "images", "variation_images", "title", "variation_price", "product_categories", "brand", "special_categories"), "html", null, true);
        // line 47
        yield "</div>
    <div class=\"col-md-5\">
      ";
        // line 49
        if ( !Twig\Extension\CoreExtension::testEmpty($this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "variation_images", [], "any", false, false, true, 49)))) {
            // line 50
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "variation_images", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
        } else {
            // line 52
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["product"] ?? null), "images", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
        }
        // line 54
        yield "    </div>
  </div>

</article>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "product"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/commerce/product/commerce-product--full.html.twig";
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
        return array (  96 => 54,  93 => 52,  90 => 50,  88 => 49,  84 => 47,  82 => 46,  78 => 44,  76 => 43,  74 => 42,  72 => 41,  66 => 39,  64 => 38,  62 => 37,  60 => 36,  58 => 35,  52 => 31,  49 => 30,  47 => 24,  44 => 22,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/commerce/product/commerce-product--full.html.twig", "/Applications/MAMP/htdocs/kickstart/web/themes/contrib/belgrade/templates/commerce/product/commerce-product--full.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 24, "if" => 41);
        static $filters = array("escape" => 31, "render" => 41, "without" => 46);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape', 'render', 'without'],
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
