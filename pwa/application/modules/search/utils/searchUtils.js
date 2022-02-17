import Analytics from "../../reusable/utils/AnalyticsTracking";

export function trackEvent(actionLabel,label) {
    Analytics.event({category : 'SEARCH_MOBILE', action : actionLabel, label : label});
}

export function highlightTypedKeyword(result, keyword) {
    let keywordLen = keyword.length;
    if (keywordLen == 0) {
        return;
    }
    keyword = keyword.toLowerCase();
    let caseInsensitiveResultArray = result.split(' ');
    let newString = '';
    const keywordArray = keyword.split(' ');
    let caseInsensitiveResultCount = [];
    const stopWords = ['is','in','am','as', 'it'];
    for(let i = 0; i < caseInsensitiveResultArray.length; i++){
        const caseInsensitiveResult = caseInsensitiveResultArray[i];
        for(let j = 0; j < keywordArray.length; j++) {
            let currKeyword = keywordArray[j];
            if(currKeyword.indexOf('.') > -1)
                currKeyword = currKeyword.replace(/\./g, '');
            if(currKeyword.indexOf('\'') > -1)
                currKeyword = currKeyword.replace(/'/g, '');
            if(stopWords.indexOf(currKeyword) > -1)
                continue;
            if(caseInsensitiveResultCount[i] && caseInsensitiveResultCount[i] > currKeyword.length)
                continue;
            const matchIndex = caseInsensitiveResult.toLowerCase().indexOf(currKeyword);
            if(matchIndex > -1){
                caseInsensitiveResultArray[i] = caseInsensitiveResult.substr(0, matchIndex) +
                    '<b class="mainTuple">' + caseInsensitiveResult.substr(matchIndex, currKeyword.length) + '</b>';
                if(matchIndex + currKeyword.length <= caseInsensitiveResult.length - 1){
                    caseInsensitiveResultArray[i] +=  caseInsensitiveResult.substr(matchIndex + currKeyword.length);
                }
                caseInsensitiveResultCount[i] = currKeyword.length;
            }else if(caseInsensitiveResult.indexOf('.') > -1 || caseInsensitiveResult.indexOf('\'') > -1){
                let punLessResult = caseInsensitiveResult.toLowerCase();
                if(punLessResult.indexOf('.') > -1)
                    punLessResult = punLessResult.replace(/\./g, '');
                if(punLessResult.indexOf('\'') > -1)
                    punLessResult = punLessResult.replace(/'/g, '');
                const matchIndexNew = punLessResult.indexOf(currKeyword);
                if(matchIndexNew > -1){
                    caseInsensitiveResultArray[i] = getHighlightedString(caseInsensitiveResult, currKeyword);
                    caseInsensitiveResultCount[i] = currKeyword.length;
                }
            }
            /* while ((index = caseInsensitiveResult.indexOf(currKeyword, startIndex)) > -1) {
                 newString += result.substr(startIndex, (index - startIndex)) + '<b class="mainTuple">' + result.substr(index, keywordLen) + '</b>';
                 startIndex = index + keywordLen;
             }*/
        }
    }
    for(let i = 0; i < caseInsensitiveResultArray.length - 1; i++){
        newString += caseInsensitiveResultArray[i] + ' ';
    }
    newString += caseInsensitiveResultArray[caseInsensitiveResultArray.length - 1];
    return createHTMLObj(newString);
}

export function createHTMLObj(html) { return {__html: html}; }
function getHighlightedString(suggestorString, keyword) {
    let start = -1, end = -1, notFound = false;
    const lowerCaseSuggestorString = suggestorString.toLowerCase();
    const suggestorLen = lowerCaseSuggestorString.length;
    const keywordLen = keyword.length;
    for(let i = 0; i <= suggestorLen - keywordLen; i++){
        if(lowerCaseSuggestorString[i] === '.' || lowerCaseSuggestorString[i] === '\'')
            continue;
        let index = i, j;
        for(j = 0; j < keywordLen; j++){
            if(index >= suggestorLen){
                notFound = true;
                break;
            }
            if(lowerCaseSuggestorString[index] !== keyword[j]) {
                break;
            }
            index++;
            while(lowerCaseSuggestorString[index] === '.' || lowerCaseSuggestorString[index] === '\''){
                index++;
            }
        }
        if(j === keywordLen){
            start = i;
            end = index;
            break;
        }
        if(notFound){
            break;
        }
    }
    if(start === -1 || end === -1){
        return suggestorString;
    }
    let updatedString = suggestorString.substring(0, start) +
    '<b class="mainTuple">' + suggestorString.substring(start, end) + '</b>';
    if(end < suggestorLen)
        updatedString += suggestorString.substring(end, suggestorLen);
    return updatedString;
}

export function reformatLoadMoreCourses(loadMoreCourses){
    let formattedLoadMoreCourses = {};
    for (let index in loadMoreCourses){
        if(!loadMoreCourses.hasOwnProperty(index))
            continue;
        formattedLoadMoreCourses[loadMoreCourses[index].instituteId] = loadMoreCourses[index].courseIds;
    }
    return formattedLoadMoreCourses;
}