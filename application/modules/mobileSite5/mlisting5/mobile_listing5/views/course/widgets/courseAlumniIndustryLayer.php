
            <?php if(count($naukri_specializations)>0 && $total_naukri_employees > 30){ ?>
                    <div class="crs-alumni">
                        <div class="layer-abs" >
                            <p>View By Specialization</p>
                            <div class="dropdown-primary">
                                <input class="option-slctd" id="naukriLayerSpecializationSelect_input" ga-attr="ALUMNI_FILTER_COMPANY_LAYER_ALL_SPEC_COURSEDETAIL_MOBILE" readonly value='<?php
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
                                        ?>'>
                            </div>
                            <div class="select-Class">
                              <select id="naukriLayerSpecializationSelect" fromLayer="Industry" style="display:none;">
                                    <option value="All Specialization">All Specializations</option>
                                    <?php foreach($naukri_specializations as $splz){
                                        if($splz == "Systems" || $splz == "Other Management" || $splz == "#N/A") { continue; }
                                        elseif($splz == "Marketing") {?>
                                            <option class="naukriWidgetDropDownList" value="Marketing" ga-attr="ALUMNI_FILTERLAYER_MARKETING_COURSEDETAIL_MOBILE">Sales & Marketing</option>
                                    <?php }
                                        elseif($splz == "HR/Industrial Relations"){?>
                                            <option class="naukriWidgetDropDownList" value="HR/Industrial Relations" ga-attr="ALUMNI_FILTERLAYER_HR_INDUSTRIAL_RELATIONS_COURSEDETAIL_MOBILE">Human Resources</option>
                                    <?php }
                                        elseif($splz == "Information Technology"){?>
                                            <option class="naukriWidgetDropDownList" value="Information Technology" ga-attr="ALUMNI_FILTERLAYER_IT_COURSEDETAIL_MOBILE">IT</option>
                                    <?php }
                                        else{?>
                                            <option class="naukriWidgetDropDownList" value="<?php echo $splz; ?>" ga-attr="ALUMNI_FILTERLAYER_<?php echo strtoupper($splz);?>_COURSEDETAIL_MOBILE"><?php echo $splz; ?></option>
                                    <?php }
                                    } 
                                    if(in_array("Other Management", $naukri_specializations)) { ?>
                                            <option class="naukriWidgetDropDownList" value="Other Management" ga-attr="ALUMNI_FILTERLAYER_OTHER_MANAGEMENT_COURSEDETAIL_MOBILE">Other Management</option>
                                    <?php } ?>
                                 
                              </select>
                            </div>
                        </div>   
                        <div class="comp-bus-bar">
                          <?php if($industry_count>0){ ?>
                                <div class="comp-bCol bus-col">
                                    <p>Business functions they are in</p>
                                    <ul id="naurkiLayerContent">
                                    <?php $industryData             = json_decode($industry,true);
                                          $maximumEmployeesIndustry = $industryData[0]['no_of_emps'];
                                        for($i=0;$i<count($industryData);$i++){
                                            if(!empty($industryData[$i])){
                                        ?>
                                            <li>
                                                <label><strong><?php echo $industryData[$i]['no_of_emps'];?></strong> <span> Employees</span> | <?php echo $industryData[$i]['industry']?></label>
                                                <div class="prg-bar"><p class="slctd-bar" style="width:<?php echo $industryData[$i]['no_of_emps']*100/$maximumEmployeesIndustry ;?>%"></p></div>
                                            </li>
                                            <?php
                                            }
                                        }
                                    ?>
                                    </ul>
                                </div>
                           <?php } ?>
                        </div>
                    </div>
            <?php }?>
