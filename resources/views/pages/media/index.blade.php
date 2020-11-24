@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/media-planning.css')}}">
@endsection

@section('content')
<div class="container">
    <input type="hidden" id="campaign_id" data-id="{{$_GET['id']}}">
    <input type="hidden" id="active_channel" value="{{$activetab}}">

    <div class="row">
        <div class="tabHeadTitle">
            <h3>{{ $campaignName}} <a href="{{url('planning')}}"><span class="close">&times;</span></a></h3>
        </div><!--tabHeadTitle-->
        
        <div class=" border-redius-l-0">

            @include('layouts.channels')


            <div id="Online" class="tabcontent pdding-0">
                <div class="row">
                    <div class="demo-x lightScrollbar">
                        <div class="tab innerTab pdding-0">
                            <a href="{{url('planning/params?channel='.$activetab.'&id=').$_GET['id']}}" class="tablinks">{{trans('language.planningParameter1')}}</a>
                            <a href="javascript:void(0)" class="tablinks view">{{trans('language.mediaPlanning1')}}</a>
                            <a href="{{url('advertising?channel='.$activetab.'&id=').$_GET['id']}}" class="tablinks">{{trans('language.advertisingPrintDist1')}}</a>
                        </div>
                    </div><!--lightScrollbar-->
                    <div class="innerTabcontent">
                        <div class="whiteBgWrap row pdding-0">
                            <div class="mediaPlanningBlack">
                                <img src="{{asset('images/ic-information.svg')}}" class="ic-info">
                                <div class="clearDiv"></div>
                                <p>{{trans('language.addctgmsg2')}}</p>
                                <div class="clearDiv"></div>
                                <div class="btnCenterGroup">
                                    <button type="button" class="btn" data-modal="#modal3">{{trans('language.addCategory1')}}</button>
                                    <button type="button" class="btn" data-modal="#modal4">{{trans('language.planduplicate')}}</button>
                                </div>
                                <div id="modal3" class="modal">                     
                                    <!-- Modal content -->
                                    <div class="modal-content">                     
                                        <div class="modal-body">
                                            <h1>{{trans('language.addCategory1')}}</h1>
                                            <div class="clearDiv"></div>
                                            <p>{{trans('language.editCtgMSG')}}</p>
                                            <form action="{{('/media/fill?channel='.$activetab.'&id=').$_GET['id']}}" method="post" id="new_planning">
                                                @csrf
                                                @if(sizeof($data) > 0)
                                                    <div class="form-group selectLine">
                                                        <div class="custom-select" style="width: 100%;">
                                                            <label class="label">{{trans('language.category')}}</label>
                                                            <select id="constant-kategorie">
                                                                <!-- <option value="0">{{trans('language.category')}}</option> -->
                                                                <option value=""></option>
                                                                @foreach ($data as $row)
                                                                    <option value="{{$row['ID']}}">{{$row['name']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div><!--custom-select-->
                                                    </div>
                                                @endif

                                                <div class="form-group addLessBox"> 
                                                    <span class="input input--hoshi">
                                                        <input class="input__field input__field--hoshi" type="text" id="modal3_indi_category"/>
                                                        <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                                            <span class="input__label-content input__label-content--hoshi">{{trans('language.indivisualctg')}}</span>
                                                        </label>
                                                    </span>
                                                    <button type="button" class="button" id="individuelle-kategorie"><img src="{{asset('images/icon_grun_moreInfo_gray.png')}}" style="cursor: initial;" id="modal3_indi_img"></button>
                                                </div>
                                                <div class="form-group" id="kategorie-list">
                                                    @foreach ($currentCategories as $idX => $ctg)
                                                    <span class="addOpt" data-id="{{$idX}}">
                                                        <button class="del-kategorie"><img src="{{asset('images/Close.svg')}}"></button>
                                                        {{$ctg->name}}
                                                    </span>
                                                    @endforeach
                                                </div>
                                                <div class="clearDiv"></div>
                                                <div class="clearDiv"></div>

                                                <div class="form-group textCenter">
                                                    <input type="text" name="categories" id="categories" style="display: none;" data-id="<?=$_GET['id']?>"> 
                                                    <button type="button" class="btn" id="btn_newCtg"><i class="fa fa-spinner fa-spin" id="waitingIcon1" style="display: none;color: white;margin-right: 5px;"></i>{{trans('language.confirm')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div><!--modal-->
                                <div id="modal4" class="modal">                     
                                    <!-- Modal content -->
                                    <div class="modal-content">                     
                                        <div class="modal-body">
                                            <h1>{{trans('language.planduplicate')}}</h1>
                                            <div class="clearDiv"></div>
                                            <p>{{trans('language.selectduplicate')}}</p>
                                            <form method="POST" action="{{('/media/fill?channel='.$activetab.'&id=').$_GET['id']}}" id="mediaForm">
                                                @csrf
                                                <div class="form-group selectLine"> 
                                                    <div class="custom-select" style="width: 100%;">
                                                        <label class="label">{{trans('language.campaignName')}}</label>
                                                        <select class="select-kategorie">
                                                            <!-- <option value="0">{{trans('language.category')}}</option> -->
                                                            @foreach ($campaings as $row)
                                                                <option value="{{$row['id']}}">{{$row['name']}}</option>
                                                            @endforeach
                         
                                                        </select>
                                                    </div><!--custom-select-->
                                                </div>                                                  
                                                
                                                <div class="clearDiv"></div>
                                                <div class="clearDiv"></div>

                                                <div class="form-group textCenter">
                                                    <input type="text" id="campaign_data" data-campaign-id={{$_GET['id']}}  style="display:none">
                                                    <button type="button" class="btn" id="duplicate_category">{{trans('language.confirm')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div><!--modal-->
                            </div><!--mediaPlanningBlack-->
                        </div>
                    </div>
                </div>
            </div>


        </div><!--whiteBgWrap-->
    </div>
</div>

    <div id="deleteRowModal" class="modal">
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
                        <a href="#close-modal" rel="modal:close"><button type="button" class="btn2">{{trans('language.Cancel')}}</button></a>
                        <a href="#close-modal" rel="modal:close"><button type="button" class="btn btn-deleterow">{{trans('language.delete')}}</button></a>
                    </div>

                </form>
            </div>

        </div>
    </div><!--modal-->

@endsection     

@section('page-js')
<script type="text/javascript" src="{{asset('js/pages/media-planning.js')}}"></script>
@endsection
