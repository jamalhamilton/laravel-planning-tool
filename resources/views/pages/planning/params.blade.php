@extends('layouts.app')

@section('page-css')
	<link rel="stylesheet" type="text/css" href="{{asset('css/pages/planning-parameters.css')}}">
@endsection

@section('content')
	<div class="container">
		<input type="hidden" id="campaign_id" data-id="{{$campaignID}}">
		<div class="row">
			<div class="tabHeadTitle">
				<h3>{{ $campaignName}} <a href="{{url('planning')}}"><span class="close">&times;</span></a></h3>
			</div><!--tabHeadTitle-->

			<div class=" border-redius-l-0">

				@include('layouts.channels')

				<div id="Übersicht" class="tabcontent pdding-0">
				</div><!--tabcontent-->

				<div id="Online" class="tabcontent pdding-0">
					<div class="row">
						<div class="" >
							<div class="tab innerTab pdding-0">
								<a href="javascript:void(0)" class="tablinks view">{{trans('language.planningParameter1')}}</a>
								<a href="{{url('media?channel='.$activetab.'&id=').$campaignID}}" class="tablinks">{{trans('language.mediaPlanning1')}}</a>
								<a href="{{url('advertising?channel='.$activetab.'&id=').$campaignID}}" class="tablinks">{{trans('language.advertisingPrintDist1')}}</a>
								<div class="tabRightBtns">
									<button class="btn" id = "shift-to-edit-mode">{{trans('language.editParameter')}}</button>
									<button class="btn" id = "open-category-create">{{trans('language.addCategory')}}</button>
								</div>
								<div id="modal2" class="modal">
									<!-- Modal content -->
									<div class="modal-content">
										<div class="modal-body">
											<h1>{{trans('language.planningParameter1')}}</h1>
											<div class="clearDiv"></div>
											<form>
												<div class="form-group selectLine">
													<div class="custom-select">
														<label class="label">{{trans('language.planningAndsetup')}}</label>
														<select>
															<option value="0">Mediaplanung & Verhandlung</option>
															<option value="1">Kampagnen-SetUp Display-Media</option>
															<option value="2">Kampagnen-SetUp Social-Media </option>
															<option value="3">Optimierung & Reporting</option>
														</select>
													</div><!--custom-select-->
												</div>

												<div class="form-group addLessBox" id = "indiv_form">
                                            <span class="input input--hoshi">
                                                <input class="input__field input__field--hoshi" type="text" id="modal3_indi_category"/>
                                                <label class="input__label input__label--hoshi input__label--hoshi-color-1">
													<span class="input__label-content input__label-content--hoshi">{{trans('language.indivisualctg')}}</span>
												</label>
                                            </span>
													<button type="button" class="button" id="individuelle-kategorie"><img src="{{asset('images/icon_grun_moreInfo_gray.png')}}" style="cursor: initial;" id="modal3_indi_img"></button>
												</div>

												<div class="form-group" id="params-list">

												</div>
												<div class="clearDiv"></div>
												<div class="clearDiv"></div>

												<div class="form-group textCenter">
													<input type="text" name="categories" id="categories" style="display: none;" data-id="<?=$_GET['id']?>">
													<button type = "button" id="add-cost-element" class="btn">{{trans('language.Save')}}</button>
												</div>

											</form>
										</div>
									</div>
								</div><!--modal-->

								<div id="modal3" class="modal">
									<!-- Modal content -->
									<div class="modal-content">
										<div class="modal-body">
											<h1>{{trans('language.addcate')}}</h1>
											<div class="clearDiv"></div>
											<form  id="addservicegroup">
												<p>{{trans('language.addcatetxt')}}</p>

												<div class="form-group addLessBox">
												<span class="input input--hoshi">
													<input class="input__field input__field--hoshi" type="text" required/>
													<label class="input__label input__label--hoshi input__label--hoshi-color-1">
														<span class="input__label-content input__label-content--hoshi">{{trans('language.category')}}</span>
													</label>
												</span>
												</div>
												<div class="form-group textCenter">
													<button id="add-new-category" rel="modal:close" type="button" class="btn">{{trans('language.confirm')}}</button>
												</div>
											</form>
										</div>
									</div>
								</div><!--modal-->
							</div>
						</div>
						<div class="innerTabcontent">
							<div class="whiteBgWrap row">
								<div class="col-12">
									<div class="widewrap">
										<div class="">
											<div class="param-header">
												<div class="row">
													<div class="header-item" style="width: 30%;">{{trans('language.branch')}}</div>
													<div class="header-item" style="width: 55%;">{{trans('language.calculationBase1')}}</div>
													<div class="header-item" style="width: 15%;">{{trans('language.costInCHF1')}}</div>
												</div>
												<div class="clearfix"></div>
											</div>
											<div class="param-body" id="sortable_groups_no_affect">
												<!--<div class="column sortable">-->
												<?php $totalVal = 0; ?>
												@foreach ($parameters as $parameter)
													<div class="param-group org-category {{($parameter['isConstant'] == 1) ? 'isConstant' : ''}}" data-id="{{$parameter['ID']}}" data-type="{{$parameter['value']}}" data-sort-order="{{$parameter['sortOrder']}}">
														<div class="param-group-header tdTitle">
															<div><span class="groupTitle">{{$parameter['name']}}</span> <button class="addLessBtn" style="display:none;"><i class="fa fa-plus"></i></button><button class="trashBtn" style="display:none;"><i class="fa fa-trash"></i></button><button class="upSortBtn" style="display:none;"><i class="fa fa-trash"></i></button><button class="downSortBtn" style="display:none;"><i class="fa fa-trash"></i></button></div>
														</div>
														<div class="param-group-body">
															@if ($parameter['isEmpty'])
																<div class="setNow">
																	<p>{{trans('language.planparamMSG')}}</p>
																	<button class="btn2" disabled>{{trans('language.setupNow')}}</button>
																</div>
															@else
																@foreach ($parameter['children'] as $costElem)

																	<?php

																	$totalVal += (float)$costElem['calcValue'];
																	if ($costElem['itemType'] == 'HOURLY_RATE' || $costElem['itemType'] == 'FIXED_RATE'){
																		if($activetab == 'online' && $costElem['itemName'] == 'Traffic-Kosten'){
																			$chfType = "CHF/TAI";
																		}
																		else{
																			$chfType = "CHF/H";
																		}
																	}
																	else{
																		$chfType = "%";
																	}
																	?>

																	@if (($activetab != 'online' && $costElem['itemName'] != 'Traffic-Kosten')
                                                                    || $activetab == 'online' )

																		@if ($activetab == 'online' )
																			<div data-id="{{$costElem['paramID']}}" data-group-id="{{$parameter['ID']}}" data-service-id="{{$costElem['itemID']}}" class="cost-element {{empty($costElem['paramID']) ? 'new-element' : 'org-element'}}"  data-sort-order="{{$costElem['sortOrder']}}"style="width: 100%; position: relative">
																				<div class="calc-item" style="width: 30%;">
																					{{$costElem['itemName']}}
																				</div>
																				<div class="calc-item" style="width: 55%;">
																					<table class="calculationTable">
																						<tbody>
																						<tr>
																							@if ($costElem['itemName'] == 'Traffic-Kosten')
																								<td>
																									<label class="containerRadio" id="AdlmpsTraffic" style="display: block !important;">
																										CHF/1'000 Adlmps
																										<input type="radio" checked name="radio{{$costElem['itemID']}}" value="0">
																										<span class="checkmark"></span>
																									</label>
																								</td>
																								<td ><input type="text" id = "Traffic-Kosten" placeholder="0" class="lineField elem-value" value="{{$adP}}" readonly></td>
																								<td ><i class="icon_multiplication"></i></td>
																								<td >
																									<div class="custom-select tdSelectField disabled" value="1">
																										<select>
																											@foreach ($rates[$costElem['itemType']] as $rate)
																												@if (empty($rate->value) || $rate->value == 0)
																													<option value="{{$rate->defaultValue}}" data-id="-1" data-service-id="{{$rate->serviceID}}">{{$rate->defaultValue}} {{$chfType}}</option>
																												@else
																													<option value="{{$rate->value}}" data-id="{{$rate->ID}}" {{($rate->ID == $costElem['csID']) ? 'selected' : ''}}>{{$rate->value}} {{$chfType}}</option>
																												@endif
																											@endforeach
																										</select>
																									</div><!--custom-select-->
																								</td>
																								<td ><i class="icon_equal"></i></td>

																							@elseif ($costElem['itemName'] == 'Maintenance')
																								<td>
																									<label class="containerRadio">
																										{{trans('language.Pauschale')}}
																										<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="1">
																										<span class="checkmark"></span>
																									</label>
																								</td>

																							@elseif ($costElem['itemName'] == 'Honorar auf Media N/N')
																								<td>
																									<label class="containerRadio">
																										Mediakosten
																										<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="0">

																										<span class="checkmark"></span>
																									</label>
																								</td>

																								<td ><input type="text" id="Honorar_auf_Media" placeholder="0" class="lineField elem-value" value="{{$nnInChf}}" readonly></td>
																								<td ><i class="icon_multiplication"></i></td>
																								<td >
																									<div class="custom-select tdSelectField disabled" value="1">
																										<select>
																											@foreach ($rates[$costElem['itemType']] as $rate)
																												@if (empty($rate->value) || $rate->value == 0)
																													<option value="{{$rate->defaultValue}}" data-id="-1" data-service-id="{{$rate->serviceID}}">{{$rate->defaultValue}} {{$chfType}}</option>
																												@else
																													<option value="{{$rate->value}}" data-id="{{$rate->ID}}" {{($rate->ID == $costElem['csID']) ? 'selected' : ''}}>{{$rate->value}} {{$chfType}}</option>
																												@endif
																											@endforeach
																										</select>
																									</div><!--custom-select-->
																								</td>
																								<td ><i class="icon_equal"></i></td>

																							@elseif ($costElem['itemName'] == 'Zusatzhonorar')
																								<td>

																									<label class="containerRadio">
																										{{trans('language.Pauschale')}}
																										<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="1">
																										<span class="checkmark"></span>
																									</label>
																								</td>

																							@else
																								<td>
																									<label class="containerRadio">
																										Stundensatz
																										@if ($costElem['isFlatrate'])
																											<input type="radio" name="radio{{$costElem['itemID']}}" value="0">
																										@else
																											<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="0">
																										@endif
																										<span class="checkmark"></span>
																									</label>
																									<label class="containerRadio">
																										{{trans('language.Pauschale')}}
																										@if ($costElem['isFlatrate'])
																											<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="1">
																										@else
																											<input type="radio" name="radio{{$costElem['itemID']}}" value="1">
																										@endif
																										<span class="checkmark"></span>
																									</label>
																								</td>

																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><input type="text" placeholder="0" class="lineField elem-value" value="{{$costElem['itemValue']}}" readonly></td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><i class="icon_multiplication"></i></td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}">
																									<div class="custom-select tdSelectField disabled">
																										<select>
																											@foreach ($rates[$costElem['itemType']] as $rate)
																												@if (empty($rate->value) || $rate->value == 0)
																													<option value="{{$rate->defaultValue}}" data-id="-1" data-service-id="{{$rate->serviceID}}">{{$rate->defaultValue}} {{$chfType}}</option>
																												@else
																													<option value="{{$rate->value}}" data-id="{{$rate->ID}}" {{($rate->ID == $costElem['csID']) ? 'selected' : ''}}>{{$rate->value}} {{$chfType}}</option>
																												@endif
																											@endforeach
																										</select>
																									</div><!--custom-select-->
																								</td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><i class="icon_equal"></i></td>

																							@endif


																						</tr>
																						</tbody>
																					</table>
																				</div>
																				<div class="calc-item colTotal"  style="width: 16%">
																					<input type = "text" class="lineField input-proxi elem-calc-value" placeholder = "0" readonly="" value="{{$costElem['calcValue']}}">

																				</div>

																				<div class="calc-item trashBtn_Item_Div" style="display: none;">
																					<button class="trashBtn_item"  style="<?php if ($costElem['isConstant'] == 1) echo "visibility:hidden;" ?>"><i class="fa fa-trash"></i></button>
																				</div>
																				<div style="display: inline-block;position: absolute; top: 25px; right: 0px;">
																					<button class="SubUpSortBtn" style="display:none;float: left"><i class="fa fa-trash"></i></button><button class="SubDownSortBtn" style="display:none;float: left"><i class="fa fa-trash"></i></button>
																				</div>

																				<div class="clearfix"></div>
																			</div>
																		@elseif ($costElem['itemName'] != 'Setup AdServer' )
																			<div data-id="{{$costElem['paramID']}}" data-group-id="{{$parameter['ID']}}" data-service-id="{{$costElem['itemID']}}" class="cost-element {{empty($costElem['paramID']) ? 'new-element' : 'org-element'}}"  data-sort-order="{{$costElem['sortOrder']}}"style="width: 100%; position: relative">
																				<div class="calc-item" style="width: 30%;">
																					{{$costElem['itemName']}}
																				</div>
																				<div class="calc-item" style="width: 55%;">
																					<table class="calculationTable">
																						<tbody>
																						<tr>
																							@if ($costElem['itemName'] == 'Traffic-Kosten')
																								<td>
																									<label class="containerRadio" id="AdlmpsTraffic" style="display: block !important;">
																										CHF/1'000 Adlmps
																										<input type="radio" checked name="radio{{$costElem['itemID']}}" value="0">
																										<span class="checkmark"></span>
																									</label>
																								</td>
																								<td ><input type="text" id = "Traffic-Kosten" placeholder="0" class="lineField elem-value" value="{{$adP}}" readonly></td>
																								<td ><i class="icon_multiplication"></i></td>
																								<td >
																									<div class="custom-select tdSelectField disabled" value="1">
																										<select>
																											@foreach ($rates[$costElem['itemType']] as $rate)
																												@if (empty($rate->value) || $rate->value == 0)
																													<option value="{{$rate->defaultValue}}" data-id="-1" data-service-id="{{$rate->serviceID}}">{{$rate->defaultValue}} {{$chfType}}</option>
																												@else
																													<option value="{{$rate->value}}" data-id="{{$rate->ID}}" {{($rate->ID == $costElem['csID']) ? 'selected' : ''}}>{{$rate->value}} {{$chfType}}</option>
																												@endif
																											@endforeach
																										</select>
																									</div><!--custom-select-->
																								</td>
																								<td ><i class="icon_equal"></i></td>

																							@elseif ($costElem['itemName'] == 'Maintenance')
																								<td>
																									<label class="containerRadio">
																										{{trans('language.Pauschale')}}
																										<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="1">
																										<span class="checkmark"></span>
																									</label>
																								</td>

																							@elseif ($costElem['itemName'] == 'Honorar auf Media N/N')
																								<td>
																									<label class="containerRadio">
																										Mediakosten
																										<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="0">

																										<span class="checkmark"></span>
																									</label>
																								</td>

																								<td ><input type="text" id="Honorar_auf_Media" placeholder="0" class="lineField elem-value" value="{{$nnInChf}}" readonly></td>
																								<td ><i class="icon_multiplication"></i></td>
																								<td >
																									<div class="custom-select tdSelectField disabled" value="1">
																										<select>
																											@foreach ($rates[$costElem['itemType']] as $rate)
																												@if (empty($rate->value) || $rate->value == 0)
																													<option value="{{$rate->defaultValue}}" data-id="-1" data-service-id="{{$rate->serviceID}}">{{$rate->defaultValue}} {{$chfType}}</option>
																												@else
																													<option value="{{$rate->value}}" data-id="{{$rate->ID}}" {{($rate->ID == $costElem['csID']) ? 'selected' : ''}}>{{$rate->value}} {{$chfType}}</option>
																												@endif
																											@endforeach
																										</select>
																									</div><!--custom-select-->
																								</td>
																								<td ><i class="icon_equal"></i></td>

																							@elseif ($costElem['itemName'] == 'Zusatzhonorar')
																								<td>

																									<label class="containerRadio">
																										{{trans('language.Pauschale')}}
																										<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="1">
																										<span class="checkmark"></span>
																									</label>
																								</td>

																							@else
																								<td>
																									<label class="containerRadio">
																										Stundensatz
																										@if ($costElem['isFlatrate'])
																											<input type="radio" name="radio{{$costElem['itemID']}}" value="0">
																										@else
																											<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="0">
																										@endif
																										<span class="checkmark"></span>
																									</label>
																									<label class="containerRadio">
																										{{trans('language.Pauschale')}}
																										@if ($costElem['isFlatrate'])
																											<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="1">
																										@else
																											<input type="radio" name="radio{{$costElem['itemID']}}" value="1">
																										@endif
																										<span class="checkmark"></span>
																									</label>
																								</td>

																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><input type="text" placeholder="0" class="lineField elem-value" value="{{$costElem['itemValue']}}" readonly></td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><i class="icon_multiplication"></i></td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}">
																									<div class="custom-select tdSelectField disabled">
																										<select>
																											@foreach ($rates[$costElem['itemType']] as $rate)
																												@if (empty($rate->value) || $rate->value == 0)
																													<option value="{{$rate->defaultValue}}" data-id="-1" data-service-id="{{$rate->serviceID}}">{{$rate->defaultValue}} {{$chfType}}</option>
																												@else
																													<option value="{{$rate->value}}" data-id="{{$rate->ID}}" {{($rate->ID == $costElem['csID']) ? 'selected' : ''}}>{{$rate->value}} {{$chfType}}</option>
																												@endif
																											@endforeach
																										</select>
																									</div><!--custom-select-->
																								</td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><i class="icon_equal"></i></td>

																							@endif


																						</tr>
																						</tbody>
																					</table>
																				</div>
																				<div class="calc-item colTotal"  style="width: 16%">
																					<input type = "text" class="lineField input-proxi elem-calc-value" placeholder = "0" readonly="" value="{{$costElem['calcValue']}}">

																				</div>

																				<div class="calc-item trashBtn_Item_Div" style="display: none;">
																					<button class="trashBtn_item"  style="<?php if ($costElem['isConstant'] == 1) echo "visibility:hidden;" ?>"><i class="fa fa-trash"></i></button>
																				</div>
																				<div style="display: inline-block;position: absolute; top: 25px; right: 0px;">
																					<button class="SubUpSortBtn" style="display:none;float: left"><i class="fa fa-trash"></i></button><button class="SubDownSortBtn" style="display:none;float: left"><i class="fa fa-trash"></i></button>
																				</div>

																				<div class="clearfix"></div>
																			</div>
																		@endif

																	@endif

																@endforeach
															@endif
														</div>
													</div>
											@endforeach
											</div>

											<ul class="totalCostUl">
												<li>{{trans('language.costTotal')}}</li>
												<li class="textRight" >{{$totalVal}}</li>
											</ul>

											<div class="clearfix"></div>
											<div class="param-body" id="sortable_groups_no_affect">
												<!--<div class="column sortable">-->
												<?php $totalDeduct = 0; ?>
												@foreach ($deducts as $parameter)

													<div class="param-group org-category {{($parameter['isConstant'] == 1) ? 'isConstant' : ''}}" data-id="{{$parameter['ID']}}" data-type="{{$parameter['value']}}" data-sort-order="{{$parameter['sortOrder']}}">
														<div class="param-group-header tdTitle">
															<div><span class="groupTitle">{{$parameter['name']}}</span> <button class="addLessBtn" style="display:none;"><i class="fa fa-plus"></i></button><button class="trashBtn" style="display:none;"><i class="fa fa-trash"></i></button><button class="upSortBtn" style="display:none;"><i class="fa fa-trash"></i></button><button class="downSortBtn" style="display:none;"><i class="fa fa-trash"></i></button></div>
														</div>
														<div class="param-group-body">
															@if ($parameter['isEmpty'])
																<div class="setNow">
																	<p>{{trans('language.planparamMSG')}}</p>
																	<button class="btn2" disabled>{{trans('language.setupNow')}}</button>
																</div>
															@else
																@foreach ($parameter['children'] as $costElem)

																	<?php

																	$totalDeduct += (float)$costElem['calcValue'];
																	if ($costElem['itemType'] == 'HOURLY_RATE' || $costElem['itemType'] == 'FIXED_RATE'){
																		if($activetab == 'online' && $costElem['itemName'] == 'Traffic-Kosten'){
																			$chfType = "CHF/TAI";
																		}
																		else{
																			$chfType = "CHF/H";
																		}
																	}
																	else{
																		$chfType = "%";
																	}
																	?>


																			<div data-id="{{$costElem['paramID']}}" data-group-id="{{$parameter['ID']}}" data-service-id="{{$costElem['itemID']}}" class="cost-element {{empty($costElem['paramID']) ? 'new-element' : 'org-element'}}"  data-sort-order="{{$costElem['sortOrder']}}"style="width: 100%; position: relative">
																				<div class="calc-item" style="width: 30%;">
																					{{$costElem['itemName']}}
																				</div>
																				<div class="calc-item" style="width: 55%;">
																					<table class="calculationTable">
																						<tbody>
																						<tr>

																								<td>

																									<label class="containerRadio">
																										{{trans('language.Pauschale')}}
																										<input type="radio" checked="checked" name="radio{{$costElem['itemID']}}" value="1">
																										<span class="checkmark"></span>
																									</label>
																								</td>

																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><input type="text" placeholder="0" class="lineField elem-value" value="{{$costElem['itemValue']}}" readonly></td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><i class="icon_multiplication"></i></td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}">

																								</td>
																								<td style="{{$costElem['isFlatrate'] ? 'display:none;' : ''}}"><i class="icon_equal"></i></td>



																						</tr>
																						</tbody>
																					</table>
																				</div>
																				<div class="calc-item colTotal"  style="width: 16%">
																					<input type = "text" class="lineField input-proxi elem-calc-value" placeholder = "0" readonly="" value="{{$costElem['calcValue']}}">

																				</div>

																				<div class="calc-item trashBtn_Item_Div" style="display: none;">
																					<button class="trashBtn_item"  style="<?php if ($costElem['isConstant'] == 1) echo "visibility:hidden;" ?>"><i class="fa fa-trash"></i></button>
																				</div>
																				<div style="display: inline-block;position: absolute; top: 25px; right: 0px;">
																					<button class="SubUpSortBtn" style="display:none;float: left"><i class="fa fa-trash"></i></button><button class="SubDownSortBtn" style="display:none;float: left"><i class="fa fa-trash"></i></button>
																				</div>

																				<div class="clearfix"></div>
																			</div>


																@endforeach
															@endif
														</div>
													</div>
												@endforeach
											</div>

											<ul class="totalCostUl_deduct">
												<li>Abzüge Total</li>
												<li class="textRight" >{{$totalDeduct}}</li>
											</ul>
										</div>
										<div class="btnCenterGroup" id="online-btn-group">
											<button type="button" class="btn2" id="online-edit-cancel">{{trans('language.Cancel')}}</button>
											<button type="button" class="btn"id="online-edit-save">{{trans('language.Save')}}</button>
										</div>

									</div>
								</div>

							</div>
						</div>

					</div>
				</div>


			</div><!--whiteBgWrap-->
		</div>
	</div>
