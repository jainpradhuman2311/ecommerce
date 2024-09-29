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

/* themes/contrib/belgrade/templates/commerce/commerce-coupon-redemption-form.html.twig */
class __TwigTemplate_7862d984af2c6319f8baaeb2bb7477ba extends Template
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
        // line 14
        if (Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "coupons", [], "any", false, false, true, 14))) {
            // line 15
            yield "  ";
            if (((($__internal_compile_0 = ($context["form"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["#cardinality"] ?? null) : null) == 1)) {
                // line 16
                yield "    <div class=\"coupon-redemption-form__coupons coupon-redemption-form__coupons--single\">
      <p>
        <br>";
                // line 18
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("You applied the coupon %code to the order.", ["%code" => $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "coupons", [], "any", false, false, true, 18), 0, [], "any", false, false, true, 18), "code", [], "any", false, false, true, 18), 18, $this->source))]));
                yield "
      </p>
      ";
                // line 20
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "coupons", [], "any", false, false, true, 20), 0, [], "any", false, false, true, 20), "remove_button", [], "any", false, false, true, 20), 20, $this->source), "html", null, true);
                yield "
    </div>
  ";
            } else {
                // line 23
                yield "    <div class=\"coupon-redemption-form__coupons coupon-redemption-form__coupons--multiple\">
      <h5 class=\"mb-3\"> ";
                // line 24
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Applied coupons"));
                yield " </h5>
      <table class=\"table bg-white\">
        ";
                // line 26
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "coupons", [], "any", false, false, true, 26));
                foreach ($context['_seq'] as $context["key"] => $context["coupon"]) {
                    // line 27
                    yield "        ";
                    if ((Twig\Extension\CoreExtension::first($this->env->getCharset(), $context["key"]) != "#")) {
                        // line 28
                        yield "          <tr>
            <td class=\"w-100\"> ";
                        // line 29
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["coupon"], "code", [], "any", false, false, true, 29), 29, $this->source), "html", null, true);
                        yield " </td>
            <td> ";
                        // line 30
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["coupon"], "remove_button", [], "any", false, false, true, 30), 30, $this->source), "html", null, true);
                        yield " </td>
          </tr>
        ";
                    }
                    // line 33
                    yield "        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['key'], $context['coupon'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 34
                yield "      </table>
    </div>
  ";
            }
        }
        // line 38
        yield "
";
        // line 39
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["form"] ?? null), 39, $this->source), "coupons"), "html", null, true);
        yield "
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["form"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/commerce/commerce-coupon-redemption-form.html.twig";
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
        return array (  107 => 39,  104 => 38,  98 => 34,  92 => 33,  86 => 30,  82 => 29,  79 => 28,  76 => 27,  72 => 26,  67 => 24,  64 => 23,  58 => 20,  53 => 18,  49 => 16,  46 => 15,  44 => 14,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/commerce/commerce-coupon-redemption-form.html.twig", "/Applications/MAMP/htdocs/kickstart/web/themes/contrib/belgrade/templates/commerce/commerce-coupon-redemption-form.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 14, "for" => 26);
        static $filters = array("length" => 14, "t" => 18, "render" => 18, "escape" => 20, "first" => 27, "without" => 39);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['length', 't', 'render', 'escape', 'first', 'without'],
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
