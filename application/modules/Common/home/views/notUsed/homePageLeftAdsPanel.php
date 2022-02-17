<style>
.shImg1{background:url(/public/images/homeShikImg_1.jpg) no-repeat}
</style>
<?php
$headerTextForAds = 'Why join Shiksha.com?';
$registerButtonDisplay = '';
if(is_array($validateuser)) {
    $headerTextForAds = 'Benefits of joining Shiksha.com';
    $registerButtonDisplay = 'display:none';
}
?>
                                <div style="border:3px solid #b8deff">
                                    <div class="homeShik_spiritAll homeShik_boxtitle"><b><?php echo $headerTextForAds; ?></b></div>
                                    <div class="homeShik_spiritAll homeshik_ImageAll">
                                    	<div style="margin:0 5px">
                                        	<div class="Fnt14" style="padding-top:2px"><b><span id="showCaseHeading">Choose from multiple courses</span></b> <input type="button" value="Register Free" class="RegisterBtn bld Fnt12" style="margin-left:-2px;<?php echo $registerButtonDisplay; ?>" onclick="trackEventByGA('RegisterClick','HOMEPAGE_LEFT_PANEL_REGISTRATION_BTN');window.location='<?php echo SHIKSHA_HOME; ?>/user/Userregistration/index/'" /></div>
                                            <div style="color:#505050;padding:2px 0 3px 0" id="showCaseSubHeading">Explore 92,000 courses across 25,000 institutes</div>
                                            <div style="width:100%">
                                            	<div class="homeShik_ImageBorder">
                                                	<div>
                                                		<div id="showCaseImg" name="showCaseImg" class='shImg1' style="height:147px;width:338px;">&nbsp;</div>
                                                        <div class="posRelative" style="top:-13px">
                                                            <div style="margin-left:5px">
                                                                <div style="width:100%" id="shik_HomeJSRotationBox">
                                                                    <div style="width:111px" class="imagOpen_1"><span onclick="stopRotation(0);">&nbsp;</span></div>
                                                                    <div style="width:111px" class="imag_2"><span onclick="stopRotation(1);">&nbsp;</span></div>
                                                                    <div style="width:111px" class="imag_3"><span onclick="stopRotation(2);">&nbsp;</span></div>
                                                                    <div class="clear_L">&nbsp;</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
