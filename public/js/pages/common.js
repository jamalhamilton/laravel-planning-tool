$(document).ready(function(){
    $(".mobileMenu").click(function(){
        $(".navbar-nav").toggle();
    });
});

$(function() {
	$('button[data-modal]').on('click', function() {
    	$($(this).data('modal')).modal();
    	return false;
	});
});

(function($){
    $(window).on("load",function(){             
        $.mCustomScrollbar.defaults.theme="light-2"; //set "light-2" as the default theme
        $(".demo-x").mCustomScrollbar({
            axis:"x"
        }); 
    });
})(jQuery);

$(".demo-x").mCustomScrollbar("scrollTo","left",{
    scrollInertia:0
});

$( function() {
	$( ".datepicker" ).datepicker();
} );

function openCity(evt, cityName) {
  var i, tabContent, tabLinks;
  var elem;

  tabContent = document.getElementsByClassName("tabcontent");
  
  for (i = 0; i < tabContent.length; i++) {
    tabContent[i].style.display = "none";
  }

  tabLinks = document.getElementsByClassName("tablinks");
  
  for (i = 0; i < tabLinks.length; i++) {
    tabLinks[i].className = tabLinks[i].className.replace(" active", "");
  }

  elem = document.getElementById(cityName);
  
  if (elem) {
    elem.style.display = "block";
  }

  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
/*if (document.getElementById("defaultOpen") != null) {
    document.getElementById("defaultOpen").click();  
}*/

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "\'");//if you want use , then replace "\'"!
}

/*
var size = [$(window).width(),$(window).height()];  //public variable

$(window).on('resize', function(e){
    location.reload();
});*/
String.prototype.replaceAll = function(searchStr, replaceStr) {
    var str = this;

    // no match exists in string?
    if(str.indexOf(searchStr) === -1) {
        // return string
        return str;
    }

    // replace and remove first match, and do another recursirve search/replace
    return (str.replace(searchStr, replaceStr)).replaceAll(searchStr, replaceStr);
}