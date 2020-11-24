//password validation
var letterNum = 0;

$(".input-reset-psw").on('keyup', function(){
	letterNum = $(this).val().length;
	
	if (letterNum > 7) {
		$(this).parent().find("button img").replaceWith('<img src="/images/icon_check.svg">');
	} else if (letterNum < 9) {
		$(this).parent().find("button img").replaceWith('<img src="/images/Close-R.svg">');
	}
});

$(".input-email").on('keyup', function(){
    string = $(this).val();

    if ($(this).val().match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)) {
        $(this).parent().find("img").replaceWith('<img src="/images/icon_check.svg">');
    } else {
        $(this).parent().find("img").replaceWith('<img src="/images/Close-R.svg">');
    }

});
$(".input-email").on('change', function(){
    string = $(this).val();

    if ($(this).val().match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)) {
        $(this).parent().find("img").replaceWith('<img src="/images/icon_check.svg">');
    } else {
        $(this).parent().find("img").replaceWith('<img src="/images/Close-R.svg">');
    }

});
