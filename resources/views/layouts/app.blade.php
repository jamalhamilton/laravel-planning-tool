<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Campaign administration') }}</title>  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    <link rel="shortcut icon" type="image/png" href="{{asset('images/favicon.png')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/simple-grid.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/normalize.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.modal.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom-modal.css')}}"/>
    <link href="{{asset('css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.mCustomScrollbar.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/main-style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/components.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/responsive.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/fontawesome-all.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/regular.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/fontawesome.css')}}">




{{--    <link rel="stylesheet" type="text/css" href="{{asset('js/dataTables/datatables.min.css')}}">--}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.7/css/perfect-scrollbar.min.css" />

    @yield('page-css')
</head>
<body>

<header>
    <nav class="navbar">
        <a class="navbar-brand" href="#">Campaign</a>       
        <div class="collapse navbar-collapse">
            <button type="button" style="display:none;" class="mobileMenu"><i class="fa fa-bars"></i></button>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link iconMenu icPlaung {{($tabIndex == 1) ? 'active' : ''}}" href="{{url('planning')}}">Planung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link iconMenu icKunden {{($tabIndex == 2) ? 'active' : ''}}" href="{{url('customer')}}">Kunden</a>
                </li>

                @if(Auth::user()->group_name == 'Adminstrators')
                <li class="nav-item">
                    <a class="nav-link iconMenu icSettings {{($tabIndex == 3) ? 'active' : ''}}" href="{{url('users')}}">Settings</a>
                </li>
                @endif

                <li class="userItem" style="margin-top: 20px">                        
                    <a class="nav-link" href="{{ route('logout') }}" style="height: 20px;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <li class="userItem" style="padding-top: 10px;">                        
                    <span class="userImgRound" style="margin-right:-25px;"><img src="{{Auth::user()->picture}}" style="height:40px;"></span> 
                </li>
            </ul>
        </div>
    </nav>
</header>

<section class="ptb30">
    @yield('content')
</section>
     
<script type="text/javascript">
var base_url = "{{url('/')}}";
</script>
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/fontawesome.js')}}"></script>
<script type="text/javascript" src="{{asset('js/classie.js')}}"></script>
<script type="text/javascript" src="{{asset('js/custom-select.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mCustomScrollbar.js')}}"></script>
<script type="text/javascript" src="{{asset('js/inputEffect.js')}}"></script>
{{--<script type="text/javascript" src="{{asset('js/jquery.dataTables.js')}}"></script>--}}
<script type="text/javascript" src="{{asset('js/dataTables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/dataTables/api.js')}}"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/pages/common.js')}}"></script>
<script type="text/javascript" src="{{asset('js/pages/check-channel.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.modal.js')}}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.7/js/min/perfect-scrollbar.jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
@yield('page-js')
<script type="text/javascript">
    $(function() {
        $('.multiselect-ui').multiselect();
    });
</script>
</body>
</html>