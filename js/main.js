/*
 * Copyright 2014 Alrik Hausdorf
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
var $user = null;
function loadUserFiles() {
    if ($user === null)
        return false;
    $('#blocks').removeClass('hidden');
    $('#datasets .wrapper').html('<p>Loading...</p>');
    $.getJSON("upload/",
            {
                "function": "getDatasets",
                "user": $user
            },
    function(data) {
        $('#datasets .wrapper').html('');
        if (data.length === 0) {
            $('#datasets .wrapper').append("<p>You don't have any Datasets or Querys stored</p>");
            return;
        }
        if (typeof(data.error) !== typeof(undefined)) {
            var alert = '<div class="alert alert-danger"><strong>Error!</strong> ' + data.error + '\n\</div>';
            $('#datasets .wrapper').append(alert);
            return;
        }
        for (index = 0; index < data.length; ++index) {
            var panel = '<div class="panel panel-default" id="file_' + data[index].input + '"><div class="panel-heading">File: ' + data[index].filename + '\n\
                        <div class="pull-right">\n\
                        <a href="Query/?user=' + encodeURIComponent($user) + '&file=' + data[index].input + '" type="button" class="btn btn-success btn-xs ">Add Config</a>\n\
                        <a href="upload/?function=removeFile&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '" class="removeFile btn btn-danger btn-xs ">Remove File</a>\n\
                        </div>\n\
                        </div> <div class="panel-body"></div>\n\
                        </div>';
            $('#datasets .wrapper').append(panel);

            if (data[index].jobs.length === 0) {
                $('#datasets .wrapper')
                        .find('#file_' + data[index].input + ' .panel-body')
                        .html('')
                        .append('<div class="alert alert-warning">\n\
                            <strong>No Querys for this file!</strong>\n\
                            <a href="Query/?user=' + encodeURIComponent($user) + '&file=' + data[index].input + '" class="btn btn-success btn-xs ">Add Config</a>\n\
                            </div>');

            } else {
                var state = "test";
                for (i = 0; i < data[index].jobs.length; ++i) {
                    if (data[index].jobs[i].state === "waiting") {
                        state = '<i class="fa fa-clock-o fa-fw" data-toggle="tooltip" data-placement="right" title="Geolift process is pending"></i>';
                    } else if (data[index].jobs[i].state === "running") {
                        state = '<i class="fa fa-spinner fa-spin fa-fw" data-toggle="tooltip" data-placement="right" title="Geolift process is currently computing"></i>';
                    } else if (data[index].jobs[i].state === "done") {
                        state = '<i class="fa fa-check fa-fw" data-toggle="tooltip" data-placement="right" title="Geolift process is done"></i>';
                    }
                    var buttons = '';
                    if (data[index].jobs[i].state === "waiting") {
                        buttons += '<a href="upload/?function=removeJob&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class="removeJob btn btn-danger btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="remove this config"><i class="fa fa-fw fa-trash-o"></i></a>';
                        buttons += '<a href="upload/?function=getConfig&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class=" btn btn-success btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="download generated config file"><i class="fa fa-fw fa-cogs"></i></a>';
                        buttons += '<a href="Query/?user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class=" btn btn-success btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="edit this config"><i class="fa fa-fw fa-pencil"></i></a>';
                        buttons += '<a href="upload/?function=runJob&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class="runJob btn btn-success btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="run this config"><i class="fa fa-fw fa-play"></i></a>';
                    }
                    if (data[index].jobs[i].state === "done" || data[index].jobs[i].state === "running") {
                        buttons += '<a href="Query/?user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class=" btn btn-success btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="view this config"><i class="fa fa-fw fa-eye"></i></a>';
                        buttons += '<a href="upload/?function=getConfig&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class=" btn btn-success btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="download generated config file"><i class="fa fa-fw fa-cogs"></i></a>';
                        buttons += '<a target="_blank" href="upload/?function=getOutput&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class="getOutput btn btn-success btn-xs pull-right"><i class="fa fa-fw fa-download "  data-toggle="tooltip" data-placement="left" title="download output of this config"></i></a>';
                        buttons += '<a target="_blank" href="upload/?function=getLogOutput&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class="getLogOutput btn btn-success btn-xs pull-right" ><i class="fa fa-fw fa-bars" data-toggle="tooltip" data-placement="left" title="download current log file of this config"></i></a>';
                        buttons += '<a href="upload/?function=runJob&user=' + encodeURIComponent($user) + '&file=' + data[index].input + '&job=' + data[index].jobs[i].file + '" class="runJob btn btn-success btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="rerun this config"><i class="fa fa-fw fa-refresh"></i></a>';

                    }
                    $('#datasets .wrapper')
                            .find('#file_' + data[index].input + ' .panel-body')
                            .append('<div class="row job-row">\n\
                                <div class="col-xs-9">' + state + '<strong>Job #' + i + ': </strong>' + data[index].jobs[i].name + '</div><div class="col-xs-3">' + '\
                                ' + buttons + '</div>\n\
                                </div>');

                }
            }
        }
    });
}

$(document).ready(function() {
    $('body').tooltip({
        selector: '[data-toggle=tooltip]'
    });
    var storage = new Storage();
    var $userKey = "loggedinas";
    if (storage.hasValue($userKey)) {
        $user = storage.getValue($userKey);
        $('.notloggedin').fadeOut();
        $('.loggedin').fadeIn().find("strong").html($user);

        loadUserFiles();
    }
    $("#refreshBtn").unbind('click').click(function() {
        loadUserFiles();
    });
    $('#submit').unbind('click').click(function() {
        var url = $(this).parent().find('#urlInput').val();
        var endpoint = $(this).parent().find('#endPointInput').val();
        $.getJSON("upload/",
                {
                    "url": url,
                    "endpoint": endpoint,
                    "user": $user
                }, function(data) {
            if (typeof data.success !== typeof undefined)
                loadUserFiles();
            else
                alert('Error:' + data.error);

        });
    });
    $('body').on('click', ".removeFile", function(e) {
        e.preventDefault();
        var r = confirm("Are you sure you want to remove this File?");
        if (r === true) {
            var url = $(this).attr('href');
            $.get(url, function(data) {
                if (typeof data.success !== typeof undefined)
                    loadUserFiles();
                else
                    alert('Error:' + data.error);
            });
        }
    }).on('click', ".removeJob", function(e) {
        e.preventDefault();
        var r = confirm("Are you sure you want to remove this Query?");
        if (r === true) {

            var url = $(this).attr('href');
            $.get(url, function(data) {
                if (typeof data.success !== typeof undefined)
                    loadUserFiles();
                else
                    alert('Error:' + data.error);
            });
        }
    }).on('click', '.runJob', function(e) {
        e.preventDefault();
        var r = confirm("Are you sure you want to run this Query?");
        if (r === true) {

            var url = $(this).attr('href');
            $.get(url, function(data) {
                if (typeof data.success !== typeof undefined)
                    loadUserFiles();
                else
                    alert('Error:' + data.error);
            });
        }
    });

    $("#loginbtn").unbind('click').click(function() {
        var mail = $(this).parents('.notloggedin').find('#email').eq(0).val();
        $.getJSON("upload/",
                {
                    "function": "login",
                    "user": mail
                }, function(data) {

            if (typeof(data.error) === typeof(undefined)) {
                $('.notloggedin').fadeOut();
                $('.loggedin').fadeIn().find("strong").html(data.mail);
                storage.setValue($userKey, data.mail);
                $user = data.mail;
                loadUserFiles();
            } else {
                $('.notloggedin').fadeIn();
            }
        });
    });
    $(".loggedin>button").unbind('click').click(function() {
        $('#datasets .wrapper').html('<p>Please login first</p>');
        storage.removeValue("loggedinas");
        $('.notloggedin').fadeIn();
        $('.loggedin').fadeOut();
        $('#blocks').addClass('hidden');
    });
});
