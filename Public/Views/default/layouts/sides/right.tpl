<div class="navigation">
    <div class="top">
        <a href="{{ settings.get('website_url') }}messaging"><span class="fas fa-envelope {% if mail.checkMail %}text-white faa-shake animated{% endif %}"></span></a>
        <a href="{{ settings.get('website_url') }}airport">{{ user.data().location }}</a>
        <form>
            <div class="form-group">
                <input class="form-control" type="text" name="gangster_name" placeholder="Gangster Name">
            </div>
        </form>
    </div>
    <ul class="info-menu">
        <li class="menu-header">RANK</li>
        <li class="menu-item">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ user.data().exp }}%" aria-valuemax="100">{{ user.data().rank }} ({{ user.data().exp }}%)</div>
            </div>
        </li>
        <li class="menu-item">
            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ user.data().health }}%" aria-valuemax="100">Health ({{ user.data().health }}%)</div>
            </div>
        </li>
    </ul>
    <ul class="info-menu">
        <li class="menu-header"></li>
        <li class="menu-item">${{ user.data().cash | number_format}}</li>
    </ul>
    <ul class="info-menu">
        <li class="menu-header">CREDITS</li>
        <li class="menu-item">{{ user.data().credits | number_format }}</li>
    </ul>
    <ul class="info-menu">
        <li class="menu-header">BULLETS</li>
        <li class="menu-item">{{ user.data().bullets | number_format}}</li>
    </ul>
    <ul class="info-menu">
        <li class="menu-header">WEAPON</li>
        <li class="menu-item">None</li>
    </ul>
    <ul class="info-menu">
        <li class="menu-header">PROTECTION</li>
        <li class="menu-item">None</li>
    </ul>
    <ul class="info-menu">
        <li class="menu-header">CREW</li>
        <li class="menu-item">{{ user.data().crewProfile | raw }}</li>
    </ul>
</div>