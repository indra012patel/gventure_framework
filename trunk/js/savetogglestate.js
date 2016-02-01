$(document).ready(function() {
    var toShow = $.cookie('lastShownIndex'),
        topLevel = $('#am_menu').find('> li');

    topLevel.click(function(){
       $(this).children('ul').slideToggle().end().siblings().children('ul').slideUp();
       $.cookie('lastShownIndex', $(this).index());
       }).eq(toShow).find('ul').show();
       $.removeCookie("lastShownIndex"); 
});
