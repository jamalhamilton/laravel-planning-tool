@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/planning-overview.css')}}">
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="tableTopBox">
            <div class="col-3">
                <div class="searchField">
                    <input type="text" placeholder="Suchen…">
                    <button type="button"><img src="images/icon_search.svg"></button>
                </div>
            </div>
            <div class="col-9 textRight">
                <button class="btn" type="button" data-modal="#modal_channel">{{trans('language.createPlan')}}</button>
            </div>
            
            
            <div id="modal_channel" class="modal">                      
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close" >&times;</span>                         
                    <div class="modal-body">
                        <h1>{{trans('language.createPlan')}}</h1>
                        <div class="clearDiv"></div>
                        <form  action="{{route('addcampaign')}}" method="post" id="modal_campaign">
                            @csrf
                            <div class="form-group selectLine">
                                <label class="label">{{trans('language.Customer')}}</label>
                                <div class="">
                                    <input type='text' name='client_name' style="display:none;" id='client_name'/>
                                    <input type='text' name='campaign_id' style="display:none;" id='selected_id'/>
                                    <select class="select-search select-selected" required>
                                        <option value=""></option>
                                        @foreach ($clientName as $id =>$row)
                                            <option value="{{$row['ID']}}">{{$row['name']}}</option>
                                        @endforeach
                                    </select>
                                </div><!--custom-select-->
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi" type="text" name = 'campaign_name' id="input_campaignname" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.campaignName1')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="error-alert"></div>
                            <h4>{{trans('language.channelSelect')}}</h4>
                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.online1')}}
                                    <input id="channel_online" type="checkbox" class="chk-channel" data-channel="online" checked>
                                    <span class="checkmark"></span>
                                </label>                                    
                            </div>
                            <ul class="col2box">
                                <li>
                                    <div class="styled-input dateField">
                                      <input id="dt_online_start" name="dt_online_start" class="datepicker" type="text" required />

                                      <label>{{trans('language.start1')}}</label>
                                      <span></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="styled-input dateField">
                                      <input id="dt_online_end" class="datepicker" type="text" required/>
                                      <label>{{trans('language.end1')}}</label>
                                      <span></span>
                                    </div>
                                </li>
                            </ul><!--col2box-->
                            
                            <div class="clearDiv"></div>
                            <p class="form-group statusTd">
                                <label class="switch">
                                    <input type="checkbox" id="ckb_option">
                                    <span class="slider round"></span>
                                </label>{{trans('language.extraWeekMSG')}}
                            </p>
                            <div class="clearDiv"></div>
                            
                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.print1')}}
                                    <input id="channel_print" type="checkbox" class="chk-channel" data-channel="print" checked>
                                    <span class="checkmark"></span>
                                </label>                                    
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                          <input id="dt_print_start" class="datepicker" type="text" required/>
                                          <label>{{trans('language.start1')}}</label>
                                          <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                          <input id="dt_print_end" class="datepicker" type="text" required/>
                                          <label>{{trans('language.end1')}}</label>
                                          <span></span>
                                        </div>
                                    </li>
                                </ul><!--col2box-->
                            </div><!--form-group-->
                            
                           <div class="clearDiv"></div>

                            <!-- plakat -->

                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.plakat1')}}
                                    <input id="channel_plakat" type="checkbox" class="chk-channel" data-channel="plakat" checked>
                                    <span class="checkmark"></span>
                                </label>                                    
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                          <input id="dt_plakat_start" class="datepicker" type="text" required/>
                                          <label>{{trans('language.start1')}}</label>
                                          <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                          <input id="dt_plakat_end" class="datepicker" type="text" required/>
                                          <label>{{trans('language.end1')}}</label>
                                          <span></span>
                                        </div>
                                    </li>
                                </ul><!--col2box-->
                            </div><!--form-group-->

                            <div class="clearDiv"></div>

                            <!-- TV -->
                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.tv1')}}
                                    <input id="channel_tv" type="checkbox" class="chk-channel" data-channel="tv" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_tv_start" class="datepicker" type="text" required/>
                                            <label>{{trans('language.start1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_tv_end" class="datepicker" type="text" required/>
                                            <label>{{trans('language.end1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                </ul><!--col2box-->
                            </div><!--form-group-->

                            <div class="clearDiv"></div>

                            <!-- radio -->
                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.radio1')}}
                                    <input id="channel_radio" type="checkbox" class="chk-channel" data-channel="radio" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_radio_start" class="datepicker" type="text" required/>
                                            <label>{{trans('language.start1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_radio_end" class="datepicker" type="text" required/>
                                            <label>{{trans('language.end1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                </ul><!--col2box-->
                            </div><!--form-group-->

                            <div class="clearDiv"></div>

                            <!-- kino -->

                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.kino1')}}
                                    <input id="channel_kino" type="checkbox" class="chk-channel" data-channel="kino" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_kino_start" class="datepicker" type="text" required/>
                                            <label>{{trans('language.start1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_kino_end" class="datepicker" type="text" required/>
                                            <label>{{trans('language.end1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                </ul><!--col2box-->
                            </div><!--form-group-->


                            <!-- Ambient -->

                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.Ambient')}}
                                    <input id="channel_ambient" type="checkbox" class="chk-channel" data-channel="ambient" >
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_ambient_start" class="datepicker" type="text" required/>
                                            <label>{{trans('language.start1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                            <input id="dt_ambient_end" class="datepicker" type="text" required/>
                                            <label>{{trans('language.end1')}}</label>
                                            <span></span>
                                        </div>
                                    </li>
                                </ul><!--col2box-->
                            </div><!--form-group-->


                            <div class="form-group textCenter">
                                <button type="submit" class="btn btn-addrow" id="btn_submit"><i class="fa fa-spinner fa-spin" id="waitingIcon" style="display: none;color: white;margin-right: 5px;"></i>{{trans('language.Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--modal-->
            
            <div id="modal_confirm" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>                          
                    <div class="modal-body">
                        <h1>{{trans('language.areyousure')}}</h1>
                        <form>
                            <div class="clearDiv"></div>
                            <div class="form-group">
                                <p>{{trans('language.delCtgMsg')}}</p>
                            </div>                                                      
                            <div class="clearDiv"></div>              
                            <div class="clearDiv"></div>

                            <div class="form-group btnCenterGroup textCenter">
                                <button type="button" class="btn2" id="btn_close">{{trans('language.Cancel')}}</button>
                                <button type="button" class="btn" id="btn_confirm" edit-channel="">{{trans('language.delete')}}</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div><!--modal-->

            <!--modal-->

            <div id="modal_confirm2" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="modal-body">
                        <h1>{{trans('language.warning_channel')}}</h1>
                        <form>
                            <div class="clearDiv"></div>
                            <div class="form-group">
                                <p>{{trans('language.warning_channel_content')}}</p>
                            </div>
                            <div class="clearDiv"></div>
                            <div class="clearDiv"></div>

                            <div class="form-group btnCenterGroup textCenter">
                                <button type="button" class="btn" id="btn_close">{{trans('language.Ok')}}</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
            <!--modal-->

            <div class="col-12-sm selectFiltern">
                <label style="padding-top:15px;">{{trans('language.filterBy')}}</label>
                <div class="custom-select filter-select" data-field="1">
                    <select>
                        <option value="1">{{trans('language.allPlan')}}</option>
                        <option value="2">{{trans('language.active')}}</option>
                        <option value="5">{{trans('language.inactive')}}</option>
                       <!--  <option value="3">{{trans('language.live')}}</option> -->
                        <option value="4">{{trans('language.completed')}}</option>
                    </select>
                </div><!--custom-select-->
                <div class="custom-select filter-select" data-field="2">
                    <select>
                        <option >{{trans('language.allCustomer')}}</option>
                        @foreach ($clientName as $id =>$row)
                            <option value="{{$row['ID']}}">{{$row['name']}}</option>
                        @endforeach            
                    </select>
                </div><!--custom-select-->
                
            </div><!--col-5-->
        </div><!--tableTopBox-->
        <div class="col-12 tableScroll">
            <div class="demo-x">
            <table class="table planOverview display"  id="example" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{trans('language.customer1')}}</th>
                        <th>{{trans('language.campaignName1')}}</th>
                        <th style="width: 120px;">{{trans('language.status1')}} </th>
                        <th style="min-width: 380px;">{{trans('language.channels1')}}</th>
                        <th style="min-width: 235px;">{{trans('language.duration1')}}</th>
                        <th style="min-width: 50px;"></th>
                    </tr>
                </thead>
                <tbody class="tbody">

                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                           
                    </tr>
                </tfoot>
            </table>
            </div>
        </div><!-- tableScroll-->
        <div class="col-12">
            <ul class="pagination">

            </ul><!--pagination-->
        </div>  
        
    </div><!--row-->
</div>
@endsection     

@section('page-js')
<script type="text/javascript" src="{{asset('js/pages/planning-overview.js')}}"></script>
@endsection
