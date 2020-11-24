	var oCache = {
		iCacheLower: -1
	};
	
	function fnSetKey( aoData, sKey, mValue )
	{
		for ( var i=0, iLen=aoData.length ; i<iLen ; i++ ) {
			if ( aoData[i].name == sKey ) {
				aoData[i].value = mValue;
			}
		}
	}
	
	function fnGetKey( aoData, sKey )
	{
		for ( var i = 0, iLen = aoData.length ; i < iLen ; i++ ) {
			if ( aoData[i].name == sKey ) {
				return aoData[i].value;
			}
		}

		return null;
	}
	
	function fnDataTablesPipeline ( sSource, aoData, fnCallback, oSettings ) {		
		var loading = $('#example_processing');
		loading.css("display","block");
		$("#example_info").before(loading);

		var iPipe = 5; /* Ajust the pipe size */
		var bNeedServer = false;
		var sEcho = fnGetKey(aoData, "sEcho");
		var iRequestStart = fnGetKey(aoData, "iDisplayStart");
		var iRequestLength = fnGetKey(aoData, "iDisplayLength");
		var iRequestEnd = iRequestStart + iRequestLength;
		oCache.iDisplayStart = iRequestStart;
	
		if ( oCache.iCacheLower < 0 || iRequestStart < oCache.iCacheLower || iRequestEnd > oCache.iCacheUpper ) {
			bNeedServer = true;
		}

		if ( oCache.lastRequest && !bNeedServer ) {
			for( var i=0, iLen=aoData.length ; i<iLen ; i++ ) {
				if ( aoData[i].name != "iDisplayStart" && aoData[i].name != "iDisplayLength" && aoData[i].name != "sEcho" ) {
					if ( aoData[i].value != oCache.lastRequest[i].value ) {
						bNeedServer = true;
						break;
					}
				}
			}
		}
	
		oCache.lastRequest = aoData.slice();
 
 		if ( bNeedServer ) {
			if ( iRequestStart < oCache.iCacheLower ) {
				iRequestStart = iRequestStart - (iRequestLength*(iPipe-1));
				if ( iRequestStart < 0 ) {
					iRequestStart = 0;
				}
			}
			
			oCache.iCacheLower = iRequestStart;
			oCache.iCacheUpper = iRequestStart + (iRequestLength * iPipe);
			oCache.iDisplayLength = fnGetKey( aoData, "iDisplayLength" );
			fnSetKey( aoData, "iDisplayStart", iRequestStart );
			fnSetKey( aoData, "iDisplayLength", iRequestLength*iPipe );
			
			oSettings.jqXHR = $.getJSON( sSource, aoData, function (json) { 

				oCache.lastJson = jQuery.extend(true, {}, json);
				
				if ( oCache.iCacheLower != oCache.iDisplayStart ){
					json.aaData.splice( 0, oCache.iDisplayStart - oCache.iCacheLower );
				}
				json.aaData.splice( oCache.iDisplayLength, json.aaData.length );
				
				oTable.fnClearTable();

				loading.css("display","none"); 
				fnCallback(json);
			} );
		} else {
			loading.css("display","none"); 
			json = jQuery.extend(true, {}, oCache.lastJson);
			json.sEcho = sEcho;
			json.aaData.splice( 0, iRequestStart-oCache.iCacheLower );
			json.aaData.splice( iRequestLength, json.aaData.length );
			loading.css("display","none")  ; 
			fnCallback(json);
			
			return;
		}
	}
var oTable;

