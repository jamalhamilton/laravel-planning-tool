@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/user-management.css')}}">
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
                <button class="btn" type="button" data-modal="#modal1">{{trans('language.addUser')}}</button>
            </div>
            
            <div id="modal1" class="modal">                     
                <!-- Modal content -->
                <div class="modal-content">                     
                    <div class="modal-body">
                        <h1>{{trans('language.addUser')}}</h1>
                        <div class="clearDiv"></div>
                        <form>
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi input-firname" type="text" />
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.firstName')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi input-lasname" type="text" />
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.lastName')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi input-initials" type="text" />
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.Initials')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input id="modal1_email" class="input__field input__field--hoshi input-email" type="text" />
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.email')}}</span>
                                    </label>
                                </span>
                                
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi">
                                    <input class="input__field input__field--hoshi input-pass" type="text" />
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.password1')}}</span>
                                    </label>
                                </span>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="uploadFieldBox">
                                    <img class="userImgBox realm" src="uploads\user\default.jpg" width="160px" height="160px" id="img_upload">
                                    <div class="btn" id="logo_display"  style="margin-top:30px; margin-right:170px;">
                                        <span onclick="openFileDialog()">{{trans('language.logoUpload')}}</span>
                                        <input type="file" id="input_upload" onchange="photoPreview()" style="display:none;" name="image" />
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group" style="display: none;">
                                <label class="containerCheckbox check-channel">{{trans('language.apiAccess')}}
                                    <input id="ckb_api" class="normal-check" type="checkbox" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div><!--form-group-->

                            <div class="form-group selectLine">
                                <label class="label">{{trans('language.group1')}}</label>
                                <div class="custom-select div-group">
                                    <select>
                                        @foreach ($data['groups'] as $group)
                                        <option value="{{$group->ID}}">{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                </div><!--custom-select-->
                            </div>
                                                            
                            <div class="clearDiv"></div>
                            
                            <div class="form-group selectLine" style="display: none;">
                                <label class="label">{{trans('language.status1')}}</label>
                                <div class="custom-select div-status">
                                    <select>
                                        @foreach ($data['statuses'] as $status)
                                        <option value="{{$status->ID}}">{{$status->status}}</option>
                                        @endforeach
                                    </select>
                                </div><!--custom-select-->
                            </div>
                            
                            <div class="clearDiv"></div>
                            
                            <div class="form-group textCenter">
                                <button type="button" class="btn btn-addrow">{{trans('language.confirm1')}}</button>
                            </div>
                            
                        </form>
                    </div>
                    
                </div>
            </div><!--modal-->
            
            <div id="modal2" class="modal">                     
                <!-- Modal content -->
                <div class="modal-content">                     
                    <div class="modal-body">
                        <h1>{{trans('language.addUser')}}</h1>
                        <div class="clearDiv"></div>
                        <form>
                            <div class="form-group">
                                <span class="input input--hoshi input--filled">
                                    <input class="input__field input__field--hoshi input-firname" type="text" id="modal2_firstname" value="" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.firstName')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi input--filled">
                                    <input class="input__field input__field--hoshi input-lasname" type="text" id="modal2_lastname" value="" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.lastName')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi input--filled">
                                    <input class="input__field input__field--hoshi input-initials" type="text" id="modal2_initial" value="" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.Initials')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi input--filled">
                                    <input class="input__field input__field--hoshi input-email" type="text" id="modal2_email" value="" disabled="" />
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.email')}}</span>
                                    </label>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input input--hoshi input--filled">
                                    <input class="input__field input__field--hoshi input-pass" type="password" id="modal2_password" value="" required/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.password1')}}</span>
                                    </label>
                                </span>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="uploadFieldBox">
                                    <img class="userImgBox realm" id="modal2_avatar">
                                    <div class="btn" id="logo_display" style="margin-right:110px; margin-top:30px;">
                                        <span onclick="openFileDialog2()">{{trans('language.logoUpload')}}</span>
                                        <input type="file" id="modal2_upload" onchange="photoPreview2()" name="image" style="display:none;" />
                                    </div>

                                </div>
                            </div>
                            <br>
                            <div class="form-group" style="display: none;">
                                <label class="containerCheckbox check-channel">{{trans('language.apiAccess')}}
                                    <input id="modal2_ckb" class="normal-check" type="checkbox" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                            <div class="form-group" style="display: none;">
                                <span class="input input--hoshi input--filled">
                                    <input class="input__field input__field--hoshi input-token" type="text" id="modal2_tokenKey" value="" disabled/>
                                    <label class="input__label input__label--hoshi input__label--hoshi-color-1">
                                        <span class="input__label-content input__label-content--hoshi">{{trans('language.apiAccess')}}</span>
                                    </label>
                                </span>
                            </div>

                            <div class="form-group selectLine div-group">
                                <label class="label">{{trans('language.group1')}}</label>
                                <div class="custom-select">
                                    <select id="modal2_group">
                                        @foreach ($data['groups'] as $group)
                                        <option value="{{$group->ID}}">{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                </div><!--custom-select-->
                            </div>

                            <div class="clearDiv"></div>

                            <div class="form-group selectLine div-status" style="">
                                <label class="label">{{trans('language.status1')}}</label>
                                <div class="custom-select">
                                    <select id="modal2_status">
                                        @foreach ($data['statuses'] as $status)
                                        <option value="{{$status->ID}}">{{$status->status}}</option>
                                        @endforeach
                                    </select>
                                </div><!--custom-select-->
                            </div>

                            <div class="clearDiv"></div>


                            <div class="form-group textCenter">
                                <button type="button" class="btn btn-editrow">{{trans('language.confirm2')}}</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div><!--modal-->
                                <div id="deleteModal" class="modal">                        
                                    <!-- Modal content -->
                                    <div class="modal-content">                     
                                        <div class="modal-body">
                                            <h1>{{trans('language.delUser')}}</h1>
                                            <div class="clearDiv"></div>
                                            <p>{{trans('language.delUserMSG')}}</p>
                                            <form>

                                                <div class="clearDiv"></div>
                                                <div class="clearDiv"></div>

                                                <div class="form-group btnCenterGroup">
                                                    <button type="submit" class="btn2">{{trans('language.Cancel')}}</button>
                                                    <button type="button" class="btn btn-deleterow">{{trans('language.deactive')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div><!--modal-->

        </div><!--tableTopBox-->
        <div class="col-12 tableScroll">
            <div class="demo-x">
                <table class="table managementTable" id="users" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{trans('language.firstName')}}</th>
                            <th>{{trans('language.lastName')}}</th>
                            <th>{{trans('language.Initials')}}</th>
                            <th>{{trans('language.email')}}</th>
                            <!--<th>{{trans('language.apiAccess')}}</th>-->
                            <th>{{trans('language.group1')}}</th>
                            <th>{{trans('language.status1')}}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="tbody">                        

                    </tbody>
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
<script type="text/javascript" src="{{asset('js/pages/user-management.js')}}"></script>
@endsection
