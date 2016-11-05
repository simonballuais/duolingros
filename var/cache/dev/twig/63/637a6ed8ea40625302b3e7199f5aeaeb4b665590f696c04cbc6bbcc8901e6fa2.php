<?php

/* nologged_base.html.twig */
class __TwigTemplate_22d772d57180e6fa33cbc7d99cef4f1b05ddd3a5633e1fcc2f2460fa53b07977 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheet' => array($this, 'block_stylesheet'),
            'javascripts' => array($this, 'block_javascripts'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_b6bbb0cd870636fff68c9e33f34cb9fdb60120f17c6bd3ee98c836614de4c313 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_b6bbb0cd870636fff68c9e33f34cb9fdb60120f17c6bd3ee98c836614de4c313->enter($__internal_b6bbb0cd870636fff68c9e33f34cb9fdb60120f17c6bd3ee98c836614de4c313_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "nologged_base.html.twig"));

        // line 2
        echo "<html>
    <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        <title>
            ";
        // line 7
        $this->displayBlock('title', $context, $blocks);
        // line 8
        echo "        </title>
        <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
        ";
        // line 10
        $this->displayBlock('stylesheet', $context, $blocks);
        // line 18
        echo "
        ";
        // line 19
        $this->displayBlock('javascripts', $context, $blocks);
        // line 24
        echo "    </head>

    <body class=\"ad-login\">
        <div class=\"row\">
            <div class=\"col-md-4 col-md-offset-4\">
                <div class=\"login-logo\">
                    <a href=\"#\">New Project</a>
                </div>
                ";
        // line 32
        $this->displayBlock('content', $context, $blocks);
        // line 34
        echo "            </div>
        </div> <!-- /.login-box -->
    </body>
