<style type="text/css">
    .disabled {
        pointer-events: none;
        cursor: default;
    }
    .icons {
        background-image: url("https://images.shiksha.ws/pwa/public/images/desktop/shiksha-icons-sprite-6.png");
        background-repeat: no-repeat;
        display: inline-block;
    }
</style>

<?php
global $mmpFormsForCaching;
$headerData = array();
$headerData['css'] = array('userRegistrationDesktop');
$headerData['js'] = array('header','common','userRegistrationDesktop','footer');
$this->load->view('multipleMarketingPage/newMMPHeader',$headerData);

$mmpFormId = $mmp_details['page_id'];
$destination_url = $mmp_details['destination_url'];

?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v4.2"); ?>"></script>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

<style type="text/css">
.regClose { display:none; }
</style>

<script>
$j(document).ready(function()  { 
<?php if($bg_url){ ?>

        $('background_url').height = '3500px !important';
        $('iframe_div1').remove();
        
<?php } else { ?>

        $('background_url').height = $j(document).height()-5+'px';
        $('iframe_div1').remove();
        
<?php } ?>
        
});

var destination_url = '<?php echo $destination_url; ?>';
var mmpFormsForCaching = [<?php echo implode(',',  $mmpFormsForCaching ) ?>];
$j(document).ready(function()  { 
        
    var uname = '<?php echo $this->input->get('uname');?>';
    var resetpwd = '<?php echo $this->input->get('resetpwd');?>';
    var usremail = '<?php echo $this->input->get('usremail');?>';        

    if ((uname != '') && (resetpwd != '') && (usremail != '')) {
            
        registrationForm.showResetPasswordLayer(uname,usremail);
            
    } else {

        var customFields = <?php echo json_encode($mmp_details['formCustomization']); ?>;

        var formData = {
            'trackingKeyId' : '<?php echo $trackingKeyId;?>',
            'customFields':customFields,
            'callbackFunction':'registrationFromMMPCallback',
            'submitButtonText':'<?php echo $submitButtonText;?>',
            'httpReferer':'',
            'formHelpText':'<?php echo addslashes($customHelpText);?>'
        };

        registrationForm.showRegistrationForm(formData);
       
    }

});

function registrationFromMMPCallback() {
    if(destination_url != '') {
        window.location = destination_url;
    }  else {
        window.location = JSURL;
    }
}

if(typeof(home_shiksha_url) == 'undefined'){
    var home_shiksha_url = '<?php echo SHIKSHA_HOME;?>';
}

</script>       

<iframe id="background_url" <?php echo $bg_url; ?> style="width:99%; position: absolute;  display: block; top: 0; left: 0; z-index: 1; border:none; <?php echo $bg_image; ?> "></iframe>

<iframe name="iframe_div1" id="iframe_div1" style="width: 99%; position:absolute; display: block; top: 0; left: 0;  z-index: 1000; background-color: rgba(0, 0, 0, 0.3);" scrolling="no" allowtransparency="true"></iframe>

        
<script>
var isLogged = '<?php echo $logged; ?>';        
     
// js var for google event tracking
var currentPageName = '<?php echo $pagename; ?>';
var pageTracker = null;

</script>

<div id="emptyDiv" style="display:none;">&nbsp;</div>

<script id="galleryDiv_script_validate">
        
function trackEventByGA(eventAction,eventLabel) {        
    if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
            pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
    }
    return true;
}

</script>

<?php

echo TrackingCode::SCANSmartPixel($googleRemarketingParams); 

if($load_ga) {
    
    $data_array = array('beaconTrackData'=>array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array()));
    $this->load->view('common/ga',$data_array);
    
} 

$beaconTrackData = array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array());
loadBeaconTracker($beaconTrackData);    
        
    
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');

$this->load->view('multipleMarketingPage/newMMPFooter',$headerData);
?>
        
