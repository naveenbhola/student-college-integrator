<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
              <h2>Add New Member</h2>                    
              <div class="clearfix"></div>
            </div>
            <div class="x_content" style="display: block;">
              <br>
              <div id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                  <div class="item form-group bad">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" >Select Group Name <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="groupId"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="true">Select Group Name<span class="caret"></span>
                            </button>
                            <?php if(count($groupDetails) > 6){
                                $class = 'overflow_auto';
                              } ?>
                            <ul class="dropdown-menu <?php echo $class;?>" aria-labelledby="groupId" >

                              <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                                            <a href="javascript:void(0)" title="Select Group Name" >Select Group Name</a>
                                </li>                               
                                <?php                              
                                    $i=1;
                                    foreach ($groupDetails as $key => $value) { ?>
                                    <li data-dropdown="<?php echo $key;?>">
                                        <a href="javascript:void(0)" title="<?php echo $value; ?>">
                                            <?php echo $value; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <input type="hidden" name="groupId" value='0' id="hidden_groupId" />
                    </div>
                  </div>
                  <div class="item form-group bad cropper-hidden">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" >Select Manager <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12" >

                        <div class="dropdown">
                            <button
                                class="btn btn-default dropdown-toggle white_space_normal_overwrite " 
                                type="button" id="managerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select Manager<span
                                class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="managerId">
                            </ul>
                        </div>
                        <input type="hidden" name="managerId" value="0" id="hidden_managerId"/>
                    </div>
                  </div>

                  <div class="item form-group bad cropper-hidden">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" >Select Lead <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="dropdown">
                            <button
                                class="btn btn-default dropdown-toggle white_space_normal_overwrite " 
                                type="button" id="leadId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select Lead<span
                                class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="leadId">
                            </ul>
                        </div>
                        <input type="hidden" name="leadId" value="0" id="hidden_leadId"/>
                    </div>
                  </div>

                  <?php if($branchDetails){ ?>
                  <div class="item form-group bad" style="display:none" id = "branchIdDiv">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Select Branch Name <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="branchId"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="true">Select Branch Name<span class="caret"></span>
                            </button>
                            <?php if(count($branchDetails) > 6){
                                $class = 'overflow_auto';
                              } ?>
                            <ul class="dropdown-menu <?php echo $class;?>" aria-labelledby="branchId" >

                              <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                                            <a href="javascript:void(0)" title="Select Group Name" >Select Branch Name</a>
                                </li>                               
                                <?php
                                    $i=1;
                                    foreach ($branchDetails as $key => $value) { ?>
                                      <li data-dropdown="<?php echo $key;?>">
                                          <a href="javascript:void(0)" title="<?php echo $value; ?>">
                                              <?php echo $value; ?>
                                          </a>
                                      </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <input type="hidden" name="branchId" value='0' id="hidden_branchId" />
                    </div>
                  </div>
                  <?php } ?>

                  <div class="item form-group bad">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email" name="email" required="required" style="border-color:#ccd0d7;box-shadow:none !important" class="form-control col-md-7 col-xs-12" onblur="shikshaSales.validateEmail(this.value)">
                    </div>                             
                  </div>
                  <div class="item form-group bad">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    </label>
                    <div class=" col-md-6 col-sm-6 col-xs-12" id="validateEmail" style="display:none">email address is invalid</div>
                    <div  id="userId" style="display:none"></div>
                  </div>
            
                  <div class="ln_solid"></div>                      
              </div>
              <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                      <button type="submit" id ="addNewMember" class="btn btn-success">Add New Member</button>
                    </div>
                  </div>
          </div>
        </div>
    </div>                                          
</div>