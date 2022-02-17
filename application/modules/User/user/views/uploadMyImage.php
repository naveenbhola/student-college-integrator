<div id="updateMyImageOverlay" style="display:none;">
		<form enctype="multipart/form-data" onsubmit="if(validateUploadImage() === false){ return false; } else { AIM.submit(this, {'onStart' : startCallback, 'onComplete' : updateMyImage}); }"  action="<?php if(!(isset($vcard)))echo site_url().'user/MyShiksha/uploadFile/0/1/'.$isPic;else echo site_url().'/user/MyShiksha/uploadFile/1/1/'.$isPic;?>" method="post" name="update_form">
		
		<div class="lineSpace_5">&nbsp;</div>
		<div class="row">
			<div>
				<span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio" name = "changeavtar" id = "upload" value = "upload" onClick = "opendiv(this.value)"/>
                    <label class="prf-radio" for="upload"> <i class="icons ic_radiodisable1" style="top:11px;"></i>Upload Photo</label>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio" name = "changeavtar" id = "profilepic" value = "profilepic" onClick = "opendiv(this.value)"/>
                    <label class="prf-radio" for="profilepic"> <i class="icons ic_radiodisable1" style="top:11px;"></i>Choose Avatar</label>
                </span>




				<!-- <input type="radio" name = "changeavtar" id = "upload" value = "upload" onClick = "opendiv(this.value)"/>Upload Photo <span style="margin-left:36px"></span>
				<input type="radio" name = "changeavtar" id = "profilepic" value = "profilepic" onClick = "opendiv(this.value)"/>Choose Avatar <span style="margin-left:36px"></span> -->
			</div>
			
		</div>
		<br clear="left" />
		<div class="row" id = "selectpic" style = "display:none">
			<div class="normaltxt_11p_blk bld"></div>
			<div style="margin-left:16px">
				<span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio" name = "changeavtar1" id = "profilepic1" value = "/public/images/girlav1.gif" checked />
                    <label class="prf-radio" for="profilepic1"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif1"></em></label>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio" name = "changeavtar1" id = "profilepic2" value = "/public/images/girlav2.gif" />
                    <label class="prf-radio" for="profilepic2"> <i class="icons ic_radiodisable1" style="top:11px;"></i> <em class="gif-sprite img-gif2"></em></label>
                </span>
				<span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio"name = "changeavtar1" id = "profilepic4" value = "/public/images/girlav4.gif" />
                    <label class="prf-radio" for="profilepic4"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif3"></em>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio"name = "changeavtar1" id = "profilepic5" value = "/public/images/girlav5.gif" />
                    <label class="prf-radio" for="profilepic5"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif4"></em>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio"name = "changeavtar1" id = "profilepic6" value = "/public/images/girlav6.gif" />
                    <label class="prf-radio" for="profilepic6"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif5"></em>
                </span>
				
				<span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio"name = "changeavtar1" id = "profilepic7" value = "/public/images/men3.gif"/>
                    <label class="prf-radio" for="profilepic7"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif6"></em>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio"name = "changeavtar1" id = "profilepic8" value = "/public/images/men6.gif"/>
                    <label class="prf-radio" for="profilepic8"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif7"></em>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio"name = "changeavtar1" id = "profilepic9" value = "/public/images/men1.gif"/>
                    <label class="prf-radio" for="profilepic9"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif8"></em>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio" name = "changeavtar1" id = "profilepic10" value = "/public/images/men4.gif"/>
                    <label class="prf-radio" for="profilepic10"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif9"></em>
                </span>
                <span class="prf-r" style="position:relative">
                    <input type="radio" class="prf-inputRadio" name = "changeavtar1" id = "profilepic11" value = "/public/images/men5.gif"/>
                    <label class="prf-radio" for="profilepic11"> <i class="icons ic_radiodisable1" style="top:11px;"></i><em class="gif-sprite img-gif10"></em>
                </span>
                <div class="lineSpace_5">&nbsp;</div>







				<!-- <input type="radio" name = "changeavtar1" id = "profilepic7" value = "/public/images/men3.gif"/>
				<img src = "/public/images/men3.gif">
				<input type="radio" name = "changeavtar1" id = "profilepic8" value = "/public/images/men6.gif"/>
				<img src = "/public/images/men6.gif">
				<input type="radio" name = "changeavtar1" id = "profilepic9" value = "/public/images/men1.gif"/>
				<img src = "/public/images/men1.gif">
				<input type="radio" name = "changeavtar1" id = "profilepic10" value = "/public/images/men4.gif"/>
				<img src = "/public/images/men4.gif">
				<input type="radio" name = "changeavtar1" id = "profilepic11" value = "/public/images/men5.gif"/>
				<img src = "/public/images/men5.gif">
				<div class="lineSpace_5">&nbsp;</div> -->
			</div>
		</div>
		
		<div>
				<div class="row" id = "uploadpic" style = "display:none">
					<div class="normaltxt_11p_blk bld lineSpace_20 float_L"></div>
					<div><input type="file" name="myImage[]" id="myImage"/></div>
					<br clear="left" />
					<div id="error_myImage" class="normaltxt_11p_blk" style = "display:none;color:red;margin-left:34px"></div>
				</div>
				<div class="lineSpace_1">&nbsp;</div>
				<div id="myImageSelection_error" class="normaltxt_11p_blk errorMsg" style = "display:none;"></div>
				<div class="lineSpace_8">&nbsp;</div>
				<div class="row" style="">
					<div class="buttr3">
						<button class="btn_orngT1 w3" value="" type="submit" >
							<div>Save</div>
						</button>
					</div>
					<div class="buttr3">
						<button class="btn-grey w3" value="" type="button" onClick="hideOverlay();">
							<div>Cancel</div>
						</button>
					</div>
					<br clear="left" />
				</div>
		</div>
		<span id="nr" style="display:inline"></span>	
		</form>
		<div class="lineSpace_10">&nbsp;</div>
</div>
