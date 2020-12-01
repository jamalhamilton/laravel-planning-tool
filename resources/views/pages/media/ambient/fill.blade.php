@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/media-planning.css')}}">
@endsection

@section('content')
    <input type="hidden" id="campaign_id" data-id="{{$campaignID}}" value="{{$campaignID}}">
    <input type="hidden" id="active_channel" value="{{$activetab}}" />

    <style>
        textarea{
            width: 100%;
            overflow: hidden;
            border: 2px solid #03BF9B !important;
        }
        td.free-input {
            word-break: break-word !important;
        }
        #form_add_category_modal .select-items div:first-child{
            display: none;
        }
        .media-table-region .select-items {
            border-top: 1px solid #BDCAD9;
            height: 103px !important;
        }
    </style>
    <div id="editModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-body">
                <h1>{{trans('language.editCtg')}}</h1>
                <div class="clearDiv"></div>
                <p>{{trans('language.editCtgMSG')}}</p>
                <form>
                    <div class="form-group">
                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi input-ctgname" type="text" />
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                            <span class="input__label-content input__label-content--hoshi">{{trans('language.categoryname')}}</span>
                        </label>
                    </span>
                    </div>
                    <div class="form-group">
                        <label class="containerCheckbox margin-0 check-channel" style="display: none;">{{trans('language.commentTxt')}}
                            <input type="checkbox" checked class='ckb-addinfo'>
                            <span class="checkmark"></span>
                        </label>
                    </div>

                    <div class="form-group">
                        <textarea class="textArea textarea-addinfo" placeholder="{{trans('language.comment')}}"></textarea>
                    </div>
                    <div class="clearDiv"></div>
                    <div class="clearDiv"></div>

                    <div class="form-group textCenter">
                        <button type="button" class="btn btn-editrow">{{trans('language.Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!--modal-->
    <div id="deleteModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-body">
                <h1>{{trans('language.delctg')}}</h1>
                <div class="clearDiv"></div>
                <p>{{trans('language.delctgMsg1')}}</p>
                <form>

                    <div class="clearDiv"></div>
                    <div class="clearDiv"></div>

                    <div class="form-group textCenter">
                        <button type="submit" class="btn">{{trans('language.yesdel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!--modal-->
    <div class="container">
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
                                <a href="{{url('planning/params?channel='.$activetab.'&id=').$campaignID}}" class="tablinks">{{trans('language.planningParameter1')}}</a>
                                <a href="javascript:void(0)" class="tablinks view">{{trans('language.mediaPlanning1')}}</a>
                                <a href="{{url('advertising?channel='.$activetab.'&id=').$campaignID}}" class="tablinks">{{trans('language.advertisingPrintDist1')}}</a>
                                <div class="tabRightBtns">
                                    <button class="btn" id="addctgmodal">{{trans('language.addCategory')}}</button>
                                </div>
                                <div id="modal3" class="modal">
                                    <!-- Modal content -->
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h1>{{trans('language.addCategory1')}}</h1>
                                            <div class="clearDiv"></div>
                                            <p>{{trans('language.editCtgMSG')}}</p>
                                            <form id="form_add_category_modal">
                                                @csrf
                                                <div class="form-group selectLine">
                                                    <div class="custom-select" style="width: 100%;">
                                                        <label class="label">{{trans('language.category')}}</label>
                                                        <select id="constant-kategorie">
                                                            <option value=""></option>
                                                            @foreach ($constantCategories as $row)
                                                                <option value="{{$row['ID']}}">{{$row['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div><!--custom-select-->
                                                </div>
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
                                                    <!-- @foreach ($currentCategories as $idX => $ctg)

                                                    @endforeach -->
                                                </div>
                                                <div class="clearDiv"></div>
                                                <div class="clearDiv"></div>

                                                <div class="form-group textCenter">
                                                    <input type="text" name="categories" id="categories" style="display: none;" data-id="<?=$_GET['id']?>">
                                                    <button type="button" class="btn" id="btn_add_category">{{trans('language.confirm')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div><!--modal-->
                            </div>
                        </div>
                        <div class="innerTabcontent">
                            <div class="whiteBgWrap row pdding-0">
                                <div class="mediaPlanningTable demo-x">
                                    <div class="custom-select selectLine media-table-region"  style="display:none;">
                                        <label class="label"></label>
                                        <select >
                                            @foreach($regions as $idx => $region)
                                                <option value="{{$idx}}">{{$region->name}}</option>
                                            @endforeach
                                        </select>
                                    </div><!--custom-select-->

                                    <table class="table bigTable1780" style="    margin-bottom: 0px;">
                                        <thead>
                                        <tr class="tableHeadInline">
                                            <th style="width:16.8%;">platzierung</th>
                                            <th style="width:16.8%">details</th>
                                            <th style="width:6.4%" class="text-truncate">Sprache</th>
                                            <th style="width:11.7%" class="text-truncate">format</th>
                                            <th style="width:6.4%; text-align: right" class="text-truncate">werbedruck</th>
                                            <th style="width:6.7%; text-align: right">tkp/cpc brutto in chf</th>
                                            <th style="width:6.4%; text-align: right">KOSTEN<br> BRUT</th>
                                            <th style="width:3.9%; text-align: right">rabatt in %</th>
                                            <th style="width:6.4%; text-align: right">KOSTEN<br> NET</th>
                                            <th style="width:3.9%; text-align: right">bk<br> in %</th>
                                            <th style="width:6.4%; text-align: right">KOSTEN<br> NET-NET</th>
                                            <th style="width:6.4%; text-align: right">tkp/cpc n/n<br> in chf</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        </tbody>
                                    </table>
                                    @foreach ($data as $idx => $table)
                                        <table class="table bigTable1780" data-id="{{$table['id']}}" data-isconst="{{$table['isConstant']}}">

                                            <tbody>
                                            <tr>
                                                <td colspan="12" class="pdding-0">
                                                    <div class="collapsibleBar margin-0">
                                                        <ul class="rightOption">
                                                            <li class="subOpt"><button href="#" class="ic-categories"></button>
                                                                <ul class="optionBtnGroup">
                                                                    <li><button type="button" class="btn-edit"><img src="{{asset('/images/icon_edit.svg')}}"></button></li>
                                                                    <li style="display:none;"><button type="button"><img  src="{{asset('/images/icon_grun_duplicate.svg')}}"></button></li>
                                                                    <li><button type="button" class="btn-deletectg"><img src="{{asset('/images/icon_grun_delete.svg')}}"></button></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                        @if ($table['note'] != "")
                                                            <div style="width:17%;position: relative;"><span class="note ic-triangle"><div class="note-data">{{$table['note']}}</div></span></div>
                                                        @endif
                                                        <button class="collapsible"> {{$table['name']}} </button>
                                                        <div class="contentCollap" style="max-width: 100%;max-height: 100%;">
                                                            @if (isset($table['media'][0]))
                                                            <table class="table tableDisplay mytable" data-first="{{$table['media'][0]['id']}}" data-clickrate="{{$clickrateArr[$table['name']]??''}}">
                                                                <thead>
                                                                <tr>
                                                                    <th style="width:16.8%"></th>
                                                                    <th style="width:16.8%"></th>
                                                                    <th style="width:6.4%"></th>
                                                                    <th style="width:11.7%"></th>
                                                                    <th style="width:6.4%"></th>
                                                                    <th style="width:6.7%"></th>
                                                                    <th style="width:6.4%"></th>
                                                                    <th style="width:3.9%"></th>
                                                                    <th style="width:6.4%"></th>
                                                                    <th style="width:3.9%"></th>
                                                                    <th style="width:6.4%"></th>
                                                                    <th style="width:6.4%"></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="tableToAdd">
                                                                @foreach ($table['media'] as $idR => $media)
                                                                    <tr class="blankInputTd @if($media['is_cpc']) hasCPC @endif" data-clickrate="{{$clickrateArr[$table['name']]??''}}" data-id="{{$media['id']}}" data-adimpressions="{{$media['ad_impressions']}}">
                                                                        <td data-type="free-input" class="{{(isset($media['mediaNotes']['1'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['1'])) ? $media['mediaNotes']['1'] : ''}}">{!!$media['placement']!!}</td>
                                                                        <td data-type="free-input" class="{{(isset($media['mediaNotes']['2'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['2'])) ? $media['mediaNotes']['2'] : ''}}">{!! $media['details'] !!}</td>
                                                                        <td data-type="free-input" class="{{(isset($media['mediaNotes']['3'])) ? 'free-input':''}}" data-note="{{(isset($media['mediaNotes']['3'])) ? $media['mediaNotes']['3'] : ''}}">{{$media['region']}}</td>
                                                                        <td data-type="auto-complete" class="{{(isset($media['mediaNotes']['4'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['4'])) ? $media['mediaNotes']['4'] : ''}}">{!!$media['format']!!}</td>
                                                                        <td data-type="free-input" class="cpcFlag cpcSummary {{(isset($media['mediaNotes']['5'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['5'])) ? $media['mediaNotes']['5'] : ''}}">{{$media['adPrint']}}</td>
                                                                        <td data-type="free-input" class="{{(isset($media['mediaNotes']['6'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['6'])) ? $media['mediaNotes']['6'] : ''}}">{{$media['tkpGrossCHF']}}</td>
                                                                        <td data-type="free-input" class="kostenCol {{(isset($media['mediaNotes']['7'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['7'])) ? $media['mediaNotes']['7'] : ''}}">{{$media['grossCHF']}}</td>
                                                                        <td data-type="free-input" class="{{(isset($media['mediaNotes']['8'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['8'])) ? $media['mediaNotes']['8'] : ''}}">{{$media['discountPersentual']}}</td>
                                                                        <td data-type="free-input" class="cpcFlag {{(isset($media['mediaNotes']['9'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['9'])) ? $media['mediaNotes']['9'] : ''}}">{{$media['netCHF']}}</td>
                                                                        <td data-type="free-input" class="{{(isset($media['mediaNotes']['10'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['10'])) ? $media['mediaNotes']['10'] : ''}}">{{$media['bkPersentual']}}</td>
                                                                        <td data-type="free-input" class="{{(isset($media['mediaNotes']['11'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['11'])) ? $media['mediaNotes']['11'] : ''}}">{{$media['nnCHF']}}</td>
                                                                        <td class="" data-type="free-input" class="{{(isset($media['mediaNotes']['12'])) ? 'cell-note':''}}" data-note="{{(isset($media['mediaNotes']['12'])) ? $media['mediaNotes']['12'] : ''}}">{{$media['tkpNNCHF']}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                            @endif
                                                        </div><!--contentCollap-->

                                                    </div>

                                                    <table class="table tableDisplay tableDisplayField totalDisplayFooter">
                                                        <thead>
                                                        <tr>
                                                            <th style="width:16.8%"></th>
                                                            <th style="width:16.8%"></th>
                                                            <th style="width:6.4%"></th>
                                                            <th style="width:11.7%"></th>
                                                            <th style="width:6.4%"></th>

                                                            <th style="width:6.7%"></th>
                                                            <th style="width:6.4%"></th>
                                                            <th style="width:3.9%"></th>
                                                            <th style="width:6.4%"></th>
                                                            <th style="width:3.9%"></th>
                                                            <th style="width:6.4%"></th>
                                                            <th style="width:6.4%"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr class="blankInputTd ">
                                                            <td><strong>Total {{$table['name']}}</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="subtotal"></td>

                                                            <td class="tkpGrossCHF"></td>
                                                            <td class="subtotal"></td>
                                                            <td class="grossCHF"></td>
                                                            <td class="subtotal"></td>
                                                            <td class="bkPersentual"></td>
                                                            <td class="subtotal"></td>
                                                            <td class="tkpNNCHF"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    @endforeach
                                    <div id="blanktable"></div>
                                    <table class="table bigTable1780 tableDisplay tableDisplayField tableTotalMedia totalDisplayFooter totalMedia">
                                        <thead>
                                        <tr>
                                            <th style="width:16.8%"></th>
                                            <th style="width:16.8%"></th>
                                            <th style="width:6.4%"></th>
                                            <th style="width:11.7%"></th>
                                            <th style="width:6.4%"></th>

                                            <th style="width:6.7%"></th>
                                            <th style="width:6.4%"></th>
                                            <th style="width:3.9%"></th>
                                            <th style="width:6.4%"></th>
                                            <th style="width:3.9%"></th>
                                            <th style="width:6.4%"></th>
                                            <th style="width:6.4%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="blankInputTd ">
                                            <td class = "disabled">{{trans('language.totalMedia')}}</td>
                                            <td class = "disabled"></td>
                                            <td class = "disabled"></td>
                                            <td class = "disabled"></td>
                                            <td class="alltotal adpresssum disabled"></td>

                                            <td class="tkpGrossCHF disabled"></td>
                                            <td class="alltotal disabled"></td>
                                            <td class="grossCHF disabled"></td>
                                            <td class="alltotal disabled"></td>
                                            <td class="bkPersentual disabled"></td>
                                            <td class="alltotal nnsum disabled"></td>
                                            <td class="tkpNNCHF disabled"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div><!--mediaPlanningTable-->

                                <div class="voiceSplit">
                                    <div class="col-7">
                                        <p>{{trans('language.Sprachsplit')}}</p>
                                        <div class="">
                                            <table class="table table660">
                                                <thead>
                                                <tr>
                                                    <th>{{trans('language.Sprachregion')}}</th>
                                                    <th>{{trans('language.Werbedruck')}}</th>
                                                    <th>{{trans('language.persent')}}</th>
                                                    <th>{{trans('language.costNN')}}</th>
                                                    <th>{{trans('language.persent')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($calRegions as $regioname => $val)
                                                    <tr>
                                                        <td>{{$regioname}}</td>
                                                        <td class="adpresstotal"></td>
                                                        <td class="adpresspercent"></td>
                                                        <td class="netCHFtotal"></td>
                                                        <td class="netCHFpercent"></td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>{{trans('language.total')}}</td>
                                                    <td class="adpressall"></td>
                                                    <td class="adpresspercent"></td>
                                                    <td class="netCHFall"></td>
                                                    <td class="netCHFpercent"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div style="margin-top: 50px">
                                            <p>
                                                <strong style="border-left:5px solid #A4B7CB; padding-left: 10px;">CPC Klickpreismodell</strong> - die gekennzeichneten Platzierungen werden pro Klick verrechnet. Der Werbedruck wird in Klicks angegeben. Der zu erwartende Werbedruck in Ad Impressions wird auf Basis von Annahmen bei den Clickraten (Display 0.25%, Mobile 0.30% und Native 0.20%) berechnet.
                                            </p>
                                        </div>
                                    </div>
                                </div><!--voiceSplit-->


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
    <div id="deleteCategoryModal" class="modal">
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
                        <a href="#close-modal" rel="modal:close"><button type="button" class="btn btn-deletectg">{{trans('language.delete')}}</button></a>
                    </div>

                </form>
            </div>
        </div>
    </div><!--modal-->
    <div class="note-bubble right" style="display:none;">
        <div class="arrow"></div>
        <h3 class="note-title">
            <span class="text-info"></span>
            <div class="note_button">
                <a class="btn-note-delete">
                    <img class="mCS_img_loaded" src="{{asset('/images/icon_weiss_delete.svg')}}">
                </a>
                <a class="btn-note-close" style="margin-right:10px;">
                    <img class="mCS_img_loaded" src="{{asset('/images/icon_weiss_outline_close.svg')}}">
                </a>
            </div>
        </h3>
        <div class="note-content">
            <form><textarea class="textArea">Lorem Ipsum is simply dummy text of the printing and typesetting industry</textarea></form>
        </div>
    </div>

    <textarea class="media-table-format" style="display:none;" ></textarea>

    <div class="media-table-menu" style="display:none;">
        <div id="menu_insert_line">{{trans('language.insertLine')}}</div>
        <div id="menu_duplicate_line">{{trans('language.duplicatetLine')}}</div>
        <div id="menu_delete_line">{{trans('language.deleteLine')}}</div>
        <div id="menu_insert_note">{{trans('language.insertNote')}}</div>
        <div id="menu_insert_cpc">{{trans('language.insertCPC')}}</div>
    </div><!--custom-select-->
@endsection

@section('page-js')
    <script type="text/javascript" src="{{asset('js/pages/media-planning.js')}}"></script>
@endsection
