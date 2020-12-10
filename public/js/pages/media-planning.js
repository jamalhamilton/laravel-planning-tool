var formats = [];
var max_char_limit = 200;

function isEmptyWerbedruck() {
	var empty = false;
	var len = $('.cpcSummary').length;
	$('.cpcSummary').each(function () {
		if($(this).hasClass('free-input')){
			var val = $(this).text();
			if(val === "0") empty = true;
		}

	})
	console.log("cpcSummary",len,empty);
}

function numberWithCommas(x) {
	var str = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "\'");
	if(str == "NaN")
		str = "";
     return str;//if you want use , then replace "\'"!
}

function calTotal() {
	var elems, total;
    var index = [5,7,9,11];
    elems = document.getElementsByClassName('subtotal');

    for (var i = 0; i < elems.length; i++) {
        var idx = index[i % 4];



        var cells = $(elems[i]).parents("table.bigTable1780").find(".contentCollap  td:nth-child("+idx+")");



        total = 0;
        for (var j = 0; j < cells.length; j++) {
			// if(i==0)console.log(total,j,cells[j].innerHTML,parseFloat($(cells[j]).parents("tr").attr('data-adimpressions')));

            if (idx == 5 && cells[j].innerHTML != "") {
            	if($(cells[j]).parents("tr").hasClass('hasCPC') && $(cells[j]).parents("table.tableCategory").attr('data-clickrate')){
            		total += parseFloat($(cells[j]).parents("tr").attr('data-adimpressions'));
				}else{
					total += parseInt(cells[j].innerHTML.replace("\'","").replace("\'","").replace("\'","").replace(",",""));
				}

            }
            else {
                total += parseFloat(cells[j].innerHTML.replace("\'","").replace("\'","").replace("\'","").replace(",",""));
            }

        }

        if (isNaN(total)) {
            if(idx == 5)
                $(elems[i]).text("0");
            else
                $(elems[i]).text("0.00");
        } else {
			if (idx == 5) {
				$(elems[i]).text(numberWithCommas(total.toFixed(2)));
			}
			else {
				$(elems[i]).text(numberWithCommas(total.toFixed(2)));
			}
        }

    }



    elems = document.getElementsByClassName('alltotal');
    
    for (var i = 0; i < elems.length; i++) {
    	var idx = index[i % 4];
        var cells = $(".totalDisplayFooter td.subtotal:nth-child("+idx+")");
        total = 0;
        
        for (var j = 0; j < cells.length; j++) {  	
            total += parseFloat(cells[j].innerText.replace("\'","").replace("\'","").replace("\'","").replace(",",""));
        };  	
    	if(idx == 5)
    		$(elems[i]).text(numberWithCommas(total));	
    	else
        	$(elems[i]).text(numberWithCommas(total.toFixed(2)));
    };  


    elems = document.getElementsByClassName('tkpGrossCHF');
    var FinaGrossTotal = 0;
    for (var i = 0; i < elems.length; i++) {
              var currentTable = $(".tableToAdd")[i];
              var tableRows = $(currentTable).children("tr");
              var totalValue = 0;
              for (var j = 0 ; j < tableRows.length; j++) {
                var TableData = $(tableRows[j]).children("td")[5];
                  totalValue += parseFloat($(TableData).text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));
              }
              
              $(elems[i]).text(totalValue.toFixed(2));
            FinaGrossTotal += totalValue;
    }


    $(elems[elems.length - 1]).text(FinaGrossTotal.toFixed(2));

    elems = document.getElementsByClassName('grossCHF');

	var FinalNetto = 0, FinalBrutto_InChf = 0;

    for (var i = 0; i < elems.length; i++) {
              var currentTable = $(".tableToAdd")[i];
              var tableRows = $(currentTable).children("tr");
			var netto_inchf = 0, brutto_inchf = 0;

              for (var j = 0 ; j < tableRows.length; j++) {
				  var TableData = $(tableRows[j]).children("td")[8];
				  var tmp = parseFloat($(TableData).text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));
				  if(!isNaN(tmp)) netto_inchf += parseFloat(tmp);

				  TableData = $(tableRows[j]).children("td")[6];
				  var tmp = parseFloat($(TableData).text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));
				  if(!isNaN(tmp)) brutto_inchf += tmp
              }

			if(netto_inchf == 0){
				$(elems[i]).text("0.00");
			}
			else {
				if(brutto_inchf == 0){
					$(elems[i]).text("0.00");
				}
				else {
					$(elems[i]).text((100 - (netto_inchf / (brutto_inchf / 100))).toFixed(2));
				}
				FinalNetto += netto_inchf;
				FinalBrutto_InChf += brutto_inchf;
			}
    }

	if(FinalNetto == 0){
		$(elems[elems.length - 1]).text("0.00");
	}
	else {
		if(FinalBrutto_InChf == 0){
			$(elems[elems.length - 1]).text("0.00");
		}
		else {
			$(elems[elems.length - 1]).text((100 - ( FinalNetto / (FinalBrutto_InChf / 100))).toFixed(2));
		}
	}


	elems = document.getElementsByClassName('bkPersentual');
	var FinalNetto = 0, FinalNN_InChf = 0;
    for (var i = 0; i < elems.length; i++) {
         var currentTable = $(".tableToAdd")[i];
        var tableRows = $(currentTable).children("tr");

		var netto_inchf = 0, nn_inchf = 0;

		for (var j = 0 ; j < tableRows.length; j++) {
                var TableData = $(tableRows[j]).children("td")[8];
                var tmp = parseFloat($(TableData).text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));
                if(!isNaN(tmp)) netto_inchf += tmp

				var tmp = parseFloat($(TableData).text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));
					TableData = $(tableRows[j]).children("td")[10];
				if(!isNaN(tmp)) nn_inchf += tmp;
              }
			if(netto_inchf == 0){
				$(elems[i]).text("0.00");
			}
			else {
					$(elems[i]).text((100 - (nn_inchf / (netto_inchf / 100))).toFixed(2));
					FinalNetto += netto_inchf;
					FinalNN_InChf += nn_inchf;
				}
    }
	if(FinalNetto == 0){
		$(elems[elems.length - 1]).text("0.00");
	}
	else {
		$(elems[elems.length - 1]).text((100 - (FinalNN_InChf / (FinalNetto / 100))).toFixed(2));
	}


     elems = document.getElementsByClassName('tkpNNCHF');
    FinaGrossTotal = 0;
    for (var i = 0; i < elems.length; i++) {
              var currentTable = $(".tableToAdd")[i];
              var tableRows = $(currentTable).children("tr");
              var totalValue = 0;
              for (var j = 0 ; j < tableRows.length; j++) {
                var TableData = $(tableRows[j]).children("td")[11];
                  totalValue += parseFloat($(TableData).text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));
              }

              $(elems[i]).text(totalValue.toFixed(2));
            FinaGrossTotal += totalValue;
    }

    $(elems[elems.length - 1]).text(FinaGrossTotal.toFixed(2));

    elems = document.getElementsByClassName('adpresstotal');
    var $regions = $(".voiceSplit table tbody tr");
    var cells = $("table.bigTable1780").find(".contentCollap  td:nth-child(5)");
    var sum = {};
    
    for (var j = 0; j < $regions.length - 1; j++) {
    	region = $($regions[j]).find('td:first').text();
    	sum[region] = 0;
    }
    var val = 0;
    for (var j = 0; j < cells.length; j++) {

    	region = $(cells[j]).prev().prev()[0].innerHTML;

        if($(cells[j]).parents('tr').hasClass('hasCPC')){
            val = $(cells[j]).parents('tr').data('adimpressions');
        }else{
            val = parseFloat($(cells[j])[0].innerHTML.replace("\'","").replace("\'","").replace("\'","").replace(",",""))
        }
        if(!isNaN(val)){
			sum[region] += val;
		}

    }

    current = 0;
    
    for (var j in sum) {
    	if(j != "") {
            current += sum[j];
        }
	}

    total = parseFloat($(".adpresssum").text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));

    for (var i = 0; i < elems.length; i++) {
    	region =$(elems[i]).prev()[0].innerHTML;

    	if (isNaN(sum[region])) {
    		$(elems[i]).text("0");
    	} else {
    		$(elems[i]).text(numberWithCommas(sum[region].toFixed(2)));
    	}
    };

    if (isNaN(current)) {
    	$(".adpressall").text("0");
    } else {
    	$(".adpressall").text(numberWithCommas(current.toFixed(2)));
    }

    elems = document.getElementsByClassName('adpresspercent');
    
    for (var i = 0; i < elems.length; i++) {
    	value = parseFloat($(elems[i]).prev()[0].innerHTML.replace("\'","").replace("\'","").replace("\'",""));
        
        if ( value == 0) {
        	$(elems[i]).text("0.00");
        } else {
        	if (isNaN(value) || isNaN(total))
        		$(elems[i]).text("0.00");  
        	else
    			$(elems[i]).text((value/total*100).toFixed(2));    
		}
    };

   	elems = document.getElementsByClassName('netCHFtotal');
    var cells = $("table.bigTable1780").find(".contentCollap  td:nth-child(11)");
    var sum = {};
    
    for (var j = 0; j < $regions.length - 1; j++) {
    	region = $($regions[j]).find('td:first').text();
    	sum[region] = 0;
    }
    
    for (var j = 0; j < cells.length; j++) {
    	region = $(cells[j]).prev().prev().prev().prev().prev().prev().prev().prev()[0].innerHTML;
    	sum[region] += parseFloat($(cells[j])[0].innerHTML.replace("\'","").replace("\'","").replace("\'","").replace(",",""));
    }

    current = 0;
    
    for (var j in sum) {
        if(j != "") {
            current += sum[j];
        }
    }

	total = parseFloat($(".nnsum").text().replace("\'","").replace("\'","").replace("\'","").replace(",","")); 

    for (var i = 0; i < elems.length; i++) {
    	region =$(elems[i]).prev().prev().prev()[0].innerHTML;
    	
    	if (isNaN(sum[region]))
    		$(elems[i]).text("0.00");
    	else
    		$(elems[i]).text(numberWithCommas(sum[region].toFixed(2)));    	   	
    };

    if (isNaN(current)) {
    	$(".netCHFall").text("0.00");
    } else {
    	$(".netCHFall").text(numberWithCommas(current.toFixed(2)));
    }


    elems = document.getElementsByClassName('netCHFpercent');
    
    for (var i = 0; i < elems.length; i++) {
    	value = parseFloat($(elems[i]).prev()[0].innerHTML.replace("\'","").replace("\'","").replace("\'",""));
    	if (isNaN(value) || total == 0) {
        	$(elems[i]).text("0.00");
        } else {
        	$(elems[i]).text((value / total * 100 + 0.0015).toFixed(2));  
		}

    }


	//ludct
	tkpCalculate();
	//end lucdt
};



