{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">
        Auto Thefts <a href="{{ settings.get('website_url') }}admin/thefts/?new=show"><i class="fas fa-plus"></i> New</a>
        <span class="float-right"><a href="{{ settings.get('website_url') }}admin" class="btn btn-dark"><i class="fas fa-arrow-left pr-2"></i>Admin Panel</a></span>
    </div>
    <div class="game-box p-0">
        <div class="table-responsive">
            <table class="table table-sm table-borderless mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {% for theft in thefts %}
                <tr class="hover-tr">
                    <td width="85%">{{ theft.name }}</td>
                    <td width="15%">
                        <a href="{{ settings.get('website_url') }}admin/thefts/?edit={{ theft.id }}">Edit</a>
                        <a onclick="return confirm('Are you sure you want to delete this theft?');" href="{{ settings.get('website_url') }}admin/thefts/?delete={{ theft.id }}">Delete</a>
                    </td>
                </tr>
                {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
</div>
{% endblock %}