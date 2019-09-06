{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">Crew Overview</div>
    <div class="game-box p-0">
        <div class="table-responsive">
            <table class="table table-sm table-borderless mb-0">
                <thead>
                <tr>
                    <th>Crew Name</th>
                    <th>Boss</th>
                    <th>UnderBoss</th>
                    <th>Members</th>
                    <th>Recruiting</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    {% for crew in crews %}
                    <tr>
                        <td width="30%"><a href="{{ settings.get('website_url') }}crews/profile/{{ crew.id }}">{{ crew.name }}</a></td>
                        <td width="15%">{{ crew.boss }}</td>
                        <td width="15%">{{ crew.underboss }}</td>
                        <td width="5%">{{ crew.members | number_format }}</td>
                        <td width="15%">{{ crew.recruiting }}</td>
                        <td width="20%"></td>
                    </tr>
                    {% else %}
                    <tr>
                        <td colspan="6">There are no crews yet.</td>
                    </tr>
                    {% endfor %}
                </tbody>
                <tfoot>
                    <tr>
                        <td><p class="small text-muted">Total: {{ crew.crewNum() }} out of 7 crews.</p></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="game-body">
    <div class="game-header">Create A Crew</div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="game-box">
                <p class="small mb-2">To create a crew, you need to be ranked to Cell Boss and have the minimum required funds. Two crew sizes are available holding a maximum of 30 members. Smaller crews can be upgraded at anytime after creation.</p>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="game-box">
                <form method="post" action="{{ settings.get('website_url') }}crews/create">
                    <div class="row no-gutters">
                        <div class="col-12 col-md-6">
                            <input class="form-control" name="C_name" id="crew_name" placeholder="Crew Name">
                        </div>
                        <div class="col-12 col-md-6">
                            <select class="form-control" name="C_size" id="crew_size">
                                <option value="10">10 Gangsters - $10,000,000</option>
                                <option value="30">30 Gangsters - $30,000,000</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-dark">Create Crew</button>
                        <input type="hidden" name="token" value="{{ token }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}