var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
	    this.classList.toggle("active");
	    var content = this.nextElementSibling;
	    
	    if (content.style.maxHeight) {
	      content.style.maxHeight = null;
	    } else {
	      content.style.maxHeight = content.scrollHeight + "px";
	    } 

    });
}


function tkpCalculate(){

	$('.bigTable1780 .totalDisplayFooter .blankInputTd').each(function () {
		var aryTotal = new Array();
		$(this).find('td').each(function () {
			aryTotal.push($(this).text().replace("\'", "").replace("\'", "").replace("\'", ""));
		});
		// aryTotal[4] :  WERBEDRUCK
		if(aryTotal[4] != 0 ){
			aryTotal[5] = aryTotal[6]/aryTotal[4] * 1000;
			aryTotal[11] = aryTotal[10]/aryTotal[4] * 1000;
		}
		else{
			aryTotal[5] = 0;
			aryTotal[11] = 0;
		}

		$(this).find("td:nth-child(6)").html(aryTotal[5].toFixed(2));
		$(this).find("td:nth-child(12)").html(aryTotal[11].toFixed(2));

	});

	$('.tableTotalMedia  .blankInputTd').each(function () {
		var aryTotal = new Array();
		$(this).find('td').each(function () {
			aryTotal.push($(this).text().replace("\'", "").replace("\'", "").replace("\'", ""));
		});
		// console.log(aryTotal);

		if(aryTotal[4] != 0 ){
			aryTotal[5] = aryTotal[6]/aryTotal[4] * 1000;
			aryTotal[11] = aryTotal[10]/aryTotal[4] * 1000;

		}
		else{
			aryTotal[5] = 0;
			aryTotal[11] = 0;
		}
		$(this).find("td:nth-child(6)").html(aryTotal[5].toFixed(2));
		$(this).find("td:nth-child(12)").html(aryTotal[11].toFixed(2));
	});



}

