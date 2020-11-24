function numberWithCommas(x) {
     return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "\'");//if you want use , then replace "\'"!
}
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
function calTotal(){
    var elems;
    var total;
    var weekNum = $("#weeknum").val();
    elems = document.getElementsByClassName('subtotal');
    
    for (var i = 0; i <= elems.length; i++) {
        if(i > 10){
            var jjj = 12;
        }
        var idx = (i % (parseInt(weekNum) + 2)) + 2;
        console.log(idx);
        var cells = $(elems[i]).parents("tr.categorytr").find(".contentCollap td:nth-child("+idx+")");
        total = 0;
        
        for (var j = 0; j < cells.length; j++) {
            if(cells[j].childNodes.length > 1) {
                var eVal = cells[j].childNodes[1].value;
                if (typeof eVal !== "undefined") {
                    if (eVal != "" && eVal != "tdb")
                        total += parseInt(eVal.replaceAll("\'", "").replaceAll(",", ""));
                }
            }
        }
        if(total != 0) {
            $(elems[i]).text(numberWithCommas(total));
        }
    }

    elems = document.getElementsByClassName('alltotal');
    
    for (var i = 0; i < elems.length; i++) {
        var idx = (i % (parseInt(weekNum)+2))+2;
        var cells = $(elems[i]).parents("div.mediaPlanningTable").find(".subtotaltr td:nth-child("+idx+")");
        total = 0;
        
        for (var j = 0; j < cells.length; j++) {
            if(cells[j].innerText != "" && cells[j].innerText != "tdb"){
                total += parseInt(cells[j].innerText.replaceAll("\'","").replaceAll(",",""));
            }
        }
        if(total != 0) {
            $(elems[i]).text(numberWithCommas(total));
        }
    };    
}

$(document).ready(function(){
    $(".hasDatepicker").datepicker( "option", "firstDay", 1 );
    calTotal();


    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
            coll[i].classList.toggle("active");
            var content = coll[i].nextElementSibling;

            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }

    }

});

var coll = document.getElementsByClassName("collapsible");

for (var i = 0; i < coll.length; i++) {
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

//display warning box when exceeding limit value

$("body").on('focus','td input',function (){
    //$(this).val(numberWithCommas($(this).val()));
    var val = $(this).val().replaceAll("\'","");
    $(this).val(val);
});


$("body").on('blur','td input',function (){

    var number = $(this).val();
    $(this).val(numberWithCommas(number));

    if($(this).val() == ""){
        $(this).parent().css("background-color","transparent");
        $(this).parent().find('input').css("color","transparent");
    }
    else{
        $(this).parent().css("background-color","#5cbe9b");
    }

    var thiz;
    mediaID = $(this).parents('tr').data('id');
    // var adweeksum = 0; //$($(this).parents('tr').children()[2]).children().val();
    // //adweeksum = parseInt(adweeksum.replace("\'", "").replace(",", ""));
    // //adweeksum -= dif;
    // for(var i = 3; i < $(this).parents('tr').children().length ; i ++)
    // {
    //     var val = $($(this).parents('tr').children()[i]).children().val();
    //     if(val == "")
    //         val = '0';
    //     if(val == undefined)
    //         break;
    //     while(val.indexOf("\'") != -1)
    //         val = val.replaceAll("\'", "");
    //     adweeksum += parseInt(val);
    // }
    // $($(this).parents('tr').children()[2]).children().val(numberWithCommas(adweeksum));

    var adPrintSum = $($(this).parents('tr').children()[1]).children().val();
    // if(adPrintSum == numberWithCommas(adweeksum))
    //     $($(this).parents('tr').children()[2]).children().attr('style', 'color : #5C7C9D');
    // else
    //     $($(this).parents('tr').children()[2]).children().attr('style', 'color : red');

    weekNum = $(this).data('weeknum');
    thiz = $(this);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/advertising/edit',
        dataType: 'JSON',
        data:{
            'mediaID':mediaID,
            'weekNum':weekNum,
            'number':number,
            'active_channel':$("#active_channel").val(),
            'campaignID': $("#campaign_id").val()
        },
        success: function(data,textStatus,jqXHR) {
            if (data.msg != 'Edit Ok!') {
                thiz.parent().parent().parent().append("<div><span>Dieser Wert ist zu hoch</span></div>");
                thiz.parent().css("position","relative");
                thiz.addClass('over-number');

                boxWidth = thiz.parent().css('width');
                boxHeightPx = thiz.parent().css('height');
                offsetLeft = thiz.parent().position().left;
                offsetTop = thiz.parent().position().top;

                boxHeight = boxHeightPx.substr(0,boxHeightPx.length-2);
                a = parseInt(offsetTop) + parseInt(boxHeight);

                boxElem = thiz.parent().parent().parent();
                boxElem.find("div").addClass('excess-number');
                boxElem.find('div').css({"width":boxWidth,
                                        "height":boxHeight,
                                        "top": a + 'px',
                                        "left":offsetLeft});
                boxElem.find("div span").css("color","white");
            } else {
                // thiz.parents('td').removeAttr("style");
                thiz.removeClass("over-number");
                // thiz.parent().parent().siblings("div").remove();
                calTotal();
          }

      },
      error: function(jqXHR, textStatus, errorThrown){

      },
    });
});

