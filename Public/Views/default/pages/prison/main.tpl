{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="page-img d-none d-md-block">
    <img src="{{ img }}pages/prison/header_prison_{{ user.data().location | lower }}.jpg" class="img-fluid">
</div>
<div class="game-body">
    <div class="game-header">Prison</div>
    <div class="game-box p-0">
        <div class="table-responsive">
            <table class="table table-sm table-borderless mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Crew Name</th>
                    <th class="d-none d-md-block">Crime</th>
                    <th>Time</th>
                    <th>Reward</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {% if prisoners is empty %}
                <tr>
                    <td colspan="6">There are no prisoners at this time.</td>
                </tr>
                {% endif %}
                {% for prisoner in prisoners %}
                <tr class="hover-tr" data-remove-when-done data-timer-type="name" data-timer="{{ prisoner.time }}">
                    <td>{{ prisoner.userInfo.data().user | raw }}</td>
                    <td>{{ prisoner.userInfo.data().crew }}</td>
                    <td class="d-none d-md-block">{{ prisoner.userInfo.stats().GS_prisonReason | raw }}</td>
                    <td><span class="timer"></span></td>
                    <td>${{ prisoner.userInfo.stats().GS_prisonReward | number_format }}</td>
                    <td width="10%" class="m-0 p-0">
                        {% if prisoner.userInfo.data().id != user.data().id %}
                        <form method="post" action="{{ settings.get('website_url') }}prison/bust">
                            <input type="hidden" name="prisoner" value="{{ prisoner.userInfo.data().id }}">
                            <input type="hidden" name="token" value="{{ token }}">
                            <button class="btn btn-dark m-0">Bust</button>
                        </form>
                        {% endif %}
                    </td>
                </tr>
                {% endfor %}
                </tbody>
                <tfoot class="pb-0">
                <tr>
                    <td colspan="6" align="right">
                        <a href="" class="btn btn-dark mb-0" onClick="history.go(0);">Reload Page</a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="game-body">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="game-header">Top 5 Busters</div>
            <div class="game-box">
                <ul class="statics-menu">
                    <li>
                        <span class="static-name font-weight-bold">Name</span>
                        <span class="static-num font-weight-bold">Busts</span>
                    </li>
                    {% for buster in busters %}
                    <li>
                        <span class="static-name">{{ buster.userInfo.data().user | raw }}</span>
                        <span class="static-num">{{ buster.bust | number_format }}</span>
                    </li>
                    {% else %}
                        <li><p>No gangsters successed to bust yet.</p></li>
                    {% endfor %}
                </ul>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="game-header">Bribes</div>
            <div class="game-box">
                <span class="small">You have {{ user.stats().GS_bribe | number_format }} Bribes.</span>
                <form method="post" action="{{ settings.get('website_url') }}prison/bribe" class="d-inline-block">
                    <input type="hidden" name="token" value="{{ token }}">
                    <button class="btn btn-dark">Use a Bribe</button>
                </form>
            </div>
            <div class="game-header">Bust Reward (Current: ${{ user.stats().GS_prisonReward | number_format }})</div>
            <div class="game-box">
                <form method="post" action="{{ settings.get('website_url') }}prison/reward" class="d-inline-block">
                    <p class="small">Set a bust reward for other users to encourage them to bust you faster!</p>
                    <input class="form-control" type="number" name="reward" placeholder="${{ user.stats().GS_prisonReward }}">
                    <input type="hidden" name="token" value="{{ token }}">
                    <button class="btn btn-dark">Set Bust Reward</button>
                </form>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="game-header">Statistics</div>
            <div class="game-box">

                <ul class="statics-menu">
                    <li>
                        <span class="static-name">Attempted Busts:</span>
                        <span class="static-num">{{ user.stats().GS_prisonSuccess + user.stats().GS_prisonFailed | number_format }}</span>
                    </li>
                    <li>
                        <span class="static-name">Successful Busts:</span>
                        <span class="static-num">{{ user.stats().GS_prisonSuccess | number_format }}</span>
                    </li>
                    <li>
                        <span class="static-name">Failed Busts:</span>
                        <span class="static-num">{{ user.stats().GS_prisonFailed | number_format }}</span>
                    </li>
                    <li>
                        <span class="static-name">Money Earned From Busts:</span>
                        <span class="static-num">${{ user.stats().GS_moneyBust | number_format }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
{% endblock %}