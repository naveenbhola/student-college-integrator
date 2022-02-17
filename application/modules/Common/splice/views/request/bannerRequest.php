<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
    	<div class="checkbox">
	    <label class="">
	      <div class="icheckbox_flat-green checked requestCheckBox bannerRequest" style="position: relative;"><input type="checkbox" class="flat"  style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
	      <span style="font:bold;font-size: 17px;font-weight: bold;color: #73879c;position: relative; top: 4px;"><b class='requestTypeAddClass'>	<?php echo $requestDisplayName['Banner']?> Request</b></span>	   
	    </label>
	  </div>
      <div class="clearfix"></div>
      <div class="clearfix"></div>
    </div>
    <div class="x_content requestTypeHide" style="display:none">

      <br>
      <!-- Request Title -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Title *</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <input type="text" class="form-control" placeholder="Request Title" validationType='str' required="true" id='requestTitleForBanner' caption='Request Title' maxLength='255' onblur="formValidation.showErrorMessage($(this).attr('id'))">
          <div id="requestTitleForBanner_error" class="errorMsg" style="display: none"></div>
        </div>          
      </div>

      <!-- Request Type -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Type *</label>
        <div class="radio col-md-2 col-sm-2 col-xs-12"  >
          <label class="control-label " style=" padding-top: 0px !important;">
            <input type="radio" checked="" value="Create New" id="optionsRadios1" name="optionsRadiosTypeForBanner" style=" padding-top: 0px !important;">
            <span style="position: relative; top: 4px;"> Create New   </span>
          </label>
        </div>
        <div class="radio col-md-2 col-sm-2 col-xs-12" >
              <label class="control-label " style=" padding-top: 0px !important;">
                  <input type="radio" value="Edit Existing" id="optionsRadios2" name="optionsRadiosTypeForBanner" style=" padding-top: 0px !important;">
                  <span style="position: relative; top: 4px;"> Edit Existing</span>
              </label>
          </div>
      </div>

      <!-- Site -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Site *</label>
        <div class="radio col-md-2 col-sm-2 col-xs-12"  >
          <label class="control-label " style=" padding-top: 0px !important;">
            <input type="radio" checked="" value="domestic" id="optionsRadios1" name="optionsRadiosSiteForBanner" style=" padding-top: 0px !important;">
            <span style="position: relative; top: 4px;"> Domestic   </span>
          </label>
        </div>
        <div class="radio col-md-2 col-sm-2 col-xs-12" >
              <label class="control-label " style=" padding-top: 0px !important;">
                  <input type="radio" value="studyAbroad" id="optionsRadios2" name="optionsRadiosSiteForBanner" style=" padding-top: 0px !important;">
                  <span style="position: relative; top: 4px;"> Study Abroad</span>
              </label>
        </div>
      </div>

      <!-- Only For Listing Request Type is 'Edit Existing'  -->
      <div class="form-group" id ="bannerRequestEdit" style="display:none">       
        <!-- Requested By  -->
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Requested By</label>
          <!-- <div class="col-md-3 col-sm-3 col-xs-12">
            <select class="form-control" id="listingRequestType">
              <option style="background-color: khaki;" class="disabled"><a href="javascript:void(0)" title="Select Group Name" >Choose option</a></option>
              <option>Client</option>
              <option>Sales Team</option>
              <option>Requirment Not Fullfilled</option>                            
            </select>
          </div> -->
          <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="bannerRequestedBy"
                          data-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="true" style="width:130px !important">Select<span class="caret" ></span>
                  </button>
                  
                  <ul class="dropdown-menu" aria-labelledby="bannerRequestedBy" >

                    <li data-dropdown="Select" style="background-color: khaki;" class="disabled">
                        <a href="javascript:void(0)" title="Select Group Name" >Select</a>
                      </li>
                      <li data-dropdown="Client">
                          <a href="javascript:void(0)" title="Client">Client</a>
                      </li>
                      <li data-dropdown="Sales Team">
                          <a href="javascript:void(0)" title="Sales Team">Sales Team</a>
                      </li>
                  </ul>
              </div>
              <input type="hidden" name="bannerRequestedBy" value='0' id="hidden_bannerRequestedBy" />
          </div>
        </div>
        <!-- Change Type  -->
        <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Change Type *</label>
              <div class="radio col-md-2 col-sm-2 col-xs-12"  >
                <label class="control-label " style=" padding-top: 0px !important;">
                  <input type="radio" checked="" value="major" id="optionsRadios1" name="optionsRadiosChangeTypeForBanner" style=" padding-top: 0px !important;">
                  <span style="position: relative; top: 4px;"> Major  </span>
                </label>
              </div>
              <div class="radio col-md-2 col-sm-2 col-xs-12" >
                  <label class="control-label " style=" padding-top: 0px !important;">
                      <input type="radio" value="minor" id="optionsRadios2" name="optionsRadiosChangeTypeForBanner" style=" padding-top: 0px !important;">
                      <span style="position: relative; top: 4px;"> Minor</span>
                  </label>
              </div>
        </div>          
      </div>

      <!-- Banner Size MultiSelect -->
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Banner Size *</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <?php
          foreach ($bannerSize as $key => $value) { ?>
            <div class= 'bannerSizeSelected'>
              <label class="" style="font-weight:normal !important">
                <div class="icheckbox_flat-green "  style="position: relative;">
                <input type="checkbox" class="flat" id="<?php echo 'bannerSize_'.$key;?>" style="position: absolute; opacity: 0;" >
                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                <span style="font-size: 15px;position: relative; top: 4px;"><?php echo $value;?></span>
              </label>
            </div>  
        <?php  } ?> 
         <div id="bannerSize_error" class="errorMsg" style="display: none"></div>
        </div>          
      </div>

      <?php
        $data['editor'] = 'bannerTextEditor';
        $this->load->view('textArea',$data);

        $data['id'] = 'bannerRequestAttachment';
        $this->load->view('attachment',$data);
      ?>

      <!-- attachment -->
      <!-- <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" id="listingRequestedType">Attachment</label>
              <input type="file" validationtype="file" caption="application process" id="bannerRequestAttachment" name="bannerRequestAttachment" class="col-md-6 col-sm-6 col-xs-12">
            <div id="bannerRequestAttachment_error" class="errorMsg" style="display: none"></div>
      </div>       -->
    </div>
  </div>
</div>