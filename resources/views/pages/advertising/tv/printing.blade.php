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
        <input type ="hidden" value="{{$week_nums}}" id="weeknum">
        <input type="hidden" id="active_channel" value="{{$activetab}}" />
        <div class="row">
            <div class="tabHeadTitle">
                <h3>{{ $campaignName }} <a href="{{url('planning')}}"><span class="close">&times;</span></a></h3>
            </div><!--tabHeadTitle-->

            <div class=" border-redius-l-0">
                @include('layouts.channels')


                <div id="Online" class="tabcontent pdding-0">
                    <div class="row">
                        <div class="demo-x lightScrollbar">
                            <div class="tab innerTab pdding-0">
                                <a href="{{url('planning/params?channel='.$activetab.'&id=').$campaign_id}}" class="tablinks">{{trans('language.planningParameter1')}}</a>
                                <a href="{{url('media?channel='.$activetab.'&id=').$campaign_id}}" class="tablinks">{{trans('language.mediaPlanning1')}}</a>
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
                                            <th width="230"  style="font-size : 12px;text-align: left !important;">SENDER</th>
                                            <th width="120"  style="font-size : 12px;">GRP's</th>
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
                                                                            <input  type = 'text' value = 'tbd' style = 'text-align: left;' disabled>
                                                                        @endif
                                                                    </td>
                                                                    <td width="120">
                                                            @if ($media['grps'] == '0' || $media['grps'] == '' || $media['grps'] == 0)
                                                                            <input type="text"  value ="tbd"  disabled>
                                                                        @else
                                                                            <input type="text"   value ="{{$media['grps']}}"  disabled>
                                                                        @endif
                                                                    </td>
                                                                    <td width="120">
                                                            @if ($media['grps'] == '0' || $media['grps'] == '' || $media['grps'] == 0)
                                                                <input type="text"  value ="" style="color:{{$media['overflow']}}" disabled>
                                                                        @else
                                                                            <input type="text"   value ="{{$media['adWeekSum']}}" style="color:{{$media['overflow']}}" disabled>
                                                                        @endif
                                                                    </td>

                                                                    @foreach($media['distCount'] as $idx => $count)
                                                                        <td width="85">
                                                            @if ($media['grps'] == '0' || $media['grps'] == '' || $media['grps'] == 0 || $count=='tbd')
                                                                <input type="text" value ="{{$count == 'tbd'?'tbd':''}}" data-weeknum="{{$idx}}">
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


            </div><!--whiteBgWrap-->
        </div>
    </div>
@endsection

@section('page-js')
    <script type="text/javascript" src="{{asset('js/pages/advertising-printing.js')}}"></script>
@endsection
