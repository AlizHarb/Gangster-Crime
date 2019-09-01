<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="timestamp" content="{{ time }}">
    <title>{{ settings.get('website_name') }}</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Cabin|Homenaje|Monda|Lobster|Cuprum|Pacifico|Arapey|Marcellus SC|Germania One|Electrolize|Oswald|Signika|Abel|Play|Electrolize|Marmelad|Special Elite|Pathway Gothic One|Alike|Kadwa|Domine" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ bootstrap}}css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ css }}fontawesome.min.css">
    <link rel="stylesheet" href="{{ css }}font-awesome-animation.min.css">
    <link rel="stylesheet" href="{{ css }}reset.min.css">
    <link rel="stylesheet" href="{{ css }}styles.min.css">
    <style type="text/css">
        html, body {
            background: #000 url("{{ img }}backgrounds/locations/{{ user.data().location | lower }}.png") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
</head>
<body class="h-100">
<div id="mobile-menu" class="navigation d-md-none">
    <a href="javascript:void(0)" class="closebtn fas fa-times" onclick="closeMenu()"></a>
    {% include 'default/layouts/sides/mobile/left.tpl' %}
</div>
<div id="mobile-stats" class="navigation stats d-md-none">
    <a href="javascript:void(0)" class="closebtn fas fa-times" onclick="closeStats()"></a>
    {% include 'default/layouts/sides/mobile/right.tpl' %}
</div>
<header>
    <div class="d-inline-block d-md-none menu-mobile">
        <span onclick="openMenu()" class="fas fa-list icon-design"></span>
    </div>
    <div class="logo d-inline-block">
        <span class="title">{{ settings.get('website_name') }}</span>
        <span class="time d-none d-md-inline-block">{{ "now" | date("g:i") }}</span>
        <span class="announcement d-none d-md-inline-block">
            <a href="">Here is the announcement <text></text></a>
        </span>
    </div>
    <div class="d-inline-block d-md-none stats-mobile">
        <span onclick="openStats()" class="fas fa-list icon-design"></span>
    </div>
</header>
<div class="container-fluid h-100">
    <div class="row no-gutters h-100">
        <div class="d-none d-lg-block col-lg-2">
            {% include 'default/layouts/sides/left.tpl' %}
        </div>
        <div class="col-12 col-lg-8">
            <div class="game-body">
                <ul class="icon-menu">
                    <li data-toggle="tooltip" data-placement="bottom" title="Airport" class="menu-item {% if user.timer('travel') > time %} in-active {% endif %}"><a href="{{ settings.get('website_url') }}airport" class="menu-link"><i class="fas fa-plane-departure"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Auto Theft" class="menu-item {% if user.timer('autotheft') > time %} in-active {% endif %}"><a href="{{ settings.get('website_url') }}autotheft" class="menu-link"><i class="fas fa-car"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Crimes" class="menu-item {% if user.timer('crime') > time %} in-active {% endif %}"><a href="{{ settings.get('website_url') }}crimes" class="menu-link"><i class="fas fa-skull-crossbones"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Extortion" class="menu-item"><a href="{{ settings.get('website_url') }}extortion" class="menu-link"><i class="fas fa-hands"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Hijack" class="menu-item"><a href="{{ settings.get('website_url') }}hijack" class="menu-link"><i class="fas fa-truck-moving"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Organized Crime" class="menu-item"><a href="{{ settings.get('website_url') }}organizedcrime" class="menu-link"><i class="fas fa-gem"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Prison" class="menu-item {% if user.timer('prison') > time %} in-active {% endif %}"><a href="{{ settings.get('website_url') }}prison" class="menu-link"><i class="fas fa-tally"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Robbery" class="menu-item"><a href="{{ settings.get('website_url') }}robbery" class="menu-link"><i class="fas fa-theater-masks"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Smuggling" class="menu-item"><a href="{{ settings.get('website_url') }}smuggling" class="menu-link"><i class="fas fa-suitcase"></i></a></li>
                    <li data-toggle="tooltip" data-placement="bottom" title="Stock Market" class="menu-item"><a href="{{ settings.get('website_url') }}stockmarket" class="menu-link"><i class="fas fa-chart-line"></i></a></li>
                </ul>
            </div>
            {% if success %}
                <div class="alert alert-success">
                    <i class="fas fa-check mr-2"></i> {{ success | raw }}
                </div>
            {% endif %}
            {% if error %}
                <div class="alert alert-danger">
                    <i class="far fa-exclamation-triangle mr-2"></i> {{ error | raw }}
                </div>
            {% endif %}
            {% if info %}
                <div class="alert alert-info">
                    <i class="fas fa-info mr-2"></i> {{ info | raw }}
                </div>
            {% endif %}
            {% block body %} {% endblock %}
        </div>
        <div class="d-none d-lg-block col-lg-2">
            {% include 'default/layouts/sides/right.tpl' %}
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="{{ js }}jquery-3.3.1.slim.min.js"></script>
<script src="{{ js }}popper.min.js"></script>
<script src="{{ bootstrap }}js/bootstrap.min.js"></script>
<script src="{{ js }}countdown.min.js"></script>
<script src="{{ js }}jquery.min.js"></script>
</body>
</html>