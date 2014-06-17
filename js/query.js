/* 
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */


$(document).ready(function() {
    $('body').tooltip({
        selector: '[data-toggle=tooltip1]'
    });
    var splitter = function(event, ui) {
        var ah = parseInt(ui.position.top),
            bh = parseInt($('.sidebar').height())-ah;
        $('.sidebar-element').eq(0).css({height: ah});
        $('.sidebar-element').eq(1).css({top:ah,height: bh});
    };
    $('.vdragbar').draggable({
        axis: 'y',
        drag: splitter
    });
});