@endsection

@section('page-js')
	<script type="text/javascript">
		var channelID = "{{$channelID}}";
		var campaignID = "{{$campaignID}}";
		var data = {};
		var selectData = {
			"HOURLY_RATE": [],
			"FIXED_RATE": [],
			"PERCENTUAL_RATE": []
		};
		var selectData_withgroupname = {
			"HOURLY_RATE": [],
			"FIXED_RATE": [],
			"PERCENTUAL_RATE": []
		};
		var chfData = {
			"HOURLY_RATE": "CHF/h",
			"FIXED_RATE": "CHF",
			"PERCENTUAL_RATE": "%"
		};

		@foreach ($rates_groupwithname as $key => $indivRates)
            @foreach ($indivRates as $rate)
                @if (empty($rate->value) || $rate->value == 0)
                    data = {
			"ID": "-1",
			"serviceID": "{{$rate->serviceID}}",
			"value": "{{$rate->defaultValue}}",
			"name": "{{$rate->name}}"
		};
		@else
			data = {
			"ID": "{{$rate->ID}}",
			"serviceID": "0",
			"value": "{{$rate->value}}",
			"name": "{{$rate->name}}"
		};
		@endif
		selectData_withgroupname["{{$key}}"].push(data);
		@endforeach
    @endforeach


    @foreach ($rates as $key => $indivRates)
        @foreach ($indivRates as $rate)
            @if (empty($rate->value) || $rate->value == 0)
                data = {
			"ID": "-1",
			"serviceID": "{{$rate->serviceID}}",
			"value": "{{$rate->defaultValue}}",
			"name": "{{$rate->name}}"
		};
		@else
			data = {
			"ID": "{{$rate->ID}}",
			"serviceID": "0",
			"value": "{{$rate->value}}",
			"name": "{{$rate->name}}"
		};
		@endif
		selectData["{{$key}}"].push(data);
		@endforeach
		@endforeach


	</script>
	<script type="text/javascript" src="{{asset('js/pages/planning-parameters.js')}}"></script>
@endsection
