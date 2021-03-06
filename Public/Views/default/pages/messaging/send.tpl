{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="game-header">Send Message</div>
            <div class="game-box">
                <form method="post" action="{{ settings.get('website_url') }}messaging/send">
                    <div class="form-group">
                        <label class="recipient">Recipient:</label>
                        <input class="form-control" type="text" name="recipient" id="recipient" value="{% if message %}{{ userInfo.data().name }}{% endif %}">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" rows="5" name="text">{% if message %}&#13;&#10;{{ userInfo.data().name }} wrote: &#13;&#10;{{ message.M_text }}{% endif %}</textarea>
                    </div>
                    <input type="hidden" name="token" value="{{ token }}">
                    <button class="btn btn-dark"><i class="fas fa-envelope text-white"></i>Send Message</button>
                </form>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="game-header">Friends List</div>
            <div class="game-box"></div>
        </div>
    </div>
</div>
{% endblock %}