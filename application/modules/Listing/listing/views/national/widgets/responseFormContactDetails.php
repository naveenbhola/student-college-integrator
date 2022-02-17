<?php
if($validateuser != "false"){
    $userId = $validateuser[0]['userid'];
    $firstname = $validateuser[0]['firstname'];
    $lastname = $validateuser[0]['lastname'];
    $mobile = $validateuser[0]['mobile'];
    $cookiestr = $validateuser[0]['cookiestr'];
    $captcha_flag = false;
}
else
{
    $captcha_flag = true;
}
?>

<?php 	
    if($_REQUEST['city'])
    {
        $request = new CategoryPageRequest();
        $request->setData(array('cityId' => $_REQUEST['city'],
                            'localityId' => $_REQUEST['locality']
                        ));
    }
  //  $localityArray = array();
    $tempPaidCourses = array();
    $tempFreeCourses = array();
    $atleastOneCoursePaid = false;

    foreach($courses as $c)
    {
        if($c->isPaid()){
            $tempPaidCourses[] =  $c->getId();
        } else {
            $tempFreeCourses[] =  $c->getId();
        }
        $c->setCurrentLocations($request, true);
        //$localityArray[$c->getId()] = getLocationsCityWise($c->getCurrentLocations());
    }
?>

<script>
    var questionDetailPage = false;
    var userId = "<?=$userId?>";
    custom_localities = [];
    var localityArray = <?=json_encode($localityArray)?>;
    var contactDetailsPaidCourses = <?=json_encode($tempPaidCourses)?>;
    var contactDetailsFreeCourses = <?=json_encode($tempFreeCourses)?>;

    if(!questionDetailPage) {					
        $j.each(localityArray,function(index,element){
            custom_localities[index] = element;
        });
    }
    
</script>

<?php if( $source_page && $source_page == 'questionDetailPage'):?>
<script>
var questionDetailPage = true;
</script>
<input type="hidden" value="<?php echo $source_page;?>" id="question_source_page" />
<?php endif;?>
<input type="hidden" id="listingTypeInfo"></input>

    <div class="layer-title">
        <a href="#" class="flRt close" id="close" uniqueattr="ListingPage/hideLCDForm" onclick="hideContactFormOverlay(); $j('#Button_2').show(); return false;" title="Close"></a>
        <div class="title" id="contactFormRegId"><?php echo $layerTitle ? $layerTitle : "Get Contact Details on Email/SMS"; ?></div>
    </div>

    <div class="layer-contents" id="layer-contents">
        <!--<div style="float:left; font-size:14px;"><?php echo $layerHeading ? $layerHeading : "New Users, Register Free!"; ?></div>-->
        <div style="float:right;">
            <?php if($validateuser == "false"): ?>
                <a onclick="hideContactFormOverlay(); shikshaUserRegistration.showLoginLayer(); return false;" href="javascript:void(0);">Existing Users, Sign In</a>
            <?php endif; ?>
        </div>    
        <div class="clearFix"></div>

        <div id="" style="width:325px; margin-left: 15px; margin-top:15px;">
                <?php echo Modules::run('registration/Forms/LDB',NULL,'registerResponse',$formCustomData); ?>
        </div>

        <?php if($hasCallback) { ?>
            <input type="hidden" id="registerFreeHasCallback" value="<?php echo $hasCallback; ?>" />
        <?php } ?>
        

        <input type="hidden" value="<?=$institute->getId()?>" id="institute_id_<?=$widget?>">
        <input type="hidden" value="<?=html_escape($institute->getName())?>" id="institute_name_<?=$widget?>">
        <input type="hidden" value="<?php echo intval($_REQUEST['city']); ?>" id="page_selected_city_<?=$widget?>">
        <input type="hidden" value="<?php echo intval($_REQUEST['locality']); ?>" id="page_selected_locality_<?=$widget?>">

        <div style="clear:both;"></div>
         </div>
        <?php $this->load->view('registration/common/OTP/userOtpVerification'); ?>
   