$(document).ready(function() {
    $("body").click(function(event) {
        /*var className = ($(event.target).attr('class'));

        if (className != 'select-box')*/
        $('.mCSB_container .custom-select').hide();
        $('#form_add_category_modal .custom-select').show();
    });

	$(".select-selected").click(function () {
        if ($('.select-items').hasClass('select-hide'))
            $('.mCSB_container .custom-select').hide();
        $('#form_add_category_modal .custom-select').show();
    });
	
	$(".hasDatepicker").datepicker( "option", "firstDay", 1 );

	
	$('.mytable').dataTable({
		"bSearchable": false,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": false,
		"bAutoWidth": false,
		"aoColumnDefs": [
            { "sClass": "cpcSummary free-input", "aTargets": [ 4 ] },
            { "sClass": "disabled", "aTargets": [ 8 ] },
            { "sClass": "free-input", "aTargets": [ 0,1,7,9 ] },
			{ "sClass": "cpcFlag free-input", "aTargets": [ 5 ] },
            { "sClass": "select-box", "aTargets": [ 2 ] },
            { "sClass": "disabled", "aTargets": [ 6 ] },
			{ "sClass": "cpcFlag disabled", "aTargets": [ 11 ] },
			{ "sClass": "kostenCol disabled", "aTargets": [ 10 ] },
            { "sClass": "auto-complete", "aTargets": [ 3 ] }
        ],

		"fnCreatedRow": function ( row, data, index ) {
			$(row).addClass("blankInputTd");
			$(row).on('click', tableRowClick);
			$(row).on('dblclick', tableRowDblClick);
		},
        "bDestroy": true,
        "bRetrieve": true
	});

	calTotal();

	$("#Online").on('click','.btn-deletectg',function( e ) {
		selectedTab = $(this).parents('table');
	    
	    if ( selectedTab.hasClass('table_selected') ) {
	         selectedTab.removeClass('table_selected');
	    } else {
	        $('table.row_selected').removeClass('table_selected');
	        selectedTab.addClass('table_selected');
	    }

	    $('#deleteCategoryModal').modal();
	});

	$('.btn-deletectg', "#deleteCategoryModal",'.del-kategorie').on('click', function() {
	    categoryID = $('table.table_selected').data('id');
		campaignID = $('#categories').data('id');
       	
       	$.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/media/category/delete',
            data:{
            	'categoryID':categoryID,
            	'campaignID':campaignID,
				'active_channel':$("#active_channel").val(),
            },
            dataType: 'JSON',
            success: function(data,textStatus,jqXHR) {
            	calTotal();
			},
			error: function(jqXHR, textStatus, errorThrown) {

	      	},
        });
	    $('table.table_selected').remove();        
	    $("#deleteRowModal").hide();
	    $('.blocker').hide();
	    $('body').css('overflow','auto');
	} );

	$("#Online").on('click','.btn-edit',function( e ) {
		selectedTab = $(this).parents('table');

		$('table.table_selected').removeClass('table_selected');
		selectedTab.addClass('table_selected');

	    //categoryName = $(".table_selected button.collapsible").text();
        categoryName = $(this).parents('.collapsibleBar.margin-0').find('.collapsible').text();

	    isConstant = selectedTab.data('isconst');


	    var clickrate = selectedTab.data('clickrate');

	    $('#clickrate').val(clickrate);


	    $(".input-ctgname").val(categoryName);

	    if (isConstant == 1) {
	    	$(".input-ctgname").attr("disabled",true);
	    } else {
	    	$(".input-ctgname").removeAttr("disabled");
			$(".input__label--hoshi span").css('top','-12px');
	    }

	    $('#editModal').modal();
	});

	$("body").on('click','#btn_newCtg', function() {
		var categories = Array();
		var elems = document.getElementsByClassName("addOpt");
		
		for (var i = 0; i < elems.length; i++) {
			categories[i] = $(elems[i]).text();
		}

		$("#categories").val(categories);
		campaignID = $('#categories').data('id');

		$("#waitingIcon1").css("display", "inline-block");

       	$.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/media/category/add',
            data:{
            	'categories':categories,
            	'active_channel':$("#active_channel").val(),
            	'campaignID':campaignID
            },
            dataType: 'JSON',
            success: function(data,textStatus,jqXHR) {
				$("#waitingIcon").css("display", "none");
				$("#new_planning").submit();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$("#waitingIcon1").css("display", "none");

	      },
        });
	});

	$('.btn-editrow').on('click', function() {
		var categoryName=$('#editModal .input-ctgname').val();
		var checkboxInfo=($('#editModal .ckb-addinfo').is(":checked"));
		var addInfo=$('#editModal .textarea-addinfo').val();
		var clickrate=$('#clickrate').val();

		
		$('table.table_selected .collapsible').text(categoryName);
		categoryID = $('table.table_selected').data('id');

		if(checkboxInfo) {
			infoHtml='<div style="width:17%;position: relative;"><span class="note ic-triangle"><div class="note-data">'+addInfo+'</div></span></div>';
		}
       	$.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/media/category/edit',
            data:{
            	'categoryName':categoryName,
            	'checkboxInfo':checkboxInfo,
            	'addInfo':addInfo,
            	'categoryID':categoryID,
				'active_channel':$("#active_channel").val(),
				'campaignID': $("#campaign_id").val(),
				'clickrate': clickrate
            },
            dataType: 'JSON',
            success: function(data,textStatus,jqXHR) {

        		if(data.change_rate){
					$('table.table_selected').data('clickrate',data.clickrate);
					for(var i =0 ; i< data.cpc_items.length; i++){
						//console.log(i,data.cpc_items[i].ad_impressions);
						$('table.table_selected').find('tr[data-id="'+data.cpc_items[i].ID+'"]').attr('data-adimpressions',data.cpc_items[i].ad_impressions)
						$('table.table_selected').find('tr[data-id="'+data.cpc_items[i].ID+'"]').find('td:nth-child(7)').text(data.cpc_items[i].grossCHF.toFixed(2));
						$('table.table_selected').find('tr[data-id="'+data.cpc_items[i].ID+'"]').find('td:nth-child(5)').attr("title",numberWithCommas(data.cpc_items[i].ad_impressions.toFixed(2)));
					}
					calTotal();
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {

	      },
        });
		 $('table.table_selected .collapsible').before(infoHtml);
	    $("#editModal").hide();
	    $('.blocker').hide();
	    $('body').css('overflow','auto');
	} );
	
	var $edit_target = null;
	
	$('body').on('regionclick', '.select-items div', function() {
		if ($edit_target && $edit_target.hasClass('select-box')) {
			$('.media-table-region').hide();
			var $selectTag = $('select','.media-table-region');
			$edit_target.text($selectTag[0].options[$selectTag[0].selectedIndex].innerHTML);
			mediaID = $edit_target.parents('tr').data('id');
	       	colIndex = $edit_target[0].cellIndex;     	
	       	value = $edit_target.text();

			var clickrate = $edit_target.parents('table.tableCategory').data('clickrate');

	       	$.ajax({
	        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            url: '/media/col/edit',
	            data:{
	            	'mediaID':mediaID,
	            	'colIndex':colIndex,
	            	'value':value,
	            	'type':'select-search',
					'active_channel':$("#active_channel").val(),
					'campaignID':$("#campaign_id").val(),
					'clickrate':clickrate
	            },
	            dataType: 'JSON',
	            success: function(data,textStatus,jqXHR) {
					$edit_target.parents('tr').data('adimpressions',data.item.ad_impressions);
	            	calTotal();
					//alert(1);

				},
				error: function(jqXHR, textStatus, errorThrown) {

		      },
	        });
            $edit_target.removeClass("editing");
	   		$edit_target.next().trigger('dblclick');
		}		
  	});
   	$.ajax({
    	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/media/autocomplete',
        data:{
			'active_channel':$("#active_channel").val(),
        },
        dataType: 'JSON',
        success: function(data,textStatus,jqXHR) {
            formats = data.formats;
			$('.media-table-format').autocomplete({source: data.formats});
			$('.media-table-format').on('blur',function() {
				$(this).appendTo('body');
				$edit_target.removeClass('editing');
				$edit_target.html( $(this).val() );
				$('.media-table-format').css("display", "none");
			});
		},
		error: function(jqXHR, textStatus, errorThrown) {

      },
    });

	function tableRowClick(e) {
		e.preventDefault();
		if ( $edit_target && $edit_target[0] == $(e.target).parent()[0] ) return;
		$('td','tr.blankInputTd').removeClass('editing');
		//$edit_target = null;
		$(this).toggleClass('selected');
	}

	function tableRowDblClick(e) {

		if ( $edit_target && $edit_target[0] == $(e.target).parent()[0] ) {
			return;
		}

		$edit_target = $(e.target);	
		var $editor = null;

        colIndex = $edit_target[0].cellIndex;
		var clickrate = $edit_target.parents('table.tableCategory').data('clickrate');

        if(colIndex == 6){
        	var prev_val = $edit_target.prev().text();
			var prev_val_WERBEDRUCK = $edit_target.prev().prev().prev().text();
        	if(prev_val == "0" || prev_val == "0.00" || prev_val == ""){
                $edit_target.attr("class", "free-input editing");
			}
			else{
				if(prev_val_WERBEDRUCK == "0" || prev_val == "0.00" || prev_val_WERBEDRUCK == ""){
					$edit_target.attr("class", "free-input editing");
				}
				else {
					$edit_target.attr("class", "disabled");
				}
			}
		}

		if ($edit_target.hasClass('free-input')) {

			if(colIndex ==0 || colIndex ==1  || colIndex ==3){
                  $editor=$('<textarea>').val($(e.target).html().replaceAll("<br>", "\n"));
            }
			else{
				$editor=$('<input>').val($(e.target).text().replace(/\'/g,""));
			}
			$(e.target).html($editor);
			$edit_target.attr("style","");
            $($editor).select();

			$editor.on('blur',function() {

				var isValidNum = true;

                $edit_target = $(this).parent();
               // colIndex = $edittarget[0].cellIndex;

				$edit_target.removeClass('editing');

                $('.contentCollap').css('max-height', '100%');
				var value= $(this).val();
				if(colIndex ==0 || colIndex ==1 || colIndex ==3 ){
					value = value.replaceAll("\n","<br>");
				}

				$edit_target.html( value );
				campaignID = $('#categories').data('id');
				mediaID = $edit_target.parents('tr').data('id');

		       	colIndex = $edit_target[0].cellIndex;
		       	var cur_value = value.replace(/\'/g,"");


		       	if (colIndex > 2) {
		       		if (!($.isNumeric(cur_value))) {
						if(cur_value != "") {
							$edit_target.attr("style", "box-shadow: inset 0px 0px 0px 2px rgb(255,82,101) !important;");
							isValidNum = false;
						}
		       		}else{
                        if (colIndex == 4) {
                            $edit_target.html(numberWithCommas(parseInt(cur_value)));
                        }
		       			else {
                            $edit_target.html(numberWithCommas(parseFloat(cur_value).toFixed(2)));
		       		    }
                    }
		       	}
				if(colIndex == 6){
					var $tkp_ele = $($edit_target[0]).prev()[0];
					var werberdruk = $($($edit_target[0]).prev().prev()[0]).text();
					werberdruk = werberdruk.replaceAll("\'","").replaceAll(",","");

					var calc_val = (cur_value / parseInt(werberdruk)) * 1000;
					$($tkp_ele).html(numberWithCommas(calc_val.toFixed(2)));
				}

				calTotal();
				var adpresssum = parseFloat($(".adpresssum").text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));
				var nnsum  = parseFloat($(".nnsum").text().replace("\'","").replace("\'","").replace("\'","").replace(",",""));

		       	if (isValidNum) {
			       	$.ajax({
			        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: '/media/col/edit',
			            data:{
			            	'campaignID':campaignID,
			            	'mediaID':mediaID,
			            	'colIndex':colIndex,
			            	'value': cur_value,
							'adpresssum' : adpresssum,
							'nnsum' : nnsum,
							'active_channel':$("#active_channel").val(),
			            	'type':"free-input",
							'clickrate':clickrate
			            },
			            dataType: 'JSON',
			            success: function(data,textStatus,jqXHR) {

							$edit_target.parents('tr').data('adimpressions',data.item.ad_impressions);
							console.log(data.mediaData);
			            	if(colIndex>1){
			            		// if (typeof data.mediaData.adPressureValue !== 'undefined')
			            			$(e.target).parent().find("td:nth-child(5)").html(numberWithCommas(parseInt(data.mediaData.adPressureValue)));
			            		// if (typeof data.mediaData.grossCHF !== 'undefined')
				            		$(e.target).parent().find("td:nth-child(7)").html(numberWithCommas(parseFloat(data.mediaData.grossCHF).toFixed(2)));
				            	// if (typeof data.mediaData.netCHF !== 'undefined')
				            		$(e.target).parent().find("td:nth-child(9)").html(numberWithCommas(parseFloat(data.mediaData.netCHF).toFixed(2)));
				            	// if (typeof data.mediaData.nnCHF !== 'undefined')
				            		$(e.target).parent().find("td:nth-child(11)").html(numberWithCommas(parseFloat(data.mediaData.nnCHF).toFixed(2)));
				            	// if (typeof data.mediaData.tkpNNCHF !== 'undefined')
				            		$(e.target).parent().find("td:nth-child(12)").html(numberWithCommas(parseFloat(data.mediaData.tkpNNCHF).toFixed(2)));


                                $(e.target).parent().find("td:nth-child(7)").attr("class","disabled");
							}

							calTotal();


						},
						error: function(jqXHR, textStatus, errorThrown) {

				      	}
			        });
				}

			});

            $editor.on('keyup',function(event){
                $edittarget = $(this).parent();
                colIndex = $edittarget[0].cellIndex;
                var val = $(this).val();

                if(colIndex == 0 || colIndex == 1 || colIndex == 3) {
                    if (val.length > max_char_limit) {
                        val = val.substr(0, max_char_limit);
                        //alert(value);
                        $(this).val(val);
                    }
                }

            });

			$editor.on('keydown',function(event){
                $edittarget = $(this).parent();
                colIndex = $edittarget[0].cellIndex;

				if(colIndex == 0 || colIndex == 1 || colIndex == 3) {
                    var val = $(this).val();
                    if (val.length > max_char_limit) {
                        event.preventDefault();
                        return;
                    }
                }

			 	if ( event.which == 9 ) {
			   		event.preventDefault();
			   		$editor.trigger('blur');
					//$edit_target.trigger('blur');

					if ($edit_target.next().hasClass('disabled')) {
						$edit_target.next().next().trigger('dblclick');
					} else {
						$edit_target.next().addClass('editing');
						$edit_target.next().trigger('dblclick');
					}
			  	}
			});


			$editor.show().focus();
		}
		else if ($edit_target.hasClass('select-box')) {
			$editor = $('.media-table-region');
			// var oldvalue = $edit_target.html();
			
			var tblTop = $edit_target.parents('table.bigTable1780').position().top +45;
			var tblIndex = $edit_target.parents('table.bigTable1780').index();

			if (tblIndex == 1) {
				tblTop += 58;
			}

			$editor.css('left',$edit_target.position().left + 3).css('top',$edit_target.position().top + tblTop).css('position','absolute').css('width','180px').show(300);
			$editor.show().children('.select-items').removeClass('select-hide');



		} else if ($edit_target.hasClass('auto-complete')) {
            $editor = $('.media-table-format');
			$editor.val($edit_target.html().replaceAll("<br>", "\n"));

			$edit_target.html($editor);
            $($editor).select();
            $editor.on('keyup',function(event){
                $edittarget = $(this).parent();
                colIndex = $edittarget[0].cellIndex;
                var val = $(this).val();


                if( colIndex == 3) {
                    if (val.length > max_char_limit) {
                        val = val.substr(0, max_char_limit);
                        //alert(value);
                        $(this).val(val);
                    }
                }

            });

			$editor.on('keydown',function(event){

                $edittarget = $(this).parent();
                colIndex = $edittarget[0].cellIndex;

                if( colIndex == 3) {
                    var val = $(this).val();
                    if (val.length > max_char_limit) {
                        event.preventDefault();
                        return;
                    }
                }

			 	if ( event.which == 9 ) {
			 		event.preventDefault();
			   		$edit_target.removeClass('editing');
					$edit_target.html( $(this).val() );
					mediaID = $edit_target.parents('tr').data('id');
			       	colIndex = $edit_target[0].cellIndex;
			       	value = $(this).val();

			       	$.ajax({
			        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: '/media/col/edit',
			            data:{
			            	'mediaID':mediaID,
			            	'colIndex':colIndex,
			            	'value':value,
			            	'type':"auto-complete",
							'active_channel':$("#active_channel").val(),
							'campaignID':$("#campaign_id").val()
			            },
			            dataType: 'JSON',
			            success: function(data,textStatus,jqXHR) {
			        		//alert('3')
						},
						error: function(jqXHR, textStatus, errorThrown) {

				      },
			        }); 
			   		
                    $editor.appendTo('body');
                    $editor.autocomplete({source: formats});
                    $edit_target.removeClass('editing'); 
                    $edit_target.html( $editor.val() );
                    $edit_target.next().addClass('editing');
                    $edit_target.next().trigger('dblclick');
			  	}
			});

			$editor.on('blur',function() { 
				
				$edit_target.removeClass('editing'); 
				$edit_target.html( $(this).val().replaceAll("\n", "<br>") );
				mediaID = $edit_target.parents('tr').data('id');
		       	colIndex = $edit_target[0].cellIndex;
		       	value = $(this).val().replaceAll("\n","<br>");

		       	$.ajax({
		        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            url: '/media/col/edit',
		            data:{
		            	'mediaID':mediaID,
		            	'colIndex':colIndex,
		            	'value':value,
		            	'type':"auto-complete",
						'active_channel':$("#active_channel").val(),
						'campaignID':$("#campaign_id").val()
		            },
		            dataType: 'JSON',
		            success: function(data,textStatus,jqXHR) {
		        		//alert(4);
					},
					error: function(jqXHR, textStatus, errorThrown) {

			      },
		        }); 
			});
            
			$editor.show().focus();
		}
		if ($edit_target.hasClass('free-input') || $edit_target.hasClass('auto-complete'))
		{
			$(e.target).addClass('editing');
		}

	}
	

	var categoryName;
	$('#form_add_category_modal').on('click', '#btn_add_category', function() {
		$('#modal3').hide();
		$('.blocker').hide();
		$('body').css("overflow","auto");

		categoryName = $('#form_add_category_modal span.addOpt:last').text();
		var categories = Array();
		var elems = document.getElementsByClassName("addOpt");
		
		for (var i = 0; i < elems.length; i++) {
			categories[i] = $(elems[i]).text();
		}

		$("#categories").val(categories);
		campaignID = $('#categories').data('id');

       	$.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/media/category/add',
            data:{
            	'categories':categories,
				'active_channel':$("#active_channel").val(),
            	'campaignID':campaignID
            },
            dataType: 'JSON',
            success: function(data,textStatus,jqXHR) {
            	for (i = 0; i < data.ctgID.length; i++) {
					var newTableElem = 
						$('<table class="table bigTable1780" data-id="'+data.ctgID[i]+'" data-isconst="'+data.isConstant[i]+'">').append(
							$('<tbody>').append( 
								$('<tr>').append(
									$('<td colspan="12" class="pdding-0">').append(
										$('<div class="collapsibleBar">').append(
                                            '<ul class="rightOption">'+
                                                '<li class="subOpt"><button href="#" class="ic-categories"></button>'+
                                                    '<ul class="optionBtnGroup">'+
                                                        '<li><button type="button" class="btn-edit"><img src="'+base_url+'/images/icon_edit.svg"></button></li>'+
                                                        '<li  style="display:none;"><button type="button"><img src="'+base_url+'/images/icon_grun_duplicate.svg"></button></li>'+
                                                        '<li><button type="button" class="btn-deletectg"><img src="'+base_url+'/images/icon_grun_delete.svg"></button></li>'+
                                                    '</ul>'+
                                                '</li>'+
                                            '</ul>'+
											'<button class="collapsible">' + data.ctgName[i] + ' </button>').append(
											$('<div class="contentCollap" style="max-height: 52px;">').append(
												$('<table class="table tableDisplay mytable newtable">').append(
													'<thead>' +
														'<tr>' +
															'<th style="width:300px"></th>' +
															'<th style="width:300px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:210px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:120px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:70px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:70px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:115px"></th>' +
														'</tr>' +
													'</thead>' +
													'<tbody>' +
														'<tr class="blankInputTd" data-id="'+data.newMediaID[i]+'">'+
														    '<td data-type="free-input" ></td>'+
														    '<td data-type="free-input" ></td>'+
														    '<td data-type="select-search" ></td>'+
														    '<td data-type="auto-complete" ></td>'+
															'<td data-type="free-input" ></td>'+
														    '<td data-type="free-input"></td>'+
														    '<td data-type="free-input" ></td>'+
														    '<td class="" data-type="free-input" ></td>'+
														    '<td data-type="free-input" ></td>'+
														    '<td data-type="free-input" ></td>'+
														    '<td data-type="free-input" ></td>'+
														    '<td class="" data-type="free-input" ></td>'+
														'</tr>'+
													'</tbody>')
												)
											).append(
												$('<table class="table tableDisplay tableDisplayField totalDisplayFooter">').append(
													'<thead>' +
														'<tr>' +
															'<th style="width:300px"></th>' +
															'<th style="width:300px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:210px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:120px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:70px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:70px"></th>' +
															'<th style="width:115px"></th>' +
															'<th style="width:115px"></th>' +
														'</tr>' +
													'</thead>' +
													'<tbody>' +
														'<tr class="blankInputTd ">' +
															'<td><strong>Total '+ data.ctgName[i] + '</strong></td>' +
															'<td></td>' +
															'<td></td>' +
															'<td></td>' +
															'<td class="subtotal"></td>' +
															'<td class="tkpGrossCHF"></td>' +
															'<td class="subtotal"></td>' +
															'<td class="grossCHF"></td>' +
															'<td class="subtotal"></td>' +
															'<td class="bkPersentual"></td>' +
															'<td class="subtotal"></td>' +
															'<td class="tkpNNCHF"></td>' +
														'</tr>' +
													'</tbody>')
											)
										)
									)
								)
							);

					$('#blanktable').before(newTableElem);
					calTotal();

					$('.collapsible',$(newTableElem)).on("click", function() {
					    this.classList.toggle("active");
					    var content = this.nextElementSibling;
					    
					    if (content.style.maxHeight) {
					      content.style.maxHeight = null;
					    } else {
					      content.style.maxHeight = content.scrollHeight + "px";
					    } 

					});
					
					$('.mytable',$(newTableElem)).on( 'contextmenu', showContextMenu);
					$('.newtable').dataTable({
						"bSearchable": false,
						"bPaginate": false,
						"bLengthChange": false,
						"bFilter": false,
						"bSort": false,
						"bInfo": false,
						"bAutoWidth": false,
						"aoColumnDefs": [
				            { "sClass": "free-input", "aTargets": [ 0,1,4,5,7,9 ] },
				            { "sClass": "select-box", "aTargets": [ 2 ] },
				            { "sClass": "disabled", "aTargets": [ 6,8,10,11 ] },				            
				            { "sClass": "auto-complete", "aTargets": [ 3 ] }
				        ],

						"fnCreatedRow": function ( row, data, index ) {
							$(row).addClass("blankInputTd");
							$(row).on('click', tableRowClick);
							$(row).on('dblclick', tableRowDblClick);
							},
                        "bDestroy": true,
                        "bRetrieve": true
					});
				}

				for (i = 0; i < data.deletedID.length; i++) {
					$("table.bigTable1780[data-id="+data.deletedID[i]+"]").remove();
				}

				$('#menu_insert_line').trigger('click');

				},
			error: function(jqXHR, textStatus, errorThrown) {

	      },
        });
	});

