{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="game-body text-center" style="height: 100px;">
                <h3 class="mb-0">Hello, {{ user.data().name }}!</h3>
                <p>Welcome to Round X of {{ settings.get('website_name') }}.</p>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="game-body text-center" style="height: 100px;">
                <h5 class="font-weight-bold mb-0">Friends online (0):</h5>
                <small>You have no friends online at this time.</small>
            </div>
        </div>
    </div>
    <div class="game-body">
        <div class="game-header">Update Feed</div>
        <div class="game-box">
            <div class="row">
                <div class="col-2 col-md-1">
                    <div class="badge badge-danger p-1">
                        Update
                    </div>
                </div>
                <div class="col-7 col-md-9">
                    <a href="">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a>
                </div>
                <div class="col-3 col-md-2 text-left text-md-right">
                    <small>2019-07-01</small>
                </div>
            </div>
        </div>
    </div>
{% endblock %}