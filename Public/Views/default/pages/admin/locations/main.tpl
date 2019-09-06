{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="game-body">
    <div class="game-header">
        Game Locations <a href="{{ settings.get('website_url') }}admin/locations/?new=show"><i class="fas fa-plus"></i> New</a>
        <span class="float-right"><a href="{{ settings.get('website_url') }}admin" class="btn btn-dark"><i class="fas fa-arrow-left pr-2"></i>Admin Panel</a></span>
    </div>
    <div class="game-box p-0">
        <div class="table-responsive">
            <table class="table table-sm table-borderless mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Cost</th>
                    <th>Bullets</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    {% for location in locations %}
                        <tr class="hover-tr">
                            <td width="45%">{{ location.name }}</td>
                            <td width="25%">${{ location.cost | number_format }}</td>
                            <td width="20%">{{ location.bullets | number_format }}</td>
                            <td width="10%">
                                <a href="{{ settings.get('website_url') }}admin/locations/?edit={{ location.id }}">Edit</a>
                                <a onclick="return confirm('Are you sure you want to delete this location?');" href="{{ settings.get('website_url') }}admin/locations/?delete={{ location.id }}">Delete</a>
                            </td>
                        </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
</div>
{% endblock %}