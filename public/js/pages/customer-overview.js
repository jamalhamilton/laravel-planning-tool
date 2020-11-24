$(document).ready(function(){
    $(".addLessBtn").click(function(){
        $(".hiddenTr").toggle(800);
    });


});

//search box
function filterContent(elmnt) {
    var x, val;
    val = elmnt.value;
    x = document.getElementsByClassName("imgLogoBox");
 
    for (i = 0; i < x.length; i++) {
        if (x[i].getAttributeNode("data-name").value.toUpperCase().indexOf(val.toUpperCase()) == -1) {
            x[i].style.display = "none";
        } else {
        	x[i].style.display = "block";
        } 
    }
}


