@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/overview.css')}}">
@endsection

@section('content')
<div class="container">
	<div class="row">
		<input type="hidden" id="campaign_id" data-id="{{$data['campaignID']}}">
		<div class="tabHeadTitle">
			<h3>{{$data['campaignName']}}<a href="{{url('planning')}}"><span class="close">&times;</span></a></h3>
		</div><!--tabHeadTitle-->
		
		<div class="border-redius-l-0">

			@include('layouts.channels')

			<div id="Übersicht" class="tabcontent pdding-0">
				<div class="whiteBgWrap row overviewBox">
					<div class="col-6">
						<h1>{{trans('language.basicData')}}</h1>
						
						<div class="form-group">
							<span class="input input--hoshi">
								<input class="input__field input__field--hoshi" type="text" id="customer_name" value="{{ $data['customerName'] }}" readonly/>	
								<label class="input__label input__label--hoshi input__label--hoshi-color-1">
									<span class="input__label-content input__label-content--hoshi">{{trans('language.customer1')}}</span>
								</label>
							</span>
						</div>
						<div class="form-group">
							<span class="input input--hoshi">
								<input class="input__field input__field--hoshi" type="text" id="campaign_name" value="{{ $data['campaignName'] }}" readonly/>
								<label class="input__label input__label--hoshi input__label--hoshi-color-1">
									<span class="input__label-content input__label-content--hoshi">{{trans('language.campaignName1')}}</span>
								</label>
							</span>
						</div>
						<div class="clearDiv"></div>
						<div class="form-group">
							<h5 class="h5">{{trans('language.total_Duration')}}</h5>
						</div>
						<ul class="col2box">
							<li>
								<div class="styled-input dateField">
								  <input type="text" required id="total_channel_start" class="readonly" value="{{$data['totalStartdate']}}" style="background: none;" readonly/>
								  <label style="top:0;">{{trans('language.start1')}}</label>
								  <span></span>
								</div>
							</li>
							<li>
								<div class="styled-input dateField">
								  <input type="text" required id="total_channel_end"  class="readonly" value="{{$data['totalEnddate']}}" style="background: none;" readonly />
								  <label style="top:0;">{{trans('language.end1')}}</label>
								  <span></span>
								</div>
							</li>
						</ul><!--col2box-->
						
						<div class="clearDiv"></div>
						
						<h1>Interne Plan-Versionen</h1>
					{{--	<h1>{{trans('language.planningHistory')}}</h1>--}}

						<table class="table simpleTable" id="table_planningHistory">
							<thead>
								<tr>
									<th>{{trans('language.channel1')}}</th>
									<th>{{trans('language.versionAndDate')}}</th>
								</tr>
							</thead>
							<tbody>
							@foreach ($data['channelsVersions'] as $channel)
								<tr>
									@if($channel['channelName'] != 'tv')
										<td>{{ucfirst($channel['channelName'])}}</td>
									@else
										<td>TV</td>
									@endif
									<td>{{$channel['channelLatestVersion']}} / {{$channel['channelVersionDate']}}</td>
								</tr>
							@endforeach
							</tbody>
						</table><!--simpleTable-->
                        <div class="clearDiv"></div>
                        <a class="btn2" style="float: right;min-width: 110px;padding-top: 7px;"
                           href="{{URL::to('/generatePdf?id='. $data['campaignID'] . '&type=internal')}}">
                        	{{trans('language.exportPDF')}}
                        </a>

                        <div class="clearDiv"></div>

                        <h1>{{trans('language.externalPlanVersions')}}</h1>
                        <ul class="col2box">
							<li>
								<div class="styled-input">
								  <input type="text" id="new_customer_version" />
								  <label style="top:0;">{{trans('language.newCustomerVersion')}}</label>
								</div>
							</li>
							<li>
								<div class="styled-input dateField">
								  <input type="text" id="new_customer_date" class="datepicker" />
								  <label style="top:0;">{{trans('language.date')}}</label>
								</div>
							</li>
						</ul><!--col2box-->
                        <div class="clearDiv"></div>
						<div class="clearDiv"></div>
						<div class="styled-input">
							<label style="top: -20px;">Kommentare</label>
						</div>
						{{--<h1>{{trans('language.planningStatus')}}</h1>--}}

						{{--<div class="form-group">
							@foreach ($data['statuses'] as $status)
							@if ($status['status'] != 'Inaktiv')
							<label class="containerRadio readOnly">{{$status['status']}}
							  @if($status['ID'] == $data['planningStatus'])
							  <input type="radio" checked="checked" name="radio" disabled>
							  <span class="checkmark"></span>
							  @else
							  <input type="radio" name="radio" disabled>
							  <span class="checkmark"></span>
							  @endif
							</label>
							@endif
							@endforeach
						</div>

						<form action="/planning/savecomments" method="post">
							@csrf--}}
							<table class="table simpleTable">
								<thead>
								<tr>
									<th style="width:70%;">
										<textarea rows="7" cols="50" class="comment" name="comments" id="comments" style="resize: none;"></textarea>
									</th>
									{{--<th style="vertical-align: top;">
										<input type="hidden" name="id" value="{{$data['campaignID']}}" />
										<button class="btn2" type="submit">{{trans('language.Save')}}</button>
									</th>--}}
								</tr>
								</thead>
							</table>
						{{--</form>--}}


						<div class="clearDiv"></div>



                        <div class="styled-input">
                        	<label style="top:0;">{{trans('language.previousVersions')}}</label>
                        </div>



                        <div class="clearDiv"></div>
                        <div class="my-custom-scrollbar">

                            <table class="table simpleTable" id="previousVersions">
                            	<tbody>
		                            @foreach ($data['campaignExport'] as $export)
		                            <tr class="previousVersions_row" data-file="{{$export->export_file}}">
	                            		<td>{{$export->version}}</td>
	                            		<td>{{$export->date}}</td>
										<td><a href="/download/pdf/{{$export->export_file}}"><img width="24" height="24" src="/images/icon_archiv.svg" class="mCS_img_loaded"/></a> </td>
		                            </tr>
		                            @endforeach
                            	</tbody>
                            </table>
                        </div>
                        <div class="clearDiv"></div>
                        <a class="btn2" id="campaign_export" style="float: right;min-width: 110px;padding-top: 7px;"
                           href="{{URL::to('/generatePdf?id='. $data['campaignID'] . '&type=external')}}">
                        	{{trans('language.exportPDF')}}
                        </a>
                        
                        <div class="clearDiv"></div>

					</div><!--col-6-->
					
					<div class="col-6">
						<h1>{{trans('language.costOverview')}}</h1>
						<div class="">
						<table class="table simpleTable tableAlignRight">
							<thead>
								<tr class="thNoWrap">
									<th style="width: 50%;">{{trans('language.branch')}}</th>
									<th style="width: 30%;">{{trans('language.costInCHF')}}</th>
									<th>IN%</th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td colspan="3" class="tdTitle"><div>{{trans('language.mediaCost')}}</div></td>
								</tr>
								@foreach ($data['costData']['mediaData']['mediaCosts'] as $key => $mediaCost)
								<tr class="{{($key == 0) ? 'borderTdNone' : ''}}">
									@if($mediaCost['channelName'] != 'tv')
										<td style="width: 50%;">{{ucfirst($mediaCost['channelName'])}} Spendings</td>
									@else
										<td style="width: 50%;">TV Spendings</td>
									@endif

									<td style="width: 30%;">{{$mediaCost['subTotal']}}</td>
									<td>{{$mediaCost['percentage']}}</td>
								</tr>
								@endforeach
								<tr>
									<td style="width: 50%;"><strong>Total Mediakosten</strong></td>
									<td style="width: 30%;"><strong>{{$data['costData']['mediaData']['total']}}</strong></td>
									<td><strong>{{$data['costData']['mediaData']['percentage']}}</strong></td>
								</tr>
							</tbody>
						</table><!--simpleTable-->
						<br>
						<div class="">
						<table class="table simpleTable tableAlignRight">

							<tbody>
								<tr class="borderTdNone">
									<td colspan="3" class="tdTitle"><div>{{trans('language.serviceCost')}}</div></td>
								</tr>
								@foreach ($data['costData']['serviceData']['serviceCosts'] as $idx => $serviceCost)
								<tr class="{{($idx == 0) ? 'borderTdNone' : ''}}">
									<td style="width: 50%;">{{$serviceCost['groupName']}}</td>
									<td style="width: 30%;">{{$serviceCost['subTotal']}}</td>
									<td>{{$serviceCost['percentage']}}</td>
								</tr>
								@endforeach
								<tr>
									<td style="width: 50%;"><strong>Total Servicekosten</strong></td>
									<td style="width: 30%;"><strong>{{$data['costData']['serviceData']['total']}}</strong></td>
									<td><strong>{{$data['costData']['cost_percentage']}}</strong></td>
								</tr>

								<tr class="borderTdNone">
									<td colspan="3" class="tdTitle"><div>Abzüge</div></td>
								</tr>

								@foreach ($data['costData']['deductsCost']['deductServices'] as $idx => $cost)
									<tr class="{{($idx == 0) ? 'borderTdNone' : ''}}">
										<td style="width: 50%;">{{$idx}}</td>
										<td style="width: 30%;">{{$cost}}</td>
										<td></td>

									</tr>
								@endforeach
								<tr>
									<td style="width: 50%;"><strong>Total Abzüge</strong></td>
									<td style="width: 30%;"><strong>{{$data['costData']['deductsCost']['subtotal']}}</strong></td>
									<td></td>
								</tr>

								<tr style="height: 19px;"></tr>
								<tr class="borderTdNone borderRadiusTdGroup">
									<td style="width: 50%;" class="tdTitle"><div>{{trans('language.totalWithoutMWST')}}</div></td>
									<td style="width: 30%;" class="tdTitle"><div>{{$data['costData']['total']}}</div></td>
									<td class="tdTitle"><div>100.00</div></td>
								</tr>
								<tr class="borderTdNone tableLastTr borderRadiusTdGroup">
									<td style="width: 50%;" class="tdTitle"><div>{{trans('language.totalWithMWST')}}</div></td>
									<td style="width: 30%;" class="tdTitle"><div>{{$data['costData']['totalMWST']}}</div></td>
									<td class="tdTitle"><div>{{number_format(100.00 + $data['costData']['vat'],2)}}</div></td>
								</tr>
							</tbody>
						</table><!--simpleTable-->
						</div>

					</div><!--col-6-->						
				</div><!--row-->
			</div><!--tabcontent-->
			
			<div id="Online" class="tabcontent pdding-0">
				<div class="row">
				</div>
			</div>


		</div><!--whiteBgWrap-->
	</div>
</div>
@endsection

@section('page-js')
<script type="text/javascript" src="{{asset('js/pages/overview.js')}}"></script>
@endsection
