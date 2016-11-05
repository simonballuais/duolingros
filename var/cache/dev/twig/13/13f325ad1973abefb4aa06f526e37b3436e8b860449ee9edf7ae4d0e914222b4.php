<?php

/* @Twig/Exception/exception_full.html.twig */
class __TwigTemplate_a7cc2c989a214e751a43f16bdb0d97824d489eba2f275b1b022cf0257267e9f6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@Twig/layout.html.twig", "@Twig/Exception/exception_full.html.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@Twig/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_41ebc9ce63358254b99ba4ea788d9ef863e08cf97b87daf95a852f1b83a9d8e5 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_41ebc9ce63358254b99ba4ea788d9ef863e08cf97b87daf95a852f1b83a9d8e5->enter($__internal_41ebc9ce63358254b99ba4ea788d9ef863e08cf97b87daf95a852f1b83a9d8e5_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "@Twig/Exception/exception_full.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_41ebc9ce63358254b99ba4ea788d9ef863e08cf97b87daf95a852f1b83a9d8e5->leave($__internal_41ebc9ce63358254b99ba4ea788d9ef863e08cf97b87daf95a852f1b83a9d8e5_prof);

    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        $__internal_235ac457f2885db9de901145a306c69a2f2dc50cc5f223d3d1971da4b06dbdb3 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_235ac457f2885db9de901145a306c69a2f2dc50cc5f223d3d1971da4b06dbdb3->enter($__internal_235ac457f2885db9de901145a306c69a2f2dc50cc5f223d3d1971da4b06dbdb3_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "@Twig/Exception/exception_full.html.twig"));

        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\HttpFoundationExtension')->generateAbsoluteUrl($this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("bundles/framework/css/exception.css")), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
        
        $__internal_235ac457f2885db9de901145a306c69a2f2dc50cc5f223d3d1971da4b06dbdb3->leave($__internal_235ac457f2885db9de901145a306c69a2f2dc50cc5f223d3d1971da4b06dbdb3_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_3f37c9118ea110b7b841a01fe7d84ea1b16fbb9d3fe2ebc8b9ecb823c0d55d84 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_3f37c9118ea110b7b841a01fe7d84ea1b16fbb9d3fe2ebc8b9ecb823c0d55d84->enter($__internal_3f37c9118ea110b7b841a01fe7d84ea1b16fbb9d3fe2ebc8b9ecb823c0d55d84_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "@Twig/Exception/exception_full.html.twig"));

        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "message", array()), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
        
        $__internal_3f37c9118ea110b7b841a01fe7d84ea1b16fbb9d3fe2ebc8b9ecb823c0d55d84->leave($__internal_3f37c9118ea110b7b841a01fe7d84ea1b16fbb9d3fe2ebc8b9ecb823c0d55d84_prof);

    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        $__internal_d9d0db0e7b296ee5a3b5d47154b3a8331af9ab55cc7c607e9e4ed1c3230a42fa = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_d9d0db0e7b296ee5a3b5d47154b3a8331af9ab55cc7c607e9e4ed1c3230a42fa->enter($__internal_d9d0db0e7b296ee5a3b5d47154b3a8331af9ab55cc7c607e9e4ed1c3230a42fa_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "@Twig/Exception/exception_full.html.twig"));

        // line 12
        echo "    ";
        $this->loadTemplate("@Twig/Exception/exception.html.twig", "@Twig/Exception/exception_full.html.twig", 12)->display($context);
        
        $__internal_d9d0db0e7b296ee5a3b5d47154b3a8331af9ab55cc7c607e9e4ed1c3230a42fa->leave($__internal_d9d0db0e7b296ee5a3b5d47154b3a8331af9ab55cc7c607e9e4ed1c3230a42fa_prof);

    }

    public function getTemplateName()
    {
        return "@Twig/Exception/exception_full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 12,  72 => 11,  58 => 8,  52 => 7,  42 => 4,  36 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends '@Twig/layout.html.twig' %}

{% block head %}
    <link href=\"{{ absolute_url(asset('bundles/framework/css/exception.css')) }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
{% endblock %}

{% block title %}
    {{ exception.message }} ({{ status_code }} {{ status_text }})
{% endblock %}

{% block body %}
    {% include '@Twig/Exception/exception.html.twig' %}
{% endblock %}
", "@Twig/Exception/exception_full.html.twig", "/home/simon/code/php/symfony3-starter/vendor/symfony/symfony/src/Symfony/Bundle/TwigBundle/Resources/views/Exception/exception_full.html.twig");
    }
}
