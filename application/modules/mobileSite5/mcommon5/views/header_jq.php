<!DOCTYPE html>
<html>
        <!-- START HEADER TAG -->
        <head>
                <script type="text/javascript">
                        var t_pagestart=new Date().getTime();
                        var mobilePageName = "<?php echo $mobilePageName;?>";
                </script>
                
                
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php if ($m_meta_title) { echo $m_meta_title; } else { echo "Higher Education in India | Shiksha.com"; } ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<base href="<?php echo getCurrentPageURLWithoutQueryParams(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name = "format-detection" content = "telephone=no" />
<meta name = "format-detection" content = "address=no" />
<meta name="description" content="<?php  if ($m_meta_description) { echo htmlentities($m_meta_description); } else
{ echo "Explore thousands of colleges and courses on India's leading higher education portal - Shiksha.com. See details like fees, admission process, reviews and much more."; } ?>"/>

<meta name="copyright" content="Shiksha.com" />
<meta name="resource-type" content="document" />
<meta name="pragma" content="no-cache" />
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320"/>
<meta http-equiv="cleartype" content="on">
<meta http-equiv="x-dns-prefetch-control" content="off">


<link rel="publisher" href="https://plus.google.com/+shiksha"/>



        <!--<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("mcommon",'nationalMobile'); ?>" >-->
        <?php if($boomr_pageid != 'searchV2' && $boomr_pageid!='mobilesite_LDP' && !$removeJquery) { ?>
        <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("mobile5",'nationalMobile'); ?>" >
        <?php } ?>

<?php if(isset($css) && is_array($css)) {
        foreach($css as $cssFile) { ?>
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
    <?php }
}

if(isset($mobilecss) && is_array($mobilecss)) {
    foreach($mobilecss as $cssFile) { ?>
        <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion($cssFile,'nationalMobile'); ?>" >
    <?php }
} ?>
<!-- External CSS files end -->

<!-- Load JS files -->
<script src="//<?php echo JSURL; ?>/public/js/jquery-2.1.4.min.js"></script>

<?php if(isset($changeJQueryRef) && $changeJQueryRef=='true'){ ?>
        <script>$j = $.noConflict();</script>
<?php } ?>


    <script async src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("main_jq","nationalMobile"); ?>"></script>

    <?php if(isset($js) && is_array($js)) {
            foreach($js as $jsFile) { ?>
                    <script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
        <?php }
    } ?>
    
    <?php if(isset($jsMobile) && is_array($jsMobile)) {
        foreach($jsMobile as $jsFile) { ?>
                    <script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion($jsFile,'nationalMobile'); ?>"></script>
            <?php }
    } ?>


                <?php
                        //JSB9 Tracking
                        echo getHeadTrackJs();
                ?>
                <script type="text/javascript">
                        var t_headend = new Date().getTime();
                </script>

        </head>
        <!-- END HEADER TAG -->

        <body id="<?php echo  $noHeader ? 'noHeader' : '';?>">

<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1) {
    loadBeaconTracker($beaconTrackData);
}
?>
</div>

