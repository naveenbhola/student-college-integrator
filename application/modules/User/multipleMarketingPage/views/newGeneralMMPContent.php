 
<script>
        $j(document).ready(function()  { 
                <?php if($bg_url){ ?>
                
                        $('background_url').height = '3500px !important';
                        $('iframe_div1').height = '3500px !important';
                        $j("#iframe_div1").remove();
                        
                <?php } else { ?>
                
                        $('background_url').height = $j(document).height()-5+'px';
                        $('iframe_div1').height = $j(document).height()-5+'px';
                        $j("#iframe_div1").remove();
                        
                <?php } ?>
                
        });
                
</script>       
<iframe id="background_url" <?php echo $bg_url; ?> style="width:99%; position: absolute;  display: block; top: 0; left: 0; z-index: 1; border:none; <?php echo $bg_image; ?> "></iframe>

<iframe name="iframe_div1" id="iframe_div1" style="width: 99%; position:absolute; display: block; top: 0; left: 0;  z-index: 1000; background-color: rgba(0, 0, 0, 0.3);" scrolling="no" allowtransparency="true"></iframe>

<div>        
        
        <div id="mmpOverlay" class="management-layer Overlay" style="max-height: 550px; display:none; box-shadow: 1px 1px 10px #333;-moz-box-shadow: 1px 1px 10px #333; position: fixed; overflow-y: auto; overflow-x: hidden;">
                <p>
                        <div class="float_L">
                                <img src='<?php echo $logo_src; ?>' border="0" height='50' />
                        </div>
                </p>
                
                <div id="mmpAlreadyRegLayer">
                        <?php if($logged=="No") {?>
                                <div class="flRt" style="color: #979797;position: absolute;top: 43px;right: 11px;">Already Registered?
                                        <a href="javascript:void(0);" onClick="shikshaUserRegistration.showLoginLayer(false);" style="padding-left: 4px;">Sign In</a>
                                </div>
                                <?php } else {?>
                                <div class="flRt" style="color: #979797;position: absolute;top: 43px;right: 11px;">Hi <?php if(strlen($userData[0]['displayname']) > 15) { echo substr($userData[0]['displayname'],0,12).'...'; } else { echo $userData[0]['displayname']; } ?>
                                        <a href="#" onClick="SignOutUser();" style="padding-left: 4px;">Sign Out</a>
                                </div>
                        <?php }?>
                </div>
                
                <p id="mmpFormHeading" class="top-gap">
                        <?php
                                if($mmp_details['form_heading']){
                                        echo ($mmp_details['form_heading']);
                                } else {
                                        echo "Find the best institutes for you";
                                }
                        ?>
                </p>
                <div id="mmpOverlayContent"></div>
        </div>

</div>

<div class="clearFix"></div>
        
<script>
        
        var registration_context = 'MMP';
        var user_logged_in_pref_data = "";
        var userInfo = null;
        var firstTimePageLoad = true;
        var regformid = "";
    	var user_id = '<?php echo $userid;?>';
        var mmp_form_id_on_popup = '<?php echo $pageId; ?>';
        
        $j(document).ready(function()  { 
                
                var uname = '<?php echo $this->input->get('uname');?>';
                var resetpwd = '<?php echo $this->input->get('resetpwd');?>';
                var usremail = '<?php echo $this->input->get('usremail');?>';        

                if ((uname != '') && (resetpwd != '') && (usremail != '')) {
                        
                        shikshaUserRegistration.showResetPasswordLayer(uname,usremail,'MMP');
                        
                } else {

                    if(mmp_form_id_on_popup != 'undefined' && mmp_form_id_on_popup != '') {
                        var url = "/registration/Forms/MMP/"+mmp_form_id_on_popup+"?rnd="+Math.floor((Math.random()*1000000)+1)+"&<?php echo htmlentities(strip_tags($_SERVER['QUERY_STRING']));?>";
                        new Ajax.Request(url, { method:'get', onSuccess:function (data) {
                          
                                showMMPOverlay(500,'auto','',data.responseText);
                                ajax_parseJs($('mmpOverlayContent'));
                                //evaluateCss($('mmpOverlayContent'));
                                regformid = $j('#regFormId').val();
                                
                                if (user_id != '') {                                       
                                        setTimeout(getUserDetails,500);
                                }
                        }});
                    } 
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
                        updateTinyScrollBar();
					}
			});
        }
        
        function showMMPOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent, modalLess, left, top) {
                
                if(trim(overlayContent) == '')
                        return false;
                
                var body = document.getElementsByTagName('body')[0];
                $('mmpOverlay').style.width = overlayWidth + 'px';
                
                if (overlayHeight != 'auto') {
                        $('mmpOverlay').style.height = overlayHeight + 'px';
                }
                
                $('mmpOverlayContent').innerHTML = overlayContent;
                
                var divY = parseInt(screen.height)/2;
                var divX;
                
                if(typeof left != 'undefined') {
                        divX = left;
                } else {
                        divX = (parseInt(body.offsetWidth)/2) - (overlayWidth/2);
                }
            
                if(typeof top != 'undefined') {
                        divY = top;
                } else {
                        if (overlayHeight == 'auto') {
                            overlayHeight = 300;
                        }
                        divY = parseInt(divY - parseInt(overlayHeight/2)) - 180;
                }
                
                $('mmpOverlay').style.backgroundColor = '#fff';
                $('mmpOverlay').style.border = 'none';
                
                if($('mmpOverlay').scrollHeight < body.offsetHeight) {
                        $('mmpOverlay').style.left = divX + 'px';
                        $('mmpOverlay').style.top = divY + 'px';
                } else {
                        $('mmpOverlay').style.left = divX + 'px';
                        $('mmpOverlay').style.top =  '10 px';
                        //window.scrollTo(divX,'100');
                }
                
                overlayHackLayerForIE('mmpOverlay', body);
                $('mmpOverlay').style.display = 'block';
        }
                
</script>
        
