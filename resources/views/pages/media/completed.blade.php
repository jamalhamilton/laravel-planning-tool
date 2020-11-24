@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/media-planning.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="tabHeadTitle">
            <h3>{{ $campaignName}} <a href="{{url('planning')}}"><span class="close">&times;</span></a></h3>
        </div><!--tabHeadTitle-->
        
        <div class=" border-redius-l-0">
            <div class="whiteBgWrap tab">
                <a href="overview.html" class="tablinks" onclick="openCity(event, 'Übersicht')">{{trans('language.overview')}}</a>
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
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                                {{trans('language.extraWeekMSG')}}
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
                            
                            <div class="form-group textCenter">
                                <button type="submit" class="btn" id="btn_save_channel">{{trans('language.Save')}}</button>
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
            
            <div id="Online" class="tabcontent pdding-0">
                <div class="row">
                    <div class="demo-x lightScrollbar">
                    <div class="tab innerTab pdding-0">
                        <a href="planning-parameters.html" class="tablinks">{{trans('language.planningParameter1')}}</a>
                        <a href="media-planning.html" class="tablinks view">{{trans('language.mediaPlanning1')}}</a>
                        <a href="advertising-printing.html" class="tablinks">{{trans('language.advertisingPrintDist1')}}</a>
                        <div class="tabRightBtns">
                            <button class="btn" data-modal="#addCategoryModal">{{trans('language.addCategory')}}</button>
                        </div>
                        </div>
                        <div id="addCategoryModal" class="modal">                       
                            <!-- Modal content -->
                            <div class="modal-content">                     
                                <div class="modal-body">
                                    <h1>{{trans('language.addCategory')}}</h1>
                                    <div class="clearDiv"></div>
                                    <p>{{trans('language.editCtgMSG')}}</p>
                                    <form>
                                        <div class="form-group selectLine">
                                            <div class="custom-select">
                                                <label class="label">{{trans('language.category')}}</label>
                                                <select>
                                                    <option value="0">KATEGORIE</option>
                                                    <option value="1">Display</option>
                                                    <option value="2">Facebook</option>
                                                    <option value="3">YouTube</option>
                                                </select>
                                            </div><!--custom-select-->
                                        </div>
                                        <div class="form-group addLessBox"> 
                                            <span class="input input--hoshi">
                                                <input class="input__field input__field--hoshi" type="text" />
                                                <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                                    <span class="input__label-content input__label-content--hoshi">{{trans('language.indivisualctg')}}</span>
                                                </label>
                                            </span>
                                            <button class="button"><img src="/images/icon_grun_moreInfo.svg"></button>
                                        </div>
                                        <div class="form-group">
                                            <span class="addOpt">
                                                <button><img src="/images/Close.svg"></button>
                                                Display
                                            </span>
                                            <span class="addOpt">
                                                <button><img src="/images/Close.svg"></button>
                                                Aymo
                                            </span>
                                            <span class="addOpt">
                                                <button><img src="/images/Close.svg"></button>
                                                Swisscanto Luxury Pool
                                            </span>
                                        </div>
                                        <div class="clearDiv"></div>
                                        <div class="clearDiv"></div>

                                        <div class="form-group textCenter">
                                            <button type="submit" class="btn">{{trans('language.confirm')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!--modal-->  
                    </div>
                    <div class="innerTabcontent">
                        <div class="whiteBgWrap row pdding-0">
                            <div class="mediaPlanningTable demo-x">
                                <table class="table bigTable1780">
                                    <thead>
                                        <tr>
                                            <th style="width:300px">platzierung</th>
                                            <th style="width:300px">details</th>
                                            <th style="width:115px">sprachregion</th>
                                            <th style="width:115px; text-align: right">werbedruck</th>
                                            <th style="width:210px">format</th>
                                            <th style="width:120px; text-align: right">tkp brutto in chf</th>
                                            <th style="width:115px; text-align: right">brutto<br> in chf</th>
                                            <th style="width:70px; text-align: right">rabatt in %</th>
                                            <th style="width:115px; text-align: right">netto<br> in chf</th>
                                            <th style="width:70px; text-align: right">bk<br> in %</th>
                                            <th style="width:115px; text-align: right">n/n<br> in CHF</th>
                                            <th style="width:115px; text-align: right">tkp n/n<br> in chf</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="12" class="pdding-0">
                                                <div class="collapsibleBar margin-0">
                                                    <ul class="rightOption">
                                                        <li class="subOpt"><button href="#" class="ic-categories"></button>
                                                            <ul class="optionBtnGroup">
                                                                <li><button type="button" class="btn-edit"><img src="/images/icon_edit.svg"></button></li>
                                                                <li><button type="button"><img src="/images/icon_grun_duplicate.svg"></button></li>
                                                                <li><button type="button" class="btn-delete"><img src="/images/icon_grun_delete.svg"></button></li>
                                                            </ul>
                                                        </li>
                                                    </ul>


                                                    <div class="note-bubble right" style="display:none;">
                                                        <div class="arrow"></div>
                                                        <h3 class="note-title">
                                                            <span class="text-info"></span> 
                                                            <div class="note_button">
                                                                <a class="btn-note-delete">
                                                                    <img class="mCS_img_loaded" src="/images/icon_weiss_delete.svg">
                                                                </a>
                                                                <a class="btn-note-close" style="margin-right:10px;">
                                                                    <img class="mCS_img_loaded" src="/images/icon_weiss_outline_close.svg">
                                                                </a>
                                                            </div>
                                                        </h3>
                                                        <div class="note-content">
                                                            <form><textarea class="textArea">Lorem Ipsum is simply dummy text of the printing and typesetting industry</textarea></form>
                                                        </div>
                                                    </div>
                                                    <div style="width:17%;position: relative;"><span class="note ic-triangle"><div class="note-data">test display</div></span></div>
                                                    <button class="collapsible">Display </button>
                                                    <div class="contentCollap">
                                                        <table class="table tableDisplay tableDisplayField tableAlignRight">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:300px"></th>
                                                                    <th style="width:300px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:210px"></th>
                                                                    <th style="width:120px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:70px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:70px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:115px"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft">(NZZ // Stilpalast // Fallstaff // Schönes Leben </td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft">(NZZ // Stilpalast // Fallstaff // Schönes Leben </td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd totalDisplayFooter">
                                                                    <td class="textLeft"><strong>Total Display</strong></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>3’989’531</td>
                                                                    <td></td>
                                                                    <td>43.75</td>
                                                                    <td>174’560.51</td>
                                                                    <td>41.13</td>
                                                                    <td>102’764.97</td>
                                                                    <td>5.00</td>
                                                                    <td>97’626.72</td>
                                                                    <td>24.47</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div><!--contentCollap-->
                                                    <table class="table tableDisplay tableDisplayField totalDisplayFooter">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:300px"></th>
                                                                <th style="width:300px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:210px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:70px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:70px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:115px"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="blankInputTd ">
                                                                <td><strong>Total Display</strong></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>3’989’531</td>
                                                                <td></td>
                                                                <td>43.75</td>
                                                                <td>174’560.51</td>
                                                                <td>41.13</td>
                                                                <td>102’764.97</td>
                                                                <td>5.00</td>
                                                                <td>97’626.72</td>
                                                                <td>24.47</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                        
                                                </div><!--collapsibleBar-->
                    
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class="table bigTable1780">
                                    <tbody>
                                        <tr>
                                            <td colspan="12" class="pdding-0">
                                                <div class="collapsibleBar">
                                                    <ul class="rightOption">
                                                        <li class="subOpt"><button href="#" class="ic-categories"></button>
                                                            <ul class="optionBtnGroup">
                                                                <li><button type="button" class="btn-edit"><img src="/images/icon_edit.svg"></button></li>
                                                                <li><button type="button"><img src="/images/icon_grun_duplicate.svg"></button></li>
                                                                <li><button type="button" class="btn-delete"><img src="/images/icon_grun_delete.svg"></button></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                    <div style="width:17%;position: relative;"><span class="note ic-triangle"><div class="note-data">test</div></span></div>
                                                    <button class="collapsible">Aymo </button>
                                                    
                                                    <div class="contentCollap">
                                                        <table class="table tableDisplay tableDisplayField tableAlignRight">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:300px"></th>
                                                                    <th style="width:300px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:210px"></th>
                                                                    <th style="width:120px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:70px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:70px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:115px"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft">(NZZ // Stilpalast // Fallstaff // Schönes Leben </td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft">(NZZ // Stilpalast // Fallstaff // Schönes Leben </td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd totalDisplayFooter">
                                                                    <td class="textLeft"><strong>Total Display</strong></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>3’989’531</td>
                                                                    <td></td>
                                                                    <td>43.75</td>
                                                                    <td>174’560.51</td>
                                                                    <td>41.13</td>
                                                                    <td>102’764.97</td>
                                                                    <td>5.00</td>
                                                                    <td>97’626.72</td>
                                                                    <td>24.47</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div><!--contentCollap-->
                                                    <table class="table tableDisplay tableDisplayField totalDisplayFooter">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:300px"></th>
                                                                <th style="width:300px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:210px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:70px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:70px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:115px"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="blankInputTd ">
                                                                <td><strong>{{trans('language.totalDisplay')}}</strong></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>3’989’531</td>
                                                                <td></td>
                                                                <td>43.75</td>
                                                                <td>174’560.51</td>
                                                                <td>41.13</td>
                                                                <td>102’764.97</td>
                                                                <td>5.00</td>
                                                                <td>97’626.72</td>
                                                                <td>24.47</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                                        
                                                </div><!--collapsibleBar-->

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class="table bigTable1780">
                                    <tbody>
                                        <tr>
                                            <td colspan="12" class="pdding-0">
                                                <div class="collapsibleBar">
                                                    <ul class="rightOption">
                                                        <li class="subOpt"><button href="#" class="ic-categories"></button>
                                                            <ul class="optionBtnGroup">
                                                                <li><button type="button" class="btn-edit"><img src="/images/icon_edit.svg"></button></li>
                                                                <li><button type="button"><img src="/images/icon_grun_duplicate.svg"></button></li>
                                                                <li><button type="button" class="btn-delete"><img src="/images/icon_grun_delete.svg"></button></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                    <button class="collapsible">Swisscanto Luxury Pool</button>
                                                    <div class="contentCollap">
                                                        <table class="table tableDisplay tableDisplayField tableAlignRight">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:300px"></th>
                                                                    <th style="width:300px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:210px"></th>
                                                                    <th style="width:120px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:70px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:70px"></th>
                                                                    <th style="width:115px"></th>
                                                                    <th style="width:115px"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft">(NZZ // Stilpalast // Fallstaff // Schönes Leben </td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft">(NZZ // Stilpalast // Fallstaff // Schönes Leben </td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Business Alliance</td>
                                                                    <td class="textLeft">(NZZ // Moneyhouse // Cash // Finews)</td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>100’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>7’250.00</td>
                                                                    <td>15.00</td>
                                                                    <td>6’162.50</td>
                                                                    <td>5.00</td>
                                                                    <td>5'854.38</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd">
                                                                    <td class="textLeft">NZZ Style Alliance </td>
                                                                    <td class="textLeft"></td>
                                                                    <td class="textLeft">D-CH</td>
                                                                    <td>190’000</td>
                                                                    <td class="textLeft">Wideboard 994x250</td>
                                                                    <td class="bg1">72.50</td>
                                                                    <td>13’775.00</td>
                                                                    <td>15.00</td>
                                                                    <td>11’708.75</td>
                                                                    <td>5.00</td>
                                                                    <td>11’123.31</td>
                                                                    <td class="bg1">58.54</td>
                                                                </tr>
                                                                <tr class="blankInputTd totalDisplayFooter">
                                                                    <td class="textLeft"><strong>Total Display</strong></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>3’989’531</td>
                                                                    <td></td>
                                                                    <td>43.75</td>
                                                                    <td>174’560.51</td>
                                                                    <td>41.13</td>
                                                                    <td>102’764.97</td>
                                                                    <td>5.00</td>
                                                                    <td>97’626.72</td>
                                                                    <td>24.47</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div><!--contentCollap-->
                                                    <table class="table tableDisplay tableDisplayField totalDisplayFooter">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:300px"></th>
                                                                <th style="width:300px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:210px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:70px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:70px"></th>
                                                                <th style="width:115px"></th>
                                                                <th style="width:115px"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="blankInputTd ">
                                                                <td><strong>{{trans('language.totalDisplay')}}</strong></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>3’989’531</td>
                                                                <td></td>
                                                                <td>43.75</td>
                                                                <td>174’560.51</td>
                                                                <td>41.13</td>
                                                                <td>102’764.97</td>
                                                                <td>5.00</td>
                                                                <td>97’626.72</td>
                                                                <td>24.47</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                                        
                                                </div><!--collapsibleBar-->


                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class="table bigTable1780 tableDisplay tableDisplayField totalDisplayFooter totalMedia">
                                    <thead>
                                        <tr>
                                            <th style="width:300px"></th>
                                            <th style="width:300px"></th>
                                            <th style="width:115px"></th>
                                            <th style="width:115px"></th>
                                            <th style="width:210px"></th>
                                            <th style="width:120px"></th>
                                            <th style="width:115px"></th>
                                            <th style="width:70px"></th>
                                            <th style="width:115px"></th>
                                            <th style="width:70px"></th>
                                            <th style="width:115px"></th>
                                            <th style="width:115px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="blankInputTd ">
                                            <td>{{trans('language.totalMedia')}}</td>
                                            <td></td>
                                            <td></td>
                                            <td>5'481'879</td>
                                            <td></td>
                                            <td>38.73</td>
                                            <td>282'469.51</td>
                                            <td>30.84</td>
                                            <td>195'365.89</td>
                                            <td>4.49</td>
                                            <td>186'597.60</td>
                                            <td>34.04</td>
                                        </tr>
                                    </tbody>
                                </table>                                    
                            </div><!--mediaPlanningTable-->
                            
                            <div class="voiceSplit">
                                <div class="col-7">
                                    <p>Sprachsplit</p>
                                    <div class="demo-x">
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
                                            <tr>
                                                <td>D-CH</td>
                                                <td>3’661’983</td>
                                                <td>68.13</td>
                                                <td>3’661’983</td>
                                                <td>68.13</td>
                                            </tr>
                                            <tr>
                                                <td>F-CH</td>
                                                <td>1’712’896</td>
                                                <td>31.87</td>
                                                <td>1’712’896</td>
                                                <td>31.87</td>
                                            </tr>
                                            <tr>
                                                <td>I-CH</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>{{trans('language.total')}}</td>
                                                <td>5'374'879</td>
                                                <td>100.00</td>
                                                <td>5'374'879</td>
                                                <td>100.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div><!--CustomScrollbar-->
                                </div>
                            </div><!--voiceSplit-->
                            
                        </div>
                    </div>
                </div>
            </div>

            <div id="Print" class="tabcontent">
              <h3>{{trans('language.print1')}}</h3>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p> 
            </div>

            <div id="editModal" class="modal">                      
                <!-- Modal content -->
                <div class="modal-content">                     
                    <div class="modal-body">
                        <h1>{{trans('language.editCtg')}}</h1>
                        <div class="clearDiv"></div>
                        <p>{{trans('language.addcatetxt')}}</p>
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
                                <label class="containerCheckbox margin-0 check-channel">{{trans('language.commentTxt')}}
                                    <input type="checkbox" checked class='ckb-addinfo'>
                                    <span class="checkmark"></span>
                                </label>                                    
                            </div>
                            
                            <div class="form-group">
                                <textarea class="textArea textarea-addinfo" placeholder="ZUSATZINFORMATIONEN"></textarea>
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
            <div id="Plakat" class="tabcontent">
              <h3>{{trans('language.plakat1')}}</h3>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
        </div><!--whiteBgWrap-->
    </div>
</div>
@endsection     

@section('page-js')
<script type="text/javascript" src="{{asset('js/pages/media-planning.js')}}"></script>
@endsection
