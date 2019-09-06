{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">
        Auto Thefts ({{ theft.AT_name }})
        <span class="float-right"><a href="{{ settings.get('website_url') }}admin" class="btn btn-dark"><i class="fas fa-arrow-left pr-2"></i>Admin Panel</a></span>
    </div>
    <div class="game-box">
        <form method="post" action="{{ settings.get('website_url') }}admin/thefts/?edit={{ theft.id }}">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="theft_name">Theft Name</label>
                        <input class="form-control" id="theft_name" name="theft_name" autocomplete="off" value="{{ theft.AT_name }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="theft_chance">Theft Chance</label>
                        <input class="form-control" type="number" id="theft_chance" name="theft_chance" autocomplete="off" value="{{ theft.AT_chance }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="theft_max_damage">Max Damage</label>
                        <input class="form-control" type="number" id="theft_max_damage" name="theft_max_damage" autocomplete="off" value="{{ theft.AT_maxDamage }}">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="theft_worst_car">Worst Car</label>
                        <select class="form-control" name="theft_worst_car" id="theft_worst_car">
                            {% for car in cars %}
                            <option value="{{ car.id }}" {% if theft.AT_worstCar == car.id %}selected{% endif %}>{{ car.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="theft_best_car">Best Car</label>
                        <select class="form-control" name="theft_best_car" id="theft_best_car">
                            {% for car in cars %}
                            <option value="{{ car.id }}" {% if theft.AT_bestCar == car.id %}selected{% endif %}>{{ car.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="theft_items">Items</label>
                        <select class="form-control" multiple id="theft_items" name="theft_items[]">
                            {% for item in items %}
                            <option value="{{ item.id }}">{{ item.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <button type="submit" class="btn btn-danger">Save</button>
                <input type="hidden" name="token" value="{{ token }}">
            </div>
        </form>
    </div>
</div>
{% endblock %}