function ShowChannelModal(selId){
	if (selId==0) {
		$('#input_campaignname').val("");
		$("#ckb_option").attr('checked',true);
		$("#dt_online_start").attr("disabled",'');
    	$("#dt_online_end").attr("disabled",'');
        $('#dt_online_start').val("");
        $('#dt_online_end').val("");
        $('.switch input').removeAttr("disabled");
		$("#ckb_option").attr('checked',false);        
    	$("#dt_print_start").attr("disabled",'');
    	$("#dt_print_end").attr("disabled",'');				    
        $('#dt_print_start').val("");
        $('#dt_print_end').val("");
    	$("#dt_plakat_start").attr("disabled",'');
    	$("#dt_plakat_end").attr("disabled",'');				    
        $('#dt_plakat_start').val("");
        $('#dt_plakat_end').val("");

		$("#dt_tv_start").attr("disabled",'');
		$("#dt_tv_end").attr("disabled",'');
		$('#dt_tv_start').val("");
		$('#dt_tv_end').val("");

		$("#dt_radio_start").attr("disabled",'');
		$("#dt_radio_end").attr("disabled",'');
		$('#dt_radio_start').val("");
		$('#dt_radio_end').val("");

		$("#dt_kino_start").attr("disabled",'');
		$("#dt_kino_end").attr("disabled",'');
		$('#dt_kino_start').val("");
		$('#dt_kino_end').val("");


		$("#dt_ambient_start").attr("disabled",'');
		$("#dt_ambient_end").attr("disabled",'');
		$('#dt_ambient_start').val("");
		$('#dt_ambient_end').val("");

        $(".chk-channel[data-channel='online']").attr("checked",false);
        $(".chk-channel[data-channel='print']").attr("checked",false);
        $(".chk-channel[data-channel='plakat']").attr("checked",false);
        $(".chk-channel[data-channel='kino']").attr("checked",false);
        $(".chk-channel[data-channel='radio']").attr("checked",false);
        $(".chk-channel[data-channel='tv']").attr("checked",false);        
	} else {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type : 'POST',
            url: '/planning/get',
            dataType : 'JSON',
            data: {'campaignID':selId},
            success: function(data,textStatus,jqXHR) {
                $('.select-search').val(data.clientID).trigger('change');
                $('#input_campaignname').val(data.campaignName);
                channels = data.channelName;
                
                if(data.hasExtraWeek == 1){
                	$("#ckb_option").attr('checked',true);
                } else {
                	$("#ckb_option").attr('checked',false);                	
                }

                allChannels=["online","print","plakat","kino","tv","radio"];
	            
	            for(channel in channels){
			        $(".chk-channel[data-channel='" + channels[channel]+"']").attr("checked",true);
				    startDate = data.startDate.shift();
				    endDate = data.endDate.shift();
				    $("#dt_"+channels[channel]+"_start").removeAttr('disabled');
				    $("#dt_"+channels[channel]+"_end").removeAttr('disabled');
				    $("#dt_"+channels[channel]+"_start").val(startDate);
				    $("#dt_"+channels[channel]+"_end").val(endDate);
				    if(channels[channel] =='online')
				    	$('.switch input').removeAttr("disabled");
	            }

				for (channel in allChannels) {
					if(channels.indexOf(allChannels[channel]) == -1){
				        $(".chk-channel[data-channel='"+allChannels[channel]+"']").attr("checked",false);
					    $("#dt_"+allChannels[channel]+"_start").attr("disabled",'');
					    $("#dt_"+allChannels[channel]+"_end").attr("disabled",'');
					    $("#dt_"+allChannels[channel]+"_start").val("");
					    $("#dt_"+allChannels[channel]+"_end").val("");
					    if(allChannels[channel]=='online')
					        $('.switch input').attr("disabled",'').attr("checked",false);				        						
					}
				}

                $('#input_campaignname').focus();
            },
            error: function(jqXHR, textStatus, errorThrown){

            },
        });
	}
}

$(function() {
	$('button[data-modal]').on('click', function() {
	    $("#modal1 h1").text("Neue Planung erstellen");
	    ShowChannelModal(0);
	    $("#btn_submit").addClass('btn-addrow');
	    $("#btn_submit").removeClass('btn-editrow');
	    
	    $($(this).data('modal')).modal({
			"showClose": false,
			"blockerClass": "blocker-channel"
		});

	    return false;
	});

});

