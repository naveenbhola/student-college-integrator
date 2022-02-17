          <!-- Campaign Triggered Date -->
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Campaign Triggered Date *</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" class="form-control has-feedback-left active" id="campaignLiveDate" placeholder="Campaign Triggered Date" aria-describedby="inputSuccess2Status" validationType='campaignDate' required="true" caption='Campaign Triggered Date' onblur="formValidation.showErrorMessage($(this).attr('id'))">
                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                <div id="campaignLiveDate_error" class="errorMsg" style="display: none"></div>
            </div>         
          </div>

          <!-- Request Title -->      
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Title *</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" placeholder="Request Title"  validationType='str' required="true" id='requestTitleForMailer' caption='Request Title' maxLength='255' onblur="formValidation.showErrorMessage($(this).attr('id'))">
              <div id="requestTitleForMailer_error" class="errorMsg" style="display: none"></div>
            </div>          
          </div>

          <!-- Request Type  -->
          <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Type *</label>              
              <div class="dropdown col-md-3 col-sm-3 col-xs-12">
                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="requestTypeForMailer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:150px !important"  
                   caption="Request Type" required="true" validationType="select">
                  Request Type
                    <span class="caret"></span>
                </button>                
                <ul class="dropdown-menu" aria-labelledby="requestTypeForMailer">
                  <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                          <a href="javascript:void(0)" title="Choose a popular course" >Request Type</a>
                  </li>
                  <li data-dropdown="New Design">
                    <a href="javascript:void(0)" title="New Design">New Design</a>
                  </li>
                  <li data-dropdown="Direct HTML">
                    <a href="javascript:void(0)" title="Direct HTML">Direct HTML</a>
                  </li>                  
                </ul>
                <div id="requestTypeForMailer_error" class="errorMsg" style="display: none"></div>
              </div>
              <input type="hidden" name="requestTypeForMailer" value=0 id="hidden_requestTypeForMailer"    />              
          </div>

          <!-- Site -->
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Site *</label>
            <div class="radio col-md-2 col-sm-2 col-xs-12"  >
              <label class="control-label " style=" padding-top: 0px !important;">
                <input type="radio" checked="" value="domestic" id="optionsRadios1" name="optionsRadiosSiteForMailer" style=" padding-top: 0px !important;">
                <span style="position: relative; top: 4px;"> Domestic   </span>
              </label>
            </div>
            <div class="radio col-md-2 col-sm-2 col-xs-12" >
                  <label class="control-label " style=" padding-top: 0px !important;">
                      <input type="radio" value="studyAbroad" id="optionsRadios2" name="optionsRadiosSiteForMailer" style=" padding-top: 0px !important;">
                      <span style="position: relative; top: 4px;"> Study Abroad</span>
                  </label>
            </div>
          </div>

          <!-- courseListForMailerRequest  -->
          <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Choose Course *</label>              
              <div class="dropdown col-md-3 col-sm-3 col-xs-12">
                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="courseForMailerReq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:150px !important"
                caption=" Course" required="true" validationType="select"
                >Choose Course
                    <span class="caret"></span>
                </button>
                <?php if(count($courseListForMailerRequest) > 6){
                  $class = 'overflow_auto';
                } ?>
                <ul class="dropdown-menu <?php echo $class;?>" aria-labelledby="courseForMailerReq">                                    
                  <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                          <a href="javascript:void(0)" title="Choose course" >Choose course</a>
                  </li>
                  <?php foreach ($courseListForMailerRequest as $key => $value) { ?>
                      <li data-dropdown="<?php echo $key; ?>">
                          <a href="javascript:void(0)" title="<?php echo $value; ?>">
                              <?php echo $value; ?>
                          </a>
                      </li>
                  <?php } ?>                                       
                </ul>
                <div id="courseForMailerReq_error" class="errorMsg" style="display: none"></div>
              </div>
              <input type="hidden" name="courseForMailerReq" value=0 id="hidden_courseForMailerReq"/>
          </div>

          <!-- Mailer Type  -->
          <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Mailer Type</label>              
              <div class="dropdown col-md-3 col-sm-3 col-xs-12">
                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="mailerTypeMailerReq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:180px !important"
                caption=" Mailer Type" validationType="select"
                >Select Mailer Type
                    <span class="caret"></span>
                </button>
                <?php if(count($courseListForMailerRequest) > 6){
                  $class = 'overflow_auto';
                } ?>
                <ul class="dropdown-menu <?php echo $class;?>" aria-labelledby="mailerTypeMailerReq" style="width:180px !important">
                  <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                          <a href="javascript:void(0)" title="Select Mailer Type" >Select Mailer Type</a>
                  </li>
                  <?php foreach ($mailerType as $key => $value) { ?>
                      <li data-dropdown="<?php echo $key; ?>">
                          <a href="javascript:void(0)" title="<?php echo $value; ?>">
                              <?php echo $value; ?>
                          </a>
                      </li>
                  <?php } ?>                                       
                </ul>
                <div id="mailerTypeMailerReq_error" class="errorMsg" style="display: none"></div>
              </div>
              <input type="hidden" name="mailerTypeMailerReq" value=0 id="hidden_mailerTypeMailerReq"/>
          </div>

          <?php
            $data['editor'] = 'mailerTextEditor';
            $this->load->view('textArea',$data);
            
            $data['id'] = 'attachmentForMailerRequest';
            $this->load->view('attachment',$data);
          ?>