<div id = "uploadOverlay" style = "width:300px;display:none;position:absolute;z-index:2050000;">

	<div id="shadow-container">
        <div class="shadow1">
            <div class="shadow2">
                <div class="shadow3">
                    <div class="container">
                        <div class="lineSpace_1">&nbsp;</div>
<!--<?php
echo $this->ajax->form_remote_tag(array(
                            'url' => base_url().'/network/Network/uploadpic',
                            'update' => '',
                            'success' => "javascript:uploadPic(request.responseText)",
                            'failure'=>'javascript:uploadPic(request.responseText)'
) 
                    ); 
?>-->
<!--<?php
     $attribute = array('id'=>'pic', 'name' => 'pic','method' => 'post');
     echo form_open_multipart('network/Network/uploadpic',$attribute); 
  ?>-->
<form enctype = "multipart/form-data" method = "post" onSubmit=" AIM.submit(this, {'onStart' : startCallback, 'onComplete' : showUploadResponse})" action = "<?php echo base_url().'/network/Network/uploadpic'?>">
<!--form  enctype = "multipart/form-data" action="http://localhost:80/shiksha/network/Network/uploadpic" onsubmit="new Ajax.Request('http://localhost:80/shiksha/network/Network/uploadpic',{onSuccess:function(request){javascript:uploadPicResponse(request.responseText)}, onFailure:function(request){javascript:uploadPic(request.responseText)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post">-->

<input type = "hidden" name = "institute_id" id = "institute_id" value = ""/>
<input type = "hidden" name = "grouptype" id = "grouptype" value = ""/>
<input type = "hidden" name = "Datatype" id = "Datatype" />
<input type = "hidden" name = "description1" id = ""/>


						<div class="h45 normaltxt_11p_blk" style="background:#6391CC">
							<div class="lineSpace_1">&nbsp;</div>
							<div style="margin-right:4px" align="right"><img src="/public/images/crossImg.gif" onClick = "hideuploadOverlay()" /></div>
							<div class="bld mar_left_10p" style="color:#FFF">Select Your University Photo</div>
							<div class="mar_left_10p" style="color:#FFF">By clicking the 'Browse' and 'Upload' buttons.</div>							
						</div>
						<div class="bgOverLay">
							<div class="lineSpace_13">&nbsp;</div>
							<div class="mar_full_10p"><input type="file" name = "collegephoto[]" id = "collegephoto"/></div>

			<div class="row errorPlace" style="margin-top:2px;">
				<div class="errorMsg mar_left_10p" id= "uploadphoto_error"></div>
			</div>	
							<div class="lineSpace_13">&nbsp;</div>
							<div class="normaltxt_11p_blk mar_full_10p">
								<div class="lightBlack lineSpace_28"><span style="position:relative;top:-5px">(Maximum size of 1MB)</span>&nbsp;
								
								<button class="btn-submit5 w22" value="" name = "submit1" id = "submit1" type="submit">
										<div class="btn-submit5"><p class="btn-submit6">Upload</p></div>
									</button>
								</div>
							
							</div>
							<div class="lineSpace_18">&nbsp;</div>
	
						</div>
<?php echo '</form>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
