<?php
        $headerData['partnerPage'] = 'shiksha';
        $headerData['naukriAssoc'] = "false";
        $headerData['css'] = array('common_new','registration','newmmp');
        $headerData['js'] = array('header','common','user','tooltip','ajax-api','userRegistration','footer');
        $this->load->view('multipleMarketingPage/newMMPHeader',$headerData);
        $this->load->view('multipleMarketingPage/newGeneralMMPContent');
?>

        
<script>
var userCity = "";
<?php if($logged == "Yes") {?>
        userCity = "<?php echo $userData[0]['city']?>";
<?php }?>

categoryList = eval(<?php echo json_encode($allCategories);?>);
var isLogged = '<?php echo $logged; ?>';
        
</script>

<script>
        function removetip(){
                
                if (document.getElementById('helpbubble1')) {
                        document.getElementById('helpbubble1').style.display='none';
                }
                
                var other= document.getElementById('mobile').value;
                var objErr = document.getElementById('mobile_error');
                msg = validateMobileInteger(other,'mobile number',10,10,1);
                
                if(msg!==true) {
                    objErr.innerHTML = msg;
                    objErr.parentNode.style.display = 'inline';
                    return false;
                } else {
                    objErr.innerHTML = '';
                    objErr.parentNode.style.display = 'none';
                    return true;
                }
                
        }
        
        // js var for google event tracking
        var currentPageName = '<?php echo $pagename; ?>';
        var pageTracker = null;
</script>

<div id="marketingLocationLayer_ajax"></div>
<div id="marketingusersign_ajax"></div>

<div class="clear_L"></div>
<div class="lineSpace_10">&nbsp;</div>
<div id="emptyDiv" style="display:none;">&nbsp;</div>

<script id="galleryDiv_script_validate">
        
        function RenderInit() {
                addOnBlurValidate(document.getElementById('frm1'));
                addOnFocusToopTip1(document.getElementById('frm1'));
        }
        
        function OneCourseForm(){
                var selectObj = $("homesubCategories");
                var num = selectObj.options.length;
                if(num == 2){
                        selectObj.selectedIndex = 1;
                        actionDesiredCourseDD(selectObj.options[1].value);
                        $("homesubCategories").style.display = 'none';
                        var newdiv = document.createElement('div');
                        newdiv.innerHTML = selectObj.options[1].text;
                        $("subCategory").appendChild(newdiv);
                }
        }
            
        window.onload = function () {
                try{
                        OneCourseForm();
                        RenderInit();
                        publishBanners();
                        ajax_loadContent('marketingLocationLayer_ajax','/multipleMarketingPage/Marketing/ajaxform_mba/mr_page');
                } catch (e) {
                        //alert(e);
                }
        }
        
        function trackEventByGA(eventAction,eventLabel) {
                
                if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
                        pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
                }
                return true;
        }
        
</script>

<?php
        global $serverStartTime;
        $trackForPages = isset($trackForPages)?$trackForPages:false;
        $endserverTime =  microtime(true);
        $tempForTracking = ($endserverTime - $serverStartTime)*1000;
        echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');
?>
        

<?php
$this->load->view('multipleMarketingPage/newMMPFooter',$headerData);
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
        