//context menu 
var contextDataTable = null;
var contextDataRow = null;
var contextDataCol = null;
var categoryTable = null;	
$(document).on('click',function() {$('.media-table-menu').hide();});

function showContextMenu(e) {
	e.preventDefault();
	contextDataCol = e.target;
	contextDataRow = $(e.target).parents('tr')[0];
	contextDataTable = $(e.target).parents('table')[0];
	categoryTable = $(e.target).parents('.bigTable1780');
	var selCount = $('tr.selected',contextDataTable).length;

	if (selCount > 1)
		$('#menu_delete_line','.media-table-menu').text("Zeilen löschen");
	else
		$('#menu_delete_line','.media-table-menu').text("Zeile löschen");

	$('.media-table-menu').hide();
	$('.media-table-menu').css('left',e.originalEvent.pageX).css('top',e.originalEvent.pageY).show(300);
}

$('.contentCollap .mytable').on( 'contextmenu', showContextMenu);

$('#menu_insert_line').on('click', function() {
	if (contextDataRow === null || contextDataTable === null || $(contextDataTable).hasClass('totalDisplayFooter')) {
		return;
	}

	var table = $(contextDataTable).DataTable();
	var newrow = ["","","","","","","","","","","","" ];
	var index = table.row( contextDataRow ).index();

	table.row.addByPos(newrow,index + 2,contextDataTable);

	var collapseDiv  =$(contextDataTable).parent().parent()[0];
	collapseDiv.style.maxHeight = collapseDiv.scrollHeight + "px";
    categoryID = categoryTable.data('id');
    var clickrate = $(contextDataTable).parents('table.tableCategory').data('clickrate');

   	$.ajax({
    	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/media/line/insert',
        data:{
        	'categoryID':categoryID,
			'active_channel':$("#active_channel").val(),
			'campaignID':$("#campaign_id").val()
        },
        dataType: 'JSON',
        success: function(data,textStatus,jqXHR) {

			$(contextDataTable).find("tr:nth-child("+(index+2)+")").attr('data-id',data.id);
			$(contextDataTable).find("tr:nth-child("+(index+2)+")").attr('data-adimpressions',0);

			reorderTable(contextDataTable);

		},

		error: function(jqXHR, textStatus, errorThrown) {

      	},
    });
});

