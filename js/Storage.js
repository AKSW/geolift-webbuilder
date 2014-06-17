/* 
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */
var Storage = window.Storage || {};
if (!Date.now) {
    Date.now = function() {
        return new Date().getTime();
    };
}
var Storage = function() {
    var $localStorageName = "geolift.storage";
    var $storage = null;
    function getValue(key) {
        if ($storage === null)
            getFromStorage();
        if (typeof($storage[key]) === typeof(undefined))
            return null;
        return $storage[key];
    }
    ;
    function hasValue(key) {

        if ($storage === null)
            getFromStorage();
        if (typeof($storage[key]) === typeof(undefined))
            return false;
        return true;

    }
    ;
    function setValue(key, value) {
        if ($storage === null)
            getFromStorage();
        $storage[key] = value;
        saveToStorage();
        return true;
    }
    function removeValue(key) {
        if ($storage === null)
            getFromStorage();
         if (typeof($storage[key]) === typeof(undefined))
            return false;
        delete $storage[key];
        
        saveToStorage();
        return true;
    }
    function getFromStorage() {
        if (!supportsHtml5Storage())
            return {};
        if (!window['localStorage'])
            return {};
        var storage = window['localStorage'];
        var foo = storage.getItem($localStorageName);
        $storage = JSON.parse(foo) || {};
    }
    ;
    function supportsHtml5Storage() {
        try {
            return 'localStorage' in window && window['localStorage'] !== null;
        } catch (e) {
            return false;
        }
    }
    ;
    function saveToStorage() {
        var json = JSON.stringify($storage);
        if (!supportsHtml5Storage())
            return false;
        if (!window['localStorage'])
            return false;
        var storage = window['localStorage'];
        storage.setItem($localStorageName, json);
    }
    function init() {
//        document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function(s) {
//            $localStorageName ="idotter.storage."+ s.substring(3);
//        });
    }
    init();
    return {
        getValue: function(key) {
            return getValue(key);
        },
        hasValue: function(key) {
            return hasValue(key);
        },
        setValue: function(key, value) {

            return setValue(key, value);
        },
        removeValue: function(key) {
            return removeValue(key);
        }
    };
};
