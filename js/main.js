/* 
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
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
        //console.log(data);
        for (index = 0; index < data.length; ++index) {
            var panel = '<div class="panel panel-default" id="file_' + data[index].input + '"><div class="panel-heading">File: ' + data[index].filename + '\n\
            <div class="pull-right">\n\
<a href="Query/?user=' + encodeURIComponent($user) + '&file=' + data[index].input + '" type="button" class="btn btn-success btn-xs ">add Query</a>\n\
<button type="button" class="btn btn-danger btn-xs ">Remove File</button>\n\
</div>\n\
</div> <div class="panel-body"></div>\n\
</div>';
            $('#datasets .wrapper').append(panel);

            if (data[index].jobs.length === 0) {
                $('#datasets .wrapper').find('#file_' + data[index].input + ' .panel-body').html('').append('<div class="alert alert-warning">\n\
<strong>No Querys for this file!</strong>\n\
<a href="Query/?user=' + encodeURIComponent($user) + '&file=' + data[index].input + '" class="btn btn-success btn-xs ">add Query</a>\n\
</div>');

            } else {
                var state = "test";
                for (i = 0; i < data[index].jobs.length; ++i) {


                    if (data[index].jobs[i].state === "waiting") {
                        state = '<i class="fa fa-clock-o fa-fw" data-toggle="tooltip" data-placement="right" title="Query is pending"></i>';
                    } else if (data[index].jobs[i].state === "running") {
                        state = '<i class="fa fa-spinner fa-spin fa-fw" data-toggle="tooltip" data-placement="right" title="Query is currently computing"></i>';
                    } else if (data[index].jobs[i].state === "done") {
                        state = '<i class="fa fa-check fa-fw" data-toggle="tooltip" data-placement="right" title="Query is done"></i>';
                    }
                    $('#datasets .wrapper').find('#file_' + data[index].input + ' .panel-body').append('<div class="row">\n\
<div class="col-xs-10">' + state + '<strong>Job #' + i + ': </strong>' + data[index].jobs[i].name + '</div><div class="col-xs-2">' + '\
<a href="Query/?user=' + encodeURIComponent($user) + '&file=' + data[index].input + '" class="btn btn-success btn-xs pull-right " data-toggle="tooltip" data-placement="left" title="edit this Query"><i class="fa fa-pencil"></i></a></div>\n\
</div>');
                }
            }
            console.log(data[index]);
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