$('#menu_duplicate_line').on('click', function() {

		if (contextDataRow === null || contextDataTable === null || $(contextDataTable).hasClass('totalDisplayFooter')) {
			return;
		}

		var table = $(contextDataTable).DataTable();
		/*var newrow = table.row( contextDataRow ).data();*/
		var newrow1 = [];
		var index = table.row( contextDataRow ).index();
		for(var i = 0 ; i < $(contextDataRow).find("td").length ; i ++){
			newrow1.push($($(contextDataRow).find("td")[i]).html())
		}

		table.row.addByPos(newrow1,index + 2,contextDataTable);

		var collapseDiv  =$(contextDataTable).parent().parent()[0];
		collapseDiv.style.maxHeight = collapseDiv.scrollHeight + "px";
		categoryID = categoryTable.data('id');

		var cpc = $(contextDataRow).hasClass('hasCPC')?1:0;
		var clickrate = $(contextDataTable).parents('table.tableCategory').data('clickrate');

		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			url: '/media/line/insert',
			data:{
				'categoryID':categoryID,
				'active_channel':$("#active_channel").val(),
				'campaignID':$("#campaign_id").val(),
				'data':newrow1,
				'cpc':cpc,
				'clickrate':clickrate
			},
			dataType: 'JSON',
			success: function(data,textStatus,jqXHR) {

				$(contextDataTable).find("tr:nth-child("+(index+2)+")").attr('data-id',data.id);

				if(data.media.is_cpc){
					$(contextDataTable).find("tr:nth-child("+(index+2)+")").addClass('hasCPC');
					$(contextDataTable).find("tr:nth-child("+(index+2)+")").attr('data-adimpressions',data.media.ad_impressions.toFixed(2));
					if(data.media.ad_impressions){
                        $(contextDataTable).find("tr:nth-child("+(index+2)+")").find('.cpcSummary').attr('title',numberWithCommas(data.media.ad_impressions.toFixed(2)));
                    }else{
                        $(contextDataTable).find("tr:nth-child("+(index+2)+")").find('.cpcSummary').removeAttr('title');
                    }

					calTotal();
				}

				reorderTable(contextDataTable);

			},

			error: function(jqXHR, textStatus, errorThrown) {

			},
		});

	});

