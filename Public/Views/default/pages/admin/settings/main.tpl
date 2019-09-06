{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">
        Game Settings
        <span class="float-right"><a href="{{ settings.get('website_url') }}admin" class="btn btn-dark"><i class="fas fa-arrow-left pr-2"></i>Admin Panel</a></span>
    </div>
    <div class="game-box">
        <form method="post" action="{{ settings.get('website_url') }}admin/settings/?update=true">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="website_name">Game Name</label>
                        <input class="form-control" id="website_name" name="website_name" autocomplete="off" value="{{ settings.get('website_name') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="website_url">Game Url</label>
                        <input class="form-control" type="url" id="website_url" name="website_url" autocomplete="off" value="{{ settings.get('website_url') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="website_email">Game Email</label>
                        <input class="form-control" type="email" id="website_email" name="website_email" autocomplete="off" value="{{ settings.get('website_email') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="website_theme">Game Theme</label>
                        <select class="form-control" id="website_theme" name="website_theme">
                            {% for theme in themes %}
                                <option value="{{ theme }}">{{ theme }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <button type="submit" class="btn btn-danger">Save Settings</button>
                <input type="hidden" name="token" value="{{ token }}">
            </div>
        </form>
    </div>
</div>
{% endblock %}