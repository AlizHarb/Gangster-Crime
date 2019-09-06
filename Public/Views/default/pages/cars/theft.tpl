{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="row no-gutters">
        <div class="col-12 col-md-6">
            <div class="game-header">Steal a Vehicle</div>
            {% if user.timer('autotheft') >= time %}
                <div data-reload-when-done data-timer-type="name" data-timer="{{ user.timer('autotheft') }}" class="game-box" style="background:url({{ img }}pages/autotheft/waiting.jpg) #151515 no-repeat right bottom;min-height: 100px;">
                    <p>You have <span class="timer"></span> remaining until you can attempt to steal another vehicle.</p>
                </div>
            {% else %}
                <div class="game-box">
                    <ul class="theft">
                        {% for theft in thefts %}
                        <li class="theft-item">
                            <form method="post" action="{{ settings.get('website_url') }}autotheft/steal">
                                <input type="hidden" name="theft" value="{{ theft.id }}">
                                <input type="hidden" name="token" value="{{ token }}">
                                <button class="btn w-100">
                                    <span class="float-left">{{ theft.name }}</span>
                                </button>
                            </form>
                        </li>
                        {% endfor %}
                    </ul>
                </div>
            {% endif %}
        </div>
        <div class="col-12 col-md-6">
            <div class="game-header">Statistics</div>
            <div class="game-box">
                <ul class="statics-menu">
                    <li>
                        <span class="static-name">Current Skill:</span>
                        <span class="static-num">{{ skill.exp }}% {{ skill.name }}</span>
                    </li>
                    <li>
                        <span class="static-name">Vehicles Stolen:</span>
                        <span class="static-num">{{ user.stats().GS_autostolen | number_format }}</span>
                    </li>
                    <li>
                        <span class="static-name">Garage Space:</span>
                        <span class="static-num">0</span>
                    </li>
                    <li>
                        <span class="static-name">Foreign Car Dealer:</span>
                        <span class="static-num">0</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<form method="post" action="{{ settings.get('website_url') }}autotheft/action">
    <div class="row">
        <div class="col-12 col-md-8 mr-auto ml-auto text-center">
            <div class="game-body">
                <input type="submit" class="btn btn-dark mb-1 mb-md-0" name="sell" value="Sell Vehicle">
                <input type="submit" class="btn btn-dark mb-1 mb-md-0" name="smelt" value="Smelt Vehicle">
                <input type="submit" class="btn btn-dark mb-1 mb-md-0" name="remove" value="Remove From Sale">
                <input type="submit" class="btn btn-dark mb-1 mb-md-0" name="repair" value="Repair Vehicle">
                <input type="submit" class="btn btn-dark mb-1 mb-md-0" name="park" value="Park In Garage">
            </div>
        </div>
    </div>
    <div class="game-body">
        <div class="game-header">Your Vehicles</div>
        <div class="game-box p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-sm mb-0">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Vehicle</th>
                        <th>Damage</th>
                        <th>Vehicle Price</th>
                        <th>Location</th>
                        <th class="d-none d-md-inline-block">Origin</th>
                        <th class="d-none d-md-inline-block">Sale Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for car in garage %}
                    <tr class="hover-tr">
                        <td><input type="checkbox" name="cars" id="cars" value="{{ car.garage_id }}"></td>
                        <td>{{ car.name }}</td>
                        <td>%{{ car.damage }}</td>
                        <td>${{ car.price | number_format }}</td>
                        <td data-timer-type="name" data-timer="{{ car.time }}">
                            {% if car.time <= time %}
                                {{ car.now }}
                            {% else %}
                               {{ car.ship }} - <span class="timer"></span>
                            {% endif %}
                        </td>
                        <td class="d-none d-md-inline-block">{{ car.now }}</td>
                        <td class="d-none d-md-inline-block"></td>
                    </tr>
                    {% else %}
                    <tr>
                        <td colspan="7">You have no vehicles yet!</td>
                    </tr>
                    {% endfor %}
                    </tbody>
                    <tfoot class="border-0">
                        <tr>
                            <td colspan="7" align="right">
                                <p class="small text-white">You own {{ loop | number_format }} vehicles with a total value of ${{ price | number_format }}</p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="token" value="{{ token }}">
    <div class="game-body">
        <div class="row no-gutters">
            <div class="col-12 col-md-4">
                <div class="game-header">Transport</div>
                <div class="game-box">
                    <div class="row">
                        <div class="col-2 pt-1">To:</div>
                        <div class="col-10">
                            <select class="form-control custom-select-sm" style="height: 28px;" name="location">
                                {% for location in locations %}
                                    <option value="{{ location.id }}">{{ location.name }} - ${{ (location.cost / 7) | number_format }}</option>
                                {% endfor %}
                            </select>
                            <div class="text-right">
                                <input type="submit" class="btn btn-dark" name="ship" value="Ship">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="game-header">Advertise</div>
                <div class="game-box">
                    <div class="row">
                        <div class="col-5 pt-1">Sale Price:</div>
                        <div class="col-7">
                            <input type="number" name="amount" class="form-control">
                            <div class="text-right">
                                <input type="submit" class="btn btn-dark" name="advertise" value="Advertise">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="game-header">Give Vehicles</div>
                <div class="game-box">
                    <div class="row">
                        <div class="col-3 pt-1">Username:</div>
                        <div class="col-9">
                            <input type="text" name="user" class="form-control">
                            <div class="text-right">
                                <input type="submit" class="btn btn-dark" name="send" value="Send">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
{% endblock %}