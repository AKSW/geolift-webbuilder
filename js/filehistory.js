/* 
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */
var FileHistory = window.FileHistory || {};
if (!Date.now) {
    Date.now = function() { return new Date().getTime(); };
}
var FileHistory = function(){
  
    var $files=[];
    var $localStorageName= "idotter.fileHistory";
    
    function init(){
        getFromStorage();
        $('#cleanHistory').unbind("click").click(function(){
            cleanHistory();
        });
    };
    function getFromStorage (){
            
        if (!supportsHtml5Storage()) return "";
        if (!window['localStorage']) return "";
        var storage = window['localStorage'];
        var foo = storage.getItem($localStorageName);
        $files= JSON.parse(foo)||[];
        updateView();
    };
    function cleanHistory (){
        $files = [];
        saveToStorage();
    };
    function saveToStorage (){
        var json =JSON.stringify($files);
        if (!supportsHtml5Storage()) return false;
        if (!window['localStorage']) return false;
        var storage = window['localStorage'];
        storage.setItem($localStorageName,json);
        updateView();
    };
    function removeFile (file){
        for (ele in $files) 
            if($files[ele]!==null && $files[ele].f===file) 
                $files.slice(ele,1);
            
        saveToStorage();
    };
    function supportsHtml5Storage() {
        try {
            return 'localStorage' in window && window['localStorage'] !== null;
        } catch (e) {
            return false;
        }
    };
    function compare(a,b) {
        if (a.d < b.d)
            return 1;
        if (a.d > b.d)
            return -1;
        return 0;
    }


    function updateView(){
        $files.sort(compare);
        $('.file-container').remove();
        if($files.length===0)$('.file-history').append('<div class="file-container list-group-item">No Recent Uploads</div>')
        for (var thing in $files ){
            
            if(Date.now()<=($files[thing].d+(24*60*60*100))) {
                removeFile($files[thing].f);
            }else{
                $('.file-history').append('<a href="'+decodeURIComponent($files[thing].u)+'" class="file-container list-group-item list-group-item-success" >'
                        +'<small>'+$files[thing].f+'</small></a>');
            }
            
        }
        
                
    }
    init();
    return {
        addFile:function (file,url,date){
            
            $files.push({"f":file,"u":(url),"d":date});
            saveToStorage();
        },
        cleanHistory : function () {
            cleanHistory();
            
            
            return this;
        }
              
    };
};

