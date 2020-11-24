// JavaScript Document
function initNewCustomSelect(thiz) {
    if (thiz.find('.select-items').length == 0){
      
      var selItem = document.createElement("div");
      $(selItem).addClass('select-selected');
      
      var select = thiz.find('select')[0];

      //$(select).find(':selected').removeProp('selected');

      var selOptionHtml =$(select).find(':selected').html();

      $(selItem).html(selOptionHtml);

      var selList = document.createElement("div");
      $(selList).addClass("select-items select-hide");

      var selOptionIdx = $(select).find('option:selected').index();

      $(select).find('option').each(function(i){
        var selItemInList = document.createElement("div");
        $(selItemInList).html($(this).html());
        if (i == selOptionIdx) {
          $(selItemInList).addClass('same-as-selected');
        }
        $(selList).append(selItemInList);
      });

      thiz.append(selItem);
      thiz.append(selList);
    }
}

$(document).ready(function(){
  $('.custom-select').each(function(){
    var thiz = $(this);
    if (thiz.find('.select-items').length == 0){
      
      var selItem = document.createElement("div");
      $(selItem).addClass('select-selected');
      
      var select = thiz.find('select')[0];

      //$(select).find(':selected').removeProp('selected');

      var selOptionHtml =$(select).find(':selected').html();

      $(selItem).html(selOptionHtml);

      var selList = document.createElement("div");
      $(selList).addClass("select-items select-hide");

      var selOptionIdx = $(select).find('option:selected').index();

      $(select).find('option').each(function(i){
        var selItemInList = document.createElement("div");
        $(selItemInList).html($(this).html());
        if (i == selOptionIdx) {
          $(selItemInList).addClass('same-as-selected');
        }
        $(selList).append(selItemInList);
      });

      thiz.append(selItem);
      thiz.append(selList);
    }
  });

  $('body').on('click', '.select-items div', function(){
    var idx = $(this).index();
    $(this).parent().find('.same-as-selected').removeClass('same-as-selected');
    $(this).addClass('same-as-selected');
    $(this).parents('.custom-select').find('select option').removeProp('selected');
    $(this).parents('.custom-select').find('select option').removeAttr('selected');
    $(this).parents('.custom-select').find('select option:nth-child('+(idx+1)+')').attr('selected','');
    
    $(this).parent().toggleClass('select-hide');
    $(this).parent().prev().toggleClass('select-arrow-active');
    $(this).parent().prev().html($(this).html());
    $(this).trigger('regionclick');
    $(this).parents('.custom-select').find('select').trigger('change');
  });

  $('body').on("click", '.select-selected', function(e) {
      var parent;
      e.stopPropagation();
      //closeAllSelect(this);
      parent = $(this).parents('.custom-select')
      
      if (!parent.hasClass('disabled')) {
        $(this).next().toggleClass("select-hide");
        $(this).toggleClass("select-arrow-active");  
      } 
    });
});

