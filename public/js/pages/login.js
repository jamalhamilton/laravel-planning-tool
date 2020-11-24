var letterNum = 0;

//email validation
$(".input-email").on('keyup', function(){
	string = $(this).val();

	if ($(this).val().match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)) {
		$(this).parent().find("img").replaceWith('<img src="images/icon_check.svg">');
	} else {
		$(this).parent().find("img").replaceWith('<img src="images/Close-R.svg">');
	}

});
$(".input-email").on('change', function(){
    string = $(this).val();

    if ($(this).val().match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)) {
        $(this).parent().find("img").replaceWith('<img src="images/icon_check.svg">');
    } else {
        $(this).parent().find("img").replaceWith('<img src="images/Close-R.svg">');
    }

});
//password validation
$("#input_psw").on('keyup', function() {
	letterNum = $(this).val().length;
	
	if (letterNum > 7) {
		$(this).parent().find("span img").replaceWith('<img src="images/icon_check.svg">');
	} else if (letterNum < 9) {
		$(this).parent().find("span img").replaceWith('<img src="images/Close-R.svg">');
	}
	
});

//forgotten email page
function formHide() {
  $("#form_init").css('display','none');
  $("#form_forgotten").css('display','block');
}

$('#form_forgotten .btn-send').on('click', function() {
	/*$("#modal_confirm").modal({
		"blockerClass": "blocker-confirm"
	});*/
	//$('#form_forgotten').submit();
	  $("#sendmessageSpan").css("display","none");
$("#sendWaiting").css("display","inline-block");
	$.ajax({
        type: "POST",
        url: $('#form_forgotten').attr('action'),
        data:$('#form_forgotten').serialize(), 
        success: function( msg ) {
           $("#sendWaiting").css("display","none");
  		   $("#sendmessageSpan").css("display","block");

        },
        error: function(msg){
        	$("#sendWaiting").css("display","none");
        	$("#sendmessageSpan").css("display","block");
        }
    });


});

$('#modal_confirm #btn_confirm').on('click', function() {
	$('#form_forgotten').submit();
});
