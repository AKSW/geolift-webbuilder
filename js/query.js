/* 
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */


$(document).ready(function() {
    $('.sidebar').tooltip({
        selector: '[data-toggle=tooltip1]'
    });
    var splitter = function(event, ui) {
        $('.sidebar').css({'height': "100%"});
        var ah = parseInt(ui.position.top),
                bh = parseInt($('.sidebar').height()) - ah;
        $('.sidebar-element').eq(0).css({height: ah});
        $('.sidebar-element').eq(1).css({top: ah, height: bh});
    };
    $('.vdragbar').draggable({
        axis: 'y',
        drag: splitter,
        activeClass: "ui-state-active",
    });
    var query = new Query();
});
var Query = window.Query || {};
var Query = function() {

    var $watchMode = false;
    var $modules = [];
    var $operators = [];
    var $query = [];
    var $urlParams = {}
    var $user = "";
    function init() {
        $modules = [];
        $operators = [];
        $query = [];
        $user = "";
        $urlParams = {};
        findParams();
        if (typeof $urlParams["job"] !== typeof undefined)
            loadQuery();
        $.getJSON("../upload/",
                {
                    "function": "getSchema"
                },
        function(data) {

            for (var index in data.modules) {//each module
                var module1 = new Module();
                module1.setName(data.modules[index].title);
                module1.setDescription(data.modules[index].description);
                for (var index1 in data.modules[index].properties) {//each property
                    if (typeof(data.modules[index].properties[index1].type) !== typeof(undefined))
                        module1.addProperty(index1, data.modules[index].properties[index1].type, data.modules[index].properties[index1].description);
                    else
                        module1.addProperty(index1, data.modules[index].properties[index1].enum, data.modules[index].properties[index1].description);
                }
                $modules.push(module1);
            }
            for (var index in data.operators) {//each module
                var operator = new Operator();
                operator.setName(data.operators[index].title);
                operator.setDescription(data.operators[index].description);
                for (var index1 in data.operators[index].properties) {//each property
                    if (typeof(data.operators[index].properties[index1].type) !== typeof(undefined))
                        operator.addProperty(index1, data.operators[index].properties[index1].type, data.operators[index].properties[index1].description);
                    else if (typeof(data.operators[index].properties[index1].enum) !== typeof(undefined))
                        operator.addProperty(index1, data.operators[index].properties[index1].enum, data.operators[index].properties[index1].description);
                }
                $operators.push(operator);
            }
            rebuildSchemaList();
        }
        );
        $query = {
            1: {
                name: "start",
                type: "module",
                html: "S<br>T<br>A<br>R<br>T",
                to: 2,
//                y: 0,
//                ymax: 1,
//                x: 0
            },
            2: {
                name: "end",
                type: "module",
                html: "E<br>N<br>D",
                to: null,
//                y: 0,
//                ymax: 1,
//                x: 4
            },
            /*            3: {
             //                name: "split",
             //                type: "operator",
             //                html: "S<br>P<br>L<br>I<br>T",
             //                to: [5, 4],
             ////                y: 0,
             ////                ymax: 1,
             ////                x: 1
             //            },
             //            4: {
             //                name: "merge",
             //                type: "operator",
             //                html: "M<br>E<br>R<br>G<br>E",
             //                to: 2,
             ////                y: 0,
             ////                ymax: 1,
             ////                x: 3,
             //            },
             //            5: {
             //                name: "split",
             //                type: "operator",
             //                html: "S<br>P<br>L<br>I<br>T",
             //                to: [6, 6],
             ////                y: 0,
             ////                ymax: 1,
             ////                x: 1
             //            },
             //            6: {
             //                name: "merge",
             //                type: "operator",
             //                html: "M<br>E<br>R<br>G<br>E",
             //                to: 4
             ////                y: 0,
             ////                ymax: 1,
             ////                x: 3,
             //            },*/
        };
        redrawQuery();
        bindEvents();

    }
    function reinitDraggable() {
        $(".module").draggable({revert: "invalid", activeClass: "ui-state-active-my", appendTo: ".viewpoint", helper: "clone", cursor: "move"});
        $(".operator").draggable({revert: "invalid", activeClass: "ui-state-active-my", appendTo: ".viewpoint", helper: "clone", cursor: "move"});
        $(".arrow").droppable({
            cursor: "crosshair",
            activeClass: "ui-state-active-my",
            drop: function(event, ui) {
                addQueryModule(ui.draggable.attr('id'), $(this).attr("id"));
            }
        });
    }
    function loadQuery() {
        $.getJSON("../upload/",
                {
                    "function": "loadJob",
                    "user": $urlParams.user,
                    "file": $urlParams.file,
                    "job": encodeURI($urlParams.job)
                },
        function(data) {
            $('#jobName').val(data.name);
            if (data.state !== "waiting") {
                $('#saveBtn').addClass('disabled').attr('disabled', 'disabled');
                $('#jobName').attr('disabled', 'disabled');
                $watchMode = true;
                if ($('#moduleList').find('*')[0])
                    $('#moduleList').find('*').remove();

                $('#moduleList').append('<div class=" clearfix alert alert-danger " style="margin-top:15px;" role="alert"><strong>Watch-Mode.</strong> You can only display this query.</div>').parent().find('.alert-danger').show(2000);
            } else {
                $query = {};
                for (var index in data.job) {
//                    console.log("OLD:", data.job[index]);
                    var obj = {
                        name: data.job[index].name,
                        type: data.job[index].type,
                        html: data.job[index].name.toUpperCase().split('').join('<br>'),
                        to: data.job[index].next,
                        properties: data.job[index].properties
                    };
                    $query[data.job[index].id] = obj;
//                    console.log("NEW:", $query);

                }
                redrawQuery();
            }
//            console.log(data);
        });

    }
    function findParams() {
        var URLParams = decodeURIComponent(window.location.search).substr(1).split("&");
        for (var index in URLParams) {
            var par = URLParams[index].split('=');
            $urlParams[par[0]] = (decodeURI(par[1]));
        }
    }
    function trimModulePath2(coords, start) {
        if (typeof start === typeof undefined) {
            start = 1;
//         console.log("$query-------------------------------START");
//         for (var index in $query) {
//            console.log("    Element ",index," --> ",$query[index].x);
//         }

        }
        if (typeof coords === typeof undefined)//init-coordinates
            coords = {
                x: 0,
                y: 0,
                ymax: 1
            };

        var startElement = $query[start];
        var hasNext = true;
        var length = 0; //length of current path
        var count = 0;
        var next = null, old = null;
        while (hasNext) {//find first split

            count++;
            if (count > 20)//just for security
                hasNext = false;
            old = next;
            next = (next === null) ? startElement : (typeof next.to === typeof [] && next.to[0] === next.to[1]) ? $query[next.to[0]] : $query[next.to];
//            console.log("old", old, " next", next);
            if (next.type === "operator") {
                if (next.name === "split") {
//                    console.log("split");
                    length++;
                    next.x = coords.x++;
                    next.y = coords.y;
                    next.ymax = coords.ymax;
                    var mergeElement1 = trimModulePath2(
                            {
                                x: coords.x,
                                y: (coords.y * 2),
                                ymax: (coords.ymax * 2)
                            }
                    , next.to[0]);
                    var mergeElement2 = trimModulePath2(
                            {
                                x: coords.x,
                                y: ((coords.y * 2) + 1),
                                ymax: (coords.ymax * 2)
                            }
                    , next.to[1]);
                    if (mergeElement1 !== null) {
                        coords.x = mergeElement1.x + 1;
                        next = $query[mergeElement1.to];
                    }
                    if (mergeElement2 !== null) {
                        coords.x = mergeElement2.x + 1;
                        next = $query[mergeElement2.to];
                    }
                    if (mergeElement1 !== null && mergeElement2 !== null) {
                        if (mergeElement1.x > mergeElement2.x) {
                            coords.x = mergeElement1.x + 1;
                            next = $query[mergeElement1.to];
                        } else {
                            coords.x = mergeElement2.x + 1;
                            next = $query[mergeElement2.to];
                        }
                    }
                    if (mergeElement1 === null && mergeElement2 === null) {
                        next = $query[next.to[1]];
                    }
                    next.x = coords.x++;
                    next.y = coords.y;
                    next.ymax = coords.ymax;
                } else if (next.name === "merge") {
//                    console.log("Merge:", old);
                    return old;
                }
            } else if (next.type === "module") {
                length++;
                next.x = coords.x++;
                next.y = coords.y;
                next.ymax = coords.ymax;
                if (next.name === "end") {

//                    console.log("Length:", length);
                    return length;
                }
            } else {
                console.error("something bad happend:", next);
            }
//                       console.log("next-x:",next.x," -name:",next.name);

        }

    }
    function addQueryModule(moduleId, arrowId) {
        if (moduleId.indexOf("module") !== -1)
            addModule(moduleId, arrowId);
        else if (moduleId.indexOf("operator") !== -1)
            addOperator(moduleId, arrowId);
    }
    function addModule(moduleId, arrowId) {
        moduleId = parseInt(moduleId.substr(moduleId.indexOf("_") + 1));
        var arrow_from = arrowId.substr(arrowId.indexOf("_") + 1); //1_2
        var arrow_to = parseInt(arrow_from.substr(arrow_from.indexOf("_") + 1)); //2
        arrow_from = parseInt(arrow_from.substr(0, arrow_from.indexOf("_"))); //1
        var query_block_from = null;
        var query_block_to = null;
        for (var index in $query) {
            index = parseInt(index);
            if (index === arrow_from)
                query_block_from = $query[index];
            if (index === arrow_to)
                query_block_to = $query[index];
        }
        var new_module = (typeof($modules[moduleId]) !== typeof undefined) ? $modules[moduleId] : null;
        //first create new node

        var new_block = {
            name: new_module.getName(),
            type: "module",
            html: new_module.getName().toUpperCase().split('').join('<br>'),
            to: arrow_to,
        };
        var max = 0;
        //find next free object Id
        for (var index in $query) {
            if (parseInt(index) >= parseInt(max))
                max = index;
        }
        max++;
        //set the from node to-value to the new block
        if (typeof(query_block_from.to) === typeof(1)) {
//module after Module
            query_block_from.to = max;
        } else {//Merge(typeof()==typeof[])
            if (query_block_from.to[0] === arrow_to) {
                query_block_from.to[0] = max;
            } else if (query_block_from.to[1] === arrow_to) {
                query_block_from.to[1] = max;
            }
        }

        $query[max] = new_block;
        redrawQuery();
//        console.log(new_module.getName(), arrowId, arrow_from, arrow_to);
        editQueryBlock(max, new_module.getName());
    }
    function addOperator(moduleId, arrowId) {
        moduleId = parseInt(moduleId.substr(moduleId.indexOf("_") + 1));
        var arrow_from = arrowId.substr(arrowId.indexOf("_") + 1); //1_2
        var arrow_to = parseInt(arrow_from.substr(arrow_from.indexOf("_") + 1)); //2
        arrow_from = parseInt(arrow_from.substr(0, arrow_from.indexOf("_"))); //1
        var query_block_from = null;
        var query_block_to = null;
        for (var index in $query) {
            index = parseInt(index);
            if (index === arrow_from)
                query_block_from = $query[index];
            if (index === arrow_to)
                query_block_to = $query[index];
        }
        var new_module = (typeof($operators[moduleId]) !== typeof undefined) ? $operators[moduleId] : null;
        //first create new node

        if (new_module.getName() === "split") {
            var new_split = {
                name: new_module.getName(),
                type: "operator",
                html: new_module.getName().toUpperCase().split('').join('<br>'),
            };
            var new_merge = {
                name: "merge",
                type: "operator",
                html: "merge".toUpperCase().split('').join('<br>'),
                to: arrow_to,
            };



        }

        var max = 0;
        //find next free object Id
        for (var index in $query) {
            if (parseInt(index) >= parseInt(max))
                max = index;
        }
        max++;
        //set the from node to-value to the new block
        if (typeof(query_block_from.to) === typeof(1)) {
//module after Module
            query_block_from.to = max;
//            new_split.ymax = query_block_from.ymax;
//
//            new_merge.ymax = query_block_from.ymax;

        } else {//Merge(typeof()==typeof[])
//                        new_split.ymax = query_block_from.ymax+1;

//            new_merge.ymax = query_block_from.ymax + 1;
            if (query_block_from.to[0] === arrow_to) {
                query_block_from.to[0] = max;
            } else if (query_block_from.to[1] === arrow_to) {
                query_block_from.to[1] = max;
//                new_merge.y++;
//                new_split.y++;

            }
        }
        new_split.to = [max + 1, max + 1];

//then move all Query_blocks with x>= new Block to the right
//        for (var index in $query) {
//            if ($query[index].x < new_block.x)
//                continue;
//            $query[index].x++;
//        }

        $query[max] = new_split;
        $query[max + 1] = new_merge;

        redrawQuery();
//        console.log(new_module.getName(), arrowId, arrow_from, arrow_to);
        editQueryBlock(max, new_module.getName());
    }
    function bindEvents() {
        $('body').on('click', '.queryblock .deleteBtn', function(event) {
            var elementName = $(this).parent().find(".elementName").eq(0).html();
            var elementId = $(this).parent().find(".elementId").eq(0).html();
            removeQueryBlock(elementId, elementName);
            event.preventDefault();
        });
        $('body').on('click', '#backBtn', function() {
            window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf("/", window.location.href.lastIndexOf("/") - 1) + 1);
        });
        $('body').on('click', '#saveBtn', function() {
            var query = {};
            if ($(this).parents('.input-group').find('input').val() !== '')
                query.name = $(this).parents('.input-group').find('input').val();
            else
                query.name = "newQuery";
            query.job = [];
            query.state = "waiting";
            for (var index in $query) {
                var step = {};
                step.id = parseInt(index);
                step.name = $query[index].name;
                step.type = $query[index].type;
                step.next = $query[index].to;
                step.properties= $query[index].properties;
                query.job.push(step);
            }
            $.post("../upload/", {
                "function": "saveData",
                "user": $urlParams.user,
                "file": $urlParams.file,
                "data": encodeURI(JSON.stringify(query)),
                "job": $urlParams.job
            },
            function(data, textStatus) {
                console.log(data);
                if (typeof data.success !== typeof undefined) {
                    $('#mainNav').after('<div class=" clearfix alert alert-success" style="margin-top:15px;" role="alert"><strong>Success.</strong> Query saved.</div>');
                    window.setTimeout(function() {
                        window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf("/", window.location.href.lastIndexOf("/") - 1) + 1);
                    }, 2000);
                } else if (typeof data.error !== typeof undefined) {
                    $('#mainNav').after('<div class=" clearfix alert alert-danger " style="margin-top:15px;" role="alert"><strong>Error.</strong> ' + data.error + '</div>').parent().find('.alert-danger').show(2000);
                    ;
                    window.setTimeout(function() {
                        $('#mainNav').parent().find('.alert-danger').hide(2000);
                        window.setTimeout(function() {
                            $('#mainNav').parent().find('.alert-danger').remove();
                        }, 2000);
                    }, 2000);
                }
            }, "json");

            console.log(query);


        })
        $('body').on('click', '.queryblock .editBtn', function(event) {
            var elementName = $(this).parent().find(".elementName").eq(0).html();
            var elementId = $(this).parent().find(".elementId").eq(0).html();
            editQueryBlock(elementId, elementName);
            event.preventDefault();
        });
        $('#settings').on("change", '.form-control-select', function() {
            changeProperty($(this), $(this).attr('id'), $(this).val());
        });
        $('#settings').on("change", '.form-control-checkbox', function() {
            if ($(this).is(':checked'))
                changeProperty($(this), $(this).attr('id'), true);
            else
                changeProperty($(this), $(this).attr('id'), false);
        });

        $('#settings').on("change", '.form-control-text', function() {
            changeProperty($(this), $(this).attr('id'), $(this).val())
        });


    }
    function changeProperty(element, key, value) {
        var eleID = element.parents('#settings').find('.queryId').html();
        if (typeof $query[eleID] === typeof undefined)
            return;
        if (typeof $query[eleID].properties === typeof undefined)
            $query[eleID].properties = {};
        $query[eleID].properties[key] = value;
    }
    function editQueryBlock(elementId, elementName) {
        $('#settings').find('*').remove();
        var QueryBlock = $query[elementId];
        if (QueryBlock.type === "module") {
            var Module = null;
            for (var index in $modules) {
                if ($modules[index].getName() === elementName)
                    Module = $modules[index];
            }
            $('#settings').append("<h5>Modul: " + QueryBlock.name + "</h5>");
            $('#settings').append('<div class="hidden queryId">' + elementId + '</div>');
            var settings = Module.getProperties();
            //console.log(settings);
            var $div = '';
            for (var i in settings) {
                var value = null;
                if (typeof QueryBlock.properties !== typeof undefined &&
                        typeof QueryBlock.properties[settings[i].name] !== typeof undefined)
                    value = QueryBlock.properties[settings[i].name];
                if (settings[i].type === "string") {
                    $div += '<div class="form-group" data-toggle="tooltip1" data-container="body" data-placement="right" title="' + settings[i].description + '" >'
                            + '<label for="' + settings[i].name + '">' + settings[i].name + '</label>'
                            + '<input type="text" class="form-control form-control-text" id="' + settings[i].name + '" value="' + ((value !== null) ? value : '') + '" >'
                            + '</div>';
                } else if (settings[i].type === "boolean") {

                    $div += '<div class="form-group" data-toggle="tooltip1" data-container="body" data-placement="right" title="' + settings[i].description + '" >'
                            + '<label for="' + settings[i].name + '">' + settings[i].name + '</label>'
                            + ' <div class="onoffswitch"><input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox form-control-checkbox" id="' + settings[i].name + '">'
                            + '     <label class="onoffswitch-label" for="' + settings[i].name + '">'
                            + '         <span class="onoffswitch-inner"></span>'
                            + '         <span class="onoffswitch-switch"></span>'
                            + '     </label>'
                            + '</div>'
                            + '</div>';
                } else if (settings[i].type === "enum") {
//                    <div class="form-group" data-toggle="tooltip1" data-container="body" data-placement="right" title="long Description of this setting" >
//                    <label for="setting2">foxOutput</label>
//                    <select class="form-control" id="setting2">
//                        <option>TURTLE</option>             
//                        <option>JSONLD</option>             
//                        <option>N3</option>             
//                        <option>N-TRIPLE</option>             
//                        <option>RDF/JSON</option>             
//                        <option>RDF/XML</option>             
//                        <option>RDF/XML-ABBREV</option>
//                    </select>
//                </div>
                    $div += '<div class="form-group" data-toggle="tooltip1" data-container="body" data-placement="right" title="' + settings[i].description + '" >'
                            + '<label for="' + settings[i].name + '">' + settings[i].name + '</label>'
                            + '<select class="form-control form-control-select" id="' + settings[i].name + '" >';
                    for (var j in  settings[i].enum) {

                        $div += "<option>" + settings[i].enum[j] + "</option>";
                    }
                    $div += '</select></div>';
                }
            }
            $('#settings').append($div);

        } else if (QueryBlock.type === "operator") {
            $('#settings').append("<h5>Operator: " + QueryBlock.name + "</h5>");
            $('#settings').append("<p>No Settings for this operator</p>");
        }


    }
    function removeQueryBlock(elementId, elementName) {
//        console.log("$query-------------------------------before");
//         for (var index in $query) {
//            console.log("    Element ",index," --> ",$query[index].name);
//         }
        if (elementName === "merge") {
            alert('Please remove the related "Split".');
            return;
        } else if (elementName === "split") {
            var block = $query[elementId];
            if (typeof(block.to) !== typeof([]) || block.to[1] !== block.to[0]) {
                alert('Please remove all elements between before.');
                return;
            } else {
                for (var index in $query) {
                    if ($query[index].to === parseInt(elementId)) {
                        $query[index].to = $query[block.to[0]].to;
                        break;
                    }
                }
                delete $query[elementId];
                delete $query[block.to[0]];
            }
        } else {
            for (var index in $query) {
                if ($query[index].to === parseInt(elementId)) {
                    $query[index].to = $query[elementId].to;
                    break;
                } else if ($query[index].to !== null && typeof $query[index].to === typeof []) {
                    if ($query[index].to[0] === parseInt(elementId))
                        $query[index].to[0] = $query[elementId].to;
                    else if ($query[index].to[1] === parseInt(elementId))
                        $query[index].to[1] = $query[elementId].to;
                }
            }
            delete $query[elementId];
        }
//        console.log("$query-------------------------------After");
//         for (var index in $query) {
//            console.log("    Element ",index," --> ",$query[index].name);
//         }
        redrawQuery();
    }
    function redrawQuery() {
        $('.viewpoint').find('*').remove();
        trimModulePath2();
        for (var index in $query) {
            var element = $query[index];
            var $div = "";
            var top = element.y / element.ymax;
            $div = '<div \n\
                        style="left: ' + (element.x * 200 + 15) + 'px; width: 100px; top: ' + (top * 100) + '%; height: ' + (1 / element.ymax * 100 - 2) + '% " \n\
                        class="queryblock ' + (((element.name === "start") || (element.name === "end")) ? "startendBlock" : "") + '">\n\
                        <div class="options"><div class="hidden elementName">' + element.name + '</div><div class="hidden elementId">' + index + '</div></div>\n\
                        <div class="text">\n\
                            <div class="center-container">\n\
                                <div class="content-container">\n\
                                    ' + element.html + '\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
            if (element.to !== null) {//add Arrows behind
                if (typeof(element.to) !== typeof([])) {
//add one Arrow behind
                    $div += '<div \n\
                                style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + ((element.y / element.ymax) * 100) + '%; height: ' + (1 / element.ymax * 100 - 2) + '% " \n\
                                class="queryblock arrow" \n\
                                id="arrow_' + index + '_' + element.to + '">\n\
                                <div class="text">\n\
                                    <div class="center-container">\n\
                                        <div class="content-container">\
                                            <i class="fa fa-5x fa-arrow-right"></i>\n\
                                            <i class="fa fa-5x fa-plus-circle"></i>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
                } else {
                    $div += '<div \n\
                                style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + (((element.y * 2) / (element.ymax * 2)) * 100) + '%; height: ' + (((1 / element.ymax * 100 - 2) / 2)) + '% " \n\
                                class="queryblock arrow" \n\
                                id="arrow_' + index + '_' + element.to[0] + '">\n\
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
                                style="left: ' + (element.x * 200 + 100 + 15) + 'px; width: 100px; top: ' + (((element.y * 2 + 1) / (element.ymax * 2)) * 100) + '%; height: ' + ((1 / element.ymax * 100 - 2) / 2) + '% " \n\
                                class="queryblock arrow" \n\
                                id="arrow_' + index + '_' + element.to[1] + '">\n\
                                <div class="text">\n\
                                    <div class="center-container">\n\
                                        <div class="content-container">\n\
                                            <i class="fa fa-5x fa-arrow-right"></i>\n\
                                            <i class="fa fa-5x fa-plus-circle"></i>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
                }
            }
            $('.viewpoint').append($div);
        }
        $('.viewpoint').find('.options').append('<a href="#" class="btn btn-xs deleteBtn"><i class="fa fa-trash-o"></i></a>\n\
            <a href="#" class="btn btn-xs editBtn"><i class="fa fa-pencil "></i></a>');
        reinitDraggable();
    }
    function rebuildSchemaList() {
        if ($('#moduleList').find('*')[0])
            $('#moduleList').find('*').remove();
        if (!$watchMode) {
            for (var index in $modules) {
                $('#moduleList').append('<div class="module" data-toggle="tooltip1" data-container="body" id="module_' + index + '" data-placement="right" title="' + $modules[index].getDescription() + '">\n\
\n\ ' + $modules[index].getName() + ' \n\
</div>');
            }
//        console.log($operators);

            for (var index1 in $operators) {
                if ($operators[index1].getName() !== "merge")
                    $('#moduleList').append('<div class="operator" data-toggle="tooltip1" data-container="body" id="operator_' + index1 + '" data-placement="right" title="' + $operators[index1].getDescription() + '">\n\
\n\ ' + $operators[index1].getName() + ' \n\
</div>');
            }
        } else {
            $('#moduleList').append('<div class=" clearfix alert alert-danger " style="margin-top:15px;" role="alert"><strong>Watch-Mode.</strong> You can only display this query.</div>').parent().find('.alert-danger').show(2000);

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
};
var Operator = window.Operator || {};
var Operator = function() {
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
};