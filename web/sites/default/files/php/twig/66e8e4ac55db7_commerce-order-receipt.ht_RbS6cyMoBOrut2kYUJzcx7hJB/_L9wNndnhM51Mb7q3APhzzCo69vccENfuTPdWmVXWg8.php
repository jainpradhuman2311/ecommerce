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

/* modules/contrib/commerce/modules/order/templates/commerce-order-receipt.html.twig */
class __TwigTemplate_c5f6118a07f26b71f9e39d8a3882e5e9 extends Template
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
            'order_items' => [$this, 'block_order_items'],
            'shipping_information' => [$this, 'block_shipping_information'],
            'billing_information' => [$this, 'block_billing_information'],
            'payment_method' => [$this, 'block_payment_method'],
            'additional_information' => [$this, 'block_additional_information'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 23
        yield "<table style=\"margin: 15px auto 0 auto; max-width: 768px; font-family: arial,sans-serif\">
  <tbody>
  <tr>
    <td>
      <table style=\"margin-left: auto; margin-right: auto; max-width: 768px; text-align: center;\">
        <tbody>
        <tr>
          <td>
            <a href=\"";
        // line 31
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("<front>"));
        yield "\" style=\"color: #0e69be; text-decoration: none; font-weight: bold; margin-top: 15px;\">";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getStore", [], "any", false, false, true, 31), "label", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
        yield "</a>
          </td>
        </tr>
        </tbody>
      </table>
      <table style=\"text-align: center; min-width: 450px; margin: 5px auto 0 auto; border: 1px solid #cccccc; border-radius: 5px; padding: 40px 30px 30px 30px;\">
        <tbody>
        <tr>
          <td style=\"font-size: 30px; padding-bottom: 30px\">";
        // line 39
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Order Confirmation"));
        yield "</td>
        </tr>
        <tr>
          <td style=\"padding-top:15px; padding-bottom: 15px; text-align: left; border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc\">
            <div><span style=\"font-weight: bold;\">";
        // line 43
        yield "Order number:";
        yield "</span> ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getOrderNumber", [], "any", false, false, true, 43), 43, $this->source), "html", null, true);
        yield "</div>
            ";
        // line 44
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getPlacedTime", [], "any", false, false, true, 44)) {
            // line 45
            yield "              <div style=\"margin-top: 5px;\"><span style=\"font-weight: bold;\">";
            yield "Order date:";
            yield "</span> ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->env->getFilter('format_date')->getCallable()($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getPlacedTime", [], "any", false, false, true, 45), 45, $this->source), "short"), "html", null, true);
            yield "</div>
            ";
        }
        // line 47
        yield "          </td>
        </tr>
        <tr>
          <td>
            ";
        // line 51
        yield from $this->unwrap()->yieldBlock('order_items', $context, $blocks);
        // line 68
        yield "          </td>
        </tr>
        <tr>
          <td>
            ";
        // line 72
        if ((($context["billing_information"] ?? null) || ($context["shipping_information"] ?? null))) {
            // line 73
            yield "            <table style=\"width: 100%; padding-top:15px; padding-bottom: 15px; text-align: left; border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc;\">
              <tbody>
              <tr>
                ";
            // line 76
            if (($context["shipping_information"] ?? null)) {
                // line 77
                yield "                  <td style=\"padding-top: 5px; font-weight: bold;\">";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Shipping Information"));
                yield "</td>
                ";
            }
            // line 79
            yield "                ";
            if (($context["billing_information"] ?? null)) {
                // line 80
                yield "                  <td style=\"padding-top: 5px; font-weight: bold;\">";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Billing Information"));
                yield "</td>
                ";
            }
            // line 82
            yield "              </tr>
              <tr style=\"vertical-align: top;\">
                ";
            // line 84
            if (($context["shipping_information"] ?? null)) {
                // line 85
                yield "                  <td>
                    ";
                // line 86
                yield from $this->unwrap()->yieldBlock('shipping_information', $context, $blocks);
                // line 89
                yield "                  </td>
                ";
            }
            // line 91
            yield "                ";
            if (($context["billing_information"] ?? null)) {
                // line 92
                yield "                  <td>
                    ";
                // line 93
                yield from $this->unwrap()->yieldBlock('billing_information', $context, $blocks);
                // line 96
                yield "                  </td>
                ";
            }
            // line 98
            yield "              </tr>
              ";
            // line 99
            if (($context["payment_method"] ?? null)) {
                // line 100
                yield "                <tr>
                  <td style=\"font-weight: bold; margin-top: 10px;\">";
                // line 101
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Payment Method"));
                yield "</td>
                </tr>
                <tr>
                  <td>
                    ";
                // line 105
                yield from $this->unwrap()->yieldBlock('payment_method', $context, $blocks);
                // line 108
                yield "                  </td>
                </tr>
              ";
            }
            // line 111
            yield "              </tbody>
            </table>
            ";
        }
        // line 114
        yield "          </td>
        </tr>
        <tr>
          <td>
            <p style=\"margin-bottom: 0;\">
              ";
        // line 119
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Subtotal: @subtotal", ["@subtotal" => $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["totals"] ?? null), "subtotal", [], "any", false, false, true, 119), 119, $this->source))]));
        yield "
            </p>
          </td>
        </tr>
        ";
        // line 123
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["totals"] ?? null), "adjustments", [], "any", false, false, true, 123));
        foreach ($context['_seq'] as $context["_key"] => $context["adjustment"]) {
            // line 124
            yield "        <tr>
          <td>
            <p style=\"margin-bottom: 0;\">
              ";
            // line 127
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["adjustment"], "label", [], "any", false, false, true, 127), 127, $this->source), "html", null, true);
            yield ": ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["adjustment"], "total", [], "any", false, false, true, 127), 127, $this->source)), "html", null, true);
            yield "
            </p>
          </td>
        </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['adjustment'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 132
        yield "        <tr>
          <td>
            <p style=\"font-size: 24px; padding-top: 15px; padding-bottom: 5px;\">
              ";
        // line 135
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Order Total: @total", ["@total" => $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getTotalPrice", [], "any", false, false, true, 135), 135, $this->source))]));
        yield "
            </p>
          </td>
        </tr>
        ";
        // line 139
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getCustomerComments", [], "any", false, false, true, 139)) {
            // line 140
            yield "          <tr>
            <td>
              <table style=\"width: 100%; padding-top:15px; padding-bottom: 15px; text-align: left; border-top: 1px solid #cccccc; \">
                <tbody>
                <tr>
                  <td style=\"padding-top: 5px; font-weight: bold;\">";
            // line 145
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Customer comments"));
            yield "</td>
                </tr>
                <tr>
                  <td><p>";
            // line 148
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getCustomerComments", [], "any", false, false, true, 148), 148, $this->source));
            yield "</p></td>
                </tr>
                </tbody>
              </table>
            </td>
          </tr>
        ";
        }
        // line 155
        yield "        <tr>
          <td>
            ";
        // line 157
        yield from $this->unwrap()->yieldBlock('additional_information', $context, $blocks);
        // line 160
        yield "          </td>
        </tr>
        </tbody>
      </table>
    </td>
  </tr>
  </tbody>
