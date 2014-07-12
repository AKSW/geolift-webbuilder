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
var Upload = window.Upload || {};

var Upload = function() {
    var $url = "upload/";
    var $filedropWrapper = "#fileDropper";
    
    var $formData;
    /**
     * Event Handler if file was dropped
     * @param {type} e
     * @returns {undefined}
     */
    function fileDropper(e) {
        var files = e.originalEvent.dataTransfer.files;
        $formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            $formData.append('files[]', files[i]);
        }

        submitFile();
    }
    function fileSelector(e) {
        var files = e.target.files;
        $formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            $formData.append('files[]', files[i]);
        }
        submitFile();
    }
    function fileOnLoad(xhr) {
        $('.progress-bar')
                .css({'width': (100 + "%")})
                .html(100 + "%")
                .removeClass("progress-bar-info")
                .addClass("progress-bar-success");
        window.setTimeout(function() {
            $('.progress-bar')
                    .css({'width': (0 + "%")})
                    .html(0 + "%")
                    .parent()
                    .fadeOut('fast');
        }, 1000);
        loadUserFiles();
    }
    function fileOnProgress(e) {
        if (e.lengthComputable) {
            var complete = (e.loaded / e.total * 100 | 0);
            $('.progress-bar')
                    .css({'width': (complete + "%")})
                    .html(complete + "%");
        }

    }

    function submitFile() {
        if ($user === null)
            return false;
        else
            var url = $url + "?user=" + encodeURIComponent($user);
        $('.progress-bar')
                .removeClass("progress-bar-success")
                .addClass("progress-bar-info")
                .css({'width': (0 + "%")})
                .html(0 + "%")
                .parent()
                .fadeIn('fast');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', url);
        xhr.onload = function() {
            fileOnLoad(xhr);
        };
        xhr.upload.onprogress = function(e) {
            fileOnProgress(e);
        };
        xhr.send($formData);

    }
    function initFileDropper() {
        $($filedropWrapper).on('dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
        }).on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
        }).on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileDropper(e);
        });
    }
    ;
    function initFileSelect() {
        $("input:file").change(function(e) {
            fileSelector(e);
            e.preventDefault();
            e.stopPropagation();

        });
    }
    ;
    initFileSelect();
    initFileDropper();
    return {
    };
};

