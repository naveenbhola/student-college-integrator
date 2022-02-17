export const getRankingPageParamStr = (url, queryObj) => {
  let finalParams = {}, finalParamStr = '';
  let urlParts = url.split('/');
  let rankingPageUrlIdentifier = urlParts[urlParts.length-1].replace(/^[- ]+|[- ]+$/g, '');
  let rankingPageUrlParams = rankingPageUrlIdentifier.split('-');
  if(rankingPageUrlParams.length >= 5){
    if(
      Number.isInteger(parseInt(rankingPageUrlParams[0])) &&
      Number.isInteger(parseInt(rankingPageUrlParams[1])) &&
      Number.isInteger(parseInt(rankingPageUrlParams[2])) &&
      Number.isInteger(parseInt(rankingPageUrlParams[3])) &&
      Number.isInteger(parseInt(rankingPageUrlParams[4]))
    ){
      finalParams = {
        "rpid"        : parseInt(rankingPageUrlParams[0]),
        "country"     : parseInt(rankingPageUrlParams[1]),
        "state"       : parseInt(rankingPageUrlParams[2]),
        "city"        : parseInt(rankingPageUrlParams[3]),
        "exam"        : parseInt(rankingPageUrlParams[4])
      };
      if(queryObj != undefined && queryObj.pageNo != undefined && queryObj.pageNo >= 1){
        finalParams.pn = parseInt(queryObj.pageNo);
      }
      if(queryObj != undefined && queryObj.source != undefined && queryObj.source >= 1){
        finalParams.source = parseInt(queryObj.source);
      }
      finalParamStr = Buffer.from(JSON.stringify(finalParams)).toString('base64');
    }
  }
  return finalParamStr;
}