<script type="text/javascript">
        var t_jsstart = new Date().getTime();
        COOKIEDOMAIN = '<?=COOKIEDOMAIN?>';
        <?php
                if(is_array($userStatus) && ($userStatus != 'false')) {
                    $logged_in_user_array = $userStatus;
                } else {
                    $logged_in_user_array = $this->data['m_loggedin_userDetail'];
                }

                if (!is_array($logged_in_user_array) && $logged_in_user_array == 'false') {
                        $logged_in_user_array = array();
                } else {
                        $logged_in_user_array = $logged_in_user_array[0];
                }

                global $user_logged_in, $logged_in_userid, $shiksha_site_current_url, $shiksha_site_current_refferal, $logged_in_usermobile, $logged_in_user_name, $logged_in_first_name, $logged_in_last_name, $logged_in_user_email, $logged_in_user_city, $logged_in_user_graduation_year, $logged_in_user_xii_year, $logged_in_user_avtar_url;
                $logged_in_userid                       = (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];
                $user_logged_in                         = (!isset($logged_in_user_array['userid'])) ? 'false' : 'true';
                $logged_in_usermobile           = (!isset($logged_in_user_array['mobile'])) ? '-1' : $logged_in_user_array['mobile'];
                $logged_in_user_name            = (!isset($logged_in_user_array['displayname'])) ? 'empty' : $logged_in_user_array['displayname'];
                $logged_in_user_email           = (!isset($logged_in_user_array['cookiestr'])) ? 'empty' : $logged_in_user_array['cookiestr'];
                $values                                         = explode("|",$logged_in_user_email);
                $logged_in_user_email           = $values[0];
                $logged_in_first_name           = (!isset($logged_in_user_array['firstname'])) ? 'empty' : $logged_in_user_array['firstname'];
                $logged_in_last_name        = (!isset($logged_in_user_array['lastname'])) ? 'empty' : $logged_in_user_array['lastname'];
                $logged_in_user_city        = (!isset($logged_in_user_array['city'])) ? 'empty' : $logged_in_user_array['city'];
                $shiksha_site_current_url       = current_url();
                if($_SERVER['HTTP_REFERER']) {
                        $shiksha_site_current_refferal =  htmlentities(strip_tags($_SERVER['HTTP_REFERER']));
                } else {
                        $shiksha_site_current_refferal = "www.shiksha.com";
                }
                $encoded_current_url                    = url_base64_encode($shiksha_site_current_url);
                $encoded_current_refferal               = url_base64_encode($shiksha_site_current_refferal);
                $logged_in_user_avtar_url           = (empty($logged_in_user_array['avtarurl'])) ? 'empty' : addingDomainNameToUrl(array('url'=>$logged_in_user_array['avtarurl'],'domainName'=>MEDIA_SERVER));
        ?>

        base_url="<?php echo SHIKSHA_HOME;?>";
        shiksha_site_current_url="<?php echo $shiksha_site_current_url; ?>";
        shiksha_site_current_refferal="<?php echo $shiksha_site_current_refferal; ?>";
        logged_in_user_first_name="<?php echo addslashes($logged_in_first_name); ?>";
        logged_in_user_last_name="<?php echo addslashes($logged_in_last_name) ;?>";
        logged_in_user_name="<?php echo addslashes($logged_in_user_name);?>";
        is_user_logged_in="<?php echo $user_logged_in;?>";
        logged_in_userid="<?php echo $logged_in_userid;?>";
        logged_in_mobile="<?php echo $logged_in_usermobile;?>";
        logged_in_email="<?php echo $logged_in_user_email;?>";
        logged_in_user_city="<?php echo $logged_in_user_city;?>";
        logged_in_user_graduation_year="<?php echo $logged_in_user_graduation_year;?>";
        logged_in_user_xii_year="<?php echo $logged_in_user_xii_year;?>";
        logged_in_user_avtar_url="<?=$logged_in_user_avtar_url;?>";

        <?php
        deleteTempUserData('flag_google_adservices');
        // set these variable to make common autosuggestor function in main.js
        if((isset($searchPage) && $searchPage!='') || (isset($collegeReviewPage) && $collegeReviewPage!='') || MOBILE_SEARCH_V2_INTEGRATION_FLAG == 1){ ?>
                var mobileSearch = 'true', fromSearchPage = '<?php if(isset($fromSearchPage)){echo $fromSearchPage;}?>', isPopulate = '<?php if(isset($isPopulate)){echo $isPopulate;}?>', searchFrom = '<?php if(isset($searchFrom)){echo $searchFrom;}?>', totalResult= '<?php if(isset($totalResult)){echo $totalResult;}?>', schemaName = '<?php if(isset($schemaName)){echo $schemaName;}?>', inputKeyId = '<?php if(isset($inputKeyId)){ echo $inputKeyId;}?>', container  = '<?php if(isset($container)){ echo $container;}?>', SEARCH_PAGE_URL_PREFIX = '<?php echo SEARCH_PAGE_URL_PREFIX; ?>';
        <?php } ?>
        var boomerPageName = '<?php echo strtoupper($boomr_pageid);?>';
</script>

