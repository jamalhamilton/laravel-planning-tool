function ShowEditModal(selId) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/planning/get',
        dataType: 'JSON',
        data:{
        	'campaignID':selId
        },
        success: function(data,textStatus,jqXHR) {
            $('.select-search').val('1').trigger('change');
            $('#input_campaginname').val(data.campaignName);
            channels = data.channelName;
           
            if (data.hasExtraWeek == 1) {
            	$("#ckb_option").attr('checked',true);
            } else {
            	$("#ckb_option").attr('checked',false);                	
            }

            allChannels = ["online","print","plakat","kino","tv","radio", "ambient"];

            for (channel in channels) {
		        $(".chk-channel[data-channel='"+channels[channel]+"']").attr("checked",true);
			    startDate = data.startDate.shift();
			    endDate = data.endDate.shift();
			    $("#dt_"+channels[channel]+"_start").removeAttr('disabled');
			    $("#dt_"+channels[channel]+"_end").removeAttr('disabled');
			    $("#dt_"+channels[channel]+"_start").val(startDate);
			    $("#dt_"+channels[channel]+"_end").val(endDate);

			    if (channels[channel]=='online') {
			    	$('.switch input').removeAttr("disabled");
			    }
            }

			for (channel in allChannels) {
				if (channels.indexOf(allChannels[channel]) == -1) {
			        $(".chk-channel[data-channel='"+allChannels[channel]+"']").attr("checked",false);
				    $("#dt_"+allChannels[channel]+"_start").attr("disabled",'');
				    $("#dt_"+allChannels[channel]+"_end").attr("disabled",'');
				    $("#dt_"+allChannels[channel]+"_start").val("");
				    $("#dt_"+allChannels[channel]+"_end").val("");
				    if (allChannels[channel]=='online') $('.switch input').attr("disabled",'').attr("checked",false);				        						
				}

			}

            $('#input_campaginname').focus();
        },
        error: function(jqXHR, textStatus, errorThrown) {

        },
    });
}

$(document).ready(function() {
	$("#btn_channel_modal").on("click",function() {
		campaignID = $("#campaign_id").data('id');
		ShowEditModal(campaignID);
		confirmStatus = 0;
		$('#modal_channel').modal({
			"showClose": false,
			"blockerClass": "blocker-channel"
		});
	});

	$("body").on("click", ".switch input[type='checkbox']", function() {
		if ($(this).attr("disabled") !== "disabled") {
			
			if ($(this).attr("checked") === "checked") {
				$(this).removeAttr("checked");
			} else {
				$(this).attr("checked", "checked");
			}
		}
	});

	$(".chk-channel").on("click", function(e) {

		var channel = $(this).data("channel");
		if ($(this).attr("checked") === "checked") {
			var ischeck = $('#modal_channel .check-channel input[checked]').length
			console.log("ischeck",ischeck);

			if(ischeck < 2){
				e.preventDefault();
				e.stopImmediatePropagation();
				$("#modal_confirm2").modal({
					"closeExisting": 0,
					"blockerClass": "blocker-confirm"
				});
				return ;
			}

			if (channel == "online" || channel == "print" || channel == "plakat"
					|| channel == "radio" || channel == "kino" || channel == "tv" || channel == "ambient") {
				e.preventDefault();
				e.stopImmediatePropagation();
				confirmStatus = 1;
				console.log(confirmStatus);
				$("#modal_confirm #btn_confirm").attr("edit-channel",channel);
				$("#modal_confirm").modal({
					"closeExisting": 0,
					"blockerClass": "blocker-confirm"
				});
				$(".blocker-confirm.behind").remove();
				return false;	
			} else {
				$(this).removeAttr("checked");
				return true;
			}
		}
		
		$(this).attr("checked","checked");
		
		if (channel =="online") {
			$('#ckb_option').removeAttr('disabled');	
		}

		$("#dt_"+channel+"_start").removeAttr("disabled");
		$("#dt_"+channel+"_end").removeAttr("disabled");
		
		return true;	
	});
	$(".blocker.current").on("click",function(){
		$("body").css("overflow","hidden");
	});


	$("body").on("click", "#modal_confirm #btn_confirm",function() {
		var channel;
		modalclose();

		channel = $(this).attr("edit-channel");
		if(channel == "online") {
			$('#ckb_option').removeAttr('checked');
			$('#ckb_option').attr('disabled',true);
		}

		$("#channel_"+channel).removeAttr("checked");
		$("#dt_"+channel+"_start").attr("disabled",true).val("");
		$("#dt_"+channel+"_end").attr("disabled",true).val("");
	});
	
	$("body").on("click","#modal_channel .close",function() {
		$("#modal_channel").hide();
		$(".blocker-channel").hide();
		$("body").css("overflow","hidden");
	});
	$("body").on("click",".blocker-channel.blocker.current",function(){
		$("body").css("overflow","hidden");		
	});
	$("body").on("click","#modal_confirm #btn_close", function() {
		modalclose();
	});
	$("body").on("click","#modal_confirm2 #btn_close", function() {
		modalclose2();
	});
	$("body").on("click", "#modal_confirm .close",function() {
		modalclose();
	});
	$("body").on("click", "#modal_confirm2 .close",function() {
		modalclose2();
	});
	$("body").on("click", "#btn_close",function() {
		modalclose();
	});
	function modalclose(){
			var modalconfirm = $("#modal_confirm");
		$("#modal_confirm").hide();
		$(".blocker-confirm").remove();
		$("body").append(modalconfirm);
		$(".blocker-channel").removeClass("behind").addClass("current");
		$(".blocker-channel").css("display","");
		$("#modal_channel").css("display","inline-block");
		$("body").css("overflow","hidden");
	}
	function modalclose2(){
		var modalconfirm = $("#modal_confirm2");
		$("#modal_confirm2").hide();
		$(".blocker-confirm").remove();
		$("body").append(modalconfirm);
		$(".blocker-channel").removeClass("behind").addClass("current");
		$(".blocker-channel").css("display","");
		$("#modal_channel").css("display","inline-block");
		$("body").css("overflow","hidden");
	}

	function closeModalChanel(){
		$(".modal").modal("hide");


	}

	$(document).click(function (e) {
		var c = $(e.target).attr("class");
		if(c == "blocker-confirm blocker current"){
			closeModalChanel();
		}
	})


    $('#form_add_channel').on('submit',function( e ) {
		var thiz = $('#btn_save_channel');
		e.preventDefault();
		e.stopPropagation();

		var checked = ($(".chk-channel[data-channel='ambient']").attr("checked"));

		$("#waitingIcon").css("display", "inline-block");
		var data = {
				'campaignID':$("#campaign_id").data('id'),
				'clientName': $('#customer_name').val(),
                'campaignName': $('#campaign_name').val(),
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

        $.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/planning/edit',
            dataType: 'JSON',
            data: data,
            success: function(data,textStatus,jqXHR) {
				$("#modal_channel").hide();
				$(".blocker-channel").hide();
				$("body").css("overflow","auto");
				thiz.trigger('channel.changed');
				location.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$("#modal_channel").hide();
				$(".blocker-channel").hide();
				$("body").css("overflow","auto");
	        },
        });

        return false;
    });
});