</table>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["order_entity", "billing_information", "shipping_information", "payment_method", "totals"]);        yield from [];
    }

    // line 51
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_order_items(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 52
        yield "            <table style=\"padding-top: 15px; padding-bottom:15px; width: 100%\">
              <tbody style=\"text-align: left;\">
              ";
        // line 54
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["order_entity"] ?? null), "getItems", [], "any", false, false, true, 54));
        foreach ($context['_seq'] as $context["_key"] => $context["order_item"]) {
            // line 55
            yield "              <tr>
                <td>
                  ";
            // line 57
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Twig\Extension\CoreExtension']->formatNumber($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["order_item"], "getQuantity", [], "any", false, false, true, 57), 57, $this->source)), "html", null, true);
            yield " x
                </td>
                <td>
                  <span>";
            // line 60
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["order_item"], "label", [], "any", false, false, true, 60), 60, $this->source), "html", null, true);
            yield "</span>
                  <span style=\"float: right;\">";
            // line 61
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["order_item"], "getTotalPrice", [], "any", false, false, true, 61), 61, $this->source)), "html", null, true);
            yield "</span>
                </td>
              </tr>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['order_item'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 65
        yield "              </tbody>
            </table>
            ";
        yield from [];
    }

    // line 86
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_shipping_information(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 87
        yield "                      ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["shipping_information"] ?? null), 87, $this->source), "html", null, true);
        yield "
                    ";
        yield from [];
    }

    // line 93
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_billing_information(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 94
        yield "                      ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["billing_information"] ?? null), 94, $this->source), "html", null, true);
        yield "
                    ";
        yield from [];
    }

    // line 105
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_payment_method(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 106
        yield "                      ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["payment_method"] ?? null), 106, $this->source), "html", null, true);
        yield "
                    ";
        yield from [];
    }

    // line 157
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_additional_information(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 158
        yield "              ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Thank you for your order!"));
        yield "
            ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/commerce/modules/order/templates/commerce-order-receipt.html.twig";
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
        return array (  378 => 158,  371 => 157,  363 => 106,  356 => 105,  348 => 94,  341 => 93,  333 => 87,  326 => 86,  319 => 65,  309 => 61,  305 => 60,  299 => 57,  295 => 55,  291 => 54,  287 => 52,  280 => 51,  266 => 160,  264 => 157,  260 => 155,  250 => 148,  244 => 145,  237 => 140,  235 => 139,  228 => 135,  223 => 132,  210 => 127,  205 => 124,  201 => 123,  194 => 119,  187 => 114,  182 => 111,  177 => 108,  175 => 105,  168 => 101,  165 => 100,  163 => 99,  160 => 98,  156 => 96,  154 => 93,  151 => 92,  148 => 91,  144 => 89,  142 => 86,  139 => 85,  137 => 84,  133 => 82,  127 => 80,  124 => 79,  118 => 77,  116 => 76,  111 => 73,  109 => 72,  103 => 68,  101 => 51,  95 => 47,  87 => 45,  85 => 44,  79 => 43,  72 => 39,  59 => 31,  49 => 23,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/commerce/modules/order/templates/commerce-order-receipt.html.twig", "/Applications/MAMP/htdocs/kickstart/web/modules/contrib/commerce/modules/order/templates/commerce-order-receipt.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 44, "block" => 51, "for" => 123);
        static $filters = array("escape" => 31, "t" => 39, "format_date" => 45, "commerce_price_format" => 119, "raw" => 148, "number_format" => 57);
        static $functions = array("url" => 31);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'block', 'for'],
                ['escape', 't', 'format_date', 'commerce_price_format', 'raw', 'number_format'],
                ['url'],
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
