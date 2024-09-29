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

/* themes/contrib/belgrade/templates/layout/region--top-bar.html.twig */
class __TwigTemplate_65b4f48884ada303a6db4f716bb57ca5 extends Template
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

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 17
        return "@belgrade/layout/region.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 15
        $macros["svg"] = $this->macros["svg"] = $this->loadTemplate("@belgrade/macros.twig", "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig", 15)->unwrap();
        // line 19
        CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "setAttribute", ["role", "navigation"], "method", false, false, true, 19);
        // line 17
        $this->parent = $this->loadTemplate("@belgrade/layout/region.html.twig", "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig", 17);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "navigation_toggle_visibility", "navigation_toggle_text", "content"]);    }

    // line 21
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 22
        yield "  <div class=\"d-flex align-items-center justify-content-between fw-light\">
    <a class=\"navigation-toggle me-auto cursor-pointer ";
        // line 23
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["navigation_toggle_visibility"] ?? null)) ? ("d-lg-none") : ("")));
        yield "\" data-bs-toggle=\"offcanvas\" data-bs-target=\"#navigationRegion\" aria-controls=\"navigationRegion\">
      ";
        // line 24
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(CoreExtension::callMacro($macros["svg"], "macro_getIcon", ["list", 32, 32], 24, $context, $this->getSourceContext()));
        yield "
      ";
        // line 25
        if (($context["navigation_toggle_text"] ?? null)) {
            // line 26
            yield "        <span>";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t($this->sandbox->ensureToStringAllowed(($context["navigation_toggle_text"] ?? null), 26, $this->source)));
            yield "</span>
      ";
        }
        // line 28
        yield "    </a>
    ";
        // line 29
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 29, $this->source), "html", null, true);
        yield "
  </div>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig";
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
        return array (  88 => 29,  85 => 28,  79 => 26,  77 => 25,  73 => 24,  69 => 23,  66 => 22,  59 => 21,  53 => 17,  51 => 19,  49 => 15,  42 => 17,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig", "/Applications/MAMP/htdocs/kickstart/web/themes/contrib/belgrade/templates/layout/region--top-bar.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 15, "extends" => 17, "do" => 19, "if" => 25);
        static $filters = array("t" => 26, "escape" => 29);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['import', 'extends', 'do', 'if'],
                ['t', 'escape'],
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
