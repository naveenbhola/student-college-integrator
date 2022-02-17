<script language="JavaScript">
/*
 * This function is required. It processes the google_ads
 JavaScript object,
 * which contains AFS ads relevant to the user's search query. The
 name of
 * this function <i>must</i> be <b>google_afs_request_done</b>. If this
 * function is not named correctly, your page will not display AFS ads.
 */
function google_afs_request_done(google_ads) {
    /*
     * Verify that there are actually ads to display.
     */
    var google_num_ads = google_ads.length;
    if (google_num_ads <= 0)
        return;
    var wideAds = ""; //wide ad unit html text
    var narrowAds = ""; //narrow ad unit html text
    for(i = 0; i < google_num_ads; i++){
    	var border = 'border-bottom: 1px dotted rgb(242, 242, 242);';
    	border = '';
        if (google_ads[i].type=="text/wide"){
            //** render a wide ad
            if(wideAds != '') {
            	border = 'border-top: 1px dotted rgb(242, 242, 242);';
            }
            wideAds+='<div style="'+ border +' margin: 5px;padding-top:5px;"><a target="_blank" href="' + google_ads[i].url + '" ' +
                'onmouseout="window.status=\'\';return true" ' +
                'onmouseover="window.status=\'go to ' +
                google_ads[i].visible_url + '\';return true" ' +
                'style="text-decoration:none;">' +
                '<span style="text-decoration: underline;font-family: Arial,Helvetica,sans-serif; font-size: 11px;">' +
                '' + google_ads[i].line1 + '</span><div style="line-height:5px;">&nbsp;</div><div style="color:#000;">' +
                google_ads[i].line2 + '</div><div style="line-height:5px;">&nbsp;</div>' +
                '<span style="color:#008000">' +
                google_ads[i].visible_url + '</span></a><br/><br/></div>';
        } else {
       		if(narrowAds != '') {
            	border = 'border-top: 1px dotted rgb(242, 242, 242);';
            }
            //render a narrow ad
            narrowAds+='<div style="'+ border +' margin:5px;padding-top:5px;"> <a target="_blank" href="' + google_ads[i].url +
                '" ' +
                'onmouseout="window.status=\'\';return true" ' +
                'onmouseover="window.status=\'go to ' +
                google_ads[i].visible_url + '\';return true" ' +
                'style="text-decoration:none;padding-top:5px;">' +
                '<span style="text-decoration: underline;font-family: Arial,Helvetica,sans-serif; font-size: 11px;overflow:none">' +
                '' + google_ads[i].line1.replace(/[^\s]{18,}?/g,'$&<wbr/>')+ '</span><div style="line-height:5px;">&nbsp;</div>' +
                '<span style="color:#000000">' +
                google_ads[i].line2.replace(/[^\s]{13,}?/g,'$&<wbr/>') + '&nbsp;' +
                google_ads[i].line3.replace(/[^\s]{18,}?/g,'$&<wbr/>') + '</span><div style="line-height:5px;">&nbsp;</div>' +
                '<span style="color:#008000">' +
                google_ads[i].visible_url.replace(/[^\s]{18,}?/g,'$&<wbr/>') + '</span></a><br/><br/></div>';
        }
    }
    if (narrowAds != "") {
        narrowAds = '<div style="border: 1px solid rgb(242, 242, 242); overflow: hidden;">'+narrowAds+'</div><div align="right"><a href="http://www.google.com/ads_by_google.html" target="_blank" style="font-family:Arial;color:#B5B5B5;font-size:11px">Sponsored Links</a></div>';
    }
    if (wideAds != "") {
        wideAds = '<div style="border: 1px solid rgb(242, 242, 242); overflow: hidden; ">'+wideAds+'</div><div align="right" style="padding-right:30px"><a href="http://www.google.com/ads_by_google.html" target="_blank" style="font-family:Arial;color:#B5B5B5;font-size:11px">Sponsored Links</a></div>';
    }
    //** Write HTML for wide and narrow ads to the proper <div> elements
	if(document.getElementById("wide_ad_unit")){
		document.getElementById("wide_ad_unit").innerHTML = '<div>'+wideAds+ '</div>';	
	}
	if(document.getElementById("narrow_ad_unit")){
		document.getElementById("narrow_ad_unit").innerHTML = narrowAds;	
	}
}
	google_afs_query = '<?php echo str_replace("\n","",$keyword); ?>';//GetParam('q');
	google_afs_client = 'shiksha-js'; // substitute your client ID
	google_afs_channel = '<?php echo (isset($channelId) ? $channelId : 'MAIN_SEARCH_PAGE'); ?>';
	/*// enter your
	comma-separated
	channel IDs
	*/
  <?php 
    if(!isset($channelId) || $channelId == 'MAIN_SEARCH_PAGE') {
        $google_afs_ad = 'w5n5';
        $google_afs_adpage = 1;    
    } else {
        $google_afs_ad = 'w5';
        $google_afs_adpage = 1;
    }
    $google_context[] = isset($urlparams['course_type']) && !empty($urlparams['course_type']) ? $urlparams['course_type']  : '';;
    $google_context[] = isset($urlparams['course_level']) && !empty($urlparams['course_level']) ? $urlparams['course_level'] : '';
    $google_context = trim(implode('|', $google_context),'|');

  ?>
    google_afs_adpage 		= '<?php echo $google_afs_adpage; ?>';
    google_afs_ad 			= '<?php echo $google_afs_ad; ?>'; // specify the number of ads you are requesting
	google_afs_ie 			= 'utf8'; // select input encoding scheme
	google_afs_oe 			= 'utf8'; // select output encoding scheme
	google_afs_adsafe 		= 'high'; // specify level for filtering non-family-safe ads
	google_afs_adtest 		= 'off'; // ** set parameter to off before launch to production
	google_afs_qry_ctxt 	='<?php echo $google_context; ?>';
	google_afs_qry_lnk 		= '<?php echo $urlparams['search_type']; ?>';
	// google_afs_hl = 'en'; // enter your interface language if not English
</script>
<!--
/*
 * The JavaScript returned from the following page uses
 * the parameter values assigned above to populate an array
 * of ad objects. Once that array has been populated,
 * the JavaScript will call the google_afs_request_done
 * function to display the ads.
 */
-->
<script language="JavaScript" src="http://www.google.com/afsonline/show_afs_ads.js"></script>
