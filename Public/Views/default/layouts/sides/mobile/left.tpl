{% for value,row in menus %}
<ul class="menu">
    <li class="menu-header">{{ value }}</li>
    {% for menu,key in row %}
    <li class="menu-item"><a class="menu-link" href="{{ settings.get('website_url') }}{{ key.url }}"><i class="{{ key.icon }}"></i>{{ key.name }}</a></li>
    {% endfor %}
</ul>
{% endfor %}
<ul class="menu">
    <li class="menu-header">MESSAGING</li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-comment"></i>Forums</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-question-circle"></i>Help Desk</a></li>
    <li class="menu-item"><a class="menu-link" href="{{ settings.get('website_url') }}messaging"><i class="fal fa-envelope"></i>Inbox</a></li>
    <li class="menu-item"><a class="menu-link" href="{{ settings.get('website_url') }}messaging/newMessage"><i class="fal fa-envelope-open-text"></i>Send Message</a></li>
</ul>
<ul class="menu">
    <li class="menu-header">CASINO</li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>Black Jack</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>Keno</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>Lottery</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>Race Track</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>Roulette</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>Russian Roulette</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>Slots</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-dollar-sign"></i>War</a></li>
</ul>
<ul class="menu">
    <li class="menu-header">VARIOUS</li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-trophy-alt"></i>Achievements</a></li>
    <li class="menu-item"><a class="menu-link" href="{{ settings.get('website_url') }}stats"><i class="fal fa-chart-line"></i>Game Stats</a></li>
    <li class="menu-item"><a class="menu-link" href="{{ settings.get('website_url') }}home"><i class="fal fa-home"></i>Home</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-user"></i>My Account</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-tasks"></i>My Progress</a></li>
    <li class="menu-item"><a class="menu-link" href="{{ settings.get('website_url') }}playersonline"><i class="fal fa-circle"></i>Players Online</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-edit"></i>Suggestion Box</a></li>
    <li class="menu-item"><a class="menu-link" href=""><i class="fal fa-life-ring"></i>Support GC</a></li>
</ul>
<ul class="menu">
    <li class="menu-item"><a class="menu-link" href="{{ settings.get('website_url') }}home/logout"><i class="fal fa-sign-out-alt"></i>Logout</a></li>
</ul>