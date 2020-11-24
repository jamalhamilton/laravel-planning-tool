/*var myCustomScrollbar = document.querySelector('.my-custom-scrollbar');
var ps = new PerfectScrollbar(myCustomScrollbar);

var scrollbarY = myCustomScrollbar.querySelector('.ps.ps--active-y>.ps__scrollbar-y-rail');

myCustomScrollbar.onscroll = function() {
  scrollbarY.style.cssText = `top: ${this.scrollTop}px!important; height: 400px; right: ${-this.scrollLeft}px`;
}*/

$( function() {
	$(".hasDatepicker").datepicker( "option", "firstDay", 1 );
	$('#btn_save_channel').on('channel.changed', function() {
		location.reload();
	});
} );

$(document).ready(function() {

	$( ".previousVersions_row" ).dblclick(function() {
		var f = $(this).data('file');
		document.location = '/download/pdf/'+f;
	});
	
	$('.my-custom-scrollbar').perfectScrollbar();
	$('#new_customer_version, #new_customer_date').click(function() {
		$(this).css('border-bottom', '1px solid #B9C1CA');
	});

	$('#previousVersions tbody tr').click(function() {
		$('#previousVersions tbody tr').removeClass('active');
		$(this).addClass('active');
	});

	$('#campaign_export').click(function(e) {
    	var version = '';
    	var vDate = '';
    	
    	var flag = true;

    	if ($('#new_customer_version').val() == '') {
    		$('#new_customer_version').css('border-bottom', '1px solid #ff0000');
    		flag = false;
    	} else {
    		version = $('#new_customer_version').val()
    	}

    	if ($('#new_customer_date').val() == '') {
    		$('#new_customer_date').css('border-bottom', '1px solid #ff0000');
    		flag = false;
    	} else {
    		vDate = $('#new_customer_date').val()
    	}
		var comments = $("#comments").val();
		if(comments == null || comments == undefined){
			comments = "";
		}
		comments = encodeURI(comments);
    	if (flag) {
    		$(this).attr('href', $(this).attr('href') + '&version=' + version + '&date=' + vDate + '&comments=' + comments);
    		$.ajax({
	        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            url: '/planning/campaignExport',
	            data:{
	            	'ID':$("#campaign_id").data('id'),
	            	'version':version,
	            	'vDate':vDate
	            },
	            dataType: 'JSON',
	            success: function(data,textStatus,jqXHR) {
	            	var version = data['version'];
	            	var vDate = data['date'];

	            	var isNew = data['isNew'];
	            	var file_name = data['file_name'];
	            	if(isNew) {
						var txt = '<tr class="previousVersions_row" data-file="'+file_name+'"><td>' + version + '</td>' +
								'<td>' + vDate + '</td><td><a href="/download/pdf/'+file_name+'"><img width="24" height="24" src="/images/icon_archiv.svg" class="mCS_img_loaded"/></a> </td></tr>';
						$('#previousVersions').prepend(txt);
					}

					$( ".previousVersions_row" ).dblclick(function() {
						 var f = $(this).data('file');
						 document.location = '/download/pdf/'+f;
					});
					$("#comments").text("");
					$("#comments").val("");
				},
				error: function(jqXHR, textStatus, errorThrown) {
                   alert(errorThrown);
		      	},
	        });
    	} else {
    		e.preventDefault();
    	}
    });
});
