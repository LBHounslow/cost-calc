<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cost Calculator')) }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- Styles -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link href="/css/app.css?v7ed2dh" rel="stylesheet">

    <!-- Fonts -->
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel =
        <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>;

        var baseUrl = "<?php echo secure_url('/'); ?>";
    </script>

    <!-- Select 2 -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet"/>

    @yield('headerScripts')
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
              <?php
              if (null !== env('APP_BRAND')) {
                echo '<img src="'. env('APP_BRAND') . '" width="170" height="45" alt="' . env('APP_NAME') .'" >';
              } ?>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                @else

                    @if(auth()->user()->hasPermission('uploadFile'))
                        <li data-toggle="tooltip" data-placement="bottom" title="Upload File">
                            <a href="/uploads"><i class="fa fa-upload"></i></a>
                        </li>
                    @endif

                    @if(auth()->user()->hasPermission('reports'))
                        <li data-toggle="tooltip" data-placement="bottom" title="Reports & Analysis">
                            <a href="/reports"><i class="fa fa-line-chart"></i></a>
                        </li>
                    @endif

                    @if(auth()->user()->hasPermission('clientLookup'))
                        <li data-toggle="tooltip" data-placement="bottom" title="Client Lookup">
                            <a href="/clients"><i class="fa fa-address-card-o"></i></a>
                        </li>
                    @endif

                    @if(auth()->user()->hasPermission('settings'))
                        <li data-toggle="tooltip" data-placement="bottom" title="Settings">
                            <a href="/settings"><i class="fa fa-cog"></i></a>
                        </li>
                    @endif
                    <li data-toggle="tooltip" data-placement="bottom" title="Help">
                        <a href="/help"><i class="fa fa-question-circle"></i></a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<div class="container">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">
                    @yield('title')
                </div>

                <div class="panel-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (Session::has('flash_message'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ Session::get('flash_message') }}
                        </div>
                    @endif

                    @if (Session::has('status'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ Session::get('status') }}
                        </div>
                    @endif

                    @yield('content')
                </div>

            </div>
        </div>
    </div>

</div>
<script src="/js/spend.js"></script>
<script src="/js/piechart.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

<script>
    $('.select2').select2();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $.fn.digits = function () {
        return this.each(function () {
            $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
        })
    }
</script>

@yield('footerScripts')
</body>
</html>
