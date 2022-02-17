<div id="alumni-layer" class="universal-layer">
        <?php $placementData            = json_decode($placement_data,true);
              $maximumEmployees         = $placementData[0]['no_of_emps'];
              $industryData             = json_decode($industry,true);
              $maximumEmployeesIndustry = $industryData[0]['no_of_emps'];
        ?>                                       
    <div class="box">
        <hgroup>
            <h2 class="head-2 titl">Employment details of <?php echo $total_naukri_employees ?> alumni of this course</h2>
             <div class="gen-cat">
                                <p>View by Specialization</p>
                                <div class="dropdown-primary" id = "naukriSalaryWidgetFromLayer">
                                    <span class="option-slctd" id="optnSlctd">
                                        <?php
                                        if($selected_naukri_splzn) {
						if($selected_naukri_splzn == "Marketing") {
						    echo "Sales & Marketing";
						}
						elseif($selected_naukri_splzn == "HR/Industrial Relations") {
						    echo "Human Resources";
						}
						elseif($selected_naukri_splzn == "Information Technology") {
						    echo "IT";
						}
            elseif($selected_naukri_splzn == "All Specialization") {
                echo "All Specializations";
            }
						else {
						      echo $selected_naukri_splzn;
						}
					    }
					    else {
						echo "All Specializations";
					    }
                                        ?>
                                    </span>
                                    <span class="icon"></span>
                                    <ul class="dropdown-nav" id="click-layer2" style="display: none;">
                                        <li><a class="naukriWidgetDropDownList" href="javascript:void(0);" ga-track="ALUMNI_FILTERLAYER_ALL_SPEC_COURSEDETAIL_DESKTOP" uniqueattr="All Specialization" fromLayer="yes">All Specializations</a></li>
                                     <?php foreach($naukri_specializations as $splz){
                                                if($splz == "Systems" || $splz == "Other Management" || $splz == "#N/A") { continue; }
                                                elseif($splz == "Marketing") {?>
                                                    <li><a class="naukriWidgetDropDownList" href="javascript:void(0);" ga-track="ALUMNI_FILTERLAYER_MARKETING_COURSEDETAIL_DESKTOP" uniqueattr="Marketing" fromLayer="yes">Sales & Marketing</a></li>
                                            <?php }
                                                elseif($splz == "HR/Industrial Relations"){?>
                                                   <li><a class="naukriWidgetDropDownList"  href="javascript:void(0);" ga-track="ALUMNI_FILTERLAYER_HR_COURSEDETAIL_DESKTOP" uniqueattr="HR/Industrial Relations" fromLayer="yes">Human Resources</a></li> 
                                            <?php }
                                                elseif($splz == "Information Technology"){?>
                                                   <li><a class="naukriWidgetDropDownList"  href="javascript:void(0);" ga-track="ALUMNI_FILTERLAYER_IT_COURSEDETAIL_DESKTOP" uniqueattr="Information Technology"  fromLayer="yes">IT</a></li> 
                                            <?php }
                                                else{?>
                                                   <li><a class="naukriWidgetDropDownList"  href="javascript:void(0);" ga-track="<?='ALUMNI_FILTERLAYER_'.strtoupper($splz).'_COURSEDETAIL_DESKTOP'?>" uniqueattr="<?php echo $splz; ?>" fromLayer="yes"><?php echo $splz; ?></a></li> 
                                            <?php }
                                            } 
                                            if(in_array("Other Management", $naukri_specializations)) { ?>
                                                   <li><a class="naukriWidgetDropDownList"   href="javascript:void(0);" ga-track="ALUMNI_FILTERLAYER_OTHER_MGT_COURSEDETAIL_DESKTOP" uniqueattr="Other Management" fromLayer="yes">Other Management</a></li>
                                            <?php } ?>
                                    </ul>
                                </div>
                            </div>
        </hgroup>
        <section>
            <div>
                <div>
                    <div class="tabSection">
                        <ul class="h-tabs">
                            <li data-index="1" class="active naukriLayerTabs" tab="left"> <h2>Companies they work for</h2></li>
                            <li data-index="2" class="naukriLayerTabs"     tab="right"  > <h2>Business functions they are in</h2></li>
                        </ul>
                    </div>
                    <div class="tabContent ext-space">
                        <div class="comp-bus-bar lcard active" data-index="1" fromLayer="yes">
                            <div class="comp-bCol">
                            <ul>
                            <?php 
                                  for($i=0;$i<count($placementData);$i++){
                                      if(!empty($placementData[$i])){
                                  ?>
                                      <li>
                                          <label><strong><?php echo $placementData[$i]['no_of_emps'];?></strong> <span> Employees</span> | <?php echo $placementData[$i]['comp_name']?></label>
                                          <div class="prg-bar"><p class="slctd-bar" style="width:<?php echo $placementData[$i]['no_of_emps']*100/$maximumEmployees ;?>%"></p></div>
                                      </li>
                                      <?php
                                      }
                                      else{
                                          break;
                                      }
                                  }

                            ?>
                            </ul>
                            </div>
                        </div>
                        <div class="comp-bus-bar lcard" data-index="2" style="display:none">
                            <div class="comp-bCol bus-col">
                            <ul>
                                <?php 
                                    for($i=0;$i<count($industryData);$i++){
                                      if(!empty($industryData[$i])){
                                ?>
                                      <li>
                                          <label><strong><?php echo $industryData[$i]['no_of_emps'];?></strong> <span> Employees</span> | <?php echo $industryData[$i]['industry']?></label>
                                          <div class="prg-bar"><p class="slctd-bar" style="width:<?php echo $industryData[$i]['no_of_emps']*100/$maximumEmployeesIndustry ;?>%"></p></div>
                                      </li>
                                <?php
                                      }
                                      else{
                                          break;
                                      }
                                  }
                                ?>
                            </ul>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>