<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>{{ config('app.name', 'Campaign administration') }}</title>	
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body>

<section class="bgColor1 vh-100 ptb30">
	<div class="container">
		<div class="row">
			<div class="loginBox">
				<div class="col-6 leftImgBox">
					<img src="{{asset('images/logoIcon.svg')}}">
					<h1>{{ __('Headline Anmelden') }}</h1>
					<img src="{{asset('images/Bitmap.png')}}" class="manDesk">
				</div><!--col-6-->
				<div class="col-6 rightFormBox">
					<form id="form_init" method="POST" action="{{ route('password.update') }}">
						@csrf
						<br>
						<br>
						<h1>{{ __('Reset password') }}</h1>
						<div class="form-group">
							<span class="input input--hoshi">
								<input class="input__field input__field--hoshi input-email" type="text" name="email" value="{{ old('email') }}" required autofocu/>
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
						<div class="form-group">
							<span class="input input--hoshi">
								<input class="input__field input__field--hoshi input-reset-psw" type="password" name="password" required/>
								<label class="input__label input__label--hoshi input__label--hoshi-color-1">
									<span class="input__label-content input__label-content--hoshi">{{ __('Password') }}</span>
								</label>
								<button class="icRight"><img src="{{asset('images/Close-R.svg')}}"></button>
							</span>
							@if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
						</div>
						<div class="form-group">
							<span class="input input--hoshi">
								<input class="input__field input__field--hoshi input-reset-psw" type="password" name="password_confirmation" required/>
								<label class="input__label input__label--hoshi input__label--hoshi-color-1">
									<span class="input__label-content input__label-content--hoshi">{{ __('Confirm Password') }}</span>
								</label>
								<button class="icRight"><img src="{{asset('images/Close-R.svg')}}"></button>
							</span>
						</div>
						<div class="form-group mCenter">
							<button type="submit" class="btn">{{ __('Senden') }}</button>
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
     
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/fontawesome.js')}}"></script>
<script type="text/javascript" src="{{asset('js/classie.js')}}"></script>
<script type="text/javascript" src="{{asset('js/inputEffect.js')}}"></script>
<script type="text/javascript" src="{{asset('js/pages/reset-password.js')}}"></script>

</body>              
</html>