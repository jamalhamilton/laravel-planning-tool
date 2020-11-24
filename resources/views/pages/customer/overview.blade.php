@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/customer-creates.css')}}">
@endsection

@section('content')
<div class="container customerCreatesBox">
    <div class="row">
        <div class="tabHeadTitle">
            <h3>{{ session('customerName')}} <a href="{{url('customer')}}"><span class="close">&times;</span></a></h3>
        </div><!--tabHeadTitle-->
        
        <div class=" border-redius-l-0">
            <div id="modal1" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>                          
                    <div class="modal-body">
                        <h1>{{trans('language.addNewCostcategory')}}</h1>
                        <div class="clearDiv"></div>
                        <div class="clearDiv"></div>
                        <form>
                        @csrf                              
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi" id="input_category" type="text" />
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">NAME DER KATEGORIE</span>
                                    </label>
                                </span>
                            </div>
                            
                            <div class="clearDiv"></div>
                            <div class="clearDiv"></div>
                            
                            <div class="form-group textCenter">
                                <button type="button" class="btn" id="btn_category">Erstellen</button>
                            </div>                              
                        </form>
                    </div>
                    
                </div>
            </div><!--modal-->
            <div id="modal2" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>                          
                    <div class="modal-body">
                        <h1>{{trans('language.addNewCustomer')}}</h1>
                        <div class="clearDiv"></div>
                        <div class="clearDiv"></div>
                        <div>                     
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi" type="text" id="input_cost" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.name')}}</span>
                                    </label>
                                </span>
                            </div>
                            
                            <div class="clearDiv"></div>
                            <div class="clearDiv"></div>
                            
                            <div class="form-group textCenter">
                                <button type="button" class="btn" id="btn_cost">{{trans('language.Save')}}</button>
                            </div>                              
                        </div>
                    </div>
                    
                </div>
            </div><!--modal-->

            <div class="whiteBgWrap row">
                <div class="col-6 relative">
                    <button id="btn_contact_edit" class="editTopBtn"><img src="{{asset('images/icon_edit.svg')}}"></button>
                    <h1>{{trans('language.Kontaktdaten')}}</h1>
                    <form id="form_contact" enctype="multipart/form-data">
                        <!-- @csrf -->
                        <input id="clientID" type="hidden" name="clientID" value="{{$data['clientID']}}"/>
                        <div class="form-group">
                            <span class="input input--hoshi">
                                <input id="client_name" class="input__field input__field--hoshi" type="text" name="name" value="{{$data['clientName']}}" disabled />
                                <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                    <span class="input__label-content input__label-content--hoshi">{{trans('language.name')}}</span>
                                </label>
                            </span>
                        </div>
                        <div class="form-group">
                            <span class="input input--hoshi">
                                <input id="client_address" class="input__field input__field--hoshi" type="text" name="street" value="{{$data['clientStreet']}}" disabled/>
                                <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                    <span class="input__label-content input__label-content--hoshi">{{trans('language.streetNr')}}</span>
                                </label>
                            </span>
                        </div>
                        <ul class="col_3_9">
                            <li>
                                <div class="form-group">
                                    <span class="input input--hoshi">
                                        <input id="client_zip" class="input__field input__field--hoshi" type="text" name="postcode" value="{{ $data['clientPostcode']}}" disabled>
                                        <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                            <span class="input__label-content input__label-content--hoshi">{{trans('language.plz')}}</span>
                                        </label>
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <span class="input input--hoshi">
                                        <input id="client_state" class="input__field input__field--hoshi" type="text" name="state" value="{{ $data['clientState']}}" disabled>
                                        <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                            <span class="input__label-content input__label-content--hoshi">{{trans('language.ort')}}</span>
                                        </label>
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <div class="clearDiv"></div>
                        <div class="clearDiv"></div>
                        <div class="form-group textRight">
                            <div class="uploadFieldBox">
                                <div class="imgLogoBox">
                                    <img src="{{$data['clientLogo']}}" height="130px" width="130px" id="img_upload">
                                </div>
                                <div class="btn showUploadButton" id="logo_display" style="margin-top:50px;" disabled>
                                    <span onclick="openFileDialog()">{{trans('language.logoUpload')}}</span>
                                    <input type="file" id="input_upload" onchange="photoPreview()" style="display:none" name="image" disabled />
                                </div>
                            </div>
                        </div>

                        <div class="add-empty-line" style="display:none;"></div>

                        <div class="form-group textRight">
                            <button id="btn_contact_save" type="button" class="btn" style="display:none">{{trans('language.Save')}}</button>
                        </div>
                    </form>

                    <div class="clearfix"></div>
                </div><!--col-6-->

                <div class="col-6 relative">
                    <button class="editTopBtn" id="btn_edit"><img src="{{asset('images/icon_edit.svg')}}"></button>
                    
                    <div class="leftRightBox part-adserving">
                        <h1>{{trans('language.adservingSystems')}}</h1>
                        <form method="post" action="{{url('customer/overview')}}" id="form_calculate">
                        @csrf
                            <div class="cost-category" data-category-id="1" data-category-type="1">
                                <p >{{trans('language.planning&setup')}}<button type="button" class="addLessBtn icFr" disabled></button>
                                    <div class="clearfix"></div>
                                </p>
                                <div class="clearfix"></div>

                                @foreach ($services['hourlyServices'] as $service)
                                <div class="col-6 leftTextBox">
                                    <strong>{{trans('language.CHF/h')}}</strong>
                                </div>
                                <div class="col-6 rightTextBox">
                                    <span class="input input--hoshi">
                                        <input class="input__field input__field--hoshi" type="text" data-id="{{$service->ID}}" data-service-id="{{$service->serviceID}}" value="{{empty($service->value) ? '' : $service->value}}" disabled>
                                        <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                            <span class="input__label-content input__label-content--hoshi">{{$service->name}}</span>
                                        </label>
                                    </span>
                                </div>
                                <div class="clearfix"></div>
                                @endforeach
                            </div>

                            <div class="clearDiv"></div>

                            <div class="cost-category" data-category-id="2" data-category-type="2">
                                <p>{{trans('language.techCost')}}<!--<button type="button" class="addLessBtn icFr" disabled ></button>--></p>
                                <div class="clearfix"></div>
                                @foreach($services['fixedServices'] as $service)
                                @if($service->name == 'Maintenance')
                                <div style="display: none">
                                @else
                                <div class="col-6 leftTextBox">
                                @endif
                                    @if($service->name == 'Traffic-Kosten')
                                    <strong>
                                        {{trans('language.CHF')}}
                                        {{--<input class="radio-btn radio-btn-left" type="radio" checked="checked" name="radio{{$service->serviceID}}" disabled>--}}
                                        {{--<span class="checkmark"></span>--}}
                                    </strong>
                                    @elseif($service->name == 'Maintenance')
                                    <strong>
                                        {{trans('language.pauschal')}}
                                        {{--<input class="radio-btn radio-btn-right" type="radio" checked="checked" name="radio{{$service->serviceID}}" disabled>--}}
                                        {{--<span class="checkmark"></span>--}}
                                    </strong>
                                    @endif
                                </div>
                                @if($service->name == 'Maintenance')
                                <div style="display: none">
                                @else
                                <div class="col-6 rightTextBox">
                                @endif
                                    <span class="input input--hoshi">
                                        <input class="input__field input__field--hoshi" type="text" data-id="{{$service->ID}}" data-service-id="{{$service->serviceID}}" value="{{empty($service->value) ? '' : $service->value}}" is-flat-rate="{{$service->isFlatrate}}" disabled>
                                        <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                            <span class="input__label-content input__label-content--hoshi">{{$service->name}}</span>
                                        </label>
                                    </span>
                                </div>
                                <div class="clearfix"></div>
                                @endforeach
                            </div>

                            <div class="clearDiv"></div>

                            <div class="cost-category" data-category-id="3" data-category-type="3">
                                <p>{{trans('language.honour')}}<!--<button type="button" class="addLessBtn icFr" disabled></button>--></p>
                                <div class="clearfix"></div>

                                @foreach($services['percentualServices'] as $service)

                                @if($service->name == 'Zusatzhonorar')
                                    <div style="display: none">
                                @else
                                <div class="col-6 leftTextBox">
                                @endif
                                    @if($service->name == 'Honorar Auf Media n/n')
                                    <strong>{{trans('language.percent')}}
                                        {{--<input class="radio-btn radio-btn-left" type="radio" checked="checked" name="radio{{$service->serviceID}}" disabled>--}}
                                        {{--<span class="checkmark"></span>--}}
                                    </strong>

                                    @elseif($service->name == 'Zusatzhonorar')

                                    <strong>{{trans('language.pauschal')}}
                                        {{--<input class="radio-btn radio-btn-right" type="radio" checked="checked" name="radio{{$service->serviceID}}" disabled>--}}
                                        {{--<span class="checkmark"></span>--}}
                                    </strong>
                                    @endif
                                </div>
                                @if($service->name == 'Zusatzhonorar')
                                <div style="display: none">
                                @else
                                <div class="col-6 rightTextBox">
                                @endif
                                    <span class="input input--hoshi">
                                        <input class="input__field input__field--hoshi" type="text" data-id="{{$service->ID}}" data-service-id="{{$service->serviceID}}" value="{{empty($service->value) ? '' : $service->value}}" is-flat-rate='{{$service->isFlatrate}}' disabled>
                                        <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                            <span class="input__label-content input__label-content--hoshi">{{$service->name}}</span>
                                        </label>
                                    </span>
                                </div>
                                <div class="clearfix"></div>
                                @endforeach
                            </div>

                            <div class="clearDiv"></div>
                            <div class="clearDiv"></div>

                            <div class="form-group textRight">
                                <button id="btn_save" type="button" class="btn" style="display:none;">{{trans('language.Save')}}</button>
                            </div>
                        </form>
                    </div><!--row / leftRightBox-->                     
                </div><!--col-6-->
            </div><!--row-->
        </div><!--whiteBgWrap-->
    </div>
</div>
@endsection     

@section('page-js')
<script>
    var strCHF = "{{trans('language.CHF')}}";
    var strpauschal = "{{trans('language.pauschal')}}";
    var strPercent = "{{trans('language.percent')}}";
</script>
<script type="text/javascript" src="{{asset('js/pages/customer-creates.js')}}"></script>
@endsection
