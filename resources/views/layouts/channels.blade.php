<div class="whiteBgWrap tab">
    <a class="tablinks {{$activetab == 'overview'?'active':''}}" href="{{url('planning/overview?id=').$campaignID}}" >{{trans('language.overview')}}</a>

    @foreach ($channels as $channel)
        <a class="tablinks {{$activetab == $channel['name']?'active':''}}" href="{{url('planning/params?channel='.$channel['name'].'&id=').$campaignID}}">{{ucfirst($channel['name'])}}</a>
    @endforeach
  {{--  <a class="tablinks {{$activetab == 'online'?'active':''}}"  href="{{url('planning/params?channel=online&id=').$campaignID}}" >{{trans('language.online')}}</a>
    <a class="tablinks {{$activetab == 'print'?'active':''}}" href="{{url('planning/params?channel=print&id=').$campaignID}}" >{{trans('language.print')}}</a>

    <a class="tablinks" href="{{url('planning/overview?id=').$campaignID}}" >{{trans('language.plakat')}}</a>
    <a class="tablinks" href="{{url('planning/overview?id=').$campaignID}}" >{{trans('language.tv')}}</a>
    <a class="tablinks" href="{{url('planning/overview?id=').$campaignID}}" >{{trans('language.radio')}}</a>
    <a class="tablinks" href="{{url('planning/overview?id=').$campaignID}}" >{{trans('language.kino')}}</a>--}}

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
                    <button type="button" class="btn2" id="btn_close" >{{trans('language.Cancel')}}</button>
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

<div id="modal_confirm_del" class="modal">
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
                    <button type="button" class="btn2" id="btn_close" rel="modal:close">{{trans('language.Cancel')}}</button>
                    <button type="button" class="btn" id="btn_del_confirm">{{trans('language.delete')}}</button>
                </div>

            </form>
        </div>

    </div>
</div><!--modal-->

<div id="itemmodal_confirm_del" class="modal">
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
                    <button type="button" class="btn2" rel="modal:close">{{trans('language.Cancel')}}</button>
                    <button type="button" class="btn" id="itembtn_del_confirm">{{trans('language.delete')}}</button>
                </div>

            </form>
        </div>

    </div>
</div><!--modal-->
