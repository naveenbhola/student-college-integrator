
import {dfpConfig} from '../../common/config/dfpBannerConfig';
import {getObjectSize} from './../../../utils/commonHelper';

function initializeBanner()
{
    if(typeof window != 'undefined' && typeof window.lazyLoadJs == 'function') {
        lazyLoadJs(document,"script",['https://www.googletagservices.com/tag/js/gpt.js']);
    }
    window.slots = [];
    window.googletag = window.googletag || {};
    window.googletag.cmd = window.googletag.cmd || [];
}

function pushBannerToGoogleDFP(dfpParams)
{
    const stickyBannerExcludeList = ['DFP_CategoryPage', 'DFP_SearchPage'];
    const showStickyBanner = stickyBannerExcludeList.indexOf(dfpParams['parentPage']);
    var currentConfig = dfpConfig[dfpParams['parentPage']];
    if(typeof currentConfig != 'object' || (typeof currentConfig == 'object' && getObjectSize(currentConfig) == 0)){
        currentConfig = dfpConfig['DFP_Others'];
    }
    if(typeof dfpParams == 'object' && getObjectSize(dfpParams)>0 && getObjectSize(dfpParams.addMoreDfpSlot)>0){
        currentConfig = { ...currentConfig, ... dfpParams.addMoreDfpSlot};
    }

    if(Array.isArray(slots))
    {
        if(typeof window.googletag.destroySlots == "function")
        {
            window.googletag.destroySlots(slots);
        }
        /*slots.map(function(key){
            window.googletag.pubads().clear([key]);
        });*/
    }
    window.googletag.cmd.push(function() {
    //window.googletag.pubads().enableAsyncRendering();
    googletag.pubads().collapseEmptyDivs();
    //googletag.pubads().disableInitialLoad();
    for(var i in currentConfig){
        var uniqueslot = window.googletag.defineSlot(currentConfig[i]["slotId"], [currentConfig[i]["width"],currentConfig[i]["height"]], currentConfig[i]["elementId"]).addService(window.googletag.pubads());
        slots.push(uniqueslot);
    }

    const footerDeskConfig = dfpConfig['shiksha']['footer_desktop'];
    const footerDeskslot = window.googletag.defineSlot(footerDeskConfig["slotId"], [footerDeskConfig["width"], footerDeskConfig["height"]], footerDeskConfig["elementId"]).addService(window.googletag.pubads());
    slots.push(footerDeskslot);

    var footerConfig = dfpConfig['shiksha']['footer'];
    var footerslot = window.googletag.defineSlot(footerConfig["slotId"], [footerConfig["width"], footerConfig["height"]], footerConfig["elementId"]).addService(window.googletag.pubads());
    slots.push(footerslot);

    let searchLayerConfig = dfpConfig['shiksha']['searchLayer'];
    let searchLayerslot = window.googletag.defineSlot(searchLayerConfig["slotId"], [searchLayerConfig["width"], searchLayerConfig["height"]], searchLayerConfig["elementId"]).addService(window.googletag.pubads());
    slots.push(searchLayerslot);

    if(!window.mobileApp && showStickyBanner === -1){
        let stickyBannerConfig = dfpConfig['shiksha']['sticky_banner'];
        let stickyBanner = window.googletag.defineSlot(stickyBannerConfig["slotId"], [stickyBannerConfig["width"], stickyBannerConfig["height"]], stickyBannerConfig["elementId"]).addService(window.googletag.pubads());
        slots.push(stickyBanner);
    }
    if(showStickyBanner === -1) {
        let stickyBannerDesktopConfig = dfpConfig['shiksha']['sticky_banner_desktop'];
        let stickyBannerDesktop = window.googletag.defineSlot(stickyBannerDesktopConfig["slotId"], [stickyBannerDesktopConfig["width"], stickyBannerDesktopConfig["height"]], stickyBannerDesktopConfig["elementId"]).addService(window.googletag.pubads());
        slots.push(stickyBannerDesktop);
    }

    //window.googletag.pubads().refresh([footerConfig["elementId"]]);
    //window.googletag.pubads().refresh(null, {changeCorrelator: false});
    //window.googletag.pubads().collapseEmptyDivs(true);
    //window.googletag.pubads().clearTargeting();
    for(var i in dfpParams)
    {   
        if(!dfpParams[i] || i == 'addMoreDfpSlot')
                continue;

        if((typeof dfpParams[i] == 'string' || typeof dfpParams[i] == 'number') && dfpParams[i])
        {
            //stringData = "'" + stringData + "'";
            let tempArr = [];
                tempArr.push(dfpParams[i]);
            let stringData = tempArr.join();
            window.googletag.pubads().setTargeting(i, stringData);
        }else if(typeof dfpParams[i] == 'object' && Object.keys(dfpParams[i]).length>0){
            let objArr     = Object.keys(dfpParams[i]).map((key)=>dfpParams[i][key]);
            let stringData = objArr.join();
            window.googletag.pubads().setTargeting(i,[stringData]);
        }else if(typeof dfpParams[i] == 'array' && dfpParams[i].length>0){
            let stringData = dfpParams[i].join();
            window.googletag.pubads().setTargeting(i,[stringData]);
        }
    }
    //window.googletag.pubads().enableAsyncRendering();
    //googletag.pubads().disableInitialLoad();
    window.googletag.pubads().enableLazyLoad({
      fetchMarginPercent: 100,  // Fetch slots within 5 viewports.
      renderMarginPercent: 100,  // Render slots within 2 viewports.
      mobileScaling: 1.0  // Double the above values on mobile.
    });
     //googletag.pubads().enableLazyLoad({fetchMarginPercent: -1});
    /*if(Array.isArray(slots)){
            slots.map(function(key){ window.googletag.display(key);});
    }*/
    window.googletag.enableServices();
    //window.googletag.pubads().refresh(slots);
    window.googletag.pubads().updateCorrelator();
  });
}

function displayDFPBanner(elementId)
{
    window.googletag.cmd.push(function() { window.googletag.display(elementId); });
}
export default {initializeBanner,pushBannerToGoogleDFP,displayDFPBanner};
