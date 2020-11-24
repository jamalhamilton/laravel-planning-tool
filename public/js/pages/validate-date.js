function checkBrowser() {
    ua = navigator.userAgent.toLowerCase(),
    check = function(r){
        return r.test(ua);
    },

    DOC = document,
    isStrict = DOC.compatMode == "CSS1Compat",
    isOpera = check(/opera/),
    isChrome = check(/\bchrome\b/),
    isFirefox = check(/firefox/),
    isSafari =! isChrome && check(/safari/);
}

function validateDate(index){
    var date1 = $('#datepicker' + (index * 2 - 1)).val();
    var date2 = $('#datepicker' + (index * 2)).val();

    var splitDate1 = date1.split(".");
    var YMDdate1 = splitDate1[2] + splitDate1[1] + splitDate1[0];

    var splitDate2 = date2.split(".");
    var YMDdate2 = splitDate2[2] + splitDate2[1] + splitDate2[0];

    if (YMDdate2 > YMDdate1) {
        flag = true;
        $('#datepicker' + (index * 2 - 1)).siblings("span").css("background-color","#03BF9B");
        $('#datepicker' + (index * 2)).siblings("span").css("background-color","#03BF9B");
        $('#datepicker' + (index * 2 - 1)).css("color","#03BF9B");
        $('#datepicker' + (index * 2)).css("color","#03BF9B");
    } else {
        flag = false;
        $('#datepicker' + (index * 2 - 1)).siblings("span").css("background-color","red");
        $('#datepicker' + (index * 2)).siblings("span").css("background-color","red");
        $('#datepicker' + (index * 2 - 1)).css("color","red");
        $('#datepicker' + (index * 2)).css("color","red");
    }
    
    return flag;
}
