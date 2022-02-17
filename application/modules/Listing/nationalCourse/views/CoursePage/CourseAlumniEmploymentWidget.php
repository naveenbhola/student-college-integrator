<?php if($total_naukri_employees > 30 || $salary_total_employee >30) {?>  
<div class="new-row alum-sec">
                <div class="group-card no__pad gap">
                    <div class="stat-heading">
                        <h2 class="head-1 stat-head">Alumni Employment Stats </h2>
                        <span class="head-spn stat-txt">Data based on resumes from <i class="data-source-logo"></i>, India's no.1 job site</span>
                    </div>
                    <?php if($salary_data_count>0 && $salary_total_employee >30){ ?>
                        <h3 class="crse-title pad__16">Average annual salary details of <?php echo $salary_total_employee ?> alumni of this course <span style="color:#999;font-size: 11px;font-weight: 400">(by work experience)</span></h3>
                        <div id="salary-data-chart" class="alumni-graph">

                        </div>
                    <?php } ?>
                    <?php if(count($naukri_specializations)>0 && $total_naukri_employees > 30){ ?>
                    <div class="crs-alumini">
                        <div class="mb10">
                            <h3 class="crse-title">Employment details of <?php echo $total_naukri_employees ?> alumni of this course</h3>
                            <div class="gen-cat">
                                <p>View by Specialization</p>
                                <div class="dropdown-primary" id = "naukriSalaryWidget">
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
                					    }else {
                						  echo "All Specializations";
                					    }
                                        ?>
                                    </span>
                                    <span class="icon"></span>
                                    <ul class="dropdown-nav" id="click-layer2" style="display: none;">
                                        <li><a class="naukriWidgetDropDownList" href="javascript:void(0);" ga-track="ALUMNI_FILTER_ALL_SPEC_COURSEDETAIL_DESKTOP" uniqueattr="All Specialization">All Specializations</a></li>
                                            <?php foreach($naukri_specializations as $splz){
                                                if($splz == "Systems" || $splz == "Other Management" || $splz == "#N/A") { continue; }
                                                elseif($splz == "Marketing") {?>
                                                    <li><a class="naukriWidgetDropDownList" href="javascript:void(0);" ga-track="ALUMNI_FILTER_MARKETING_COURSEDETAIL_DESKTOP" uniqueattr="Marketing">Sales & Marketing</a></li>
                                            <?php }
                                                elseif($splz == "HR/Industrial Relations"){?>
                                                   <li><a class="naukriWidgetDropDownList"  href="javascript:void(0);" ga-track="ALUMNI_FILTER_HR_COURSEDETAIL_DESKTOP" uniqueattr="HR/Industrial Relations">Human Resources</a></li> 
                                            <?php }
                                                elseif($splz == "Information Technology"){?>
                                                   <li><a class="naukriWidgetDropDownList"  href="javascript:void(0);" ga-track="ALUMNI_FILTER_IT_COURSEDETAIL_DESKTOP" uniqueattr="Information Technology">IT</a></li> 
                                            <?php }
                                                else{?>
                                                   <li><a class="naukriWidgetDropDownList"  href="javascript:void(0);" ga-track="<?='ALUMNI_FILTER_'.strtoupper($splz).'_COURSEDETAIL_DESKTOP'?>" uniqueattr="<?php echo $splz; ?>"><?php echo $splz; ?></a></li> 
                                            <?php }
                                            } 
                                            if(in_array("Other Management", $naukri_specializations)) { ?>
                                                   <li><a class="naukriWidgetDropDownList"   href="javascript:void(0);" ga-track="ALUMNI_FILTER_OTHER_MGT_COURSEDETAIL_DESKTOP" uniqueattr="Other Management">Other Management</a></li>
                                            <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="comp-bus-bar">
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
                                <a id="com-plcmnt" class="view-m openNaurkriLayer" ga-track="ALUMNI_COMP_VIEW_MORE_COURSEDETAIL_DESKTOP" tab="left">View more</a>
                                <?php }?>
                            </div>
                            <?php } ?>
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
                                <a id="com-plcmnt" class="view-m openNaurkriLayer" ga-track="ALUMNI_BUSINESS_VIEW_MORE_COURSEDETAIL_DESKTOP" tab="right">View more</a>
                                <?php }?>
                            </div>
                            <?php } ?>
                        </div>
                            
                        <p class="disclm-info"><strong>Disclaimer :</strong> Shiksha alumni salary and employment data relies entirely on salary information provided by individual users on Naukri. To maintain confidentiality, only aggregated information is made<span class="hid"> available. Salary and employer data shown here is only indicative and may differ from the actual placement data of college. Shiksha offers no guarantee or warranty as to the correctness or accuracy of the information provided & will not be liable for any financial or other loss directly or indirectly arising or related to use of the same.</span><a
                                class="view-m" id="expandNaukriDisclaimer">... Read more</a> </p>
                    </div>
                    <?php }?>
                </div>
            </div>
<?php }?>

<script>
    var chart_data = eval('(' + '<?php echo isset($chart)?$chart:0;?>' + ')'); 
    var selected_specialization = '<?php echo $selected_naukri_splzn;?>';
    var naukri_layer_selected_tab = 'left';
    var max_value = 0;
    </script>