function fnFormatDetails ( oTable, nTr, data )
{
	var aData = oTable.fnGetData( nTr );
	var width1 = $(nTr).find('td:nth-child(1)').width() + 13;
	var width2 = $(nTr).find('td:nth-child(2)').width() + 4;
	var width3 = $(nTr).find('td:nth-child(3)').width() + 1;
	var width4 = $(nTr).find('td:nth-child(4)').width() + 6;
	var width5 = $(nTr).find('td:nth-child(5)').width();
	var width6 = $(nTr).find('td:nth-child(6)').width();
	var width7 = $(nTr).find('td:nth-child(7)').width();


	var sOut = "<table class='collapTable'><thead><tr><th style='width:"+width1+"px;'></th><th style='width:"+width2+"px;'>Channel</th><th style='width:"+width3+"px;'>datum / TIME </th><th style='width:"+width4+"px;'>planer</th><th style='width:"+width5+"px;'>MEDIAKOSTEN N/N</th><th style='width:"+width6+"px;'></th><th style='width:"+width7+"px;'></th></tr></thead>";
	
	for (i = 0 ; i < data['name'].length; i++){
		sOut +=	"<tr><td></td><td>"+data['name'][i]+"</td><td>"+data['versionTime'][i]+" / "+data['versionTimeHMS'][i]+"</td><td>"+data['versionUser'][i]+"</td><td><span style='text-align: left'>"+numberWithCommas(parseFloat(data['nnCHFTotal'][i]).toFixed(2))+"</span></td><td></td><td></td></tr>";
	}

	sOut += "</table>";

	return sOut;
}

function fnCreateSelect( aData )
{	 
	var r = '<select><option value=""></option>', i, iLen = aData.length;
	
	for ( i = 0 ; i < iLen ; i++ ) {
		r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
	}

	return r + '</select>';
}

$('a[data-modal]').on('click', function() {
	$($(this).data('modal')).modal();
	
	return false;
});

$.fn.dataTableExt.oPagination.mypagination = {
     "fnInit": function ( oSettings, nPaging, fnCallbackDraw ) { 

     },
     
     "fnUpdate": function ( oSettings, fnCallbackDraw ) {
		var iPageCount = 40;
		var iPageCountHalf = Math.floor(iPageCount / 2);
		var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
		var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
		var sList = "";
		var iStartButton, iEndButton, i, iLen;
       	PreviousHtml='<li><a href="javascript:;" data-pgidx = "previous"><i class="fa fa-angle-double-left"></i></a></li>';
       	NextHtml='<li><a href="javascript:;" data-pgidx = "next"><i class="fa fa-angle-double-right"></i></a></li>';

		if ( oSettings._iDisplayLength === -1 ) {
			iStartButton = 1;
			iEndButton = 1;
			iCurrentPage = 1;
		} else if (iPages < iPageCount) {
			iStartButton = 1;
			iEndButton = iPages;
		} else if (iCurrentPage <= iPageCountHalf) {
			iStartButton = 1;
			iEndButton = iPageCount;
		} else if (iCurrentPage >= (iPages - iPageCountHalf)) {
			iStartButton = iPages - iPageCount + 1;
			iEndButton = iPages;
		} else {
			iStartButton = iCurrentPage - Math.ceil(iPageCount / 2) + 1;
			iEndButton = iStartButton + iPageCount - 1;
		}

		for ( i=iStartButton ; i<=iEndButton ; i++ ) {
			sList += (iCurrentPage !== i) ?
				'<li><a href="javascript:;"  data-pgidx="'+oSettings.fnFormatNumber(i-1)+'">'+oSettings.fnFormatNumber(i)+'</a></li>' :
				'<li class="active"><a href="javascript:;"  data-pgidx="'+oSettings.fnFormatNumber(i-1)+'">'+oSettings.fnFormatNumber(i)+'</a></li>';
		}
		displayend = (oSettings._iDisplayStart+10<oSettings.fnRecordsTotal()) ? oSettings._iDisplayStart+10:oSettings.fnRecordsTotal();
		PageinfoHtml ='<span class="pagNote">Showing '+(oSettings._iDisplayStart+1)+' to '+displayend+' of '+oSettings.fnRecordsTotal()+' entries</span>';
		$(".pagination").empty();
		$(".pagination").append(PreviousHtml, sList, NextHtml,PageinfoHtml);
       
        if ( !oSettings.aanFeatures.p ) {
            return;
        }
     }
   };

