{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<ul class="game-tabs">
    <li class="tab-item active"><a href="{{ settings.get('website_url') }}airport" class="tab-link">Airport</a></li>
    <li class="tab-item"><a href="" class="tab-link">Crew Turf</a></li>
    <li class="tab-item"><a href="" class="tab-link">Overview</a></li>
</ul>

<div class="page-img d-none d-md-block">
    <img src="{{ img }}pages/airport/{{ user.data().location | lower }}_header.jpg" class="img-fluid">
</div>
<div class="row">
    <div class="col-12 col-md-10 mr-auto ml-auto">
        {% if user.timer('travel') > time %}
            <div class="alert alert-info mt-2" data-timer-type="name" data-timer="{{ user.timer('travel') }}">
                <i class="fal fa-plane pr-2"></i> The next available flight departs after <span class="timer"></span>
            </div>
        {% endif %}
        <div class="game-body">
            <i class="fas fa-suitcase icon-design"></i> <a href="{{ settings.get('website_url') }}smuggling">Need extra cash? Carry smuggled goods every time you travel.</a>
        </div>
        <div class="game-body">
            <div class="game-header">Travel Destination</div>
            <div class="row">
                {% for location in locations %}
                    <div class="col-12 col-md-6">
                        <form method="post" action="{{ settings.get('website_url') }}airport/fly" class="box-game" style="background-image: url('{{ img }}pages/airport/{{ location.name | lower }}_travel.png')">
                            <div class="row">
                                <div class="col-8">
                                    <div class="text">
                                        <p class="font-weight-bold">{{ location.name }}</p>
                                        <p>${{ location.cost | number_format }}</p>
                                    </div>
                                </div>
                                <div class="col-4 text-right">
                                    <input type="hidden" name="location" value="{{ location.id }}">
                                    <input type="hidden" name="token" value="{{ token }}">
                                    <button class="btn btn-dark btn-sm">Book Flight</button>
                                </div>
                            </div>
                        </form>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>
</div>

{% endblock %}