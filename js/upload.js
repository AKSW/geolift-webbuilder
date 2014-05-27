/* 
 * Copyright (c) 2014, Gurkware Solutions GbR  All rights reserved. 
 */
var Upload = window.Upload || {};

var Upload = function(){
    var $url = "upload/";
    var $filedropWrapper = "#fileDropper";
    var $fileHistory = new FileHistory();;
    var $formData;
    /**
     * Event Handler if file was dropped
     * @param {type} e
     * @returns {undefined}
     */
    function fileDropper(e){
        var files = e.originalEvent.dataTransfer.files;
        $formData = new FormData();
        console.log(files);
        for (var i = 0; i < files.length; i++) {
            $formData.append('files[]', files[i]);
        }
       
        submitFile();
    }
    function fileSelector(e){
        console.log(e);
        var files = e.target.files;
        $formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            $formData.append('files[]', files[i]);
        }
        submitFile();
    }
    function fileOnLoad(xhr){
        console.log(xhr.responseText);
        var array1=JSON.parse(xhr.responseText);
        for (var a in array1){
            $fileHistory.addFile(array1[a].filename,array1[a].url,array1[a].available);
        }
        
        
        
        
        $('.progress-bar')
                .css({'width':(100+"%")})
                .html(100+"%")
                .removeClass("progress-bar-info")
                .addClass("progress-bar-success");
        window.setTimeout(function(){
            $('.progress-bar')
                    .css({'width':(0+"%")})
                    .html(0+"%")
                    .parent()
                    .fadeOut('fast');
        },1000);
    }
    function fileOnProgress(e){
        if (e.lengthComputable) {
            var complete = (e.loaded / e.total * 100 | 0);
            $('.progress-bar')
                    .css({'width':(complete+"%")})
                    .html(complete+"%");
        }
            
    }

    function submitFile(){
        $('.progress-bar')
                .removeClass("progress-bar-success")
                .addClass("progress-bar-info")
                .css({'width':(0+"%")})
                .html(0+"%")
                .parent()
                .fadeIn('fast');
                
        var xhr = new XMLHttpRequest();
        xhr.open('POST', $url);
        xhr.onload = function(){fileOnLoad(xhr);};
        xhr.upload.onprogress = function(e){fileOnProgress(e);};
        xhr.send($formData);
         
    }
    function initFileDropper(){
        $($filedropWrapper).on('dragenter', function(e){
            e.preventDefault();
            e.stopPropagation();
        }).on('dragover', function(e){
            e.preventDefault();
            e.stopPropagation();
        }).on('drop', function(e){
            console.log("drop()");
            e.preventDefault();
            e.stopPropagation();
            fileDropper(e);
        });
    };
    function initFileSelect(){
        $("input:file").change(function (e){
            fileSelector(e);
            e.preventDefault();
            e.stopPropagation();
            
        });
    };
    initFileSelect();
    initFileDropper();
    return {
        //        setCanonicalURL : function(canoURL){
        //            
        //        }
    };
};

