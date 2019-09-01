{% extends 'default/layouts/login.tpl' %}
{% block body %}
    <div class="row">
        <div class="col-12 col-lg-6 order-2 order-lg-1">
            <!-- About box -->
            {% include 'default/pages/auth/about.tpl' %}
            <!-- Register box -->
            {% include 'default/pages/auth/register.tpl' %}
        </div>
        <div class="col-12 col-lg-6 order-1 order-lg-2">
            <!-- Login box -->
            {% include 'default/pages/auth/login.tpl' %}
            <!-- Extra box -->
            {% include 'default/pages/auth/extra.tpl' %}
        </div>
    </div>
{% endblock %}