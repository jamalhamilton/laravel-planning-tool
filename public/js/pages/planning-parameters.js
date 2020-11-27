function numberFormat(){
    $('.custom-select.tdSelectField').each(function (index, value){
       $(this).find('option').each(function (index, value){
            var val = $(this).val();
            var text = $(this).text();

            var rate = "";
            if(text.indexOf("%") > 0)
				rate = " %";
			else if(text.indexOf("CHF/H") > 0)
				rate = " CHF/H";
            else if(text.indexOf("CHF/TAI") > 0)
				rate = " CHF/TAI";
			else
				rate = " CHF";

            $(this).text(numberWithCommas(val) + rate);
        });
        $(this).find('.select-items>div').each(function (index, value){
            var val = $(this).text();
            val = val.replace(" CHF/H", "");
            val = val.replace(" CHF/TAI", "");
            val = val.replace(" CHF", "");
            val = val.replace(" %", "");

            var text = $(this).text();

            var rate = "";
			if(text.indexOf("%") > 0)
				rate = " %";
			else if(text.indexOf("CHF/H") > 0)
				rate = " CHF/H";
			else if(text.indexOf("CHF/TAI") > 0)
				rate = " CHF/TAI";
			else
				rate = " CHF";

            $(this).text(numberWithCommas(val) + rate);
        });

        $(this).find('.select-selected').each(function (index, value){
            var val = $(this).text();
            val = val.replace(" CHF/H", "");
			val = val.replace(" CHF/TAI", "");
            val = val.replace(" CHF", "");
            val = val.replace(" %", "");

            var text = $(this).text();

            var rate = "";
			if(text.indexOf("%") > 0)
				rate = " %";
			else if(text.indexOf("CHF/H") > 0)
				rate = " CHF/H";
			else if(text.indexOf("CHF/TAI") > 0)
				rate = " CHF/TAI";
			else
				rate = " CHF";

            $(this).text(numberWithCommas(val) + rate);
        });

    });

    $('.lineField.elem-value').each(function (index, value){

        var val = $(this).val().replace(/\'/g,"");
        $(this).val(numberWithCommas(val));
    });
    $('.lineField.input-proxi.elem-calc-value').each(function (index, value){

        var val = $(this).val().replace(/\'/g,"");
        $(this).val(numberWithCommas(val));
    });

}
function numberRemoveFormat(){
    $('.lineField.elem-value').each(function (index, value){

        var val = $(this).val().replace(/\'/g,"");
        $(this).val(val);
    });
    $('.lineField.input-proxi.elem-calc-value').each(function (index, value){

        var val = $(this).val().replace(/\'/g,"");
        $(this).val(val);
    });
}


$(document).ready(function(){

	$(".hasDatepicker").datepicker( "option", "firstDay", 1 );
    numberFormat();
    calculateHonorar();
	calculateTrafficKosten();

	var currentCategory;
	var groupType = 'HOURLY_RATE';
	var fEditMode = false;

	$("#Online .custom-select").each(function() {
		if ($(this).find('select option[selected]').length == 0) {
			$(this).find('select option:first').attr("selected", "");
		}
	});

	$("#Online .containerRadio input[checked='checked']").parent().css('display', 'block');
	
	$("#Online .param-group .cost-element .calculationTable input[type='text']").attr("readonly","");
	
	$("#shift-to-edit-mode").on('click', function(){
		numberRemoveFormat();

		fEditMode = true;
		$("#open-category-create").show();
		$("#online-btn-group").show();
		
		$(this).hide();
		$(".addLessBtn").show();
		$(".trashBtn").show();
		$(".upSortBtn").show();
		$(".downSortBtn").show();
		$(".SubUpSortBtn").show();
		$(".SubDownSortBtn").show();
		$(".colTotal").css("width","8%");
		$(".setNow .btn2").removeAttr("disabled");
		$("#Online input[type='text']").removeAttr("readonly");

        $("#Online .custom-select").each(function(index,value){
        	if($(this).attr("value") != "1") {
				$(this).removeClass("disabled");
			}
		});

		$("#Online .calc-item .input-proxi.elem-calc-value").each(function(){
			if ($(this).parents('.cost-element').find('input[type=radio]').val() == 0) {
				$(this).attr("readonly","");
			}
			else {
				$(this).removeAttr("readonly");
			}

		});

		$("#Online .containerRadio").css("display", "block");
		$(".param-group.isConstant").find('.trashBtn').hide();

		$("#Honorar_auf_Media").attr("readonly","1");
		$("#Traffic-Kosten").attr("readonly","1");

		$(".trashBtn_Item_Div").css("display","block");
	});

	$("#online-edit-save").on('click', function(){
		fEditMode = false;

		var orgServiceGroups = [];
		var newServiceGroups = [];

		$(".org-category").each(function() {
			var childElems = [];
			var orgCat = {
				"ID": $(this).data('id'),
				"sortOrder": $(this).data('sort-order')
			};
			
			$(this).find(".cost-element").each(function(){
				var current = $(this);
				if (current.hasClass('new-element')) {
					var costElem = {
						"name": current.children(".calc-item:first").text(),
						"itemID": current.attr('data-service-id'), 
						"groupID": current.attr('data-group-id'), 
						"value": current.find('.elem-value').val(),
						"calcValue": current.find('.elem-calc-value').val(),
						"csID": current.find('.custom-select select option[selected]').attr("data-id"),
						"csVal": current.find('.custom-select select option[selected]').val(),
						"conID": current.find('.custom-select select option[selected]').attr("data-service-id"),
						"isFlatrate": current.find("input[type='radio']:checked").val(),
						"sortOrder": current.attr('data-sort-order'),
					};	
				} else {
					var costElem = {
						"ID": current.attr('data-id'),
						"itemID": current.attr('data-service-id'), 
						"groupID": current.attr('data-group-id'),
						"value": current.find('.elem-value').val(),
						"calcValue": current.find('.elem-calc-value').val(),
						"csID": current.find('.custom-select select option[selected]').attr("data-id"),
						"csVal": current.find('.custom-select select option[selected]').val(),
						"conID": current.find('.custom-select select option[selected]').attr("data-service-id"),
						"isFlatrate": current.find("input[type='radio']:checked").val(),
						"sortOrder": current.attr('data-sort-order'),
					};	

				}

				childElems.push(costElem);
			});	
			
			orgCat['elems'] = childElems;
			// console.log('childElems', childElems);
			orgServiceGroups.push(orgCat);
		});

		$(".new-category").each(function() {
			var childElems = [];
			var newCat = {
				"name": $(this).find(".param-group-header div").text(),
				"sortOrder": $(this).data('sort-order')
			};
			
			$(this).find(".cost-element").each(function(){
				var current = $(this);

				if (current.hasClass('new-element')) {
					
					var costElem = {
						"name": current.children(".calc-item:first").text(),
						"value": current.find('.elem-value').val(),
						"calcValue": current.find('.elem-calc-value').val(),
						"csID": current.find('.custom-select select option[selected]').attr("data-id"),
						"csVal": current.find('.custom-select select option[selected]').val(),
						"conID": current.find('.custom-select select option[selected]').attr("data-service-id"),
						"isFlatrate": current.find("input[type='radio']:checked").val()
					};
					childElems.push(costElem);
				}
			});

			
			newCat['elems'] = childElems;
			newServiceGroups.push(newCat);
		});

        numberFormat();
		$.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/planning/params/update',
            dataType: 'JSON',
            data:{
            	"campaignID": campaignID,
            	"channelID": channelID,
            	"orgDatas": orgServiceGroups,
            	"newDatas": newServiceGroups
            },
            success: function(data,textStatus,jqXHR) {
        		if (data['status'] == 'success') {
        			var orgDatas = data['data']['orgDatas'];
        			var newDatas = data['data']['newDatas'];

        			$(".org-category").each(function() {
						
						$(this).find('.cost-element').each(function(){
							var current = $(this);
							
							if (current.hasClass('new-element')) {
								var orgData = orgDatas.shift();

		        				current.attr('data-id', orgData['paramID']);
		        				current.attr('data-group-id', orgData['groupID']);
		        				current.attr('data-service-id', orgData['serviceID']);
		        				current.find(".custom-select select option[selected]").attr('data-id', orgData['conID']);

		        				current.removeClass('new-element');
		        				current.addClass('org-element');
							}
						});

					});

					$(".new-category").each(function() {
						var newData = newDatas.shift();
						var childs = newData['childs'];

						$(this).attr('data-id', newData['groupID']);

						$(this).find('.cost-element').each(function(){
							var current = $(this);
							
							if (current.hasClass('new-element')) {
								var childData = childs.shift();

			                    current.attr('data-id', childData['paramID']);
		        				current.attr('data-service-id', childData['serviceID']);
		        				current.find(".custom-select select option[selected]").attr('data-id', childData['conID']);

		        				current.removeClass('new-element');
		        				current.addClass('org-element');
							}
						});
						
						$(this).removeClass('new-category');
						$(this).addClass('org-category');
					});


        		}
        	},
        	error: function(jqXHR, textStatus, errorThrown){

            }
        });

		$("#open-category-create").hide();
		$("#shift-to-edit-mode").show();
		$("#Online input[type='text']").attr("readonly", "");
		$("#Online .containerRadio").hide();
		$("#Online .containerRadio input[checked='checked']").parent().show();
		$("#online-btn-group").hide();
		$(".addLessBtn").hide();
		$(".trashBtn").hide();
		$(".upSortBtn").hide();
		$(".downSortBtn").hide();
		$(".SubUpSortBtn").hide();
		$(".SubDownSortBtn").hide();
		$(".colTotal").css("width","15%");
		$(".setNow .btn2").attr("disabled","");
		$("#Online .input-proxi").attr("readonly", "");
		$("#Online .custom-select").addClass("disabled");

		$("#AdlmpsTraffic").show();

		$(".trashBtn_Item_Div").css("display","none");

	});

	$("#online-edit-cancel").on('click', function(){
		location.reload(); 
		// fEditMode = false;
		
		// $("#open-category-create").hide();
		// $("#shift-to-edit-mode").show();
		// $("#Online input[type='text']").attr("readonly", "");
		// $("#Online .containerRadio").hide();
		// $("#Online .containerRadio input[checked='checked']").parent().show();
		// $("#online-btn-group").hide();
		// $(".addLessBtn").hide();
		// $(".setNow .btn2").attr("disabled","");
		// $("#Online .input-proxi").attr("readonly", "");
		// $("#Online .custom-select").addClass("disabled");
	});

	$("#open-category-create").on('click', function() {

		$('#modal3').modal({
			"closeExisting": false,
			"showClose": false,
			'blockerClass':'blocker-confirm1'
		});
	});

	$("#addservicegroup").on('click', "#add-new-category", function(){
		if ($('#modal3 input').val() == '') {
			return;
		}
		

		
		var groupName = $("#modal3 input").val();
		var sortOrder = $(".param-group").length + 1;

		if ($('#modal3 input').val()) {
			planningSetup = '<div class="param-group new-category" data-id="" data-type="HOURLY_RATE" data-sort-order="'+sortOrder+'">';
			planningSetup += '	<div class="param-group-header tdTitle">';
			planningSetup += '		<div>'+groupName+' <button class="addLessBtn"><i class="fa fa-plus"></i></button><button class="trashBtn"><i class="fa fa-trash"></i></button><button class="upSortBtn"><i class="fa fa-trash"></i></button><button class="downSortBtn"><i class="fa fa-trash"></i></button></div>';	
			planningSetup += '	</div>';
			planningSetup += '	<div class="param-group-body">';
			planningSetup += '		<div class="setNow">';
			planningSetup += '			<p>Lege bitte die Planungsparameter für diesen Bereich fest.</p>';
			planningSetup += '			<button class="btn2">Jetzt festlegen</button>';
			planningSetup += '		</div>';
			planningSetup += '	</div>';
			planningSetup += '</div>'; 
			
			$("#sortable_groups_no_affect").append(planningSetup);

			$("#modal3 input").val('');
		
			$('#modal3').hide();
			$('.blocker-confirm1').hide();
			$('body').css('overflow', 'auto');
		}
	});

	//contentToAdd ='<tr data-id="" data-group-id="" data-service-id="" class="new-element"><td>Bewirtschaftung</td><td><table class="calculationTable"><tbody><tr><td><label class="containerRadio" style="display: inline;">Stundensatz<input type="radio" checked="checked" name="" value="0"><span class="checkmark"></span></label><label class="containerRadio" style="display: block;">Pauschale<input type="radio" name="" value="1"><span class="checkmark"></span></label></td><td><input type="text" placeholder="0" class="lineField elem-value"></td><td><i class="icon_multiplication"></i></td><td><div class="custom-select tdSelectField"><select></select></div></td><td><i class="icon_equal"></i></td></tr></tbody></table><td><input type="text" class="lineField input-proxi elem-calc-value" placeholder="0"></td></tr>';

	contentToAdd = ''
	contentToAdd += '<div data-id="" data-group-id="" data-service-id="" data-sort-order="" class="cost-element new-element" style="width: 100%; position: relative">';
	contentToAdd += '	<div class="calc-item" style="width: 30%;">Bewirtschaftung</div>';
	contentToAdd += '	<div class="calc-item" style="width: 55%;">';
	contentToAdd += '		<table class="calculationTable">';
	contentToAdd += '			<tbody>';
	contentToAdd += '				<tr>';
	contentToAdd += '					<td>';
	contentToAdd += '						<label class="containerRadio" style="display: block;">Stundensatz';
	contentToAdd += '							<input type="radio" checked="checked" name="unnamed" value="0">';
	contentToAdd += '							<span class="checkmark"></span>';
	contentToAdd += '						</label>';
	contentToAdd += '						<label class="containerRadio" style="display: block;">Pauschale';
	contentToAdd += '							<input type="radio" name="unnamed" value="1">';
	contentToAdd += '							<span class="checkmark"></span>';
	contentToAdd += '						</label>';
	contentToAdd += '					</td>';
	contentToAdd += '					<td style=""><input type="text" placeholder="0" class="lineField elem-value" value="0"></td>';
	contentToAdd += '					<td style=""><i class="icon_multiplication"></i></td>';
	contentToAdd += '					<td style="">';
	contentToAdd += '					<div class="custom-select tdSelectField">';
	contentToAdd += '						<select>';
	contentToAdd += '						</select>';
	contentToAdd += '					</td>';
	contentToAdd += '					<td style=""><i class="icon_equal"></i></td>';
	contentToAdd += '				</tr>';
	contentToAdd += '			</tbody>';
	contentToAdd += '		</table>';
	contentToAdd += '	</div>';
	contentToAdd += '	<div class="calc-item colTotal" style="width: 8%"><input type="text" class="lineField input-proxi elem-calc-value" placeholder="0.00" value="0.00" readonly="readonly"></div>';
	contentToAdd += '	<div class="calc-item trashBtn_Item_Div" ><button class="trashBtn_item" ><i class="fa fa-trash"></i></button></div>';
	contentToAdd += '<div style="display: inline-block;position: absolute; top: 25px; right: 0px;"><button class="SubUpSortBtn" style="float: left"><i class="fa fa-trash"></i></button><button class="SubDownSortBtn" style="float: left"><i class="fa fa-trash"></i></button></div>';
	contentToAdd += '	<div class="clearfix"></div>';
	contentToAdd += '</div>';

	$('.elem-value').on('click', function(){
		if (fEditMode) {
			this.select();
		}
	});

	function parentNextElement(currentThis){
		var element = $(currentThis).parents('.org-category').next();

		if(element.find('.elem-value').length == 0){
			element = $(currentThis).parents('.new-category').next();
		}
		while(element.find('.elem-value').length == 0)
		{
			if(element.is(':last-child')){
				break;
			}
			element = element.next();
			if(element.length == 0){
				break;
			}
		}

		return element;
	}
	$("#Online").on('keydown', '.elem-value', function(e) {
		var keyCode = e.keyCode || e.which;

		if (keyCode == 9) {
			e.preventDefault();

			var flag = 0;
			var element = $(this).parents('.org-element').next();
			if(element.length == 0){
				element = $(this).parents('.new-element').next();
			}
			if(element.length == 0){
				element = parentNextElement(this);

			}
			else{
				if(element.find('.elem-value').length == 0){
					if (element.is(':last-child')) {
						element = parentNextElement(this);
					}
					else{
						element = element.next();
						while (element.find('.elem-value:first').parent().attr("style") && element.find('.elem-value:first').parent().attr("style").indexOf("none") >= 0) {
							if (element.is(':last-child')) {
								element = parentNextElement(this);
								break;
							}
							element = element.next();
							if(element.length == 0){
								break;
							}
						}
					}
				}
				else {
					while (element.find('.elem-value:first').parent().attr("style") && element.find('.elem-value:first').parent().attr("style").indexOf("none") >= 0) {
						if (element.is(':last-child')) {
							element = parentNextElement(this);
							break;
						}
						element = element.next();
						if(element.length == 0){
							break;
						}

					}
				}
			}
			element.find('.elem-value:first').select();
			// call custom function here
		}
	});

	$('.elem-calc-value').on('click', function(){
		if (fEditMode) {
			this.select();
		}
	});

	$('#add-cost-element').on('click', function(){
		if (fEditMode) {
            groupType = 'HOURLY_RATE';

			var selectHtml = '', div_selectHtml='';
			var selects = selectData[groupType];
			var chfType = chfData[groupType];
			var customSelect;
			var groupID = 0;
			var radioIdx = 0;
			var textToReplace;


			for (var i = 0; i < selects.length; i++) {
				var selected = (i == 0) ? 'selected' : '';
				selectHtml += '<option value="' + selects[i].value +'" data-id="'+ selects[i].ID +'" data-service-id="'+selects[i].serviceID+'" '+selected+'>'+ numberWithCommas(selects[i].value) +' ' + chfType + '</option>';

                var div_selected = (i == 0) ? 'class="same-as-selected"' : '';
				div_selectHtml += '<div '+div_selected+'>' + numberWithCommas(selects[i].value) +' ' + chfType  + '</div>';

			}

			var pCategory = currentCategory;

			$('#params-list span').each(function(){
                var neworder = pCategory.find('.param-group-body .cost-element').length + 1;

                $new_element = $(contentToAdd);

                $new_element.attr('data-group-id', groupID);
                $new_element.attr('data-sort-order', neworder);
                $new_element.find('select').html(selectHtml);
                $new_element.find('select').next().next().html(div_selectHtml);

                initNewCustomSelect($new_element.find('.custom-select'));

                textToReplace = $(this).text();

                radioIdx = getMaxID(textToReplace);

                $new_element.find(' .calc-item:first').text(textToReplace);
                $new_element.find('input[type="radio"]').attr("name", textToReplace+'_'+radioIdx);
                $new_element.find('input.input-proxi').attr("readonly", true);

                $('.elem-value',$new_element).on('click', function(){
                    if (fEditMode) {
                        this.select();
                    }
                });
                $('.elem-calc-value',$new_element).on('click', function(){
                    if (fEditMode) {
                        this.select();
                    }
                });

                pCategory.find('.param-group-body').append($new_element);
            });

			/*$('#params-list span').each(function(){
				if (pCategory.find('.param-group-body .setNow').length > 0)
					pCategory.find('.param-group-body .setNow').remove();

				pCategory.find('.param-group-body').append(contentToAdd);
				
				groupID = pCategory.find('div').attr('data-id');
				var neworder = pCategory.find('.param-group-body .cost-element').length;
				
				pCategory.find('.param-group-body .cost-element:last-child').attr('data-group-id', groupID);
				pCategory.find('.param-group-body .cost-element:last-child').attr('data-sort-order', neworder);
				customSelect = pCategory.find('.custom-select');
				customSelect.find('select').html(selectHtml);

				//reset the tab index
				$("input.lineField").each(function (i) { $(this).attr('tabindex', i + 1); });

				var val = 0;
				if(selects.length > 0){
					val = selects[0].value;
				}

				var len = customSelect.length;
				var span_len = parseInt($('#params-list span').length);
				for(var i = len - 1 ; i >= len - span_len  ; i --){
                    $(customSelect[i]).find('select').next().html(val + ' ' + chfType);
                    $(customSelect[i]).find('select').next().next().html(div_selectHtml);
				}

				initNewCustomSelect(customSelect);
				
				textToReplace = $(this).text();
				
				radioIdx = getMaxID(textToReplace);
				
				pCategory.find('.param-group-body .cost-element:last .calc-item:first').text(textToReplace);
				pCategory.find('.param-group-body .cost-element:last input[type="radio"]').attr("name", textToReplace+'_'+radioIdx);
				pCategory.find('.param-group-body .cost-element:last input.input-proxi').attr("readonly", true);

				$('.elem-value').on('click', function(){
					if (fEditMode) {
						this.select();
					}
				});
				$('.elem-calc-value').on('click', function(){
					if (fEditMode) {
						this.select();
					}
				});
			});*/

			$('#modal2').hide();
			$('.blocker-confirm').hide();
			$('body').css('overflow', 'auto');
			
			$('#params-list').html("");
		}

	});

	$('#modal2').on('modal:close', function() {
		$('#params-list').html("");
	});

	$("#Online").on('click', '.containerRadio', function(){
		$(this).children("input").attr("checked", "checked");
		$(this).siblings().children("input").removeAttr("checked");
		
		if ($(this).children('input').val() == '1') {
			$(this).parent().next().hide();
			$(this).parent().next().next().hide();
			$(this).parent().next().next().next().hide();
			$(this).parent().next().next().next().next().hide();

			$(this).parents('.calculationTable').parents('.cost-element').find('input.elem-calc-value').removeAttr('readonly');
		} else {
			$(this).parent().next().show();
			$(this).parent().next().next().show();
			$(this).parent().next().next().next().show();
			$(this).parent().next().next().next().next().show();
			$(this).parents('.calculationTable').parents('.cost-element').find('input.elem-calc-value').attr('readonly', '');
		}
	});

	$("#Online").on('click', '.addLessBtn',  function(){
		if (fEditMode == true) {

			var existingItems = "";
			$(this).parent().parent().next().children('.org-element').each(function(){
				existingItems += $(this).children(":first").text() + ",";
			});

			currentCategory = $(this).parents('.param-group');
			groupType = $(this).parents('.param-group').data('type');
			
			var categoryName = $(this).parent().find('.groupTitle').text();

			var selectHtml = '';
			var selects = selectData_withgroupname[groupType];
			
			selectHtml += '<option value="" data-id="" data-service-id="" selected></option>';
			
			for (var i = 0; i < selects.length; i++) {
				if(existingItems.indexOf(selects[i].name + ",") >= 0){

				}
				else {
					selectHtml += '<option value="' + selects[i].value + '" data-id="' + selects[i].ID + '" data-service-id="' + selects[i].serviceID + '">' + selects[i].name + '</option>';
				}
			}

			$('#modal2 .custom-select .label').html(categoryName.toUpperCase());
			$('#modal2 .custom-select select').html(selectHtml);
			$('#modal2 .custom-select .select-items').remove();
			$('#modal2 .custom-select .select-selected').remove();

			if(categoryName != 'Technische Kosten ' && categoryName != 'Media-Honorar ' && categoryName != 'Abzüge ')
			{
				//$('#indiv_form').hide();
				initNewCustomSelect($('#modal2 .custom-select'));
			}

			else
			{
				$('#indiv_form').show();
			}

			$('#modal2').modal({
				"closeExisting": false,
				"showClose": false,
				'blockerClass':'blocker-confirm'
			});



			$("#modal3_indi_category").val("");
		}
	});

	var selectItemToRmove;
	$('#Online').on('click','.trashBtn_item',function() {
		if (fEditMode == true) {
			selectItemToRmove = $(this).parent().parent();

			currentCategory = $(this).parents('.param-group');
			$('#itemmodal_confirm_del').modal({
				"closeExisting": false,
				"showClose": true,
				'blockerClass':'blocker-confirm2'
			});
		}
	});

	$('#itembtn_del_confirm').on('click', function() {

		var serviceGroupItemId = selectItemToRmove.attr("data-service-id");

		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			url: '/planning/params/delete_groupitem',
			dataType: 'JSON',
			data:{
				"serviceGroupItemId": serviceGroupItemId
			},
			success: function(data,textStatus,jqXHR) {
				if (data['status'] == 'success') {

					calculateTotalCost();
				}
			},
			error: function(err) {

			}
		});

		$('#itemmodal_confirm_del').hide();
		$('.blocker-confirm2').hide();
		$('body').css('overflow', 'auto');

		selectItemToRmove.remove();
	})

	$('#Online').on('click','.trashBtn',function() {
		if (fEditMode == true) {
			currentCategory = $(this).parents('.param-group');
			$('#modal_confirm_del').modal({
				"closeExisting": false,
				"showClose": true,
				'blockerClass':'blocker-confirm2'
			});
		}
	});

	$("#Online").on('click', '.upSortBtn',  function(){
		if (fEditMode == true) {
			var sortOrder = 0;
			var currentGroup = $(this).parents('.param-group');
			var prevGroup = currentGroup.prev();

			prevGroup.before(currentGroup);	

			prevGroup.attr('data-sort-order', prevGroup.index() + 1);
			currentGroup.attr('data-sort-order', currentGroup.index() + 1);
		
		}
	});

	$("#Online").on('click', '.downSortBtn',  function(){
		if (fEditMode == true) {
			var currentGroup = $(this).parents('.param-group');
			var nextGroup = currentGroup.next();

			nextGroup.after(currentGroup);	
			
			nextGroup.attr('data-sort-order', nextGroup.index() + 1);
			currentGroup.attr('data-sort-order', currentGroup.index() + 1);
		
		}
	});

	$("#Online").on('click', '.SubUpSortBtn',  function(){
		if (fEditMode == true) {
			var sortOrder = 0;
			var currentGroup = $(this).parents('.cost-element');
			var prevGroup = currentGroup.prev();

			prevGroup.before(currentGroup);

			prevGroup.attr('data-sort-order', prevGroup.index() + 1);
			currentGroup.attr('data-sort-order', currentGroup.index() + 1);

		}
	});

	$("#Online").on('click', '.SubDownSortBtn',  function(){
		if (fEditMode == true) {
			var currentGroup = $(this).parents('.cost-element');
			var nextGroup = currentGroup.next();

			nextGroup.after(currentGroup);

			nextGroup.attr('data-sort-order', nextGroup.index() + 1);
			currentGroup.attr('data-sort-order', currentGroup.index() + 1);

		}
	});

	$('#btn_del_confirm').on('click', function() {
		$.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/planning/params/delete',
            dataType: 'JSON',
            data:{
            	"channelID": channelID,
            	"groupID": currentCategory.data('id')
            },
            success: function(data,textStatus,jqXHR) {
        		if (data['status'] == 'success') {
        			currentCategory.remove();
        		}
        	},
        	error: function(err) {

        	}
        });
		
		$('#modal_confirm_del').hide();
		$('.blocker-confirm2').hide();
		$('body').css('overflow', 'auto');
	})


	$("#Online").on('click', '.setNow .btn2', function(){
		if (fEditMode == true) {
			currentCategory = $(this).parents('.param-group');
			groupType = 'HOURLY_RATE';

			var categoryName = $(this).parents('.param-group').find('.groupTitle').text();
			var selectHtml = '';
			var selects = selectData_withgroupname[groupType];
			
			selectHtml += '<option value="" data-id="" data-service-id="" selected></option>';
			
			for (var i = 0; i < selects.length; i++) {
				selectHtml += '<option value="' + selects[i].value +'" data-id="'+ selects[i].ID +'" data-service-id="'+selects[i].serviceID+'">'+ selects[i].name + '</option>';
			}

			$('#modal2 .custom-select .label').html(categoryName.toUpperCase());
			$('#modal2 .custom-select select').html(selectHtml);
			$('#modal2 .custom-select .select-items').remove();
			$('#modal2 .custom-select .select-selected').remove();


			if(categoryName != 'Technische Kosten' && categoryName != 'Media-Honorar' && categoryName != 'Abzüge')
			{
			initNewCustomSelect($('#modal2 .custom-select'));
			}else{
				$('#modal2 .custom-select select').html('');
				$('#modal2 .custom-select .select-items').remove();
				$('#modal2 .custom-select .select-selected').remove();
			}


			$('#modal2').modal({
				"closeExisting": false,
				"showClose": false,
				'blockerClass':'blocker-confirm'
			});
		}

	});

	$('#Online').on('keypress', '.calculationTable input', function(e){ // 'keyup change'
		if ((e.keyCode<48 || e.keyCode>57)) {
			if (e.keyCode != 8 && e.keyCode != 46) {
				e.preventDefault();
				e.stopPropagation();

			}
		}
	});

	$('#Online').on('input propertychange paste', '.calculationTable input', function(e){ // 'keyup change'

		var groupType = $(this).parents('.param-group').data('type');
		var stTitle = $(this).parent().text();
		var radioTitle = $(this).parents('.calculationTable').find('.containerRadio').text();
		var calcVal;

		l_Number = $(this).val();
		r_Number = $(this).parents('.calculationTable').find('option[selected]').val();
		
		//if (groupType == 'PERCENTUAL_RATE')
		//	calcVal = (l_Number * r_Number / 100).toFixed(2);
		//else
			calcVal = (l_Number * r_Number).toFixed(2);

		$(this).parents('.calculationTable').parent('.calc-item').next().children('input').val(calcVal);
	
		calculateTotalCost();
	} );

	$('#Online').on('input propertychange paste', 'input.elem-calc-value', function(e){ // 'keyup change'
		calculateTotalCost();
	} ); 

	$('#Online').on('change', '.custom-select select',  function() {
		var groupType = $(this).parents('.param-group').data('type');
		var calcVal;

		l_Number = $(this).parents('.calculationTable').find('.elem-value').val();
		r_Number = $(this).val();
		
		//if (groupType == 'PERCENTUAL_RATE')
		//	calcVal = (l_Number * r_Number / 100).toFixed(2);
		//else
			calcVal = (l_Number * r_Number).toFixed(2);

		$(this).parents('.calculationTable').parent('.calc-item').next().children('input').val(calcVal);
	
		calculateTotalCost();
	});

	
	$('#modal2 .custom-select').on('change','select', function(){
		var elemID = $(this).find('option[selected]').data('id');
		var elemServiceID = $(this).find('option[selected]').data('service-id');

		var elemText = $(this).find('option[selected]').text();

		if (elemID == "" && elemServiceID == "" && elemText == "")
			return;

		var costElem =  '<span class="addOpt" data-id="'+elemID+'" data-service-id="'+elemServiceID+'">'+
                            '<button class="del-param"><img src="'+base_url+'/images/Close.svg"></button>'+elemText+
                        '</span>';
        var existing = false;
                        
        $('#params-list span').each(function(){
        	if ($(this).data('id') == elemID) {
        		existing = true;
        	}
        });              

        if (!existing) {
        	 $('#params-list').append(costElem);
        }
	});

	$('#params-list').on('click','span .del-param', function(){
		$(this).parent().remove();
	});

	// Draggable
	$("#sortable_groups").sortable({
        connectWith: ".param-group",
        items: ".param-group", 
        opacity: 0.8,
        coneHelperSize: true,
        placeholder: 'sortable-placeholder',
        forcePlaceholderSize: true,
        tolerance: "pointer",
        helper: "clone",
        tolerance: "pointer",
        revert: 250, // animation in milliseconds
        update: function(b, c) {
            if (c.item.prev().hasClass("sortable-empty")) {
                c.item.prev().before(c.item);
            }                    
        }
    });

	$("#modal3_indi_category").on('keyup',function(event){
		var val = $("#modal3_indi_category").val();
		if(val == ""){
			$("#modal3_indi_img").attr("src",base_url + "/images/icon_grun_moreInfo_gray.png" );

			$("#modal3_indi_img").css("cursor","initial");
		}
		else{
			$("#modal3_indi_img").attr("src",base_url + "/images/icon_grun_moreInfo.svg" );
			$("#modal3_indi_img").css("cursor","pointer");
		}
	});

	$("#modal3_indi_category").on('change',function(event){
		var val = $("#modal3_indi_category").val();
		if(val == ""){
			$("#modal3_indi_img").attr("src",base_url + "/images/icon_grun_moreInfo_gray.png" );

			$("#modal3_indi_img").css("cursor","initial");
		}
		else{
			$("#modal3_indi_img").attr("src",base_url + "/images/icon_grun_moreInfo.svg" );
			$("#modal3_indi_img").css("cursor","pointer");
		}
	});

});


