{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<ul class="game-tabs">
    <li class="tab-item active"><a href="{{ settings.get('website_url') }}crews" class="tab-link">Main</a></li>
    <li class="tab-item"><a href="{{ settings.get('website_url') }}crews/bank" class="tab-link">Crew Bank</a></li>
    <li class="tab-item"><a href="{{ settings.get('website_url') }}crews/management" class="tab-link">Crew Management</a></li>
</ul>
<div class="game-body">
    <div class="game-header text-center">{{ crew.C_name }}'s Crew</div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="game-header">Gangsters Online</div>
            <div class="game-box" style="min-height: 150px;">
                <ul class="list-inline">
                    <li class="list-inline-item">xxxx</li>
                    <li class="list-inline-item">xxxx</li>
                    <li class="list-inline-item">xxxx</li>
                    <li class="list-inline-item">xxxx</li>
                </ul>
                <hr>
                <ul class="list-inline small" style="position: absolute;bottom:9px;">
                    <li class="list-inline-item">Keys:</li>
                    <li class="list-inline-item text-success">xxx</li>
                    <li class="list-inline-item text-danger">xxx</li>
                    <li class="list-inline-item text-primary">xxx</li>
                    <li class="list-inline-item text-info">xxx</li>
                </ul>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="game-header">Crew's Information</div>
            <div class="game-box">
                <ul class="statics-menu">
                    <li>
                        <span class="static-name">stats</span>
                        <span class="static-num">num</span>
                    </li>
                    <li>
                        <span class="static-name">stats</span>
                        <span class="static-num">num</span>
                    </li>
                    <li>
                        <span class="static-name">stats</span>
                        <span class="static-num">num</span>
                    </li>
                    <li>
                        <span class="static-name">stats</span>
                        <span class="static-num">num</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
{% endblock %}