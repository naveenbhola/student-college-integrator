
<style>
.scrollbar1 .scrollbar { visibility:visible !important; }
.layer-title .close { display: block !important; }
input.orange-button + a { display: inline !important; }
</style>

<div id="mmpOverlay" class="management-layer Overlay layer-title" style="display: block;max-height: 550px; box-shadow: 1px 1px 10px #333;-moz-box-shadow: 1px 1px 10px #333; overflow: hidden;width:490px">
    <p>
        <div class="float_L">
            <img src='/public/images/desktopLogo.png' border="0" height='50' />
        </div>
        <?php if(empty($showpopup)) { ?>
            <div style="float: right;"><a id="close" class="close" href="javascript:void(0);" onclick="registration_context = '';$('close').style.display='block';$('mmpOverlayForm').remove();$('iframe_div1').remove();$('iframe_div').remove();"></a></div>
        <?php } ?>
    </p>
                
    <div id="mmpAlreadyRegLayer">
         <?php if($isUserLoggedIn=="false") {?>
                <div class="flRt" style="color: #979797;position: absolute;top: 43px;right: 11px;">Already Registered?
                    <a href="javascript:void(0);" onClick="shikshaUserRegistration.showLoginLayer(false);" style="padding-left: 4px;">Sign In</a>
                </div>
         <?php } else {?>
                <div class="flRt" style="color: #979797;position: absolute;top: 43px;right: 11px;">Hi <?php if(strlen($displayName) > 15) { echo substr($displayName,0,12).'...'; } else { echo $displayName; } ?>
                    <a href="#" onClick="SignOutUser(window.location = window.location.href.split('?')[0]);" style="padding-left: 4px;">Sign Out</a>
                </div>
         <?php }?>
    </div>
                
    <p id="mmpFormHeading" class="top-gap"><?php echo $mmp_form_heading;?></p>
    
    <div id="mmpOverlayContent"><?php echo $formHTML;?></div>

</div>

<div class="clearFix"></div>

<script>
    var regformid = '<?php echo $regFormId;?>';
    var user_id = '<?php echo $user_id;?>';
    var registration_context = 'MMP';
    var user_logged_in_pref_data = "";
    var userInfo = null;
    var firstTimePageLoad = true;
    
    $j(document).ready(function(){
         if (user_id != '') {
           setTimeout(getUserDetails,1000);
            
        }
    });
    
     
    function getUserDetails() {

	    var ajaxCallUrl = '/user/Userregistration/getUserInfo/'+user_id;

		new Ajax.Request( ajaxCallUrl,
       {	
            method:'get',
                   
            onSuccess: 
            function(response){
                
                userInfo = eval('(' + response.responseText + ')');
                var city_field = 'residenceCity_'+regformid;
                
                if(userInfo.residenceCity>0 && typeof($(city_field)) !='undefined') {			
                    $j("#"+city_field).val(userInfo.residenceCity);
                    $j("#"+city_field).change();
                }
                            
                shikshaUserRegistrationForm[regformid].populateFormOnLoad(userInfo.desiredCourse);
                
            }
	    });
    }
</script>
