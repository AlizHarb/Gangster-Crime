{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">Admin Panel</div>
    <div class="game-box text-center">
        <div class="row">
            <div class="col-6 col-md-4">
                <ul class="list-unstyled">
                    <li class="list-header">Main</li>
                    <li><a href="{{ settings.get('website_url') }}admin/settings">Game Settings</a></li>
                    <li><a href="{{ settings.get('website_url') }}admin/archive">Game Archive</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-4">
                <ul class="list-unstyled">
                    <li class="list-header">Game</li>
                    <li><a href="{{ settings.get('website_url') }}admin/locations">Locations</a></li>
                    <li><a href="{{ settings.get('website_url') }}admin/thefts">Auto Thefts</a></li>
                    <li><a href="{{ settings.get('website_url') }}admin/">Cars</a></li>
                    <li><a href="{{ settings.get('website_url') }}admin/">Crimes</a></li>
                    <li><a href="{{ settings.get('website_url') }}admin/">Items</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
{% endblock %}