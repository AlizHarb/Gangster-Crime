<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ settings.get('website_name') }} - A world of crime, power and loyalty</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Cabin|Homenaje|Monda|Lobster|Cuprum|Pacifico|Arapey|Marcellus SC|Germania One|Electrolize|Oswald|Signika|Abel|Play|Electrolize|Marmelad|Special Elite|Pathway Gothic One|Alike|Kadwa|Domine" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ bootstrap}}css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ css }}fontawesome.min.css">
    <link rel="stylesheet" href="{{ css }}font-awesome-animation.min.css">
    <link rel="stylesheet" href="{{ css }}reset.min.css">
    <link rel="stylesheet" href="{{ css }}style.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-9 ml-auto mr-auto">
            <header>
                <span class="title">{{ settings.get('website_name') }}</span>
                <div class="round">Round X</div>
            </header>
            <div class="main-box">
                {% block body %} {% endblock %}
            </div>
            <footer>
                Copyright &copy; 2019 {{ settings.get('website_name') }}. All rights reserved unless stated otherwise.<br>
                <b>Contact email:</b> {{ settings.get('website_email') }}
            </footer>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="{{ js }}jquery-3.3.1.slim.min.js"></script>
<script src="{{ js }}popper.min.js"></script>
<script src="{{ settings.get('website_url') }}public/assets/bootstrap/js/bootstrap.min.css"></script>
</body>
</html>