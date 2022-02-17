import renderer from '../../utils/renderer';
import {getHeaders} from './../nodeHelper';
import config from './../../../config/config';
import {postRequest} from './../../utils/ApiCalls';
import {fetchRankingPageData} from './../../modules/ranking/actions/RankingPageAction';
import {getRankingPageParamStr} from './../../modules/ranking/utils/rankingUtil';

module.exports = (req, res, store, extraParams = {}) => {
  let template = extraParams.template;
  let finalParamStr = getRankingPageParamStr(req.path, req.query);
  if(finalParamStr != ''){
    let headerConfig = getHeaders(req);
    let isServerCall = config().API_SERVER_CALL;
    const rankingPageData = store.dispatch(fetchRankingPageData(finalParamStr, headerConfig, isServerCall));
    Promise.resolve(rankingPageData).then( (response) => {
      if(typeof req.query.pageNo != 'undefined' && (req.query.pageNo < 1 || req.query.pageNo > Math.ceil(store.getState().rankingPageData.numberOfResultsFound/20))){
        res.redirect(301, req.path);
        return;
      }else if(!store.getState().rankingPageData || (store.getState().rankingPageData && store.getState().rankingPageData.show404 == true)){
  			res.render(template, renderer(req, store, '404Page'));
  		}else if(store.getState().rankingPageData && store.getState().rankingPageData.seoData.canonicalUrl !=  '' && req.path != store.getState().rankingPageData.seoData.canonicalUrl){
  			res.redirect(301, store.getState().rankingPageData.seoData.canonicalUrl);
        return;
  		}else if(store.getState().rankingPageData && store.getState().rankingPageData.redirectUrl !=  null){
        res.redirect(301, store.getState().rankingPageData.redirectUrl);
        return;
      }else{
        res.render(template, renderer(req, store, 'rankingPage'));
  		}
      let endserverTime = Date.now();
  		if(endserverTime-extraParams.serverStartTime>500){
        let postData = 'serverStartTime='+extraParams.serverStartTime+'&endserverTime='+endserverTime+'&requestedUrl='+req.url+'&module=ranking&teamname=listings';
  			postRequest(config().SHIKSHA_HOME + extraParams.logPerformanceUrl, postData).then((res)=>{}).catch((e)=>{});
  		}
    }).catch(function(err){
      res.render(template, renderer(req, store, 'ErrPage'));
    });
  }else{
    res.render(template, renderer(req, store, '404Page'));
  }
};
