import React from 'react';
import { makeURLAsHyperlink } from './urlUtility';
export function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
export function htmlEntities(string)
{
	return string.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
	   return '&#'+i.charCodeAt(0)+';';
	});
}

export function decodeHtmlEntities(str) {

let htmlEntities = {
    nbsp: ' ',
    cent: '¢',
    pound: '£',
    yen: '¥',
    euro: '€',
    copy: '©',
    reg: '®',
    lt: '<',
    gt: '>',
    quot: '"',
    amp: '&',
    apos: '\''
};

    return str.replace(/\&([^;]+);/g, function (entity, entityCode) {
        var match;

        if (entityCode in htmlEntities) {
            return htmlEntities[entityCode];
            /*eslint no-cond-assign: 0*/
        } else if (match = entityCode.match(/^#x([\da-fA-F]+)$/)) {
            return String.fromCharCode(parseInt(match[1], 16));
            /*eslint no-cond-assign: 0*/
        } else if (match = entityCode.match(/^#(\d+)$/)) {
            return String.fromCharCode(~~match[1]);
        } else {
            return entity;
        }
    });
}


export function strip_tags (string) {
	if(string){
  		return string.replace(/(<([^>]+)>)/ig,"");
	}
}
export function sanitizeMsgTxt(string,entityType='question')
{
	if(!string || typeof string == 'undefined')
		return '';

	string = string.replace(/\?+/i,'?');
	string = string.replace(/\.+/i,'.');
	string = string.replace(/^ +/gm, '');
	if(entityType != 'question' || entityType != 'discussion'){
		string = makeURLAsHyperlink(string);
		string = string.replace(/([\r\n]+)/g,"<br/>");
	}else{
		string = string.replace(/([\r\n]+)/g,"\n");
	}

	return string;

}
export function cutStringWithShowMore(string,characterCheck,id,text='view more',showStringAsHyperLink=true,doHtmlEntities=true,anaText=false,isAmp=false)
{
	var stringText = '';
	if(anaText){
		stringText = sanitizeMsgTxt(string);
	}
	else if(showStringAsHyperLink && doHtmlEntities)
	{
		stringText = nl2br(makeURLAsHyperlink(htmlEntities(string)));
	}
	else if(showStringAsHyperLink && !doHtmlEntities)
	{
		stringText = nl2br(makeURLAsHyperlink(string));
	}
	else
	{
		stringText = string;
	}
  var keyString = 'key="showless"';
  var keyStringMore = 'key="showori"';
  var keymore = 'key="showmore"';
  if(isAmp)
  {
    keyString = "";
    keyStringMore = "";
    keymore="";
  }
	if(stringText && strip_tags(stringText).length > characterCheck )
	{
		string = '<span class="f13 color-3 l-16 lt word-wrap" '+keyString+'>'+nl2br(strip_tags(stringText).substr(0,(characterCheck-3)))+'...</span><span '+keymore+' class="read-more-target f13 color-3 l-16 listhide word-wrap">'+stringText+'</span>';
        string  += '<label for="'+id+'" class="read-more-trigger f14 color-b i-block">'+text+'</label>';
	}
	else
	{
		string = '<span class="f13 color-3 l-16 word-wrap" '+keyStringMore+'>'+stringText+'</span>';
	}
	return string;
}

export function makeRandomString(digitCount) {
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for (var i = 0; i < digitCount; i++){
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	}
	return text;
}



export function cutString(string,characterCheck,id,isAmp=false)

{
	var stringText = string;
  var keyString = 'key="showless"';
  var keyStringMore = 'key="showori"';
  var keymore = 'key="showmore"';
  if(isAmp)
  {
    keyString = "";
    keyStringMore = "";
    keymore= "";
  }
	if(stringText && strip_tags(stringText).length > characterCheck )
	{

		string = '<span class="f13 color-3 l-16 lt word-wrap" '+keyString+'>'+nl2br(strip_tags(stringText).substr(0,(characterCheck-3)))+'...</span><span '+keymore+' class="read-more-target f13 color-3 l-16 listhide word-wrap">'+stringText+'</span><label for='+id+' class="read-more-trigger f14 color-b i-block">more</label>';

	}
	else
	{
		string = '<span class="f13 color-3 l-16 word-wrap" '+keyString+'>'+stringText+'</span>';
	}
	return string;
}

export function getTextFromHtml(htmlString,limit,tagsNotToSkip ){
	if(typeof(tagsNotToSkip) =='undefined'){
		tagsNotToSkip = ['table'];
	}

	var finalString ='',
	index =0,
	lengthOfString = htmlString.length,
	countOfChar = 0,
	countOfDiv = 0,
	tagToCheckOpen = ['a','p','strong','div','span'],
	openTags = [];

	while(index<lengthOfString){
		if(htmlString[index] == '<'){
            var tagName = '';
            while(htmlString[index] !='>'){
                tagName += htmlString[index];
                finalString += htmlString[index];
                index++;
            }
            tagName += htmlString[index];
            finalString += htmlString[index];
            index++;
            for(var i=0;i<tagToCheckOpen.length;i++){
            	var tag = tagToCheckOpen[i];
            	var tagLength = tag.length;
            	var closingTag = '</'+tag+'>';
            	if(tagName.substr(1,tagLength) == tag){
            		openTags.push(tag);
           		}
            	else if(tagName.substr(0,tagLength+3) == closingTag){
            		openTags.pop(tag);
            	}

            }
            for(var i=0;i<tagsNotToSkip.length;i++){
            	var tagToIgnore = tagsNotToSkip[i];
                var tabLength = tagToIgnore.length;
                if(tagToIgnore == 'table'){
                	if(tagName.substr(1,tabLength) == tagToIgnore){
                		var tableCount = 0 ;
                		var endTag = '</table>';
                	    while(htmlString.substr(index,tabLength+3)!=endTag  || tableCount>0){
                	        if(htmlString.substr(index,6) == '<table'){
                	        	tableCount++;
                	        }
                	        if(htmlString.substr(index,7) == '</table'){
                	        	tableCount--;
                	        }
                	        finalString +=htmlString[index];
                	        index++;
                	    }
                	}
                }else if(tagName.substr(1,tabLength) == tagToIgnore){
					endTag = '</'+tagToIgnore+'>';
					while(htmlString.substr(index,tabLength+3)!=endTag){
						finalString +=htmlString[index];
						index++;
					}
				}
            }
        }
        else{
            countOfChar++;
            finalString +=htmlString[index];
            index++;
        }
   	    if(countOfChar == limit || index>lengthOfString){
   	        break;
   	    }
	}
	while(openTags.length>0){
		tag = openTags.pop();
		endTag = '</'+tag+'>';
		finalString +=endTag;
	}
	return finalString;
}

export function stringTruncate(string, noOfChar){
  if(string.length < noOfChar){
    return string;
  }
  return string.substr(0, noOfChar-3) + '...';
}

export function cutHTML(content, length) {
	let currentIndex = 0, chosenTextLength = 0, pop = null, subtract = 0;
	let index, finalHtml;
	let tag = null, tags = [];
	do {
		index = content.indexOf('<', currentIndex);
		if(index > currentIndex) {
			chosenTextLength += (index - currentIndex - 1);
			currentIndex = index;
		}
		if(chosenTextLength >= length) {
			break;
		}
		if(index !== -1) {
			index = content.indexOf('>', index);
			tag = content.substring(currentIndex + 1, index);
			if(!tag.startsWith("/")) {
				if(tag.endsWith("/")) {
					tag = tag.substring(0, tag.length - 1);
				}
				tags.push(tag.trim());
			}else{
				tag = tag.substring(1);
				do {
					if(tags.length === 0) {
						break;
					}
					pop = tags.pop();
					if(pop.toUpperCase() === tag.toUpperCase()){
						break;
					}
				} while(true);
			}
			currentIndex = index;
		}
		if(index === -1) {
			break;
		}
	} while(true);
	if(chosenTextLength > length) {
		subtract = chosenTextLength - length;
		currentIndex = currentIndex - subtract;
	}
	if(tags.length === 0) {
		return content.substring(0, currentIndex+1);
	}
	finalHtml = content.substring(0, currentIndex);
	let stackSize = tags.length;
	for(let i = 0; i < stackSize; i++) {
		tag = tags.pop();
		if("br" !== tag.toLowerCase()){
			if(tag.trim().indexOf(" ") !== -1){
				finalHtml += '</' + tag.substring(0, tag.indexOf(" ")) + '>';
			}else{
				finalHtml += '</' + tag + '>';
			}
		}
	}
	return finalHtml.toString();
}


export function strip_html_tags(str)
{
   if ((str===null) || (str===''))
       return false;
  else
   str = str.toString();
  return str.replace(/<[^>]*>/g, '');
}

export function jsUcfirst(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}