$('#menu_delete_line').on('click', function() {
	if (contextDataRow === null || contextDataTable === null || $(contextDataTable).hasClass('totalDisplayFooter')) {
	    return;
	}

	$("#deleteRowModal").modal();
});


function reorderTable(contextDataTable){
	//reoder /media/reorder
	var num_row = $(contextDataTable).find("tr").length;
	var ids = [];
	var first_id = $(contextDataTable).attr("data-first");
	// console.log("first_id",first_id);
	ids.push(first_id);
	for(var i = 2; i < num_row; i++){
		var tmp = $(contextDataTable).find("tr:nth-child("+(i)+")").attr('data-id');
		ids.push(tmp);
	}

	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'POST',
		url: '/media/reorder',
		data:{
			'ids':ids
		},
		dataType: 'JSON',
		success: function(data,textStatus,jqXHR) {
			console.log(data);
		},
		error: function(jqXHR, textStatus, errorThrown) {

		},
	});
}

//$('button.btn', "#deleteRowModal").on('click',function(e) {

$('button.btn', "#deleteRowModal").on('click',function(e) {
	var selectedRows = $('tr.selected',contextDataTable);

	if (!selectedRows.length) {
		alert("Please select line(s) to delete.");
		return;
	}
	
	for(i = selectedRows.length - 1; i >= 0; i--){
		$(contextDataTable).dataTable().fnDeleteRow(selectedRows[i]);
	}
   	
   	selectedID = new Array();

   	for (i = 0; i < selectedRows.length; i++) {
   		selectedID[i] = $(selectedRows[i]).data('id');
   	}

   	$.ajax({
    	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/media/line/delete',
        data:{
        	'selectedID':selectedID,
			'active_channel':$("#active_channel").val(),
			'campaignID':$("#campaign_id").val()
        },
        dataType: 'JSON',
        success: function(data,textStatus,jqXHR) {
			calTotal();
		},
		error: function(jqXHR, textStatus, errorThrown) {

      },
    }); 
});

