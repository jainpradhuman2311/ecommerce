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

/* modules/contrib/commerce/modules/order/templates/commerce-order--admin.html.twig */
class __TwigTemplate_69536efba0eb60a8ef6428809a5032cc extends Template
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
        // line 20
        yield "
";
        // line 21
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("commerce/admin-layout"), "html", null, true);
        yield "
";
        // line 22
        $context["order_state"] = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getState", [], "any", false, false, true, 22), "getLabel", [], "any", false, false, true, 22);
        // line 23
        yield "
<div class=\"layout-order-admin layout-commerce-admin\">
  <div class=\"layout-region layout-region--commerce-main\">
    <div class=\"layout-region__content\">
      ";
        // line 27
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "order_items", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
        yield "
      ";
        // line 28
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "total_price", [], "any", false, false, true, 28), 28, $this->source), "html", null, true);
        yield "

      ";
        // line 30
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "activity", [], "any", false, false, true, 30)) {
            // line 31
            yield "        <h2>";
            yield t("Order activity", array());
            yield "</h2>
        ";
            // line 32
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "activity", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
            yield "
      ";
        }
        // line 34
        yield "    </div>
  </div>
  <div class=\"layout-region layout-region--commerce-secondary\">
    <div class=\"entity-meta\">
      <div class=\"entity-meta__header\">
        <h3 class=\"entity-meta__title\">
          ";
        // line 40
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["order_state"] ?? null), 40, $this->source), "html", null, true);
        yield "
        </h3>
        ";
        // line 42
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(["completed", "placed", "changed"]);
        foreach ($context['_seq'] as $context["_key"] => $context["key"]) {
            // line 43
            yield "          ";
            if ((($__internal_compile_0 = ($context["order"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[$context["key"]] ?? null) : null)) {
                // line 44
                yield "            <div class=\"form-item\">
              ";
                // line 45
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_1 = ($context["order"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[$context["key"]] ?? null) : null), 45, $this->source), "html", null, true);
                yield "
            </div>
          ";
            }
            // line 48
            yield "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['key'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        yield "        ";
        if (((($context["stores_count"] ?? null) > 1) && CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "store_id", [], "any", false, false, true, 49))) {
            // line 50
            yield "          <div class=\"form-item\">
            ";
            // line 51
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "store_id", [], "any", false, false, true, 51), 51, $this->source), "html", null, true);
            yield "
          </div>
        ";
        }
        // line 54
        yield "        ";
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "balance", [], "any", false, false, true, 54)) {
            // line 55
            yield "          <div class=\"form-item\">
            ";
            // line 56
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "balance", [], "any", false, false, true, 56), 56, $this->source), "html", null, true);
            yield "
          </div>
        ";
        }
        // line 59
        yield "        ";
        // line 60
        yield "        ";
        if ( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getState", [], "any", false, false, true, 60), "getTransitions", [], "any", false, false, true, 60))) {
            // line 61
            yield "          ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "state", [], "any", false, false, true, 61), 61, $this->source), "html", null, true);
            yield "
        ";
        }
        // line 63
        yield "      </div>
      <details open class=\"claro-details\">
        <summary role=\"button\" class=\"claro-details__summary\">
          ";
        // line 66
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Customer Information"));
        yield "
        </summary>
        <div class=\"details-wrapper claro-details__wrapper\">
          ";
        // line 69
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(["uid", "mail", "ip_address"]);
        foreach ($context['_seq'] as $context["_key"] => $context["key"]) {
            // line 70
            yield "            ";
            if ((($__internal_compile_2 = ($context["order"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2[$context["key"]] ?? null) : null)) {
                // line 71
                yield "              <div class=\"form-item\">
                ";
                // line 72
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_3 = ($context["order"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3[$context["key"]] ?? null) : null), 72, $this->source), "html", null, true);
                yield "
              </div>
            ";
            }
            // line 75
            yield "          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['key'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 76
        yield "        </div>
      </details>
      ";
        // line 78
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "billing_information", [], "any", false, false, true, 78)) {
            // line 79
            yield "        <details open class=\"claro-details\">
          <summary role=\"button\" class=\"claro-details__summary\">
            ";
            // line 81
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Billing information"));
            yield "
          </summary>
          <div class=\"details-wrapper claro-details__wrapper\">
            ";
            // line 84
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "billing_information", [], "any", false, false, true, 84), 84, $this->source), "html", null, true);
            yield "
          </div>
        </details>
      ";
        }
        // line 88
        yield "      ";
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "shipping_information", [], "any", false, false, true, 88)) {
            // line 89
            yield "        <details open class=\"claro-details\">
          <summary role=\"button\" class=\"claro-details__summary\">
            ";
            // line 91
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Shipping information"));
            yield "
          </summary>
          <div class=\"details-wrapper claro-details__wrapper\">
            ";
            // line 94
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order"] ?? null), "shipping_information", [], "any", false, false, true, 94), 94, $this->source), "html", null, true);
            yield "
          </div>
        </details>
      ";
        }
        // line 98
        yield "      ";
        if (($context["additional_order_fields"] ?? null)) {
            // line 99
            yield "        <details open class=\"claro-details\">
          <summary role=\"button\" class=\"claro-details__summary\">
            ";
            // line 101
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Other"));
            yield "
          </summary>
          ";
            // line 104
            yield "          <div class=\"details-wrapper claro-details__wrapper\">
            ";
            // line 105
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["additional_order_fields"] ?? null), 105, $this->source), "html", null, true);
            yield "
          </div>
        </details>
      ";
        }
        // line 109
        yield "    </div>
  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["order_entity", "order", "stores_count", "additional_order_fields"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/commerce/modules/order/templates/commerce-order--admin.html.twig";
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
        return array (  248 => 109,  241 => 105,  238 => 104,  233 => 101,  229 => 99,  226 => 98,  219 => 94,  213 => 91,  209 => 89,  206 => 88,  199 => 84,  193 => 81,  189 => 79,  187 => 78,  183 => 76,  177 => 75,  171 => 72,  168 => 71,  165 => 70,  161 => 69,  155 => 66,  150 => 63,  144 => 61,  141 => 60,  139 => 59,  133 => 56,  130 => 55,  127 => 54,  121 => 51,  118 => 50,  115 => 49,  109 => 48,  103 => 45,  100 => 44,  97 => 43,  93 => 42,  88 => 40,  80 => 34,  75 => 32,  70 => 31,  68 => 30,  63 => 28,  59 => 27,  53 => 23,  51 => 22,  47 => 21,  44 => 20,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/commerce/modules/order/templates/commerce-order--admin.html.twig", "/Applications/MAMP/htdocs/kickstart/web/modules/contrib/commerce/modules/order/templates/commerce-order--admin.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 22, "if" => 30, "trans" => 31, "for" => 42);
        static $filters = array("escape" => 21, "t" => 66);
        static $functions = array("attach_library" => 21);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'trans', 'for'],
                ['escape', 't'],
                ['attach_library'],
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