//	$(".datepicker").on('click', function(){
		//var curdate = new Date();
		//var stday = curdate.getDay();
		//var stdate = curdate.getDate();
		//if(stday - 1 > 0 )
		//	stdate += (8 - stday);
		//else if(stday == 0)
		//	stdate += 1;
		//var startDate = new Date(curdate.getFullYear(), curdate.getMonth(), stdate);
        //
		//$(".datepicker").datepicker("setDate", startDate);
		//$(".hasDatepicker").datepicker( "option", "firstDay", 1 );
//	});

$(document).ready(function() {

	$(".hasDatepicker").datepicker( "option", "firstDay", 1 );
	$('.hasDatepicker').on('change', function(){
		var id = $(this).attr('id');
		var val = $(this).val();
		
		if (id == 'dt_online_start') {
			$('#dt_online_end').val(val);	
		}
		else if (id == 'dt_print_start') {
			$('#dt_print_end').val(val);
		}
		else if (id == 'dt_plakat_start') {
			$('#dt_plakat_end').val(val);
		} else if (id == 'dt_tv_start') {
			$('#dt_tv_end').val(val);
		} else if (id == 'dt_radio_start') {
			$('#dt_radio_end').val(val);
		} else if (id == 'dt_kino_start') {
			$('#dt_kino_end').val(val);
		}
		else if (id == 'dt_ambient_start') {
			$('#dt_ambient_end').val(val);
		}
	});

	$('.select-search').select2();
	$("#client_name").val($('.select-search option:selected').text());

	oTable = $('#example').dataTable( {
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "planning/list",
			"fnServerData": fnDataTablesPipeline,
		"oLanguage": {
			"sSearch": "   ",
			"sScrollY": "300px",
	        "bPaginate": false ,
	        "bScrollCollapse": true
		},
	   "sPaginationType": "mypagination",
		"aoColumnDefs": [ 
			{"sClass":"statusTd ","aTargets":[3]},
			{"sClass":"btnTdGroup ","aTargets":[4]},	
			{"sClass":"actionBtnGroup ","aTargets":[6]},												
	      { "bSearchable": false, "aTargets": [ 0,3,4,5 ] }
			
	    ],
	    "aoColumns":[
	    {
	    	"mData":'campaignID',
	    	"mRender": function(data,type,full){
	    		return '<td><button data-id="'+data+'" class="addLessBtn"><i class="fa"></i></button></td>';
	    	}
	    },
	    {
	    	"mData":'clientName',
	    	"mRender": function(data,type,full){
	    		return data;
	    	}
	    },
	    {
	    	"mData":'campaignName',
	    	"mRender": function(data,type,full){
	    		return data;
	    	}
	    },{
	    	"mData":'campaignStatus',
	    	"mRender": function(data,type,full){
	    		return '<label class="switch"><input type="checkbox" '+((data == 1) ? '':'checked')+'><span class="slider round"></span></label>'+((data == 1) ? 'inaktiv' : ' aktiv ');
	    	}
	    },{
	    	"mData":'channelName',
	    	"mRender": function(data,type,row){
	    		if(data==null || typeof data === 'undefined') data ="";
	    		return 	'<button disabled class="'+ ((data.indexOf("online") >= 0) ? 'active':'')+'">Online</button><button disabled class="'+ ((data.indexOf("plakat") >= 0) ? 'active':'')+'">Plakat</button><button disabled class="'+ ((data.indexOf("print") >= 0) ? 'active':'')+'">Print</button><button disabled class="'+ ((data.indexOf("radio") >= 0) ? 'active':'')+'">Radio</button><button disabled class="'+ ((data.indexOf("tv") >= 0) ? 'active':'')+'">TV</button><button disabled class="'+ ((data.indexOf("kino") >= 0) ? 'active':'')+'">Kino</button><button disabled class="'+ ((data.indexOf("ambient") >= 0) ? 'active':'')+'">Ambient</button>';
	    	}
	    },{
	    	"mData":'showTime',
	    	"mRender": function(data,type,full){
	    		return data;
	    	}
	    },{
	    	"mData":'urlID',
	    	"mRender": function(data,type,full){
	    		//return '<button type="button" class="btn-edit"><img src="images/icon_edit.svg"></button><a href="/planning/overview?id='+data+'"><img src="images/icon_archiv.svg"></a>';
	    		return '<a href="/planning/overview?id='+data+'"><img src="images/icon_archiv.svg"></a>';
	    	}	    
	    }],
	    "aaSorting": [[1, 'asc']],
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "bInfo": true,
        "bAutoWidth": false
	} );
	
	$('body').on('click','#example .statusTd input',function(){
		currentCampaingID = $(this).parents('tr').find("button:first").data('id');	
		optionValue = $(this).prop('checked');
		
		if (optionValue){
			$(this).parents('td').html('<label class="switch"><input type="checkbox" checked><span class="slider round"></span></label>  aktiv </td>');
		} else {
			$(this).parents('td').html('<label class="switch"><input type="checkbox"><span class="slider round"></span></label>inaktiv</td>');
		}
    
        $.ajax({ 
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type : 'POST',
            url: '/planning/option/update',
            dataType : 'JSON',
            data:{
            	'currentCampaingID':currentCampaingID,
            	'optionValue':optionValue,
            },
            success: function(data,textStatus,jqXHR) {

			},
			error: function(jqXHR, textStatus, errorThrown){

	      	},
        });
	});

	$('#btn_save').on('click', function(){
	});

	var ordered = new Array();
	var minDate;
	var maxDate;
	var flag1, flag2, flag3,flag4;

	$('#modal_campaign').submit(function( e ) {
		e.preventDefault();
		
		var data = {
				'campaignID':$("#selected_id").val(),
				'clientName': $('#client_name').val(),
                'campaignName': $('#input_campaignname').val(),
                'onlineCheck': ($(".chk-channel[data-channel='online']").attr("checked")) ? true : false,
                'printCheck': ($(".chk-channel[data-channel='print']").attr("checked")) ? true : false,
                'plakatCheck': ($(".chk-channel[data-channel='plakat']").attr("checked")) ? true : false,
                'kinoCheck': ($(".chk-channel[data-channel='kino']").attr("checked")) ? true : false,
                'radioCheck': ($(".chk-channel[data-channel='radio']").attr("checked")) ? true : false,
                'tvCheck': ($(".chk-channel[data-channel='tv']").attr("checked")) ? true : false,
			'ambientCheck': ($(".chk-channel[data-channel='ambient']").attr("checked")) ? true : false,
				'dt_online_start': $('#dt_online_start').val(),
				'dt_online_end': $('#dt_online_end').val(),
				'dt_print_start': $('#dt_print_start').val(),
				'dt_print_end': $('#dt_print_end').val(),
				'dt_plakat_start': $('#dt_plakat_start').val(),
				'dt_plakat_end': $('#dt_plakat_end').val(),
				'dt_kino_start': $('#dt_kino_start').val(),
				'dt_kino_end': $('#dt_kino_end').val(),
				'dt_radio_start': $('#dt_radio_start').val(),
				'dt_radio_end': $('#dt_radio_end').val(),
				'dt_tv_start': $('#dt_tv_start').val(),
				'dt_tv_end': $('#dt_tv_end').val(),

			'dt_ambient_start': $('#dt_ambient_start').val(),
			'dt_ambient_end': $('#dt_ambient_end').val(),
                'optionCheck': ($('#ckb_option').attr('checked')) ? true : false,
           	}

			if ($('#btn_submit').hasClass('btn-addrow')) {
				url ='/planning/add';
				$("#waitingIcon").css("display", "inline-block");
			} else {		
				url ='/planning/edit';
			}	

        $.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type : 'POST',
            url: url,
            dataType : 'JSON',
            data: data,
            success: function(data,textStatus,jqXHR) {
				$("#waitingIcon").css("display", "none");
            	if (data.status != 'error') {
            		if (url == '/planning/add') {
            			location.href = '/planning/overview?id='+data.id;
            		}
    				else {
    					location.href = '/planning';
    				}
            	} else {
            		$('.error-alert').html('<span class="invalid-feedback" role="alert">'+
                                				'<strong style="color:red">This campaign name is already existed. Name differently.</strong>'+
                            				'</span>');
    			}

				if ($('#btn_submit').hasClass('btn-addrow')) {
					// fnClickAddRow(data.id);
				} else {		
					// fnClickEditRow(data.id);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#waitingIcon").css("display", "none");
	      },
        });

    });

	$(".select-search").on('change',function(){
		$("#client_name").val($('.select-search option:selected').text());
	});

	$('#example').on('click','.btnTdGroup button',function(){
		$(this).toggleClass("active");
		currentCampaingID = $(this).parents('tr').find("button:first").data('id');
		channelValue = $(this).hasClass('active');
		channelName = $(this).text().toLowerCase();
		
		if (channelName == 'online' && channelValue == true) {
			$(this).parent().prev().find("input").removeAttr('disabled');
		} else if (channelName == 'online' && channelValue == false) {
			$(this).parent().prev().find("input").attr('disabled',"");
			$(this).parent().prev().find("input").removeAttr('checked');
		}

        $.ajax({ 
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type : 'POST',
            url: '/planning/channel/update',
            dataType : 'JSON',
            data:{
            	'currentCampaingID':currentCampaingID,
            	'channelValue':channelValue,
            	'channelName':channelName,
            },
            success: function(data,textStatus,jqXHR) {

			},
			error: function(jqXHR, textStatus, errorThrown){

	      	},
        });
	});

	function fnClickAddRow(id) {
		onlineoptValue = ($('#ckb_option').prop("checked")) ? 'checked' : '';
		onlinebtnValue = ($('#ckb_online').prop("checked")) ? ' class="active"' : '';					
		printbtnValue = ($('#ckb_print').prop("checked")) ? ' class="active"' : '';
		plakatbtnValue = ($('#ckb_plakat').prop("checked")) ? ' class="active"' : '';
		kinobtnValue = ($('#ckb_kino').prop("checked")) ? ' class="active"' : '';
		radiotvbtnValue = ($('#ckb_radio').prop("checked")) ? ' class="active"' : '';
		tvbtnValue = ($('#ckb_tv').prop("checked")) ? ' class="active"' : '';

	    oTable.fnAddData( [
	        '<button class="addLessBtn" data-id="'+id+'"><i class="fa"></i></button>',
	        $('.select2-selection__rendered').text(),
	        $('.input--hoshi input').val(),
	        '<label class="switch"><input type="checkbox" '+onlineoptValue+'><span class="slider round"></span></label>   inaktiv',
			'<button'+ onlinebtnValue +' class="active">Online</button><button '+plakatbtnValue+'>Plakat</button><button '+printbtnValue+'>Print</button><button '+radiotvbtnValue+'>Radio</button><button '+tvbtnValue+'>TV</button><button '+kinobtnValue+'>Kino</button>',
			'13.08. - 09.09.18 / KW 33 - 36',
			'<button type="button">      <img src="images/icon_edit.svg" class="btn-edit">    </button>      <button type="button">     <a href="overview.html"><img src="images/icon_archiv.svg"></a>  </button>']);
		$("#modal1").hide();
	    $('.blocker').hide();
    	$('body').css('overflow','auto');
	}

	$('#example').on('click','.addLessBtn',function () {
		var nTr = $(this).parents('tr')[0];
		var selectedID = $(this).parents('tr').find("button:first").data('id');
		var thiz = $(this);

        $.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            method: 'POST',
            url: '/planning/channel/get',
            dataType : 'JSON',
            data: {
            	"selectedID" : selectedID,
            },
            success: function(data) {
				if ( oTable.fnIsOpen(nTr) ) {
					thiz.toggleClass("addPlusBtn");
					oTable.fnClose( nTr );
				} else {
					thiz.toggleClass("addPlusBtn");						
					oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr,data), 'hiddenTr' );
				}
			},
			error: function(err){
	      	}
        });

	} );

	$('body').on("click", ".btn-edit", function(){
		$("#modal_channel h1").text("Edit Planung");
		var selId = $(this).parents('tr').find("button:first").data('id');
		$('#selected_id').val(selId);
		$("#btn_submit").addClass('btn-editrow');
		$("#btn_submit").removeClass('btn-addrow');
    	selectedTr = $(this).parents('tr');
        
        if ( selectedTr.hasClass('row_selected') ) {
             selectedTr.removeClass('row_selected');
        } else {
            oTable.$('tr.row_selected').removeClass('row_selected');
            selectedTr.addClass('row_selected');
        }
        
        ShowChannelModal(selId); 

		$("#modal_channel").modal({
			"showClose": false,
			"blockerClass": "blocker-channel"
		});
		
	});

	function fnGetSelected( oTableLocal ) {
	    return oTableLocal.$('tr.row_selected');
	}

	function fnClickEditRow(id){
	    var anSelected = fnGetSelected( oTable );
		onlineoptValue = ($('#ckb_option').prop("checked")) ? 'checked' : '';
		onlinebtnValue = ($('#ckb_online').attr("checked")) ? ' class="active"' : '';					
		printbtnValue = ($('#ckb_print').attr("checked")) ? ' class="active"' : '';
		plakatbtnValue = ($('#ckb_plakat').attr("checked")) ? ' class="active"' : '';
		kinobtnValue = ($('#ckb_kino').prop("checked")) ? ' class="active"' : '';
		radiotvbtnValue = ($('#ckb_radio').prop("checked")) ? ' class="active"' : '';
		tvbtnValue = ($('#ckb_tv').prop("checked")) ? ' class="active"' : '';				
	    
		var data = {
			'campaignID': '1ssss',
	    	'clientName`':'1',
	    	'campaignName': '1',
	    	'campaignStatus': '1',
	    	'channelName': '1',
	    	'showTime': '1',
	    	'urlID': '1'
	   };

	    oTable.fnUpdate( [
	        '<button class="addLessBtn" data-id = "'+id+'"><i class="fa"></i></button>',
	        $('.select2-selection__rendered').text(),
	        $('.input--hoshi input').val(),
	        '<label class="switch"><input type="checkbox" '+onlineoptValue+'><span class="slider round"></span></label>   inaktiv',
			'<button'+onlinebtnValue+'>Online</button><button '+plakatbtnValue+'>Plakat</button><button '+printbtnValue+'>Print</button><button '+radiotvbtnValue+'>Radio</button><button '+tvbtnValue+'>TV</button><button '+kinobtnValue+'>Kino</button>',
			'13.08. - 09.09.18 / KW 33 - 36',
			'<button type="button" class="btn-edit">      <img src="images/icon_edit.svg">    </button>      <button type="button">     <a href="overview.html"><img src="images/icon_archiv.svg"></a>  </button>'],anSelected[0]._DT_RowIndex);
		

		$("#modal1").hide();
	    $('.blocker').hide();
    	$('body').css('overflow','auto');
	}

	$(".searchField input").keyup( function () {
		oTable.fnFilter(this.value);
		// resetScroll();
	} );

	$('.pagination').on('click','li a',function(){
		var idxStr = $(this).data('pgidx');
		
		if (idxStr!='previous' && idxStr !='next') {
			$('ul.pagination li.active').removeClass('active');
			$(this).parent().addClass('active');
		}
		
		oTable.fnPageChange(idxStr);
	});

	$('.filter-select').on('click','.select-items div', function(){
		var columnIdx = $(this).parents('.filter-select').data('field');
		
		if ($(this).text() == "Alle Kunde" || $(this).text()=="Alle Planungen") {
			oTable.fnFilter( "", columnIdx);						
		} else {
			oTable.fnFilter( $(this).text(), columnIdx);
		}
		// resetScroll();
	});
});
