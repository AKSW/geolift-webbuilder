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