$('body').on('click',"#addctgmodal", function() {
	campaignID = $('#categories').data('id');
   	$.ajax({
    	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/media/category/list',
        data:{
        	'campaignID':campaignID,
			'active_channel':$("#active_channel").val()
        },
        dataType: 'JSON',
        success: function(data,textStatus,jqXHR) {
        	html = "";
        	
        	for (var i in data) {
            	html += '<span class="addOpt" data-id="'+i+'">'+
                            '<button class="del-kategorie"><img src="'+base_url+'/images/Close.svg"></button>'+data[i]+
                        '</span>';
                $("#form_add_category_modal .select-items div").each(function(){
                    var innerText = $(this).text();

                    if(innerText == data[i]){
                        $(this).css("display","none");
                    }
                });
        	}

        	$("#kategorie-list").html(html);



        	$('#modal3').modal();
		},

		error: function(jqXHR, textStatus, errorThrown) {

      	},
    });
});

	$('#menu_insert_cpc').on('click', function() {
		if (contextDataCol === null || contextDataRow === null || contextDataTable === null) {
			return;
		}
		var mediaID = $(contextDataRow).data('id');
		var clickrate = $(contextDataTable).parents('table.tableCategory').data('clickrate');

		if(!clickrate) return;

		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			url: '/media/cpc/insert',
			data:{
				'mediaID':mediaID,
				'active_channel':$("#active_channel").val(),
				'campaignID':$("#campaign_id").val(),
				'clickrate':clickrate
			},
			dataType: 'JSON',
			success: function(data,textStatus,jqXHR) {
				var table = $(contextDataTable).DataTable();
				var index = table.row( contextDataRow ).index();

				if(data.media){

					$(contextDataTable).find("tr:nth-child("+(index+1)+")").attr('data-adimpressions',data.media.ad_impressions.toFixed(2));

					$(contextDataTable).find("tr:nth-child("+(index+1)+")").find('td:nth-child(7)').text(data.media.grossCHF.toFixed(2));
					if(data.media.ad_impressions){
                        $(contextDataTable).find("tr:nth-child("+(index+1)+")").find('.cpcSummary').attr('title',numberWithCommas(data.media.ad_impressions.toFixed(2)));
                    }else{
                        $(contextDataTable).find("tr:nth-child("+(index+1)+")").find('.cpcSummary').removeAttr('title');
                    }

				}

				$(contextDataTable).find("tr:nth-child("+(index+1)+")").toggleClass('hasCPC');
				calTotal();
			},
			error: function(jqXHR, textStatus, errorThrown) {

			},
		});
	});


