<?php if($total_naukri_employees > 30 || $salary_total_employee >30) {?>  
<div class="crs-widget">
         <h2 class="head-L2">Alumni Employment Stats</h2>
        <div class="lcard">
            <p class="disc-txt">Data based on resumes from <a class="link"><i class="clg-sprite brand"></i></a>, India's no.1 job site</p>
            <?php if($salary_data_count>0 && $salary_total_employee >30){ ?>
            <div class="graph-div">
                <h3 class="admisn">Average annual salary details of <?php echo $salary_total_employee ?> alumni of this course <span>(by work experience) (In <b>INR</b>)<span></h3>
             
             <div id="salary-data-chart" style="margin-top:50px;">
                </div>
            </div>
            <?php } ?>
            <?php if(count($naukri_specializations)>0 && $total_naukri_employees > 30){ ?>
                    <div class="crs-alumni">

                        <div class="comp-bus-bar">

                          <h3 class="admisn f50">Employment details of <?php echo $total_naukri_employees ?> alumni</h3>
                            <div class="dropdown-primary">
                                <input class="option-slctd" id="naukriSpecializationSelect_input" ga-attr="ALUMNI_FILTER_ALL_SPEC_COURSEDETAIL_MOBILE" readonly value='<?php
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
                              <select id="naukriSpecializationSelect" style="display:none;">
                                    <option value="All Specialization">All Specializations</option>
                                    <?php foreach($naukri_specializations as $splz){
                                        if($splz == "Systems" || $splz == "Other Management" || $splz == "#N/A") { continue; }
                                        elseif($splz == "Marketing") {?>
                                            <option class="naukriWidgetDropDownList" value="Marketing" ga-attr="ALUMNI_FILTER_MARKETING_COURSEDETAIL_MOBILE">Sales & Marketing</option>
                                    <?php }
                                        elseif($splz == "HR/Industrial Relations"){?>
                                            <option class="naukriWidgetDropDownList" value="HR/Industrial Relations" ga-attr="ALUMNI_FILTER_HR_INDUSTRIAL_RELATIONS_COURSEDETAIL_MOBILE">Human Resources</option>
                                    <?php }
                                        elseif($splz == "Information Technology"){?>
                                            <option class="naukriWidgetDropDownList" value="Information Technology" ga-attr="ALUMNI_FILTER_IT_COURSEDETAIL_MOBILE">IT</option>
                                    <?php }
                                        else{?>
                                            <option class="naukriWidgetDropDownList" value="<?php echo $splz; ?>" ga-attr="ALUMNI_FILTER_<?php echo strtoupper($splz); ?>_COURSEDETAIL_MOBILE"><?php echo $splz; ?></option>
                                    <?php }
                                    } 
                                    if(in_array("Other Management", $naukri_specializations)) { ?>
                                            <option class="naukriWidgetDropDownList" value="Other Management" ga-attr="ALUMNI_FILTER_OTHER_MANAGEMENT_COURSEDETAIL_MOBILE">Other Management</option>
                                    <?php } ?>
                                 
                              </select>
                            </div>
                          <div id='employeeData'>
                          <?php if($placement_data_count>0){ ?>
                                <div class="comp-bCol">
                                    <p>Companies they work for</p>
                                    <ul>
                                    <?php $placementData    = json_decode($placement_data,true);
                                          $maximumEmployees = $placementData[0]['no_of_emps'];
                                        for($i=0;$i<3;$i++){
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
                                    <?php if(count($placementData)>3){?>
                                    <a href="javascript:void(0)" class="link-blue-medium  v-more openNaurkriLayer" layerOf="company" ga-attr="ALUMNI_COMP_VIEW_MORE_COURSEDETAIL_MOBILE">View more</a>
                                    <?php }?>
                                </div>
                          <?php }?>
                          <?php if($industry_count>0){ ?>
                                <div class="comp-bCol bus-col">
                                    <p>Business functions they are in</p>
                                    <ul>
                                    <?php $industryData             = json_decode($industry,true);
                                          $maximumEmployeesIndustry = $industryData[0]['no_of_emps'];
                                        for($i=0;$i<3;$i++){
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
                                    <?php if(count($industryData)>3){?>
                                        <a href="javascript:void(0)" class="link-blue-medium  v-more openNaurkriLayer" layerOf="industry" ga-attr="ALUMNI_BUSINESS_VIEW_MORE_COURSEDETAIL_MOBILE">View more</a>
                                    <?php }?>
                                </div>
                           <?php } ?>
                           </div>
                        </div>
                    </div>
            <?php }?>
            <p class="disc-txt" id="naukriDesclaimer"><?php echo cutStringWithShowMore("Disclaimer : Shiksha alumni salary and employment data relies entirely on salary information provided by individual users on Naukri. To maintain confidentiality, only aggregated information is made available. Salary and employer data shown here is only indicative and may differ from the actual placement data of college. Shiksha offers no guarantee or warranty as to the correctness or accuracy of the information provided & will not be liable for any financial or other loss directly or indirectly arising or related to use of the same.",95, 'naukriDesclaimer','more','');?></p>
        </div>
    </div>
<?php }?>
<script>
    var chart_data = eval('(' + '<?php echo isset($chart)?$chart:0;?>' + ')'); 
    var selected_specialization = '<?php echo $selected_naukri_splzn;?>';
    var max_value = 0;
    </script>
