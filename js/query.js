/* 
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */


$(document).ready(function() {
    $('body').tooltip({
        selector: '[data-toggle=tooltip1]'
    });
    var splitter = function(event, ui) {
        var ah = parseInt(ui.position.top),
                bh = parseInt($('.sidebar').height()) - ah;
        $('.sidebar-element').eq(0).css({height: ah});
        $('.sidebar-element').eq(1).css({top: ah, height: bh});
    };
    $('.vdragbar').draggable({
        axis: 'y',
        drag: splitter
    });
    var query = new Query();
});
var Query = window.Query || {};
var Query = function() {


    var $modules = [];
    var $query = [];
    var $user = "";
    function reinitDraggable() {
        $(".module").draggable({revert: "invalid", appendTo: "body", helper: "clone",cursor: "move"});
        $(".arrow").droppable({
            activeClass: "ui-state-active",
            drop: function(event, ui) {
                console.log($(this).attr("id"));
                $(this)
                        .addClass("ui-state-highlight")
                        .find("p")
                        .html("Dropped!");
            }
        });
    }
    function init() {
        $modules = [];
        $query = [];
        $user = "";
        $.getJSON("../upload/",
                {
                    "function": "getModules",
                },
                function(data) {
                    for (var index in data) {//each module
                        var module1 = new Module();
                        module1.setName(data[index].title);
                        module1.setDescription(data[index].description);
                        for (var index1 in data[index].properties) {//each property
                            if (typeof(data[index].properties[index1].type) !== typeof(undefined))
                                module1.addProperty(index1, data[index].properties[index1].type, data[index].properties[index1].description);
                            else
                                module1.addProperty(index1, data[index].properties[index1].enum, data[index].properties[index1].description);
                        }
                        $modules.push(module1);
                    }
                    rebuildModuleList();
                }
        );
        $query = {
            1: {
                name: "start",
                type: "module",
                html: "S<br>T<br>A<br>R<br>T",
                from: null,
                to: 3,
                y: 0,
                ymax: 1,
                x: 0
            },
            2: {
                name: "end",
                type: "module",
                html: "E<br>N<br>D",
                to: null,
                y: 0,
                ymax: 1,
                x: 4
            },
            3: {
                name: "split",
                type: "module",
                html: "S<br>P<br>L<br>I<br>T",
                to: [4, 4],
                y: 0,
                ymax: 1,
                x: 1
            },
            5: {
                name: "operation1",
                type: "module",
                html: "O<br>P<br>E<br>R<br>A<br>T<br>I<br>O<br>N",
                to: 2,
                y: 0,
                ymax: 2,
                x: 2,
            },
            4: {
                name: "merge",
                type: "module",
                html: "M<br>E<br>R<br>G<br>E",
                to: 2,
                y: 0,
                ymax: 1,
                x: 3,
            },
        };
        redrawQuery();

    }

    function redrawQuery() {
        $('.viewpoint').find('*').remove();
        for (var index in $query) {
            var element = $query[index];
            var $div = "";
            var top = element.y / element.ymax;

            $div = '<div \n\
    style="left: ' + (element.x * 200 + 15) + 'px; width: 100px; top: ' + (top * 100) + '%; height: ' + (1 / element.ymax * 100) + '% " \n\
    class="queryblock ' + (((element.name === "start") || (element.name === "end")) ? "startendBlock" : "") + '">\n\
    <div class="text">\n\
        <div class="center-container">\n\
            <div class="content-container">\n\
                ' + element.html + '\
            </div>\n\
        </div>\n\
    </div>\n\
</div>';

            if (element.to !== null) {//add Arrow behind
                if (typeof(element.to) !== typeof([])) {
                    //add one Arrow behind
                    $div += '<div \n\
    style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + (top * 100) + '%; height: ' + (1 / element.ymax * 100) + '% " \n\
    class="queryblock arrow" \n\
    id="arrow_' + index + '_' + element.to + '">\n\
    <div class="text">\n\
        <div class="center-container">\n\
            <div class="content-container">\n\
                <i class="fa fa-5x fa-arrow-right"></i>\n\
                <i class="fa fa-5x fa-plus-circle"></i>\n\
            </div>\n\
        </div>\n\
    </div>\n\
</div>';
                } else {
                     $div += '<div \n\
    style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + (top * 100 / 2) + '%; height: ' + (1 / element.ymax * 100) / 2 + '% " \n\
    class="queryblock arrow" \n\
    id="arrow_' + index + '_' + element.to + '">\n\
    <div class="text">\n\
        <div class="center-container">\n\
            <div class="content-container">\n\
                <i class="fa fa-5x fa-arrow-right"></i>\n\
                <i class="fa fa-5x fa-plus-circle"></i>\n\
            </div>\n\
        </div>\n\
    </div>\n\
</div>';
                     $div += '<div \n\
    style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + ((top + 1) * 100 / 2) + '%; height: ' + (1 / element.ymax * 100) / 2 + '% " \n\
    class="queryblock arrow" \n\
    id="arrow_' + index + '_' + element.to + '">\n\
    <div class="text">\n\
        <div class="center-container">\n\
            <div class="content-container">\n\
                <i class="fa fa-5x fa-arrow-right"></i>\n\
                <i class="fa fa-5x fa-plus-circle"></i>\n\
            </div>\n\
        </div>\n\
    </div>\n\
</div>';
//                    $div += '<div style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + (top * 100 / 2) + '%; height: ' + (1 / element.ymax * 100) / 2 + '% " class="queryblock arrow" id="arrow_' + index + '_' + element.to[0] + '"><div class="text"><div class="center-container"><div class="content-container"><i class="fa fa-5x fa-arrow-right"></i></div></div></div></div>';
//                    $div += '<div style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + ((top + 1) * 100 / 2) + '%; height: ' + (1 / element.ymax * 100) / 2 + '% " class="queryblock arrow" id="arrow_' + index + '_' + element.to[1] + '"><div class="text"><div class="center-container"><div class="content-container"><i class="fa fa-5x fa-arrow-right"></i></div></div></div></div>';


                }
            }
            $('.viewpoint').append($div);
        }
        reinitDraggable();
    }
    function rebuildModuleList() {
        if ($('#moduleList').find('*')[0])
            $('#moduleList').find('*').remove();
        for (var index in $modules) {
            $('#moduleList').append('<div class="module" data-toggle="tooltip1" data-container="body" data-placement="right" title="' + $modules[index].getDescription() + '">\n\
\n\ ' + $modules[index].getName() + ' \n\
</div>');
        }
        reinitDraggable();
    }
    init();
    return {};
};
var Module = window.Module || {};
var Module = function() {
    var $name = "";
    var $description = "";
    var $properties = [];
    return {
        setName: function(name) {
            $name = name;
            return this;
        },
        getName: function(name) {
            return $name;
        },
        setDescription: function(name) {
            $description = name;
            return this;
        },
        getDescription: function(name) {
            return $description;
        },
        addProperty: function(name, type, description) {
            var prop = {
                name: name,
                description: description
            };
            if (typeof(type) !== typeof([]))//no enum
                prop.type = type;
            else {
                prop.type = "enum";
                prop.enum = type;
            }

            $properties.push(prop);
            return this;
        },
        getProperties: function() {
            return $properties;
        }
    };
}