$('#menu_insert_note').on('click', function() {
	if (contextDataCol === null || contextDataRow === null || contextDataTable === null) {
		return;
	}

	var rowIndex = $(contextDataTable).dataTable()._fnNodeToDataIndex(contextDataRow);
	var colIndex = $(contextDataTable).dataTable()._fnNodeToColumnIndex(rowIndex, contextDataCol);
	var rect = contextDataCol.getBoundingClientRect();
    rect.top += 18;

	var oSettings = $(contextDataTable).dataTable().fnSettings();
	$(contextDataCol).addClass('cell-note');
	
	if ( typeof oSettings.aoData[rowIndex]["_aNote"]==="undefined") {
		oSettings.aoData[rowIndex]["_aNote"]=[];
	}

	categoryID = $(contextDataCol).parents('table.bigTable1780').data('id');
	mediaID = $(contextDataCol).parents('tr').data('id');

    //$(contextDataCol).parents('table').append($('.note-bubble'));

	showNoteEditor({left:contextDataCol.offsetLeft+contextDataCol.offsetWidth, top:contextDataCol.offsetTop},
			oSettings.aoData[rowIndex]["_aNote"][colIndex], 
			function(action, value) {
				if (action === true) {


					oSettings.aoData[rowIndex]["_aNote"][colIndex]=value;
					$(contextDataCol).data('note',value);
					$(contextDataTable).dataTable()._fnSetCellData(rowIndex, colIndex, value);
			       	
			       	$.ajax({
			        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: '/media/note/insert',
			            data:{
			            	'categoryID':categoryID,
			            	'mediaID':mediaID,
			            	'value':value,
			            	'colOrder':colIndex + 1,
							'active_channel':$("#active_channel").val(),
							'campaignID':$("#campaign_id").val()
			            },
			            dataType: 'JSON',
			            success: function(data,textStatus,jqXHR) {
						},
						error: function(jqXHR, textStatus, errorThrown) {

				      },
			        });
				} else {
					$(contextDataTable).dataTable()._fnSetCellData(rowIndex, colIndex, null);
					oSettings.aoData[rowIndex]["_aNote"][colIndex]="";
				}
	});
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

(function() {
	function addNewKategorie(name) {
		var newKategorie = $('<span>').addClass('addOpt');
		$('<button class="del-kategorie">').appendTo(newKategorie).append($('<img>').attr('src',base_url + "/images/Close.svg"));//.on('click',delKategore);
		newKategorie.append(name);
		$('#kategorie-list').append(newKategorie);
	}

	function delKategore(e) {
		e.preventDefault();

		var html = $(this).parent().html();
		var pos = html.indexOf("</button>") + 9;

		var text = html.substr(pos , html.length - 1);

		$("#form_add_category_modal .select-items div").each(function(){
			var innerText = $(this).text();

			if(innerText == text){
				$(this).css("display","block");
			}
		});

		$(this).parent().remove();
	}

	$("#constant-kategorie").on("change", function() {
        $("#form_add_category_modal .same-as-selected").css("display","none");
		addNewKategorie(this.options[this.selectedIndex].innerHTML);
	});

	$('#modal4').on('click','#duplicate_category', function(e) {
		e.preventDefault();
		campaignName = $(".select-selected","#modal4").text();
		campaignChannel = $('#campaign_data').data('campaign-id');
	   	$.ajax({
        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/media/category/duplicate',
            data:{
            	'campaignName':campaignName,
            	'campaignChannel':campaignChannel,
				'active_channel': $("#active_channel").val()
            },
            dataType: 'JSON',
            success: function(data,textStatus,jqXHR) {
            	$("#mediaForm").submit();
			},
			error: function(jqXHR, textStatus, errorThrown) {

	      },
        });

	});	

	$('#individuelle-kategorie').on('click', function(e) {
		e.preventDefault();
		ctgName = $('input',$(this).prev()).val();
		categoryID = $('#categories').data('id');
		
		if (ctgName == ""){
			return;
		}

		addNewKategorie(ctgName);  
	});

	$('#form_add_category_modal').on('click', '.del-kategorie', delKategore);
})();


//Note
var actionCallback = null;

function showNoteEditor(position, value, callback) {
	if ( $('.note-bubble').css('display') === 'none') {

		actionCallback = callback;
		$('textarea', '.note-bubble').val(value);
		if(position.left > 1600){
            $('.note-bubble').css('left',position.left - 330).css('top',position.top - $('.note-bubble').height() / 2 + 23).show(300);

            $('.note-bubble').css({'-webkit-transform':'rotateY(180deg)','transform':'rotateY(180deg)'});
            $('.note-bubble textarea').css({'-webkit-transform':'rotateY(180deg)','transform':'rotateY(180deg)'});
		}
		else{


            $('.note-bubble').css('left',position.left).css('top',position.top - $('.note-bubble').height() / 2 + 23).show(300);
            $('.note-bubble').css({'-webkit-transform':'rotateY(0)','transform':'rotateY(0)'});
            $('.note-bubble textarea').css({'-webkit-transform':'rotateY(0)','transform':'rotateY(0)'});


        }
	}
}

$('.btn-note-close', '.note-bubble').on('click', function(e) {
	actionCallback(true, $('textarea', '.note-bubble').val());
	$('.note-bubble').hide(300);
});

$('.btn-note-delete', '.note-bubble').on('click', function(e) {
	actionCallback(false);
	$('.note-bubble').siblings('span').remove();
	$('.note-bubble').hide(300);
});

$("#Online").on('mouseover','.note', function(e) {
	var note_target = e.target;
	var offset = $(note_target).position();
	var pos = offset.left+25;

	if(!$( ".note-bubble" ).is( ":hidden" )) {
        //do something with this
		//$(this).parent().remove($('.note-bubble'));
		$( ".note-bubble" ).hide();
	}
    $(this).parent().append($('.note-bubble'));
	showNoteEditor({left:pos, top:0}, $('.note-data', this).text(), function(action, value) {
		categoryID = $(e.target).parents(".bigTable1780").data('id');
		if (action === true) {
			$('.note-data', note_target).text( value );
	       	$.ajax({
	        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            url: '/media/ctnote/insert',
	            data:{
	            	'categoryID':categoryID,
	            	'value':value,
					'active_channel':$("#active_channel").val(),
					'campaignID':$("#campaign_id").val()
	            },
	            dataType: 'JSON',
	            success: function(data,textStatus,jqXHR) {
				},
				error: function(jqXHR, textStatus, errorThrown) {

		      },
	        });
		}
		else {
			$('.note-data', note_target).text("");
	       	$.ajax({
	        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            url: '/media/ctnote/delete',
	            data:{
	            	'categoryID':categoryID,
					'active_channel':$("#active_channel").val(),
					'campaignID':$("#campaign_id").val()
	            },
	            dataType: 'JSON',
	            success: function(data,textStatus,jqXHR) {
				},
				error: function(jqXHR, textStatus, errorThrown) {

		      },
	        });
		}
	});
});


$("#Online").on('focus','.note', function(e) {

	var note_target = e.target;
	var offset = $(note_target).position();
	var pos = offset.left+25;
    $(this).parent().append($('.note-bubble'));
	showNoteEditor({left:pos, top:0}, $('.note-data', this).text(), function(action, value) {
		categoryID = $(e.target).parents(".bigTable1780").data('id');
		if (action === true) {
			$('.note-data', note_target).text( value );
	       	$.ajax({
	        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            url: '/media/ctnote/insert',
	            data:{
	            	'categoryID':categoryID,
					'active_channel':$("#active_channel").val(),
	            	'value':value,
	            },
	            dataType: 'JSON',
	            success: function(data,textStatus,jqXHR) {
				},
				error: function(jqXHR, textStatus, errorThrown) {

		      },
	        });
		}
		else {
			$('.note-data', note_target).text("");
	       	$.ajax({
	        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            url: '/media/ctnote/delete',
	            data:{
	            	'categoryID':categoryID,
					'active_channel':$("#active_channel").val(),
	            },
	            dataType: 'JSON',
	            success: function(data,textStatus,jqXHR) {
				},
				error: function(jqXHR, textStatus, errorThrown) {

		      },
	        });			
		}
	});
});


$('#Online ').on('mousemove','.mytable tr>td',function(e) {
	if (! $(this).hasClass('cell-note') ) {
	    return;
	}
	
	var rect = this.getBoundingClientRect();
	 if($('div.note-bubble', this).length > 0) {
        //do something with this
		$(this).parent().remove($('.note-bubble'));
	}
	$(this).parents('.mytable').append($('.note-bubble'));
	note_data = $(this).data('note');
	
	if (rect.right-e.originalEvent.clientX >= 0 && rect.right-e.originalEvent.clientX < 20
		&& e.originalEvent.clientY-rect.top >= 0 && e.originalEvent.clientY-rect.top < 20) {
		e.preventDefault();
		showNoteEditor({left:this.offsetLeft+this.offsetWidth, top:this.offsetTop}, note_data,
				function(action, value) {
					if (action === true) {
						$(e.target).data('note',value) ;
						categoryID = $(e.target).parents('table.bigTable1780').data('id');
						mediaID = $(e.target).parents('tr').data('id');
				       	colIndex = $(e.target)[0].cellIndex;
				       	
				       	$.ajax({
				        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				            type: 'POST',
				            url: '/media/note/insert',
				            data:{
				            	'categoryID':categoryID,
				            	'mediaID':mediaID,
				            	'value':value,
				            	'colOrder':colIndex + 1,
								'active_channel':$("#active_channel").val(),
								'campaignID':$("#campaign_id").val()
				            },
				            dataType: 'JSON',
				            success: function(data,textStatus,jqXHR) {
							},
							error: function(jqXHR, textStatus, errorThrown) {

					      },
				        });
					} else {
						$(e.target).removeClass('cell-note');
						$(e.target).removeAttr('data-note');
						categoryID = $(e.target).parents('table.bigTable1780').data('id');
						mediaID = $(e.target).parents('tr').data('id');
				       	colIndex = $(e.target)[0].cellIndex;
				       	$.ajax({
				        	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				            type: 'POST',
				            url: '/media/note/delete',
				            data:{
				            	'categoryID':categoryID,
				            	'mediaID':mediaID,
				            	'colOrder':colIndex + 1,
								'active_channel':$("#active_channel").val(),
								'campaignID':$("#campaign_id").val()
				            },
				            dataType: 'JSON',
				            success: function(data,textStatus,jqXHR) {
							},
							error: function(jqXHR, textStatus, errorThrown) {

					      },
				        });

					}
				});
	}

});

$(window).resize(function () {
	if(!$( ".note-bubble" ).is( ":hidden" )) {
		$( ".note-bubble" ).hide();
	}
});