// Calculate total cost
function calculateTotalCost() {
	var totalCost = 0.0;

	$('.input-proxi').each(function() {

		totalCost += parseFloat($(this).val().replace(/\'/g,""));
	});

	$('ul.totalCostUl > li.textRight').text(numberWithCommas(totalCost.toFixed(2)));
}

function getMaxID(radioName) {
	var idx = 0;
	
	$("input[type='radio']").each(function() {
		var name = $(this).attr('name');
		var curIdx;
		
		if (name.indexOf(radioName) == 0) {
			curIdx = parseInt(name.substring(radioName.length+1));
			if (idx < curIdx) idx = curIdx;
		}
		
	});

	return idx + 1;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "\'");//if you want use , then replace "\'"!
}
function calculateHonorar(){
    var calcVal;
    l_Number = $("#Honorar_auf_Media").val();
	if(!l_Number) l_Number = "0";
    l_Number = l_Number.replace(/\'/g,"");

    r_Number = parseFloat($("#Honorar_auf_Media").parents('.calculationTable').find('.select-selected').text())
	//var text = $("#Honorar_auf_Media").parents('.calculationTable').find('.containerRadio').text();

    calcVal = (l_Number * r_Number / 100).toFixed(2);

	//if(text.indexOf('Mediakosten') == -1)
	//	calcVal = (calcVal * 100).toFixed(2);

	if(calcVal == "0.00") calcVal = "0";
    $("#Honorar_auf_Media").parents('.calculationTable').parent('.calc-item').next().children('input').val(calcVal);

    calculateTotalCost();

    numberFormat();

}

function calculateTrafficKosten(){
	var calcVal;
	l_Number = $("#Traffic-Kosten").val();
	if(!l_Number){
		l_Number = "0";
	}
	l_Number = l_Number.replace(/\'/g,"");

	r_Number = parseFloat($("#Traffic-Kosten").parents('.calculationTable').find('.select-selected').text())
	//var text = $("#Honorar_auf_Media").parents('.calculationTable').find('.containerRadio').text();
	if(!r_Number){
		r_Number = "0";
	}
	calcVal = (l_Number * r_Number / 1000).toFixed(2);

	//if(text.indexOf('CHF/1\'000 Adlmps') == -1)
	//	calcVal = (calcVal * 100).toFixed(2);

	if(calcVal == "0.00") calcVal = "0";
	$("#Traffic-Kosten").parents('.calculationTable').parent('.calc-item').next().children('input').val(calcVal);

	calculateTotalCost();

	numberFormat();

}

(function() {

	function addNewKategorie(name) {
		var newKategorie = $('<span>').addClass('addOpt');
		$('<button class="del-kategorie">').appendTo(newKategorie).append($('<img>').attr('src',base_url + "/images/Close.svg"));//.on('click',delKategore);
		newKategorie.append(name);
		$('#params-list').append(newKategorie);
	}

	function delKategore(e) {
		e.preventDefault();
		$(this).parent().remove();
	}

	$('#individuelle-kategorie').on('click', function(e) {
		e.preventDefault();
		ctgName = $('input',$(this).prev()).val();
		categoryID = $('#categories').data('id');

		if (ctgName == ""){
			return;
		}

		addNewKategorie(ctgName);
		$("#modal3_indi_category").val("");
	});

	$('#modal2').on('click', '.del-kategorie', delKategore);
})();