<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>{{ config('app.name', 'Campaign administration') }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" type="image/png" href="{{asset('images/favicon.png')}}"/>
	
	<link rel="stylesheet" type="text/css" href="{{asset('css/simple-grid.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('css/simple-grid.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('css/normalize.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('css/main-style.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('css/responsive.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('css/fontawesome-all.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/regular.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/fontawesome.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.modal.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom-modal.css')}}"/>
</head>

<body>

	<section class="bgColor1 vh-100 ptb30" style="">
		<div class="container" style="display: table;">
			<div>
				<div class="hideDesktop showMobile" style="text-align: center">
					<div><img width="200" src="{{asset('images/amo-logo.png')}}" ></div>
					<div class="icon-logo">
						<img src="{{asset('images/icon_online.svg')}}" >
						<img src="{{asset('images/icon_anzeige.svg')}}" >
						<img src="{{asset('images/icon_print.svg')}}" >
						<img src="{{asset('images/icon_radio.svg')}}" >
						<img src="{{asset('images/icon_tv.svg')}}" >
						<img src="{{asset('images/icon_kino.svg')}}" >
						<img style="width: 40px" src="{{asset('images/icon_ambient.png')}}" >
					</div>
				</div>
				<div class="loginBox" style="min-width: 1060px;">
					<div class="leftImgBox">
						<div class="boxLogo" style="text-align: center">
							<img  src="{{asset('images/amo-logo.png')}}" >
							<div class="icon-logo">
								<img src="{{asset('images/icon_online.svg')}}" >
								<img src="{{asset('images/icon_anzeige.svg')}}" >
								<img src="{{asset('images/icon_print.svg')}}" >
								<img src="{{asset('images/icon_radio.svg')}}" >
								<img src="{{asset('images/icon_tv.svg')}}" >
								<img src="{{asset('images/icon_kino.svg')}}" >
								<img style="width: 40px" src="{{asset('images/icon_ambient.png')}}" >
							</div>
						</div>
					</div><!--col-6-->
					<div class="rightFormBox">

						<form id="form_init" method="POST" action="{{ route('login') }}">
							@csrf
							<br>
							<br>
							<div class="form-group">
								<span class="input input--hoshi">
									<input class="input__field input__field--hoshi input-email" type="text" name="email" value="{{ old('email') }}" required autofocus/>
									<label class="input__label input__label--hoshi input__label--hoshi-color-1">
										<span class="input__label-content input__label-content--hoshi">{{ __('Email') }}</span>
									</label>
									<span class="icRight"><img src="{{asset('images/Close-R.svg')}}"></span>
								</span>
								@if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>
							<div class="form-group">
								<span class="input input--hoshi">
									<input class="input__field input__field--hoshi" type="password" id="input_psw" name="password" style="-webkit-text-security: disc;" autocomplete="off" required/>
									<label class="input__label input__label--hoshi input__label--hoshi-color-1">
										<span class="input__label-content input__label-content--hoshi">{{ __('Password') }}</span>
									</label>
									<span class="icRight"><img src="{{asset('images/Close-R.svg')}}"></span>
								</span>
								@if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
							</div>
							<div class="form-group mCenter">
								<button type="submit" class="btn">{{ __('Anmelden') }}</button>
							</div>
							<div class="form-group mCenter">
								@if (Route::has('password.request'))
									<a href="javascript:void(0);" onclick="formHide()">{{ __('Passwort vergessen?') }}</a>
								@endif
							</div>
						</form>
						<form id="form_forgotten" action="{{ route('password.email') }}" method="POST" style=" width: 70%;margin-right: auto;margin-left: auto;margin-top: 80px;display:none;">
							@csrf
							<br>
							<br>
							<br>
							<br>
							<br>
							<span class="valid-feedback"  id="sendmessageSpan" style="display:none;">
                                        <strong style="color: #d65050;">Wir haben die eine E-Mail mit deinem Passwort gesendet.</strong>
                             </span>
							<div class="form-group">
								<span class="input input--hoshi">
									<input class="input__field input__field--hoshi input-email" type="text" name="email" value="{{ old('email') }}" required/>
									<label class="input__label input__label--hoshi input__label--hoshi-color-1">
										<span class="input__label-content input__label-content--hoshi">{{ __('Email') }}</span>
									</label>
									<button class="icRight"><img src="{{asset('images/Close-R.svg')}}"></button>
								</span>
								@if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>

							<div class="form-group mCenter">
								<button type="button" class="btn btn-send"><i id="sendWaiting" class="fa fa-spinner fa-spin" style="display:none;color: white;margin-right: 7px;"></i>{{ __('Senden') }}</button>
							</div>
							<div class="form-group mCenter">
								<a href="{{url('login')}}">{{ __('Back to Login') }}</a>
							</div>
						</form>
					</div><!--col-6-->
				</div><!--loginBox-->
			</div><!--row-->
		</div><!--container-->
	</section>


	<div id="modal_confirm" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>                          
            <div class="modal-body">
                <h1>Sind Sie sicher?</h1>
                <form>
                    <div class="clearDiv"></div>
                    <div class="form-group">
                        <p>Wir haben die eine E-Mail mit deinem Passwort gesendet..</p>
                    </div>                                                      
                    <div class="clearDiv"></div>              
                    <div class="clearDiv"></div>

                    <div class="form-group btnCenterGroup textCenter">
                        <button type="button" class="btn2" rel="modal:close" id="btn_close">Abbrechen</button>
                        <button type="button" class="btn" id="btn_confirm" edit-channel="">Löschen</button>
                    </div>

                </form>
            </div>

        </div>
    </div><!--modal-->

	<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/fontawesome.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/classie.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/inputEffect.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery.modal.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/pages/common-login.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/pages/login.js')}}"></script>
	<script>

	</script>
</body>    
</html>