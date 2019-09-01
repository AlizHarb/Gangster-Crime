{% extends 'default/layouts/loggedin.tpl' %}
{% block body %}
<ul class="game-tabs">
    <li class="tab-item active"><a href="{{ settings.get('website_url') }}centrobank" class="tab-link">Centro Bank</a></li>
    <li class="tab-item"><a href="{{ settings.get('website_url') }}centrobank/vault" class="tab-link">Bank Vault</a></li>
    <li class="tab-item"><a href="{{ settings.get('website_url') }}escrow" class="tab-link">Escrow Trading</a></li>
</ul>
<div class="page-img d-none d-md-block">
    <img src="{{ img }}pages/centrobank/header_centrobank.jpg" class="img-fluid">
</div>
<div class="row">
    <div class="col-12">
        <div class="game-body">
            <div class="row no-gutters">
                <div class="col-12 col-md-4">
                    <div class="game-header">Interest Calculator</div>
                    <div class="game-box text-center">
                        <p class="small">Here you can find out how much you'll make back from depositing cash amounts for a large number of days.</p>
                        <form method="post" action="{{ settings.get('website_url') }}centrobank/calculator">
                            <input class="form-control" name="cash" type="number" placeholder="Cash">
                            <input class="form-control" name="days" type="number" placeholder="Days">
                            <input type="hidden" name="token" value="{{ token }}">
                            <button class="btn btn-dark mb-0">Get Total</button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="game-header">Bank Savings Account</div>
                    <div class="game-box">
                        <ul class="statics-menu">
                            <li>
                                <span class="static-name w-50">Balance:</span>
                                <span class="static-num w-50">
                                    {% if user.timer('bank') <= time %}
                                        No balance available
                                    {% else %}
                                        ${{ user.stats().GS_bank | number_format }}
                                    {% endif %}
                                </span>
                            </li>
                            <li>
                                <span class="static-name w-50">Time Remaining:</span>
                                <span class="static-num w-50">
                                    {% if user.timer('bank') <= time %}
                                        N/A
                                    {% else %}
                                        <div data-reload-when-done data-timer-type="name" data-timer="{{ user.timer('bank') }}">
                                            <span class="timer"></span>
                                        </div>
                                    {% endif %}
                                </span>
                            </li>
                            <li>
                                <span class="static-name" style="width: 30%">Interest Figure:</span>
                                <span class="static-num text-right" style="width: 70%">3% up to $1bn. Over $1bn at 1%.</span>
                            </li>
                        </ul>
                        {% if user.timer('bank') <= time %}
                            <form method="post" action="{{ settings.get('website_url') }}centrobank/deposit" class="text-center">
                                <input class="form-control" name="cash" type="number" placeholder="Enter Amount">
                                <input type="hidden" name="token" value="{{ token }}">
                                <button class="btn btn-dark mb-0">Deposit Money</button>
                            </form>
                        {% else %}
                            <form method="post" action="{{ settings.get('website_url') }}centrobank/withdraw" class="text-center">
                                <input class="form-control" name="cash" type="number" placeholder="Enter Amount">
                                <input type="hidden" name="token" value="{{ token }}">
                                <button class="btn btn-dark mb-0">Withdraw Money</button>
                            </form>
                        {% endif %}
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="game-header">Money Transfer</div>
                    <div class="game-box">
                        <p class="small">All transfers are subject to a 12% tax charge. When sending money to a bank vault please precede the account number with a # sign.</p>
                        <form method="post" action="{{ settings.get('website_url') }}centrobank/transfer">
                            <input class="form-control" name="recipient" type="text" placeholder="Recipient">
                            <input class="form-control" name="amount" type="number" placeholder="Amount">
                            <input type="hidden" name="token" value="{{ token }}">
                            <button class="btn btn-dark mb-0">Send Money</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="game-body">
            <div class="row no-gutters">
                <div class="col-12 col-md-6">
                    <div class="game-header">Incoming Transactions</div>
                    <div class="game-box p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {% if fromTransaction is empty %}
                                        <tr>
                                            <td colspan="3" align="center">There is not any transaction yet.</td>
                                        </tr>
                                    {% endif %}
                                    {% for transaction in fromTransaction %}
                                    <tr>
                                        <td>{{ transaction.user.data().user | raw }}</td>
                                        <td>${{ transaction.amount | number_format }}</td>
                                        <td>{{ transaction.date }}</td>
                                    </tr>
                                    {% endfor %}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Total: ${{ inComing.money | number_format }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="game-header">Outgoing Transactions</div>
                    <div class="game-box p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    {% if toTransaction is empty %}
                                    <tr>
                                        <td colspan="3" align="center">There is not any transaction yet.</td>
                                    </tr>
                                    {% endif %}
                                    {% for transaction in toTransaction %}
                                        <tr>
                                            <td>{{ transaction.user.data().user | raw }}</td>
                                            <td>${{ transaction.amount | number_format }}</td>
                                            <td>{{ transaction.date }}</td>
                                        </tr>
                                    {% endfor %}
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Total: ${{ outGoing.money | number_format }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}