</html>
";
        
        $__internal_b6bbb0cd870636fff68c9e33f34cb9fdb60120f17c6bd3ee98c836614de4c313->leave($__internal_b6bbb0cd870636fff68c9e33f34cb9fdb60120f17c6bd3ee98c836614de4c313_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_db01f08d1f09c3027e9ac6af6c17c129dcd368d3b085cd0466fb40969f8393e8 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_db01f08d1f09c3027e9ac6af6c17c129dcd368d3b085cd0466fb40969f8393e8->enter($__internal_db01f08d1f09c3027e9ac6af6c17c129dcd368d3b085cd0466fb40969f8393e8_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "nologged_base.html.twig"));

        echo "New Project";
        
        $__internal_db01f08d1f09c3027e9ac6af6c17c129dcd368d3b085cd0466fb40969f8393e8->leave($__internal_db01f08d1f09c3027e9ac6af6c17c129dcd368d3b085cd0466fb40969f8393e8_prof);

    }

    // line 10
    public function block_stylesheet($context, array $blocks = array())
    {
        $__internal_723083c031ddb7050a19da28bb6a6a2b2156313034fc0b9d1c86d7e914a1636a = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_723083c031ddb7050a19da28bb6a6a2b2156313034fc0b9d1c86d7e914a1636a->enter($__internal_723083c031ddb7050a19da28bb6a6a2b2156313034fc0b9d1c86d7e914a1636a_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "nologged_base.html.twig"));

        // line 11
        echo "        <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/vendor/AdminLTE/bootstrap/css/bootstrap.min.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/vendor/AdminLTE/dist/css/AdminLTE.min.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/vendor/AdminLTE/dist/css/skins/skin-blue.min.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/css/font-awesome.min.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/css/ionicons.min.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/css/base.css"), "html", null, true);
        echo "\" />
        ";
        
        $__internal_723083c031ddb7050a19da28bb6a6a2b2156313034fc0b9d1c86d7e914a1636a->leave($__internal_723083c031ddb7050a19da28bb6a6a2b2156313034fc0b9d1c86d7e914a1636a_prof);

    }

    // line 19
    public function block_javascripts($context, array $blocks = array())
    {
        $__internal_f24cd66e8e93d8bb1fcc0293329fd9c70053d1b91ff404e5c8c2b5116cc31b14 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_f24cd66e8e93d8bb1fcc0293329fd9c70053d1b91ff404e5c8c2b5116cc31b14->enter($__internal_f24cd66e8e93d8bb1fcc0293329fd9c70053d1b91ff404e5c8c2b5116cc31b14_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "nologged_base.html.twig"));

        // line 20
        echo "        <script src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/vendor/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"), "html", null, true);
        echo "\"></script>
        <script src=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/vendor/AdminLTE/bootstrap/js/bootstrap.min.js"), "html", null, true);
        echo "\"></script>
        <script src=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("assets/vendor/AdminLTE/dist/js/app.min.js"), "html", null, true);
        echo "\"></script>
        ";
        
        $__internal_f24cd66e8e93d8bb1fcc0293329fd9c70053d1b91ff404e5c8c2b5116cc31b14->leave($__internal_f24cd66e8e93d8bb1fcc0293329fd9c70053d1b91ff404e5c8c2b5116cc31b14_prof);

    }

    // line 32
    public function block_content($context, array $blocks = array())
    {
        $__internal_9a7d99971fcea256213295da1952799de92a7a5aabb70248a26f5cfccc77f000 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_9a7d99971fcea256213295da1952799de92a7a5aabb70248a26f5cfccc77f000->enter($__internal_9a7d99971fcea256213295da1952799de92a7a5aabb70248a26f5cfccc77f000_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "nologged_base.html.twig"));

        // line 33
        echo "                ";
        
        $__internal_9a7d99971fcea256213295da1952799de92a7a5aabb70248a26f5cfccc77f000->leave($__internal_9a7d99971fcea256213295da1952799de92a7a5aabb70248a26f5cfccc77f000_prof);

    }

    public function getTemplateName()
    {
        return "nologged_base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  147 => 33,  141 => 32,  132 => 22,  128 => 21,  123 => 20,  117 => 19,  108 => 16,  104 => 15,  100 => 14,  96 => 13,  92 => 12,  87 => 11,  81 => 10,  69 => 7,  58 => 34,  56 => 32,  46 => 24,  44 => 19,  41 => 18,  39 => 10,  35 => 8,  33 => 7,  26 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% trans_default_domain 'FOSUserBundle' %}
<html>
    <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        <title>
            {% block title %}New Project{% endblock title %}
        </title>
        <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
        {% block stylesheet %}
        <link rel=\"stylesheet\" href=\"{{ asset('assets/vendor/AdminLTE/bootstrap/css/bootstrap.min.css') }}\">
        <link rel=\"stylesheet\" href=\"{{ asset('assets/vendor/AdminLTE/dist/css/AdminLTE.min.css') }}\">
        <link rel=\"stylesheet\" href=\"{{ asset('assets/vendor/AdminLTE/dist/css/skins/skin-blue.min.css') }}\">
        <link rel=\"stylesheet\" href=\"{{ asset('assets/css/font-awesome.min.css') }}\">
        <link rel=\"stylesheet\" href=\"{{ asset('assets/css/ionicons.min.css') }}\">
        <link rel=\"stylesheet\" href=\"{{ asset('assets/css/base.css') }}\" />
        {% endblock stylesheet %}

        {% block javascripts %}
        <script src=\"{{ asset('assets/vendor/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js') }}\"></script>
        <script src=\"{{ asset('assets/vendor/AdminLTE/bootstrap/js/bootstrap.min.js') }}\"></script>
        <script src=\"{{ asset('assets/vendor/AdminLTE/dist/js/app.min.js') }}\"></script>
        {% endblock javascripts %}
    </head>

    <body class=\"ad-login\">
        <div class=\"row\">
            <div class=\"col-md-4 col-md-offset-4\">
                <div class=\"login-logo\">
                    <a href=\"#\">New Project</a>
                </div>
                {% block content %}
                {% endblock content%}
            </div>
        </div> <!-- /.login-box -->
    </body>
</html>
", "nologged_base.html.twig", "/home/simon/code/php/symfony3-starter/app/Resources/views/nologged_base.html.twig");
    }
}
