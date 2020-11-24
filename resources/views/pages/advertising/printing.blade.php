@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/advertising-printing.css')}}">
@endsection

@section('content')

<style>
    .table{
        /*min-width: 1700px;*/
    }
</style>
<div class="container">
    <input type="hidden" id="campaign_id" data-id="{{$campaign_id}}" value="{{$campaign_id}}">
    <input type ="hidden" value="{{$endWeek-$startWeek+1}}" id="weeknum">
    <div class="row">
        <div class="tabHeadTitle">
            <h3>{{ $campaignName }} <a href="{{url('planning')}}"><span class="close">&times;</span></a></h3>
        </div><!--tabHeadTitle-->

        <div class=" border-redius-l-0">
            <div class="whiteBgWrap tab">
                <a href="{{url('planning/overview?id=').$campaign_id}}" class="tablinks">{{trans('language.overview')}}</a>
                @foreach ($channels as $channel)
                <button class="tablinks" onclick="openCity(event, '{{ucfirst($channel["name"])}}')" id="{{($channel['name'] == 'online') ? 'defaultOpen' : ''}}">{{ucfirst($channel['name'])}}</button>
                @endforeach
                <div class="tabRightBtns">
                    <button class="btn2" id="btn_channel_modal">{{trans('language.editChannel')}}</button>
                </div>
            </div>

            <div id="modal_channel" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close" >&times;</span>
                    <div class="modal-body">
                        <h1>{{trans('language.editChannel')}}</h1>
                        <div class="clearDiv"></div>
                        <div class="clearDiv"></div>
                        <form id="form_add_channel">
                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.online1')}}
                                    <input id="channel_online" type="checkbox" class="chk-channel" data-channel="online" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <ul class="col2box">
                                <li>
                                    <div class="styled-input dateField">
                                        <input id="dt_online_start" class="datepicker" type="text" required />
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
                                </label>
                                {{trans('language.extraWeekMSG')}}
                            </p>
                            <div class="clearDiv"></div>

                            {{--	<div class="form-group">
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
                                --}}
                            <div class="clearDiv"></div>

                            <div class="form-group textCenter">
                                <button type="submit" class="btn" id="btn_save_channel">
                                    <i class="fa fa-spinner fa-spin" id="waitingIcon" style="display: none;color: white;margin-right: 5px;"></i>{{trans('language.Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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


            <div id="Online" class="tabcontent pdding-0">
                <div class="row">
                    <div class="demo-x lightScrollbar">
                        <div class="tab innerTab pdding-0">
                            <a href="{{url('planning/params?id=').$campaign_id}}" class="tablinks">{{trans('language.planningParameter1')}}</a>
                            <a href="{{url('media?id=').$campaign_id}}" class="tablinks">{{trans('language.mediaPlanning1')}}</a>
                            <a href="javascript:void(0);" class="tablinks view">{{trans('language.advertisingPrintDist1')}}</a>
                        </div>
                    </div><!--lightScrollbar-->
                    @php
                        $tableWidth = 470 + count($dateArray)*85;
                    @endphp
                    <div class="innerTabcontent">
                        <div class="whiteBgWrap row pdding-0">
                            <div class="mediaPlanningTable demo-x" style="padding-top: 15px;">
                                <table class="table" style='table-layout: fixed; width: {{$tableWidth}}px'>
                                    <thead>
                                    <tr class="right-text">
                                        <th width="230"  style="font-size : 12px;text-align: left !important;">{{trans('language.platzierung')}}</th>
                                        <th width="120"  style="font-size : 12px;">{{trans('language.advertisingPrinting')}}</th>
                                        <th width="120" style="font-size : 12px;">{{trans('language.advertisingControl')}}</th>
                                        @foreach($dateArray as $idx =>$date)

                                        <th width="85" style="font-size : 12px; white-space:pre-wrap; text-align: left!important;">{{$date}}</th>

                                        @endforeach

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($data as $idx => $table)
                                    <tr class="categorytr" data-ctgid="{{$table['id']}}" >
                                        <td colspan="{{$endWeek-$startWeek+4}}" class="pdding-0 border-0" >
                                            <button class="collapsible" style='font-size : 12px; '>{{$table['name']}} </button>
                                            <div class="contentCollap">
                                                <table class="table tableDisplay tableDisplayField tableAlignRight tableDisplayFieldPadding table-adv">
                                                    <thead>
                                                    <tr>
                                                        <th width="230"></th>
                                                        <th width="120"></th>
                                                        <th width="120"></th>
                                                        @foreach($dateArray as $idx=>$date)
                                                        <th swidth="85"></th>
                                                        @endforeach
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($table['media'] as $idR => $media)
                                                        @php
                                                        //dd($media['distCount']);
                                                        @endphp
                                                    <tr class="blankInputTd_adv" data-id={{$media['id']}}>
                                                        <td width="230">
                                                            @if ($media['placement'] != '')
                                                            <input  type = 'text' value = '{{$media['placement']}}' style = 'text-align: left;' disabled>
                                                            @else
                                                                <input  type = 'text' value = 'folgt' style = 'text-align: left;' disabled>
                                                            @endif
                                                        </td>
                                                        <td width="120">
                                                            @if ($media['adPrint'] == '0' || $media['adPrint'] == '' || $media['adPrint'] == 0)
                                                            <input type="text"  value ="folgt"  disabled>
                                                            @else
                                                            <input type="text"   value ="{{$media['adPrint']}}"  disabled>
                                                            @endif
                                                        </td>
                                                        <td width="120">
                                                            @if ($media['adWeekSum'] == '0' || $media['adWeekSum'] == '' || $media['adWeekSum'] == 0)
                                                                <input type="text"  value ="folgt" style="color:{{$media['overflow']}}" disabled>
                                                            @else
                                                                <input type="text"   value ="{{$media['adWeekSum']}}" style="color:{{$media['overflow']}}" disabled>
                                                            @endif
                                                        </td>
                                                        @foreach($media['distCount'] as $idx => $count)
                                                        <td width="85">
                                                            @if ($media['adPrint'] == '0' || $media['adPrint'] == '' || $media['adPrint'] == 0)
                                                                <input type="text" value ="folgt" data-weeknum="{{$idx}}">
                                                            @elseif($count == 0)
                                                                <input type="text"  value ="" data-weeknum="{{$idx}}">
                                                            @else
                                                                <input type="text"  value ="{{$count}}" data-weeknum="{{$idx}}">
                                                            @endif
                                                        </td>
                                                        @endforeach
                                                        @if($hasExtraWeek == 1 && $idR == 0)
                                                        <td width="85"  rowspan="<?php echo count($table['media'])?>" class="bg1 textCenter" style= "font-size: {{(count($table['media'])==1) ? 10:10}}px;"}}>{{trans('language.adverMSG')}}</td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div><!--contentCollap-->
                                            <table class="table tableDisplay tableDisplayField totalDisplayFooter" style="width: auto">
                                                <thead>
                                                <tr>
                                                    <th width="230"></th>
                                                    <th width="120"></th>
                                                    <th width="120"></th>
                                                    @foreach($dateArray as $idx =>$date)
                                                    <th width="85"></th>
                                                    @endforeach

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="blankInputTd_adv subtotaltr">
                                                    <!--<td>{{trans('language.totalPrintKW')}}</td>-->

                                                    <td width="230" style="font-size: 12px">Total {{$table['name']}}</td>
                                                    <td width="120" style="font-size: 12px" class="subtotal"></td>
                                                    <td width="120" style="font-size: 12px" class="subtotal"></td>
                                                    @foreach($dateArray as $date)
                                                            <td width="85" style="font-size: 12px" class="subtotal"></td>
                                                    @endforeach

                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <table class="table tableDisplay tableDisplayField totalDisplayFooter totalMedia" style='table-layout: fixed; width: {{$tableWidth}}px'>
                                    <thead>
                                    <tr>
                                        <th width="230" style="font-size: 12px"></th>
                                        <th width="120" style="font-size: 12px"></th>
                                        <th width="120"></th>
                                        @foreach($dateArray as $idx =>$date)
                                        <th style="width:85px; font-size: 12px"></th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="blankInputTd_adv  alltotaltr">
                                        <td width="230" style="font-size: 12px">{{trans('language.totalPrintKW')}} </td>
                                        <td width="120" style="font-size: 12px" class="alltotal"></td>
                                        <td width="120" style="font-size: 12px" class="alltotal"></td>
                                        @foreach($dateArray as $date)
                                        <td width="85" style="font-size: 12px" class= "alltotal" ></td>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!--mediaPlanningTable-->

                            <div class="clearDiv"></div>
                            <div class="clearDiv"></div>

                        </div>
                    </div>

                </div>
            </div>

            <div id="Print" class="tabcontent">
                <h3>{{trans('language.print1')}}</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div><!--tabcontent-->

            <div id="Plakat" class="tabcontent">
                <h3>{{trans('language.plakat1')}}</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div><!--tabcontent-->
        </div><!--whiteBgWrap-->
    </div>
</div>
@endsection

@section('page-js')
<script type="text/javascript" src="{{asset('js/pages/advertising-printing.js')}}"></script>
@endsection
