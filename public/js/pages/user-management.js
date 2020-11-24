$(document).ready(function(){
    $(".addLessBtn.plusBtn").click(function(){
	    $(".hiddenTr").toggle(800);
	    $(".plusBtn").toggleClass("lessBtn");
    });

    $(".normal-check").on("click", function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();

		if ($(this).attr("checked") == "checked") {
			$(this).removeAttr("checked");
		} else {
			$(this).attr("checked","checked");
		}
	});
});

function fnGetSelected( oTableLocal ){
	return oTableLocal.$('tr.row-selected');
}

$.fn.dataTableExt.oPagination.mypagination = {
   "fnInit": function ( oSettings, nPaging, fnCallbackDraw ) { 
	},
 
   "fnUpdate": function ( oSettings, fnCallbackDraw ) {	
		var iPageCount = 5;
		var iPageCountHalf = Math.floor(iPageCount / 2);
		var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
		var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
		var sList = "";
		var iStartButton, iEndButton, i, iLen;
	   	PreviousHtml='<li><a href="javascript:;" data-pgidx = "previous"><i class="fa fa-angle-double-left"></i></a></li>';
	   	NextHtml='<li><a href="javascript:;" data-pgidx = "next"><i class="fa fa-angle-double-right"></i></a></li>';

		if ( oSettings._iDisplayLength === -1 )	{
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

		PageinfoHtml='<span class="pagNote">Showing '+(oSettings._iDisplayStart+1)+' to '+oSettings._iDisplayEnd+' of '+oSettings.fnRecordsTotal()+' entries</span>';
		
		$(".pagination").empty();
		$(".pagination").append(PreviousHtml, sList, NextHtml,PageinfoHtml);
	   
	    if ( !oSettings.aanFeatures.p ) {
	        return;
	    }

    }
};

$(document).ready(function() {
	oTable = $('#users').dataTable( {
		"sAjaxDataProp" : "",
		"sAjaxSource" : "/users/list",

		"aoColumns": [
		  	{
		    	"mData":'picture',
		    	"mRender":function(data,type,full){
		    		return  "<span class='userImgTd' style='background:none;'>" +
		    					"<img src=" + data + " class='mCS_img_loaded' style='object-fit:contain; height:40px; object-position:center;'>" +
		    				"</span>";
		    	}
		    },
	        {   "mData": "firstname" },
	        {   "mData": "lastname" },
	        {   "mData": "initial" },
	        {   "mData": "email" },
	        /*{   "mData": "tokenKey" },*/
	        {   "mData": "group" },
	        {   
	        	"mData": "status",
	        	"mRender":function(data,type,full){
	        		if (data == 'Active') {
	        			return "<span style='color:black;'>" + data + "</span>";
	        		} else if (data == "Inactive") {
	        			return "<span style='color:red;'>" + data + "</span>";
	        		} else if (data == "Locked") {
	        			return "<span style='color:#a4b7cb !important;'>" + data + "</span>";;
	        		}
	        		
	        	} 
	        },
	        {
	        	"mData": "ID",
	        	"sClass": "tdRightOption tdRightOption",
		        "mRender":function(data, type, row){
			        return	'<ul class="rightOption">'+
					        	'<li class="subOpt">'+ 
					        		'<ul class="optionBtnGroup otherBtnGroup">'+
					        			'<li>'+ 
					        				'<button type="button" data-modal="#modal2" class="btn-edit" data-id="'+data+'">'+
					        					'<img src="images/icon_edit.svg" class="mCS_img_loaded">'+
					        				'</button>'+ 
					        			'</li>'+
					        			'<li>'+
					        				'<button type="button" data-modal="#deleteModal" class="btn-delete" data-id="'+data+'">'+
					        					'<img src="images/icon_grun_delete.svg" class="mCS_img_loaded">'+
					        				'</button>'+
					        			'</li>'+
					        		'</ul>'+
					        	'</li>'+
					        '</ul>'
			    }
	        },
	    ],

		"oLanguage": {
			"sSearch": "   ",
			"sScrollY": "300px",
	        "bPaginate": false,
	        "bScrollCollapse": true
		},

	    "sPaginationType": "mypagination",

	    "aaSorting": [[1, 'asc']],
	    "bLengthChange": false,
	    "bFilter": true,
	    "bSort": false,
	    "bInfo": false,
	    "bAutoWidth": false
	} );

	$('.pagination').on('click','li a',function(){
		var idxStr = $(this).data('pgidx');
		
		if(idxStr!='previous' && idxStr !='next'){
			$('ul.pagination li.active').removeClass('active');
			$(this).parent().addClass('active');
		}

		oTable.fnPageChange(idxStr);
	});

	$("#users").on('click','.btn-delete',function( e ) {		
		$('tr.row-selected').removeClass('row-selected');
		$(this).parents('tr').addClass('row-selected');
		
	    $('#deleteModal').modal();
	});

	$('.btn-deleterow').on('click', function() {
	    var id = $(".row-selected").find(".btn-delete").data('id');
	    var anSelected = fnGetSelected( oTable );

	    if ( anSelected.length !== 0 ) {
	    	$.ajax({
	            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'GET',
	            url: '/users/delete',
	            dataType: 'JSON',
	            data: {'userID':id},
	            success: function(data,textStatus,jqXHR) {
	            	//oTable.fnDeleteRow( anSelected[0] );

                    oTable.fnUpdate("Inactive",anSelected[0]._DT_RowIndex, 6);

	            	$("#deleteModal").hide();
				    $('.blocker').hide();
				    $('body').css('overflow','auto');				
	        	},
	        	error: function(jqXHR, textStatus, errorThrown){
	            }
	        });     
	    }
	} );

	$("#users").on('click','.btn-edit',function( e ) {
		$('tr.row-selected').removeClass('row-selected');
		$(this).parents('tr').addClass('row-selected');

		$('#modal2').modal();

		var id = $(".row-selected").find(".btn-edit").data('id');
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            url: '/users/show',
            dataType: 'JSON',
            data: {'userID':id},
            success: function(data,textStatus,jqXHR) {
        		$('#modal2_firstname').val(data['firstname']);
            	$('#modal2_lastname').val(data['lastname']);
            	$('#modal2_initial').val(data['initial']);
            	$('#modal2_email').val(data['email']);
            	$('#modal2_password').val(data['password']);
            	$('#modal2_avatar').attr("src",data['avatar']);
            	
            	if (data['hasAPI'] == 1) {
            		$('#modal2_ckb').attr("checked",true);            		
            	} else {
            		$('#modal2_ckb').attr("checked",false);
            	}
            	$('#modal2_tokenKey').val(data['tokenKey']);
            	
            	$('#modal2_group').val(data['group']);
            	$('#modal2_group option').removeAttr("selected");
            	var selOption = $('#modal2_group option[value="'+data['group']+'"]');

            	selOption.attr("selected","selected");
            	$('#modal2_group').parent().find('.select-selected').text(selOption.text());
            	$('#modal2_group').parent().find('.select-items > div').each(function() {
            		$(this).removeClass('same-as-selected');

            		if ($(this).text() === selOption.text()) {
            			$(this).addClass('same-as-selected');
            		}
            	});


            	$('#modal2_status').val(data['status']);
            	$('#modal2_status option').removeAttr("selected");
            	var selOption1 = $('#modal2_status option[value="'+data['status']+'"]');

            	selOption1.attr("selected","selected");
            	$('#modal2_status').parent().find('.select-selected').text(selOption1.text());
            	$('#modal2_status').parent().find('.select-items > div').each(function() {
            		$(this).removeClass('same-as-selected');

            		if ($(this).text() === selOption1.text()) {
            			$(this).addClass('same-as-selected');
            		}
            	});

            },
        	error: function(jqXHR, textStatus, errorThrown){
            }
		});
	});

	$('.btn-editrow').on('click',function(){
		var id = $(".row-selected").find(".btn-edit").data('id');
		var anSelected = fnGetSelected( oTable );

	    if ( anSelected.length !== 0 ) {
			var firname = $('#modal2 .input-firname').val();
			var lasname = $('#modal2 .input-lasname').val();					
			var initials = $('#modal2 .input-initials').val();
			var email = $('#modal2 .input-email').val();					
			var pass = $('#modal2 .input-pass').val();
			var hasAPI; 
				if ($('#modal2_ckb').attr("checked") ){
					hasAPI = 1;
				} else {
					hasAPI = 0;
				}

			var group = $('#modal2_group').val();
			var status = $('#modal2_status').val();

			var groupText = $('#modal2_group option:selected').text();
			var statusText = $('#modal2_status option:selected').text();
		}

		var formdata = false;
		if (window.FormData){
			formdata = new FormData();
		}

		var file = document.getElementById("modal2_upload").files[0];
		
		if (formdata) {
			formdata.append('ID',id);
			formdata.append('firstname',firname);
			formdata.append('lastname',lasname);
			formdata.append('initial',initials);
			formdata.append('email',email);
			formdata.append('password',pass);
			formdata.append('hasAPI',hasAPI);
			formdata.append('group',group);
			formdata.append('status',status);
			if(file){
				formdata.append('picture',file);
			}
		}

		$.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/users/edit',
            data: formdata,
            processData: false,
            contentType: false,
            success: function(data,textStatus,jqXHR) {
        		var updateRow = {
            		"picture": data['pictureURL'],
            		"firstname": firname,
            		"lastname": lasname,
            		"initial": initials,
            		"email": email,
            		"password": pass,
            		"tokenKey": data['tokenKey'],
            		"group": groupText,
            		"status": statusText,
            		'ID': id
            	};

            	oTable.fnUpdate(updateRow,anSelected[0]._DT_RowIndex);

				$("#modal2").hide();
			    $('.blocker').hide();
				$('body').css('overflow','auto');
			},
        	error: function(jqXHR, textStatus, errorThrown){
            	
            }
        });				
	});

	$(".searchField input").keyup( function () {
		oTable.fnFilter( this.value );						
	} );

	$('.btn-addrow').on('click',function(){		
		var firstname = $('#modal1 .input-firname').val();
		var lastname = $('#modal1 .input-lasname').val();					
		var initial = $('#modal1 .input-initials').val();
		var email = $('#modal1 .input-email').val();					
		var password = $('#modal1 .input-pass').val();
		var hasAPI; 

			if($('#ckb_api').attr("checked"))
				hasAPI = 1;
			else
				hasAPI = 0;

		var group = $('#modal1 .div-group .select-selected').text();
		var status = $('#modal1 .div-status .select-selected').text();

		var formdata = false;
		
		if (window.FormData){
			formdata = new FormData();
		}

		var file = document.getElementById("input_upload").files[0];
		
		if(formdata){
			formdata.append('firstname',firstname);
			formdata.append('lastname',lastname);
			formdata.append('initial',initial);
			formdata.append('email',email);
			formdata.append('password',password);
			formdata.append('hasAPI',hasAPI);
			formdata.append('group',group);
			formdata.append('status',status);
			if(file){
				formdata.append('picture',file);
			}
		}

		$.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/users/add',
            data: formdata,
            processData: false,
            contentType: false,
            success: function(data,textStatus,jqXHR) {
            	if(data['emailValid'] == 'error'){
            		$('#modal1_email').css("color","red");
            		$('#modal1_email').parent().parent().append(
        										'<span class="invalid-feedback" role="alert">'+
                                    				'<strong style="color:red">This email is already recorded.</strong>'+
                                				'</span>');
            	} else {
	            	var newRow = {
	            		"picture": data['pictureURL'],
	            		"firstname": firstname,
	            		"lastname": lastname,
	            		"initial": initial,
	            		"email": email,
	            		"tokenKey": data['tokenKey'],
	            		"group": group,
	            		"status": status,
	            		'ID': data['userID']
	            	};

                    oTable.fnAddData(newRow);

					$("#modal1").hide();
				    $('.blocker').hide();
					$('body').css('overflow','auto');


                    $('#modal1 .input-firname').val("");
                    $('#modal1 .input-lasname').val("");
                    $('#modal1 .input-initials').val("");
                    $('#modal1 .input-email').val("");
                    $('#modal1 .input-pass').val("");
                    $('#img_upload').attr("src", "uploads/user/default.jpg");

                    $('#modal1_email').css("color","#5C7C9D");
                    $('#modal1_email').parent().next().remove();

				}
        	},
        	error: function(jqXHR, textStatus, errorThrown){
            }
        });
	});
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

function openFileDialog2(){
	document.getElementById('modal2_upload').click();
}

function photoPreview2(){
	if (document.getElementById('modal2_upload').files && document.getElementById('modal2_upload').files[0]) {
		$('#modal2_avatar').css("background","none");
		var reader = new FileReader();
		reader.onload = function(e) {
		    document.getElementById('modal2_avatar').getAttributeNode("src").value = e.target.result;
		};
		reader.readAsDataURL(document.getElementById('modal2_upload').files[0]);
	}
}





