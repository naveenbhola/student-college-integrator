<!-- <script src="//<?php echo JSURL; ?>/public/js/jquery-2.1.4.min.js"></script> -->
<?php if(isset($jqueryUIRequired) && $jqueryUIRequired == TRUE){ ?>
        <script src="//<?php echo JSURL; ?>/public/js/jquery-ui-1.11.4.min.js"></script>
<?php }?>

 <!-- Needed in jquery.mobile-1.4.5.min.js -->
        <?php if(isset($is_profile_page) && $is_profile_page == true) { ?>
                <script>
                        $(document).on('mobileinit', function () {
                            $.mobile.ignoreContentEnabled = true; //Disable the Auto styling by JQuery Mobile CSS
                        $.mobile.pushStateEnabled = false;  // Disable the AJAX Navigation across pages
                        $.mobile.defaultHomeScroll = 0;
                        $.event.special.swipe.horizontalDistanceThreshold = (window.devicePixelRatio >= 2) ? 15 : 30;
                        });
                </script>
        <?php }else{ ?>
                <script>
            $(document).on('mobileinit', function () {
                            $.mobile.ignoreContentEnabled = true; //Disable the Auto styling by JQuery Mobile CSS
                            $.mobile.pushStateEnabled = false;  // Disable the AJAX Navigation across pages
                        $.mobile.ajaxEnabled = false; // Disable the AJAX Navigation across pages
                            $.mobile.defaultHomeScroll = 0;
                            $.event.special.swipe.horizontalDistanceThreshold = (window.devicePixelRatio >= 2) ? 15 : 30;
                        });
        </script>
    <?php } 
$reset_password = trim(strip_tags($_REQUEST['resetpwd']));
if($reset_password == 1) {
    $reset_password_token = trim(strip_tags($_REQUEST['uname']));
    $reset_usremail = trim(strip_tags($_REQUEST['usremail']));                          
}
    ?>
<!-- <script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("main","nationalMobile"); ?>"></script> -->
<script>
navigatorAppVersion()
var homepageWidgets = '';
var resetpswd = '<?php echo $_GET["resetpwd"]; ?>';
var usergroup = '<?php echo $_GET["usrgrp"]; ?>';

function homepageLazyLoadCallBack(){
    if(resetpswd){
        var mcommonjs = '<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("mcommon","nationalMobile"); ?>" >';
        $(mcommonjs).insertBefore($('#firstFoldCss'));
        registrationForm.showResetPasswordLayer('<?php echo $_GET["uname"]; ?>', '<?php echo $_GET["usremail"]; ?>','',usergroup);
    }
}

$(function(){
    retainHomePageTab('<?php echo $tabSelected;?>');
});

$(window).load(function(){
	$('#backgroundLayer').hide();
    var tabSelected = (getCookie('hpTab')) ? getCookie('hpTab') : '<?php echo $tabSelected;?>';
    var dataObj = {'tabSelected':tabSelected,'remainingTabs':JSON.parse('<?php echo json_encode($remainingTabs); ?>'),'hierarchyMap':JSON.parse('<?php echo json_encode($hierarchyMap); ?>'),'categoryMap':JSON.parse('<?php echo json_encode($categoryMap); ?>'),'resetPage':'<?php echo $resetPage;?>','layerPageId':'newHomepageToolLayer','activeLayer':'','userCategory':'<?php echo $userPrefData[0]['categoryId']; ?>','userSubcat':'<?php echo $userPrefData[0]['subCatId']; ?>'};
    window.dataObj = dataObj;
	homepageWidgets = new homepageWidgetsClass(dataObj);
	homepageWidgets.homepageOnLoadCalls(homepageWidgets);
});
</script>