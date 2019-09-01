{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">Commit a Crime</div>
    <p class="small">Committing crimes is a quick way to earn cash and experience. You can also find all the items you need to commit robberies.</p>
    <div class="col-12 col-md-11 mr-auto ml-auto">
        {% for crime in crimes %}
            <div class="box-game hover {% if crime.previous < 100 %} locked {% endif %}" style="background-image: url('{{ img }}pages/crimes/crimes_{{ crime.crimes.id }}.png')">
                <div class="row">
                    <div class="col-6 col-md-7">
                        <div class="text">
                            <p class="font-weight-bold">{{ crime.crimes.name }}</p>
                            <div class="progress bg-transparent">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ crime.perc }}%;" aria-valuemax="100">{{ crime.perc }}%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 col-md-3 text-center">
                        <form method="post" action="{{ settings.get('website_url') }}crimes/attempt">
                            <input type="hidden" name="crime" value="{{ crime.crimes.id }}">
                            <input type="hidden" name="token" value="{{ token }}">
                            {% if user.timer('crime-' ~ crime.crimes.id) > time %}
                            <button class="btn btn-dark" data-reload-when-done data-timer-type="name" data-timer="{{ user.timer('crime-' ~ crime.crimes.id) }}" disabled>
                                    <span class="timer"></span>
                            </button>
                            {% else %}
                            <button class="btn btn-dark">Attempt</button>
                            {% endif %}
                        </form>
                    </div>
                    <div class="col-4 col-md-2 text-right text-md-center">
                        <div class="icons">
                            {% for item in crime.items %}
                            <i class="{{ item.icon }}"></i>
                            {% endfor %}
                            <i class="fas fa-money-bill-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>
{% endblock %}