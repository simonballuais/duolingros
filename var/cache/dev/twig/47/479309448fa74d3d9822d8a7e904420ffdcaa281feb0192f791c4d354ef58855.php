<?php

/* FOSUserBundle:Security:login.html.twig */
class __TwigTemplate_b095a71c3d656165d14321eba4d3e9bbcec97ec64604a00684838733000203d9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("nologged_base.html.twig", "FOSUserBundle:Security:login.html.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "nologged_base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_40758d5d7495703cfb9081a46cf2335d11fd64410d9419b2e206620877cadb94 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_40758d5d7495703cfb9081a46cf2335d11fd64410d9419b2e206620877cadb94->enter($__internal_40758d5d7495703cfb9081a46cf2335d11fd64410d9419b2e206620877cadb94_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "FOSUserBundle:Security:login.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_40758d5d7495703cfb9081a46cf2335d11fd64410d9419b2e206620877cadb94->leave($__internal_40758d5d7495703cfb9081a46cf2335d11fd64410d9419b2e206620877cadb94_prof);

    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        $__internal_7fa42f85355e52fdbdb2d31e967459ccfef3be4638b3be6a777497f6c819a065 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_7fa42f85355e52fdbdb2d31e967459ccfef3be4638b3be6a777497f6c819a065->enter($__internal_7fa42f85355e52fdbdb2d31e967459ccfef3be4638b3be6a777497f6c819a065_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "FOSUserBundle:Security:login.html.twig"));

        // line 4
        echo "<div class=\"login-box-body\">
    <p class=\"login-box-msg\">Se connecter</p>
    ";
        // line 6
        if ((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error"))) {
            // line 7
            echo "    <div class=\"alert alert-danger alert-dismissable\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans($this->getAttribute((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "messageKey", array()), $this->getAttribute((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "messageData", array()), "security"), "html", null, true);
            echo "</div>
    ";
        }
        // line 9
        echo "    <form action=\"";
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("fos_user_security_check");
        echo "\" method=\"post\">

        <input type=\"hidden\" name=\"_csrf_token\" value=\"";
        // line 11
        echo twig_escape_filter($this->env, (isset($context["csrf_token"]) ? $context["csrf_token"] : $this->getContext($context, "csrf_token")), "html", null, true);
        echo "\" />
        <div class=\"form-group has-feedback\">
            <input class=\"form-control\" type=\"text\" id=\"username\" name=\"_username\" value=\"";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["last_username"]) ? $context["last_username"] : $this->getContext($context, "last_username")), "html", null, true);
        echo "\" required=\"required\" class=\"form-control\" placeholder=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("security.login.username", array(), "FOSUserBundle"), "html", null, true);
        echo "\"  />
            <span class=\"glyphicon glyphicon-user form-control-feedback\"></span>
        </div>
        <div class=\"form-group has-feedback\">
            <input type=\"password\" id=\"password\" name=\"_password\" required=\"required\" class=\"form-control\" placeholder=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("security.login.password", array(), "FOSUserBundle"), "html", null, true);
        echo "\" class=\"form-control\" />
            <span class=\"glyphicon glyphicon-lock form-control-feedback\"></span>
        </div>
        <div class=\"row\">
            <div class=\"col-xs-8\">
                <a href=\"";
        // line 22
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getUrl("fos_user_resetting_request");
        echo "\">";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("security.login.lost_password", array(), "FOSUserBundle"), "html", null, true);
        echo "</a>
            </div>
            <!-- /.col -->
            <div class=\"col-xs-4\">
                <button type=\"submit\" class=\"btn btn-primary btn-block btn-flat\">";
        // line 26
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("security.login.submit", array(), "FOSUserBundle"), "html", null, true);
        echo "</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
</div>
";
        
        $__internal_7fa42f85355e52fdbdb2d31e967459ccfef3be4638b3be6a777497f6c819a065->leave($__internal_7fa42f85355e52fdbdb2d31e967459ccfef3be4638b3be6a777497f6c819a065_prof);

    }

    public function getTemplateName()
    {
        return "FOSUserBundle:Security:login.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 26,  80 => 22,  72 => 17,  63 => 13,  58 => 11,  52 => 9,  46 => 7,  44 => 6,  40 => 4,  34 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'nologged_base.html.twig' %}
{% trans_default_domain 'FOSUserBundle' %}
{% block content%}
<div class=\"login-box-body\">
    <p class=\"login-box-msg\">Se connecter</p>
    {% if error %}
    <div class=\"alert alert-danger alert-dismissable\">{{ error.messageKey|trans(error.messageData, 'security') }}</div>
    {% endif %}
    <form action=\"{{ path(\"fos_user_security_check\") }}\" method=\"post\">

        <input type=\"hidden\" name=\"_csrf_token\" value=\"{{ csrf_token }}\" />
        <div class=\"form-group has-feedback\">
            <input class=\"form-control\" type=\"text\" id=\"username\" name=\"_username\" value=\"{{ last_username }}\" required=\"required\" class=\"form-control\" placeholder=\"{{ 'security.login.username'|trans }}\"  />
            <span class=\"glyphicon glyphicon-user form-control-feedback\"></span>
        </div>
        <div class=\"form-group has-feedback\">
            <input type=\"password\" id=\"password\" name=\"_password\" required=\"required\" class=\"form-control\" placeholder=\"{{ 'security.login.password'|trans }}\" class=\"form-control\" />
            <span class=\"glyphicon glyphicon-lock form-control-feedback\"></span>
        </div>
        <div class=\"row\">
            <div class=\"col-xs-8\">
                <a href=\"{{ url('fos_user_resetting_request') }}\">{{ 'security.login.lost_password'|trans }}</a>
            </div>
            <!-- /.col -->
            <div class=\"col-xs-4\">
                <button type=\"submit\" class=\"btn btn-primary btn-block btn-flat\">{{ 'security.login.submit'|trans }}</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
</div>
{% endblock content %}
", "FOSUserBundle:Security:login.html.twig", "/home/simon/code/php/symfony3-starter/app/Resources/FOSUserBundle/views/Security/login.html.twig");
    }
}
