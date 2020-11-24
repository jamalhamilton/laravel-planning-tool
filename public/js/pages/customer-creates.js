function numberFormat(){
    $('#form_calculate input[type="text"]').each(function (index, value){
        if($(this).val().length > 3){
            var val = $(this).val();
            $(this).val(numberWithCommas(val));
        }
    });
}

function decimalPoint(){
	$('.part-adserving input.input__field--hoshi').each(function(){
		var val = $(this).val();
		if(val.indexOf('.') == -1 && val.length != 0){
			$(this).val(val + '.0');
		}
	});
}

$(document).ready(function(){

    numberFormat();
	decimalPoint();

    $('#btn_edit').on('click', function(){
		$(".tabRightBtns button").removeAttr("disabled");
		$("#form_calculate .input__field").removeAttr("disabled");
		$(".radio-btn, .addLessBtn").removeAttr("disabled");
		$("#btn_save").css("display", 'inline-block');
		$(this).css("display","none");

		//$('#logo_display').css('display', 'block');
		$('.addLessBtn').css('display', 'block');

        $('#form_calculate input[type="text"]').each(function (index, value){
            if($(this).val().length > 3){
                var val = $(this).val().replace(/\'/g,"");
                $(this).val(val);
            }
        });

	});

	$('#btn_save').on('click', function(){
		//$('#logo_display').css('display', 'none');
		$('.addLessBtn').css('display', 'none');

		var newDatas = [];
		var addDatas = [];
		var editDatas = [];
		var clientID = $('#clientID').val();

		$('.part-adserving input.input__field--hoshi').each(function(){
			var data;
			var type = 0; 

			if ($(this).attr('data-id') == '') {
				if ($(this).attr('data-service-id') == '') {
					type = 1;
					$(this).addClass('new-service-elem');
				}
				else {
					type = 2;
					$(this).addClass('new-cost-elem');	
				}
			} else {
				type = 3;
				//$(this).addClass('new-type-elem');
			}
			//console.log(type)
			if (type == 1) {
				data = {
					'value': $(this).val(),
					'catType': $(this).parents('.cost-category').data("category-type"),
					'name': $(this).next().children().text(),
					'isFlatRate': $(this).attr('is-flat-rate')=="0"?'0':"1"
				};
				newDatas.push(data);
			} else if (type == 2) {
				data = {
					'conServiceID': $(this).attr('data-service-id'),
					'value': $(this).val(),
					'isFlatRate': $(this).attr('is-flat-rate')=="0"?'0':"1"
				};
				addDatas.push(data)
			} else {
				data = {
					'ID': $(this).attr('data-id'),
					'value': $(this).val(),
					'isFlatRate': $(this).attr('is-flat-rate')=="0"?'0':"1"
				};
				//console.log(data)
				editDatas.push(data);
			}
		});

		
		$.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/customer/cost/update',
            dataType: 'JSON',
            data:{
            	'clientID': clientID, 
            	'newDatas': newDatas,
            	'addDatas': addDatas,
            	'editDatas': editDatas
            },
            success: function(data,textStatus,jqXHR) {
        		if (data['status'] == 'success') {

					var respNews = data['data']['newDatas'];
        			var respAdds = data['data']['addDatas'];

        			$('.new-service-elem').each(function(idx) {
        				var tmp = respNews[idx];
        				$(this).attr('data-id',tmp.ID);
        				$(this).attr('data-service-id',tmp.serviceID);
        				$(this).removeClass('new-service-elem');
        			});

        			$('.new-cost-elem').each(function(idx) {
        				var tmp = respAdds[idx];
        				$(this).attr('data-id',tmp.ID);
        				$(this).attr('data-service-id',tmp.serviceID);
        				$(this).removeClass('new-cost-elem');
        			});

                    numberFormat();
					decimalPoint();
        		}
        	},
        	error: function(jqXHR, textStatus, errorThrown){

            }
        });
		
		$(".tabRightBtns button").attr("disabled","");
		$("#form_calculate .input__field").attr("disabled"," ");
		$(".radio-btn").attr("disabled", " ");
		$(".addLessBtn").attr("disabled", " ");
		$('#btn_edit').css('display', 'inline-block');
		$(this).css("display","none");
	});
	
	$('#btn_contact_edit').on('click', function(){
		$("#btn_contact_save").removeAttr("style");
		$("#logo_display").addClass("showUploadButton");
		$("#input_upload").removeAttr("disabled");
		$("#input_upload").css({"float":"right","display":"none"});
		$(this).css("display","none");

		$('#form_contact .input__field').removeAttr("disabled");
		$('.add-empty-line').removeAttr("style");

		$(".uploadFieldBox .showUploadButton").removeAttr("disabled");

		$('#logo_display').css('display', 'block');
	});

	var itemValue;
	var categoryIdx;
	var categoryType;
	var typeStr;

	$('.leftRightBox').on('click', '.addLessBtn', function(){
		$('#input_cost').val('');
		$('#modal2').modal();
		categoryIdx = $(this).parents('.cost-category').data("category-id");
		categoryType = $(this).parents('.cost-category').data("category-type");
		
		if (categoryType == 1)
			typeStr = "CHF/h";
		else if (categoryType == 2)
			typeStr = "CHF";
		else if (categoryType == 3)
			typeStr = "%";		
	});

	var costNum = 0;
	$('#btn_cost').on('click', function(){
		costNum++;
		var itemValue = $('#input_cost').val();
		
		if (!itemValue) {
			return;
		}

		var strFirst = '';
		var strSecond = '';
		var colFirst = '';
		var colSecond = '';

		if(categoryType == 1)
		{
			strFirst = strCHF;
			colFirst = '6';
			colSecond = '6';
		}
		else if(categoryType == 2)
		{
			strFirst = strCHF;
			strSecond = strpauschal;
			colFirst = '6';
			colSecond = '6';
		}
		else if(categoryType == 3)
		{
			strFirst = strPercent;
			strSecond = strpauschal;
			colFirst = '6';
			colSecond = '6';
		}

		var maxDataID = 0;
		var maxDataServiceID = 0;

		$('.input__field--hoshi').each(function(){
			if(maxDataID<$(this).attr('data-id'))
				maxDataID = $(this).attr('data-id');
			if(maxDataServiceID<$(this).attr('data-service-id'))
				maxDataServiceID = $(this).attr('data-service-id');
		});
		
		var costElemTempl = '<div class="col-' + colFirst + '  leftTextBox">' +
									//'<strong>' + typeStr + '</strong>' +
                                    '<label class="containerRadio margin-0">' + strFirst  +
                                        '<input class="radio-btn radio-btn-left" type="radio" checked="checked" name="radio{{$service->serviceID}}'+ costNum +'">' +
                                        '<span class="checkmark"></span>' +
                                    '</label>' +
                                    '<label class="containerRadio margin-0">' + strSecond +
                                        '<input class="radio-btn radio-btn-right" type="radio" name="radio{{$service->serviceID}}'+ costNum +'">' +
                                        '<span class="checkmark"></span>' +
                                    '</label>' +
							'</div>' +
							'<div class="col-' + colSecond + ' rightTextBox">' +
							'<span class="input input--hoshi">' +
								'<input class="input__field input__field--hoshi" type="text" data-id="" data-service-id="" is-flat-rate="0">' +
								'<label class="input__label input__label--hoshi input__label--hoshi-color-1">' +
									'<span class="input__label-content input__label-content--hoshi">' + itemValue + '</span>' +
								'</label>' +
							'</span>' +
							'</div>';
		var costElemTemplFirst ='<div class="col-6 leftTextBox">' +
                                    '<strong>CHF/H</strong>' +
                                '</div>' +
                                '<div class="col-6 rightTextBox">' +
                                    '<span class="input input--hoshi">' +
                                        '<input class="input__field input__field--hoshi" type="text" data-id="" data-service-id="">' +
                                        '<label class="input__label input__label--hoshi input__label--hoshi-color-1">' +
                                            '<span class="input__label-content input__label-content--hoshi">' + itemValue + '</span>' +
                                        '</label>' +
                                    '</span>' +
                                '</div>'
		
		$('#modal2').hide();
		$('.blocker').hide();
		$('body').css("overflow","auto");

		if (categoryIdx == 2 || categoryIdx == 3) {
			$('div.cost-category[data-category-id="'+ categoryIdx +'"]').append(costElemTempl);
		} else if (categoryIdx == 1) {
			$('div.cost-category[data-category-id="'+ categoryIdx +'"]').append(costElemTemplFirst);
		}

		$('.rightTextBox').on('blur', 'span', function() {
			if ($(this).find('input').val() != '') {
				$(this).addClass("input--filled");	
			} else if ($(this).find('input').val() == '') {
				$(this).removeClass("input--filled");
			}
		});
	});

	

	$("#btn_category").on('click',function(){
		var itemValue = $('#input_category').val();
		var max = $('div.cost-category:last').data("category-id");
		var nextVal = max + 1;
		var cateElemTempl = '<div class="clearDiv"></div>' +
								  '<div class="cost-category" data-category-id="'+nextVal +'" data-category-type="1">'+
										'<p>' + itemValue +'<button class="addLessBtn icFr"></button></p>' +
									'</div>';
		
		$('#modal2').hide();
		$('.blocker').hide();
		$('body').css("overflow","auto");
		$('div.cost-category[data-category-id="'+ max +'"]').after(cateElemTempl);
	});

});

