{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">
        Game Locations
        <span class="float-right"><a href="{{ settings.get('website_url') }}admin" class="btn btn-dark"><i class="fas fa-arrow-left pr-2"></i>Admin Panel</a></span>
    </div>
    <div class="game-box">
        <form method="post" action="{{ settings.get('website_url') }}admin/locations/?new=true">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="location_name">Location Name</label>
                        <input class="form-control" id="location_name" name="location_name" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="location_cost">Location Cost</label>
                        <input class="form-control" type="number" id="location_cost" name="location_cost" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="location_bullets_cost">Location Bullets cost</label>
                        <input class="form-control" type="number" id="location_bullets_cost" name="location_bullets_cost" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="location_bullets">Location bullets</label>
                        <input class="form-control" type="number" id="location_bullets" name="location_bullets" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="location_time">Location Time</label>
                        <input class="form-control" type="number" id="location_time" name="location_time" autocomplete="off">
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <button type="submit" class="btn btn-danger">Add</button>
                <input type="hidden" name="token" value="{{ token }}">
            </div>
        </form>
    </div>
</div>
{% endblock %}