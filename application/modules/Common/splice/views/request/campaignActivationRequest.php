<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <div class="checkbox">
	    <label class="">
	      <div class="icheckbox_flat-green checked requestCheckBox campaignActivationRequest" style="position: relative;"><input type="checkbox" class="flat"  style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
	      <span style="font:bold;font-size: 17px;font-weight: bold;color: #73879c;position: relative; top: 4px;"><b class='requestTypeAddClass'>	<?php echo $requestDisplayName['Campaign Activation']?> Request</b></span>	       	
	    </label>
	  </div>
      <div class="clearfix"></div>      
    </div>
    <div class="x_content requestTypeHide" style="display:none">
      
      <br>
      <!-- Request Title -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Title *</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <input type="text" class="form-control" placeholder="Request Title" validationType='str' required="true" id='requestTitleForCampaignActivation' caption='Request Title' maxLength='255' onblur="formValidation.showErrorMessage($(this).attr('id'))">
          <div id="requestTitleForCampaignActivation_error" class="errorMsg" style="display: none"></div>
        </div>          
      </div>

      <!-- Campaign Type MultiSelect -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Campaign Type *</label>
        <div class="col-md-9 col-sm-9 col-xs-12" id='campaignTypeSelect'>
          <?php          
          foreach ($campaignType as $key => $value) { ?>
            <div  class= 'campaignTypeSelected'>
              <label class="" style="font-weight:normal !important" id="<?php echo 'campaignType_'.$key;?>">
                <div class="icheckbox_flat-green "  style="position: relative;"><input type="checkbox" class="flat"   style="position: absolute; opacity: 0;"><ins  class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                <span style="font-size: 15px;position: relative; top: 4px;"><?php echo $value;?></span>    
              </label>
            </div>  
        <?php  } ?>
        <div id="campaignType_error" class="errorMsg" style="display: none"></div>            
        </div>          
      </div>

      <!-- Request Type -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Type *</label>
        <div class="radio col-md-2 col-sm-2 col-xs-12"  >
          <label class="control-label " style=" padding-top: 0px !important;">
            <input type="radio" checked="" value="New" id="optionsRadios1" name="optionsRadiosTypeForCampaign" style=" padding-top: 0px !important;">
            <span style="position: relative; top: 4px;"> New   </span>
          </label>
        </div>
        <div class="radio col-md-2 col-sm-2 col-xs-12" >
              <label class="control-label " style=" padding-top: 0px !important;">
                  <input type="radio" value="Replace" id="optionsRadios2" name="optionsRadiosTypeForCampaign" style=" padding-top: 0px !important;">
                  <span style="position: relative; top: 4px;"> Replace</span>
              </label>
          </div>
      </div>      

      <!-- Site -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Site *</label>
        <div class="radio col-md-2 col-sm-2 col-xs-12"  >
          <label class="control-label " style=" padding-top: 0px !important;">
            <input type="radio" checked="" value="domestic" id="optionsRadios1" name="optionsRadiosSiteForCampaign" style=" padding-top: 0px !important;">
            <span style="position: relative; top: 4px;"> Domestic   </span>
          </label>
        </div>
        <div class="radio col-md-2 col-sm-2 col-xs-12" >
              <label class="control-label " style=" padding-top: 0px !important;">
                  <input type="radio" value="studyAbroad" id="optionsRadios2" name="optionsRadiosSiteForCampaign" style=" padding-top: 0px !important;">
                  <span style="position: relative; top: 4px;"> Study Abroad</span>
              </label>
        </div>
      </div>
           

      <!-- landing Page URL -->
      <div class="form-group" id='campaignRequestEdit' style="display:none">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Landing Page URL</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" class="form-control" placeholder="Landing Page URL" validationType='url'  id='landingPageURLForCampaignActivation' caption='Request Title' maxLength='200' onblur="shikshaSales.checkIfLandingPageURLIsValid($(this).attr('id'))" value=''>
          <div id="landingPageURLForCampaignActivation_error" class="errorMsg" style="display: none"></div> 
          </div>                
      </div>

      <?php
        $data['editor'] = 'campaignActivationTextEditor';
        $this->load->view('textArea',$data);

        $data['id'] = 'campaignActivationRequestAttachment';
        $this->load->view('attachment',$data);
      ?>

      <!-- attachment -->
      <!-- <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" id="listingRequestedType">Attachment</label>
              <input type="file" validationtype="file" caption="application process" id="campaignActivationRequestAttachment" name="campaignActivationRequestAttachment" class="col-md-6 col-sm-6 col-xs-12">
            <div id="1_univApplicationProcessUpload_editUniversityForm_error" class="errorMsg" style="display: none"></div>
      </div>                 -->
    </div>
  </div>
</div>