{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<div class="page-img d-none d-md-block">
    <img src="{{ img }}pages/store/header_gunstore.jpg" class="img-fluid">
</div>
<div class="game-body">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="game-header">Shooting Range</div>
            <div class="game-box">
                <ul class="statics-menu">
                    <li>
                        <span class="static-name">Training Cost</span>
                        <span class="static-num">$0 per hour</span>
                    </li>
                    <li>
                        <span class="static-name">Current Skill</span>
                        <span class="static-num">Novice 10%</span>
                    </li>
                    <li>
                        <span class="static-name">Weapon</span>
                        <span class="static-num">No Weapon</span>
                    </li>
                    <li>
                        <span class="static-name">Hours Required</span>
                        <span class="static-num">None required</span>
                    </li>
                    <li class="text-center">
                        <select class="form-control" name="hours">
                            <option value="1">1 hour</option>
                            <option value="2">2 hours</option>
                            <option value="3">3 hours</option>
                            <option value="4">4 hours</option>
                            <option value="5">5 hours</option>
                            <option value="6">6 hours</option>
                            <option value="7">7 hours</option>
                            <option value="8">8 hours</option>
                            <option value="9">9 hours</option>
                            <option value="10">10 hours</option>
                        </select>
                    </li>
                    <li class="text-center">
                        <button class="btn btn-dark">Enter Weapons Training</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="game-header">Information</div>
            <div class="game-box text-center">
                <p class="mb-lg-3">You need to train your weapon for the required hours in order to upgrade to the next weapon.</p>
                <p class="mb-lg-3">While in the Weapons Range, you will be unable to leave.</p>
                <p class="mb-lg-2">Syvanski Verstal will certify you upon course completion.</p>
            </div>
        </div>
    </div>
</div>
<div class="game-body">
    <div class="game-header text-center">Gun Store</div>
    <div class="row">
        <div class="col-12 col-md-6">
            {% include 'default/pages/store/partials/weapons.tpl' %}
        </div>
        <div class="col-12 col-md-6">
            {% include 'default/pages/store/partials/protections.tpl' %}
        </div>
    </div>
</div>
{% endblock %}