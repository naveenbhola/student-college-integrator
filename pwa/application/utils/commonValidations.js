import React from 'react';
import shikshaConfig from './../modules/common/config/shikshaConfig';

export function isProfaneReg(str) {
    
    if(!str) {
        return;
    }
    /* code start to avoid dissallowed chars */
    var responseValue = checkDisAllowedWord(str);
    if(responseValue !== true){
        return responseValue;
    }
    /* code end to avoid dissallowed chars */
    
    var profaneWordsBag = eval(base64_decode('WyJzdWNrIiwiZnVjayIsImRpY2siLCJwZW5pcyIsImN1bnQiLCJwdXNzeSIsImhvcm55Iiwib3JnYXNtIiwidmFnaW5hIiwiYmFiZSIsImJpdGNoIiwic2x1dCIsIndob3JlIiwid2hvcmlzaCIsInNsdXR0aXNoIiwibmFrZWQiLCJpbnRlcmNvdXJzZSIsInByb3N0aXR1dGUiLCJzZXgiLCJzZXh3b3JrZXIiLCJzZXgtd29ya2VyIiwiYnJlYXN0IiwiYnJlYXN0cyIsImJvb2IiLCJib29icyIsImJ1dHQiLCJoaXAiLCJoaXBzIiwibmlwcGxlIiwibmlwcGxlcyIsImVyb3RpYyIsImVyb3Rpc20iLCJlcm90aWNpc20iLCJsdW5kIiwiY2hvb3QiLCJjaHV0IiwibG9yYSIsImxvZGEiLCJyYW5kIiwicmFuZGkiLCJ0aGFyYWsiLCJ0aGFyYWtpIiwidGhhcmtpIiwiY2hvZCIsImNob2RuYSIsImNodXRpeWEiLCJjaG9vdGl5YSIsImdhYW5kIiwiZ2FuZCIsImdhbmR1IiwiZ2FhbmR1IiwiaGFyYWFtaSIsImhhcmFtaSIsImNodWRhaSIsImNodWRuYSIsImNodWR0aSIsImJhZGFuIiwiY2hvb2NoaSIsInN0YW4iLCJuYW5naSIsIm5hbmdhIiwibmFuZ2UiLCJwaHVkZGkiLCJmdWRkaSIsImxpZmVrbm90cyIsIjA5ODEwMTEyOTU0IiwiYWJpZGphbiIsInNpZXJyYS1MZW9uZSIsInNlbmVnYWwiLCJzaWVycmEgbGVvbmUiLCJsdWNreSBtYW4iLCJzaXJhIiwibWFkaGFyY2hvZCIsInRoYWJvIiwiZnVja2VkIiwiZnVja2luZyIsInB1YmxpYyBzaXRlIiwiRGFrdSIsInByaXZhdGUgbWFpbCIsInByaXZhdGUgbWFpbGJveCIsInNleHkiLCJqb2JzIHZhY2FuY2llcyIsIm9tbmkgY2l0eSIsImJhc3R1cmQiLCJqZWhhZCIsInRlbmRlcm5lc3MgY2FyZSIsIm1lcmFjYXJlZXJndWlkZS5jb20iLCJtZXJhY2FyZWVyZ3VpZGUiXQ=='));

    var words = str.split(" ");
    
    for(var wordsCount = 0; wordsCount < words.length; wordsCount++) {
        for(var profaneWordsCount = 0; profaneWordsCount < profaneWordsBag.length; profaneWordsCount++) {
            // if(str.indexOf(profaneWordsBag[profaneWordsCount]) > -1) {
            if(words[wordsCount] == profaneWordsBag[profaneWordsCount]) {
                return profaneWordsBag[profaneWordsCount];
            }
        }
    }
    return false;

}

function checkDisAllowedWord(str){

    if(!str) {
        return;
    }
    str = str.replace(/[^\x20-\x7E]/g,'');
    
    var disallowdWordsList = base64_decode("bWVyYWNhcmVlcmd1aWRlfG1lcmFjYXJlZXJndWlkfHJlYWNoQGluZHJhaml0LmlufHd3dy5pbmRyYWppdC5pbnwwOTgxMDIyNTExNA==");
    
    var url_pattern     = new RegExp(disallowdWordsList,"i");
    var dissallowedWord = url_pattern.exec(str);
    if(dissallowedWord != null){
        return dissallowedWord;
    }
    return true;

}

function base64_decode( data ) {
   
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

    do {  // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1<<18 | h2<<12 | h3<<6 | h4;

        o1 = bits>>16 & 0xff;
        o2 = bits>>8 & 0xff;
        o3 = bits & 0xff;

        if (h3 == 64)      enc += String.fromCharCode(o1);
        else if (h4 == 64) enc += String.fromCharCode(o1, o2);
        else               enc += String.fromCharCode(o1, o2, o3);
    } while (i < data.length);

    return enc;
}

export function isBlackListedReg(str) {
    if(!str) {
        return;
    }
    var blacklisted    = false;
    var strToValidate  = trim(str);
    var textBoxContent = strToValidate.replace(/[(\n)\r\t\"\']/g,' ');
    textBoxContent     = strToValidate.replace(/[^\x20-\x7E]/g,'');
    textBoxContent.toLowerCase();
    var blacklistWords = ["advisor", "expert", "Expert", "Advisor", "18002003922"];
    for (var i=0; i < blacklistWords.length; i++) {
        if(textBoxContent.indexOf( blacklistWords[i].toLowerCase() ) >= 0) {
            blacklisted = true;
        }
    }
    if(blacklisted) {
        return true;
    }
    return false;
}

export function validateSpecialChars(str) {

    if(!str) {
        return;
    }
    var strToValidate = trim(str);
    var allowedChars  = /^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/;
    var result        = allowedChars.test(strToValidate);
    if(result == false) {
        return false;
    }
    return true;

}

export function validateMinLength(str, minLength) {

    if(!str) {
        return;
    }
    var strToValidate = trim(str);
    if(strToValidate.length < minLength) {
        return false;
    }
    return true;
    
}

export function validateMaxLength(str, maxLength) {

    if(!str) {
        return;
    }
    var strToValidate = trim(str);
    if(strToValidate.length > maxLength) {
        return false;
    }
    return true;

}

export function validateEmailAddress(email) {

    if(!email) {
        return;
    }
    var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/

    if(!filter.test(email)) {

        return false;

    } else {
        
        var domainIndex = email.indexOf('@')+1;
        var domain      = email.substring(domainIndex, email.length);
        if(shikshaConfig['invalidEmailDomains'].indexOf(domain.toLowerCase()) != -1) {
            return false;
        }

    }

    return true;

}

export function validateMobileNumber(number, isNationalNumber) {

    if(!number) {
        return;
    }
    if(typeof(isNationalNumber) == 'undefined'){
      isNationalNumber = true;
    }
    if(isNationalNumber && (number.substr(0,1) != 9) && (number.substr(0,1) != 8) && (number.substr(0,1) != 7) && (number.substr(0,1) != 6)) {
        return false;
    }
    return true;

}

export function validateMobileLength(number, length) {

    if(!number || !length) {
        return;
    }
    if(number.length != length) {
        return false;
    }
    return true;

}

function trim(str) {
    return str.replace(/^\s+|\s+$/g,"");
}