//contact data modifing using ajax
$("#btn_contact_save").on('click', function(){
	var clientName, clientID, clientAddress, clientZip, clientState;

	clientID = $('#clientID').val();
	clientName = $('#client_name').val();
	clientAddress = $('#client_address').val();
	clientZip = $('#client_zip').val();
	clientState = $('#client_state').val();

	var formData = false;
	
	if (window.FormData) {
  		formData = new FormData();
	}
	
	file = document.getElementById("input_upload").files[0];

	if (formData) {
		formData.append("name", clientName);
		formData.append("clientID", clientID);
		formData.append("street", clientAddress);
		formData.append("postcode", clientZip);
		formData.append("state", clientState);
		
		if(file){
			formData.append("image", file);
		}
	}

	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'POST',
		url: '/customer/contact/edit',
		data: formData,
		processData: false,
		contentType: false,
		success: function(data, textStatus, jqXHR) {
			$('#btn_contact_save').css("display","none");
			$("#input_upload").attr("disabled"," ");
			$('#btn_contact_edit').css("display","block");
			$('.add-empty-line').css("display","none");
			$("#form_contact .input__field").attr("disabled"," ");
			$(".uploadFieldBox .showUploadButton").attr("disabled","");
		},
		error: function(jqXHR, textStatus, errorThrown){
		}
	});
	$('#logo_display').css('display', 'none');
});

$('.leftRightBox').on('change', '.radio-btn', function(){
	if($(this).hasClass('radio-btn-left')){
		$(this).parents('.leftTextBox').next().find('input').attr('is-flat-rate', '0');
	} else {
		$(this).parents('.leftTextBox').next().find('input').attr('is-flat-rate', '1');
	}
	//alert($(this).parents('.leftTextBox').next().hasClass("rightTextBox"));
});

function openFileDialog(){
	document.getElementById('input_upload').click();
}

function photoPreview(){
	if (document.getElementById('input_upload').files && document.getElementById('input_upload').files[0]) {
		$('#img_upload').css("background","none");
		var reader = new FileReader();
		reader.onload = function(e) {
		    document.getElementById('img_upload').getAttributeNode("src").value = e.target.result;
		};
		reader.readAsDataURL(document.getElementById('input_upload').files[0]);
	}
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "\'");//if you want use , then replace "\'"!
}