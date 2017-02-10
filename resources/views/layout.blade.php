<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/animate.css">

    @yield('css')

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">CORE</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="#"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Dashboard</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tasks <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Overview</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> New Task</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Categories</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Contacts <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Übersicht</a></li>
                        <li><a href="#">New Contact</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Categories</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Notes <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Overview</a></li>
                        <li><a href="#">New Notiz</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Notebooks</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Learnings <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Overview</a></li>
                        <li><a href="#">New Learning</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Categories</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Log <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Overview</a></li>
                        <li><a href="#">New Entry</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Topics</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ url('organizations') }}">My organizations</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li>
                        <a href="{{ url('logout') }}"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> {{ trans('home.logout') }}</a>
                    </li>
                @else
                    <li>
                        <a href="{{ url('login') }}"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> {{ trans('home.login') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('register') }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ trans('home.sign_up') }}</a>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container">
    <div id="app">
        @yield('content')
    </div>
    @yield('templates')
</div>

<script src="/js/app.js"></script>
@yield('js')
</body>
</html>