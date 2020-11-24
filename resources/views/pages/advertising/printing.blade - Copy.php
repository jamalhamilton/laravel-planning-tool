@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/advertising-printing.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="tabHeadTitle">
            <h3>Swisscanto Push Q4 <span class="close">&times;</span></h3>
        </div><!--tabHeadTitle-->
        
        <div class=" border-redius-l-0">
            <div class="whiteBgWrap tab">
                <a href="{{url('planning/overview?id=').$campaign_id}}" class="tablinks">Übersicht</a>
                <button class="tablinks" onclick="openCity(event, 'Online')" id="defaultOpen">Online</button>
                <button class="tablinks" onclick="openCity(event, 'Print')">Print</button>
                <button class="tablinks" onclick="openCity(event, 'Plakat')">Plakat</button>
                <div class="tabRightBtns">
                    <button class="btn2">Export PDF</button>
                    <button class="btn2" id="btn_modal1">Channels bearbeiten</button>
                </div>
            </div>
            <div id="modal1" class="modal">                     
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>                          
                    <div class="modal-body">
                        <h1>Channels bearbeiten</h1>
                        <div class="clearDiv"></div>
                        <div class="clearDiv"></div>
                        <form id="form_add_channel">
                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel" id="label_online">Online
                                    <input type="checkbox" checked id="ckb_online">
                                    <span class="checkmark"></span>
                                </label>                                    
                            </div>
                            <ul class="col2box">
                                <li>
                                    <div class="styled-input dateField">
                                      <input type="text" required id="datepicker1"/>
                                      <label>Start</label>
                                      <span></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="styled-input dateField">
                                      <input type="text" required id="datepicker2"/>
                                      <label>Ende</label>
                                      <span></span>
                                    </div>
                                </li>
                            </ul><!--form-group-->
                            <div class="clearDiv"></div>
                            <p class="form-group statusTd">
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                                1 Woche Nachlieferung bei Unterlieferung
                            </p>
                            <div class="clearDiv"></div>
                            <div class="form-group">
                                <label class="containerCheckbox margin-0 check-channel" id="label_print">Print
                                    <input type="checkbox" checked id="ckb_print"/>
                                    <span class="checkmark"></span>
                                </label>                                    
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                            <input type="text" required id="datepicker3"/>
                                            <label>Start</label>
                                            <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                            <input type="text" required id="datepicker4"/>
                                            <label>Ende</label>
                                            <span></span>
                                        </div>
                                    </li>
                                </ul>                               
                            </div><!--form-group-->
                            
                            <div class="clearDiv"></div>
                            
                            <div class="form-group">
                                <label class="containerCheckbox margin-0  check-channel" id="label_plakat">Plakat
                                    <input type="checkbox" checked id="ckb_plakat"/>
                                    <span class="checkmark"></span>
                                </label>                                    
                            </div>
                            <div class="form-group">
                                <ul class="col2box">
                                    <li>
                                        <div class="styled-input dateField">
                                            <input type="text" required id="datepicker5"/>
                                            <label>Start</label>
                                            <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="styled-input dateField">
                                            <input type="text" required id="datepicker6"/>
                                            <label>Ende</label>
                                            <span></span>
                                        </div>
                                    </li>
                                </ul>                               
                            </div><!--form-group-->
                            
                            <div class="clearDiv"></div>
                            
                            <div class="form-group">
                                <label class="containerCheckbox">Kino
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="containerCheckbox">Radio TV
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                                </label>
                                <label class="containerCheckbox">TV
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                                </label>
                            </div><!--form-group-->
                            
                            <div class="clearDiv"></div>
                            
                            <div class="form-group textCenter">
                                <button type="button" class="btn" id="btn_form_submit">Speichern</button>
                            </div>
                            
                        </form>
                    </div>
                    
                </div>
            </div><!--modal-->
            
            
            <div id="Online" class="tabcontent pdding-0">
                <div class="row">
                    <div class="demo-x lightScrollbar">
                        <div class="tab innerTab pdding-0">
                            <a href="{{url('planning/params?id=').$campaign_id}}" class="tablinks">Planungsparameter</a>
                            <a href="{{url('media?id=').$campaign_id}}" class="tablinks">Mediaplanung</a>
                            <a href="javascript:void(0);" class="tablinks view">Werbedruck-Verteilung</a>
                            <!--<div class="tabRightBtns">
                                <button class="btn">Parameter bearbeiten</button>
                            </div>-->
                        </div>
                    </div><!--lightScrollbar-->
                    <div class="innerTabcontent">
                        <div class="whiteBgWrap row pdding-0">
                            <div class="mediaPlanningTable overflowHidden" style="padding-top: 15px;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width:426px">platzierung</th>
                                            <th style="width:120px">werbedruck</th>
                                            @foreach($date_array as $date)
                                            <th style="width:120px">{{$date}}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $idx => $table)
                                        <tr>
                                            <td colspan="12" class="pdding-0 border-0">
                                                <button class="collapsible">{{$table['name']}} <!--<a href="#" class="ic-categories"></a>--></button>
                                                <div class="contentCollap">
                                                    
                                                    <table class="table tableDisplay tableDisplayField tableAlignRight tableDisplayFieldPadding table-adv">
                                                        <thead>
                                                            @if ($idx == 0)
                                                            <tr>
                                                                <th style="width:466px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:120px"></th>
                                                                <th style="width:120px"></th>
                                                            </tr>
                                                            @endif
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($table['media'] as $idR => $media)
                                                            <tr class="blankInputTd" data-id={{$media['id']}}>
                                                                <td class="textLeft">{{$media['placement']}}</td>
                                                                <td><input type="text">{{$media['ad_print']}}</td>
                                                                <td><input type="text"></td>
                                                                <td><input type="text"></td>
                                                                <td><input type="text"></td>
                                                                <td><input type="text"></td>
                                                                <td rowspan="10" class="bg1 textCenter" style="font-size: 14px;">Reserve zur Nachlieferung bei Unterlieferungen</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div><!--contentCollap-->
                                                <table class="table tableDisplay tableDisplayField totalDisplayFooter">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th style="width:120px"></th>
                                                            <th style="width:120px"></th>
                                                            <th style="width:120px"></th>
                                                            <th style="width:120px"></th>
                                                            <th style="width:120px"></th>
                                                            <th style="width:120px"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="blankInputTd ">
                                                            <td>Total Werbedruck/KW</td>
                                                            <td>997’383</td>
                                                            <td>997’383</td>
                                                            <td>997’383</td>
                                                            <td>997’383</td>
                                                            <td>997’383</td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>                                               
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <table class="table tableDisplay tableDisplayField totalDisplayFooter totalMedia">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width:120px"></th>
                                            <th style="width:120px"></th>
                                            <th style="width:120px"></th>
                                            <th style="width:120px"></th>
                                            <th style="width:120px"></th>
                                            <th style="width:120px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="blankInputTd ">
                                            <td>Total Werbedruck/KW </td>
                                            <td>1’120’719</td>
                                            <td>1’120’719</td>
                                            <td>1’120’719</td>
                                            <td>1’120’719</td>
                                            <td>1’120’719</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>                                    
                            </div><!--mediaPlanningTable-->
                            
                            <div class="clearDiv"></div>
                            <div class="clearDiv"></div>
                            
                        </div>
                    </div>
                    <div id="modal3" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>                          
                            <div class="modal-body">
                                <h1>Sind Sie sicher?</h1>
                                <form>
                                    <div class="clearDiv"></div>
                                    <div class="form-group">
                                        <p>Seien Sie sicher, dass diese Aktion dauerhaft alle vorhandenen Informationen löscht. Wenn Sie die Informationen in der Zukunft benötigen, müssen Sie alle Informationen erneut eingeben und speichern.</p>
                                    </div>                                                      
                                    <div class="clearDiv"></div>                                            

                                    <div class="clearDiv"></div>

                                    <div class="form-group btnCenterGroup textCenter">
                                        <a href="#" rel="modal:close"><button type="submit" class="btn2" id="btn_close">Abbrechen</button></a>
                                        <a href="#" rel="modal:close"><button type="submit" class="btn" id="btn_save">Löschen</button></a>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div><!--modal-->
                </div>
            </div>
            
            <div id="Print" class="tabcontent">
                <h3>Print</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div><!--tabcontent-->
            
            <div id="Plakat" class="tabcontent">
                <h3>Plakat</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div><!--tabcontent-->
        </div><!--whiteBgWrap-->
    </div>
</div>
@endsection     

@section('page-js')
<script type="text/javascript" src="{{asset('js/pages/advertising-printing.js')}}"></script>
@endsection
