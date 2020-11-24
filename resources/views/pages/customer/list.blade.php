@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/customer-creates.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="tableTopBox">
            <div class="col-3">
                <div class="searchField">
                <?php $place = trans('language.search'); ?> 
                    <input type="text"  oninput="filterContent(this)" placeholder="{!!$place!!}">
                    <button type="button"><img src="/images/icon_search.svg"></button>
                </div>
            </div>
            <div class="col-9 textRight">
                <button class="btn" type="button" data-modal="#modal1">{{trans('language.newCustomer')}}</button>
            </div>
            <div id="modal1" class="modal">                     
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>                          
                    <div class="modal-body">
                        <h1>{{trans('language.newCustomer')}}</h1>
                        <div class="clearDiv"></div>
                        <form method="POST" action="{{route('addcontact')}}">
                            @csrf
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi" type="text" name="customer_name" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.name')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi" type="text" name="customer_street" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.streetNr')}}</span>
                                    </label>
                                </span>
                            </div>
                            
                            <ul class="col_3_9">
                                <li>
                                    <div class="form-group">
                                        <span class="input input--hoshi">
                                            <input class="input__field input__field--hoshi" type="text" name="customer_postcode" required/>
                                            <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                                <span class="input__label-content input__label-content--hoshi">{{trans('language.plz')}}</span>
                                            </label>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-group">
                                        <span class="input input--hoshi">
                                            <input class="input__field input__field--hoshi" type="text" name="customer_state" required/>
                                            <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                                <span class="input__label-content input__label-content--hoshi">{{trans('language.ort')}}</span>
                                            </label>
                                        </span>
                                    </div>
                                </li>
                            </ul>
                            
                            <div class="clearDiv"></div>
                            <div class="clearDiv"></div>
                            
                            <div class="form-group textCenter">
                                <button type="submit" class="btn">{{trans('language.confirm')}}</button>
                            </div>
                            
                        </form>
                    </div>
                    
                </div>
            </div><!--modal-->
            
            <div class="col-12-sm">
                <ul class="logoList">
                    @foreach($clients as $client)
                    <li>
                        <div class="imgLogoBox" data-name="{{ $client->name }}" style="position:relative;">
                            <div class="customer-title">{{ $client->name }}</div>
                            <a href="{{url('customer/overview?id='.$client->ID)}}"><img src="{{ $client->logo }}" height="130px" width="130px"></a>
                        </div><!--imgLogoBox-->
                    </li>
                    @endforeach
                </ul><!--logoList-->
            </div><!--col-5-->
        </div><!--tableTopBox-->
    </div>
</div>
@endsection     

@section('page-js')
<script type="text/javascript" src="{{asset('js/pages/customer-overview.js')}}"></script>
@endsection
