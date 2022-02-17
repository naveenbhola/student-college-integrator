<script>
/**
 * Method loads all required scripts for Unified Registration
 * callback function is loadRequiredDataForUnifiedRegistrationProcess
 * callback API will be alwys called either file is already loaded or not
 */
function initForUnifiedRegistration()
{       
     LazyLoad.loadOnce([      
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>'
        ],loadRequiredDataForUnifiedRegistrationProcess,null,null,true);
} 

/*	
    Function which put values into global vars. like is_ldb user or cross btn click
*/

function loadRequiredDataForUnifiedRegistrationProcess()
{
     	/* ajax to set if user register or not START */
	<?php
	/*
	 * TODO: This is temp FIX !!! Will remove following lines once get update static pages for 404,505 etc.
	 * NO NEED TO EXCUTE ISLDB AJAX CALL IN 404 ERROR PAGE
	 * 
	 */
	if ((!isset($errorPageFlag)) && ($errorPageFlag != 'true')) {
	?>
	//checkLdbUser();
	<?php
	}
        if(isset($_REQUEST['apply'])){
        ?>
            ApplyNowFromCategory();
        <?php
        }
        ?>
        /* ajax to set if user register or not END */
        /* set variable to check whether user has clicked unified overlay or not*/
        unified_form_overlay1_cancel_clicked = getCookie('is_unified_overlay1_clicked');
        unified_form_overlay2_cancel_clicked = getCookie('is_unified_overlay2_clicked');
        unified_form_overlay3_cancel_clicked = getCookie('is_unified_overlay3_clicked');
        /* set Form submit url for diff types of overlays */
        if(typeof(arr_unified) !== 'undefined') {
        	ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
        }
         if($('homepagePromotionCarousel')) {
         	//if($('questionText')) { $('questionText').value = 'Make an informed career choice, ask the expert now!';}
         }
         // showResetPasswordLayer();
}
/* UNIFIED REGISTRATION APIs END */
</script>