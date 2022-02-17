                                
                                <!--Start_Listing-->
                                <?php
                                $hideQuery = 1;
                                $countInstitutesShown= count($resultSet);
                               //echo '<pre>';
                               //print_r($resultSet);
                               //echo '</pre>';

                                $picCount=0;
                                for($j = 0;$j < count($resultSet);$j++)
                                {
                                $picCount++;
                                $institutearr = $resultSet[$j]['institute'];
                                $back = '';
                                $coursesarr = $resultSet[$j]['courses'];
								$className = "homeShik_Icon shik_dotedIcon";
                                for($mk = 0;$mk <count($countArray['mainids']);$mk++)
                                {
                                    // if(isset($institutearr['isSponsored']) && $institutearr['isSponsored'] == 'true')
                                    $className = "homeShik_Icon shik_dotedIcon";
                                    if($countArray['mainids'][$mk] == $institutearr['id'])
                                    {
                                        //$className = "homeShik_Icon shik_checkedIcon";
                                        $className ='float_L checkSign';
                                        break;
                                    }
                                }
                                    $flagname = '';
                                if($pagename == 'categorypages')
                                {
                                    for($mn = 0;$mn < count($countArray['categoryselector']);$mn++)
                                    {
                                        if($countArray['categoryselector'][$mn] == $institutearr['id'])
                                        {
                                           // $back="background:#fefde8";
                                           // $className = "homeShik_Icon shik_checkedIcon";
                                            $className = 'float_L checkSign';
                                            $flagname = 'category';
                                            break;
                                        }

                                    }
                                }
								$showspacing = "position:relative;top:1px;padding-left:5px";
								if($pagename == "categorymostviewed" || $pagename == "topinstitutes")
								{
								$showspacing = "font-size:1px";
								}
                                $style = ';border-bottom:1px solid #e7e7e7';
                                if($j == (count($resultSet) - 1) && $pagename == 'topinstitutes')
                                {
                                $style = '';
                                }

                                ?>
                                <?php

                                    $instinamelength = 50;
                                    $coursenamelength = 50;
                                    if(isset($_COOKIE['client']) && $_COOKIE['client'] < 1000)
                                    {
                                        $instinamelength = 30;
                                        $coursenamelength = 30;
                                    }
                                    if(isset($_COOKIE['client']) && $_COOKIE['client'] > 1200)
                                    {
                                        $instinamelength = 80;
                                        $coursenamelength = 80;
                                    }

                                ?>
                                <?php if (trim($snippet_type) != 'full' &&  trim($snippet_type) != 'partial'){?>
                                <div style="width:100%;<?php echo $back.$style?>;padding:4px 0;">
                                	<div style="padding-bottom:3px"><span style="<?php echo $showspacing;?>">
                                    <?php if($institutearr['isSendQuery'] == 1) { $hideQuery = 0; $idname = 'reqEbr_'.$institutearr['id'];
                                    if($showselectall == 1) {
                                    ?>
                                    <input type="checkbox" autocomplete="off" name="reqEbr[]" onClick = "checkAllFields(2);" value = "<?php echo $institutearr['id']?>" />
                                    <?php } ?>
                                    <?php } else {?>&nbsp;<?php } ?>
                                    </span><span class="<?php echo $className;?>" style="padding-left:29px">&nbsp;</span><span>
                                    <?php

                                    $instiName = htmlspecialchars($institutearr['institute_Name']); ;

                                    if(strlen($institutearr['institute_Name']) > $instinamelength)
                                    $instiName = substr($institutearr['institute_Name'],0,$instinamelength) . '...';
                                    if($institutearr['id'] == 26294)
                                    {
                                        $instiurl = "http://www.newhorizonindia.edu"; ?>
														<span url = "<?php echo $url_mail?>" title="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" type="institute" displayname="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" locationname="<?php echo $institutearr['city']?>" id="<?php echo $idname ?>">
                                    <a href="#" onClick = "openwindow('<?php echo $instiurl ?>');return false;" class="Fnt14" title = "<?php echo htmlspecialchars($institutearr['institute_Name']); ;?>"><b><?php echo $instiName;?></b></a>
                                    </span>
                                  <?php  } else { ?>
				    										<span url = "<?php echo $url_mail?>" title="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" type="institute" displayname="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" locationname="<?php echo $institutearr['city']?>" id="<?php echo $idname ?>">
                                    <a href="<?php echo $coursesarr[0]['courseurl']?>" class="Fnt14" title = "<?php echo htmlspecialchars($institutearr['institute_Name']); ;?>"><b><?php echo $instiName;?></b></a>
                                    </span>
                                    <?php }
                                    if($showcoursecount == 1)
                                    {
                                    if($institutearr['coursecount'] > 1)
                                    $coursescount = 'courses';
                                    else
                                    $coursescount = 'course';
                                    ?>
                                    <span style="color:#848388">(<?php echo $institutearr['coursecount'].' '. $coursescount;?>)</span>
                                    <?php } ?>
                                    </span></div>
                                    <div>
                                    	<div class="float_R" style="width:315px">
                                        	<div class="float_L" style="width:100%">
                                            	<div style="width:100%">

                                        			<div class="float_R" style="width:158px">
                                                    	<div class="float_L" style="width:100%">
                                                        	<div style="width:100%">
                                                   <?php if($institutearr['isSendQuery'] == 1) { ?>

                                                    			<div><input type="button" value="" class="all_ShikshaBtn_spirit shik_requestEBrochureBtn" onClick = "if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;}return calloverlayInstitute(<?php echo $institutearr['id']?>,'HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO');"/></div>
                                                    <?php } ?>

                                                                <?php if($showviews == 1){?>
                                                            	<div style="color:#848388;padding:10px 10px 0 0" class="txt_align_r">(<?php echo $institutearr['views'];?> views)</div>
                                                                <?php } else {?>
                                                <div class="lineSpace_10">&nbsp;</div>
                                                                <?php  }?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div style="margin-right:168px">
                                                    	<div class="float_L" style="width:100%"><?php echo $institutearr['city'];?></div>
                                                    </div>
                                                <?php if($institutearr['isSendQuery'] != 1 ) { ?>
                                                <div class="lineSpace_20">&nbsp;</div>
                                                <?php } ?>
                                                </div>
                                            </div>
                                            <div class="clear_L">&nbsp;</div>
                                        </div>
                                        <?php
                                        $count = count($coursesarr);
                                        if($flagname == 'category')
                                        {
                                            if($count > 3)
                                            $count = 3;
                                        }

                                        for($k = 0;$k < $count;$k++) {
                                    $courseName = $coursesarr[$k]['course_title'];
                                    if(strlen($coursesarr[$k]['course_title']) > $coursenamelength)
                                    $courseName = substr($coursesarr[$k]['course_title'],0,$coursenamelength) . '...';
  ?>
                                    	<div style="margin-right:325px">
                                        	<div class="float_L" style="width:100%">
                                            	<div style="width:100%">
                                        			<div style="padding-left:70px">
                                                    <?php if($institutearr['id'] == 26294)
                                                    {?>
                                                        <div><a href="#" onClick = "openwindow('<?php echo $instiurl?>');return false;" title = "<?php echo $coursesarr[$k]['course_title'];?>"><?php echo $courseName;?></a></div>
                                                    <?php } else {?>
                                                                <div><a href="<?php echo $coursesarr[$k]['courseurl'];?>" title = "<?php echo $coursesarr[$k]['course_title'];?>"><?php echo $courseName;?></a></div>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear_L">&nbsp;</div>
										</div>
                                        <?php
                                        }
                                        if($flagname == 'category' && $count < count($coursesarr))
                                        {
                                        ?>
                                        <div id = "<?php echo 'showallcourse'.$institutearr['id']?>" style = "display:none">
                                     <?php   for($k = $count;$k < count($coursesarr);$k++) {
                                    $courseName = $coursesarr[$k]['course_title'];
                                    if(strlen($coursesarr[$k]['course_title']) > 50)
                                    $courseName = substr($coursesarr[$k]['course_title'],0,50) . '...';?>
                                    	<div style="margin-right:325px">
                                        	<div class="float_L" style="width:100%">
                                            	<div style="width:100%">
                                        			<div style="padding-left:70px">
                                                    <?php if($institutearr['id'] == 26294)
                                                    {?>
                                                        <div><a href="#" onClick = "openwindow('<?php echo $instiurl?>');return false;" title = "<?php echo $coursesarr[$k]['course_title'];?>"><?php echo $courseName;?></a></div>
                                                    <?php } else {?>
                                                                <div><a href="<?php echo $coursesarr[$k]['courseurl'];?>" title = "<?php echo $coursesarr[$k]['course_title'];?>"><?php echo $courseName;?></a></div>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear_L">&nbsp;</div>
										</div>
                                        <?php }?>
                                        </div>
                                    	<div style="margin-right:325px">
                                        	<div class="float_L" style="width:100%">
                                            	<div style="width:100%">
                                        			<div style="padding-left:70px">
                                                    	<div><a href="#" title = "" onClick =  "showhideCourses(this,'<?php echo $institutearr['id'];?>');return false;">All courses</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear_L">&nbsp;</div>
										</div>
                                       <?php }
                                        ?>

                                        <div class="clear_B">&nbsp;</div>
                                    </div>
                                </div>
                               <?php } ?>
                                <div class="lineSpace10">
                                &nbsp;
                                </div>
                                <div class="wdh100">
									
								<?php if($recommendation_page): ?>
									<input type='hidden' id='params<?php echo $institutearr['id']; ?>' value='<?php echo html_escape(getParametersForApply($validateuser,$resultSet[$j]['courses'][0])); ?>' />
								<?php endif; ?>	
									
								
								<?php if($show_apply_checkboxes != 'no'): ?>	
                                <div class="float_L w48">
                                    <span class="float_L" style="padding:1px 0 0 2px">
                                    <?php if($institutearr['isSendQuery'] == 1) { $hideQuery = 0; $idname = 'reqEbr_'.$institutearr['id'];
                                    if($showselectall == 1) {
                                    ?>
                                    <input type="checkbox" name="reqEbr[]" onClick = "checkAllFields(2);" value = "<?php echo $institutearr['id']?>">
                                    <?php } ?>
                                    <?php } else {?>&nbsp;<?php } ?>
                                    &nbsp;</span>

                                    <span class="<?php echo $className;?>">&nbsp;</span>
                                </div>
								<?php endif; ?>
								
								
                                <div <?php if($show_apply_checkboxes != 'no'): ?> class="ml49" <?php endif; ?>>
                                  <div class="mb10">
                                    <div class="float_L wdh100" style="margin-bottom: 10px;">

                                    <?php
                                    $instiName = $institutearr['institute_Name'];
                                    $instinamelength = 50;
                                    $coursenamelength = 50;
                                    if(isset($_COOKIE['client']) && $_COOKIE['client'] < 1000)
                                    {
                                        $instinamelength = 30;
                                        $coursenamelength = 30;
                                    }
                                    if(isset($_COOKIE['client']) && $_COOKIE['client'] > 1200)
                                    {
                                        $instinamelength = 80;
                                        $coursenamelength = 80;
                                    }

                                    if(strlen($institutearr['institute_Name']) > $instinamelength)
                                    $instiName = substr($institutearr['institute_Name'],0,$instinamelength) . '...';

                                    if($institutearr['id'] == 26294)
                                    {
                                        $instiurl = "http://www.newhorizonindia.edu"; ?>

                                    <div class="mb3">

                                       <span url = "<?php echo $url_mail?>" title="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" type="institute" displayname="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" locationname="<?php echo $institutearr['city']?>" id="<?php echo $idname ?>">
                                        <a href="#" onClick = "openwindow('<?php echo $instiurl ?>');return false;" title = "<?php echo htmlspecialchars($institutearr['institute_Name']); ;?>" class="Fnt16 bld">
                                        <?php echo $instiName.', '; ?>
                                        </a>
                                       </span>

                                        <?php if($institutearr['city'] != ''){echo $institutearr['city'].', ';}if($institutearr['countryName'] != ''){echo $institutearr['countryName'];} ?>
                                    </div>

                                    <?php  } else { ?>

                                    <div class="mb3">

                                       <span url = "<?php echo $url_mail?>" title="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" type="institute" displayname="<?php echo htmlspecialchars($institutearr['institute_Name']);  ?>" locationname="<?php echo $institutearr['city']?>" id="<?php echo $idname ?>">
                                        <a href="<?php echo $coursesarr[0]['courseurl']?>" title = "<?php echo htmlspecialchars($institutearr['institute_Name']); ;?>" class="Fnt16 bld">
                                        <?php echo $instiName.', '; ?>
                                        </a>
                                       </span>

                                        <?php $firstcom=0; if($institutearr['city'] != ''){ $firstcom=1; echo $institutearr['city'];}if($institutearr['countryName'] != ''){if($firstcom == 1){echo ', ';} echo $institutearr['countryName'];} ?>
                                    </div>

                                    <?php }?>

                                    <?php if (trim($institutearr['usp']) != '' && trim($institutearr['usp']) != NULL){?>
                                    <div class="cGray bld"><em>"<?php echo $institutearr['usp']; ?>"</em></div>
                                    <?php }?>


                                   </div>
							</div>
                               
                               <div class="float_L w120">
                                   <div class="float_L" style="width:124px">
									<div class="wdh100">
										<img id="categoryPageImg<?php echo $picCount; ?>" <?php if($institutearr['headerImageUrl'] != '' && $institutearr['headerImageUrl'] != NULL ){ ?> name="<?php echo $institutearr['headerImageUrl'];?>"<?php } else { ?>  name="/public/images/recommendation-default-image.jpg" <?php }  if($picCount < 6) {   if($institutearr['headerImageUrl'] != '' && $institutearr['headerImageUrl'] != NULL ){ ?> src="<?php echo $institutearr['headerImageUrl'];?>" <?php } else {?>src="/public/images/recommendation-default-image.jpg"<?php }?> width="124" <?php }else { ?> src="/public/images/recommendation-default-image.jpg"  <?php } ?> />
								   </div>
								   </div>
							   </div>
							   <div class="ml130">
								<?php if($institutearr['isSendQuery'] == 1) { ?>
                                <div class="float_L w67p">
								<?php }else{	?>
								<div class="float_L wdh100">
								<?php	} ?>
									
                                    <?php if (trim($snippet_type) == 'full' ) { ?>

                                    <div class="float_L wdh100">
                                            <div>
                                                <?php if ($institutearr['aima_rating'] != '' && $institutearr['aima_rating'] != NULL) {?>
                                                <span class="float_L">AIMA Rating:</span>
                                                <span class="aRating"><img src="/public/images/<?php echo $institutearr['aima_rating'];?>.gif"></span>
                                                <?php } ?>

                                                <?php if ($institutearr['alumin_rating'] != '' && $institutearr['alumin_rating'] != NULL && round($institutearr['alumin_rating']) != 0) { ?>
                                                <span class="float_L">Alumni Rating:&nbsp;&nbsp;</span>
                                                <span class="float_L" style="margin-top:-1px"><?php for($i =0; $i < round($institutearr['alumin_rating']);$i++){ ?><img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" style="margin-right:2px" /><?php } ?></span>
												<span class="float_L">&nbsp;<?php echo round($institutearr['alumin_rating']);?>/5</span>
                                                <?php } ?>
                                                <div class="clear_B">&nbsp;</div>
                                            </div>
                                            <?php       if(strlen($coursesarr[0]['course_title']) > $coursenamelength)
                                                        $courseName = substr($coursesarr[0]['course_title'],0,$coursenamelength) . '...';
                                                        else
                                                        $courseName = $coursesarr[0]['course_title'];
                                            ?>
                                            <div class="mt10 mb6">

                                                <?php if($institutearr['id'] == 26294)
                                                { ?>
                                                <a href="#" onClick = "openwindow('<?php echo $instiurl?>');return false;" class="bld"><?php echo $courseName; ?></a>
                                                <?php } else { ?>
                                                <a href="<?php echo $coursesarr[0]['courseurl'];?>" class="bld" title="<?php echo $courseName; ?>" ><?php echo $courseName; ?></a>
                                                <?php } ?>
                                                <span class="Fnt11"><?php $com=0; if (trim($coursesarr[0]['duration']) != '' && trim($coursesarr[0]['duration']) != NULL && trim($coursesarr[0]['duration'][0]) != NULL){
                                                                    $com =1;
                                                                    $coursesarr[0]['duration']= trim($coursesarr[0]['duration']);
                                                                    $durar= explode(' ',$coursesarr[0]['duration']);
                                                                    $durar[1]= trim($durar[1]);
                                                                    $len= strlen($durar[1]);
                                                                    $durar[0]= intval($durar[0]);
																	$durVal = $durar[0];
																	if($durVal == 0){
																		$com = 0;
																	}
                                                                    if($durar[0] > 1)
                                                                    {
                                                                          if(substr($durar[1],$len-1,$len-1) != 's')
                                                                                                               $durar[1].= 's';

                                                                    }
                                                                    elseif($durar[0]  == 1)
                                                                    {
                                                                           if(substr($durar[1],$len-1,$len-1) == 's')
                                                                                                               $durar[1]= substr($durar[1],0,$len-2);

                                                                    }
                                                                    $coursesarr[0]['duration']= implode(" ",$durar);



								    if($durVal > 0 ){
                                                                    echo ' - '.$coursesarr[0]['duration'];}}?>
                                                                    <?php $com2=0; if (trim($coursesarr[0]['course_type']) != '' && trim($coursesarr[0]['course_type']) != NULL){ $com2=1; if ($com == 1){echo ',';}else{ echo'-'; } echo ' '.$coursesarr[0]['course_type'];}?>


                                                                    <?php if( trim($coursesarr[0]['course_level']) != '' && trim($coursesarr[0]['course_level']) != NULL)
                                                                    {

                                                                           $tempCourseLevel= explode(' ',trim($coursesarr[0]['course_level']));
                                                                           $tcl='';

                                                                           for($k = 0; $k < count($tempCourseLevel) && $k < 2 ; $k++)
                                                                           {
                                                                                if($k != 0)
                                                                                    $tempCourseLevel[$k] = strtolower($tempCourseLevel[$k]);
                                                                                $tcl= $tcl.$tempCourseLevel[$k];
                                                                           }
                                                                           if ($com2 == 1)
                                                                                echo ',';
                                                                           elseif($com2 ==0 && $com == 1)
                                                                                echo ',';
                                                                           elseif($com2 ==0 && $com == 0)
                                                                                echo '-';

                                                                           if(trim($tcl) != trim($coursesarr[0]['course_level_1']))
                                                                            echo ' '.$tcl.' '.$coursesarr[0]['course_level_1'];
                                                                           else
                                                                            echo ' '.$coursesarr[0]['course_level_1'];

                                                                    }
                                                                    else {
                                                                            if ($com2 == 1)
                                                                                echo ',';
                                                                            elseif($com2 ==0 && $com == 1)
                                                                                echo ',';

                                                                            echo ' '.$coursesarr[0]['course_level_1'];
                                                                    }?>


                                               </span>
                                            </div>
                                            <div class="Fnt11 mb6"><?php $com=0; if($coursesarr[0]['approved_granted_by'] != '' && $coursesarr[0]['approved_granted_by'] != NULL){ $com=1; echo $coursesarr[0]['approved_granted_by'];}?><?php if($coursesarr[0]['affiliated_to'] != '' && $coursesarr[0]['affiliated_to'] != NULL){ if ($com ==1 ){echo ','; } if((strpos($coursesarr[0]['affiliated_to'],'Affiliated') === false) && (strpos($coursesarr[0]['affiliated_to'],'Autonomous') === false)){echo ' Affiliated to'; } echo ' '.$coursesarr[0]['affiliated_to'].' ';}?></div>
                                            <div class="Fnt11 mb8"><?php $bar=0; if ($coursesarr[0]['fees_value'] != '' && $coursesarr[0]['fees_value'] != NULL && $coursesarr[0]['fees_unit'] != '' && $coursesarr[0]['fees_unit'] != NULL && intval($coursesarr[0]['fees_value'])> 0){ $bar=1;?><span class="fcdGya">Fee:</span> <?php echo $coursesarr[0]['fees_unit'].' ';?><?php echo $coursesarr[0]['fees_value'].' '; }?> <?php if ($coursesarr[0]['eligibility_exams'] != '' && $coursesarr[0]['eligibility_exams'] != NULL){?><?php if ($bar == 1){?><span class="sepClr">|</span><?php } ?><span class="fcdGya">&nbsp;Eligibility:</span><?php echo ' '.$coursesarr[0]['eligibility_exams']; }?></div>
                                            <?php if ($coursesarr[0]['salient_features_ids'] != ''  && $coursesarr[0]['salient_features_ids'] != NULL){ ?>
                                            <div>
                                                <?php
                                                $tmpSalArr= explode(',',$coursesarr[0]['salient_features_ids']);
                                                for($sl=0;$sl < count($tmpSalArr); $sl++)
                                                {
                                                if($sl == 2){
                                                     echo '<div class="clear_L"></div>';
                                                }
                                                ?>
                                                <span class="sprt_nlt sqrBlts Fnt11 mr10 float_L" NOWRAP style="margin-right:30px;">
 
                                                <?php switch($tmpSalArr[$sl])
                                                {
                                                    case 7 :
                                                    echo 'Placement Guaranteed ';
                                                    break;

                                                    case 9 :
                                                    echo 'Placement Assured';
                                                    break;

                                                    case 8 :
                                                    echo '100% Placement Record';
                                                    break;


                                                    case 3 :
                                                    echo 'Foreign Travel';
                                                    break;


                                                    case 6 :
                                                    echo 'Foreign Exchange';
                                                    break;


                                                    case 16 :
                                                    echo 'Free SAP Training';
                                                    break;

                                                    case 17 :
                                                    echo 'Free English Language Training';
                                                    break;

                                                    case 18 :
                                                    echo 'Free Soft Skills Training';
                                                    break;

                                                    case 19 :
                                                    echo 'Free Foreign Language Training';
                                                    break;

                                                    case 1 :
                                                    echo 'Free Laptop';
                                                    break;

                                                    case 10 :
                                                    echo 'PGDM+MBA';
                                                    break;

                                                    case 12 :
                                                    echo 'In-Campus Hostel';
                                                    break;

                                                    case 14 :
                                                    echo 'Transport Facility';
                                                    break;

                                                    case 20 :
                                                    echo 'WiFi Campus';
                                                    break;

                                                    case 22 :
                                                    echo 'AC Campus';
                                                    break;

                                                }
                                                ?>
                                                </span>
                                                <?php } ?>
                                            </div>
                                            <?php }?>
                                    </div>
                                <?php } elseif(trim($snippet_type) == 'partial' ) { ?>

                                    <div class="float_L wdh100">
                                       <?php
                                        for($ca=0; $ca < count($coursesarr) && $ca < 3; $ca ++)
                                        { ?>
                                            <?php
                                            if(strlen($coursesarr[$ca]['course_title']) > $coursenamelength)
                                                $courseName = substr($coursesarr[$ca]['course_title'],0,$coursenamelength) . '...';
                                            else
                                                $courseName = $coursesarr[$ca]['course_title'];
                                            ?>

                                            <div class="mb5">

                                                <?php if($institutearr['id'] == 26294)
                                                { ?>
                                                    <a href="#" onClick = "openwindow('<?php echo $instiurl?>');return false;" class="bld"><?php echo $courseName; ?></a>
                                                <?php } else { ?>

                                                    <a href="<?php echo $coursesarr[$ca]['courseurl'];?>" title="<?php echo $courseName; ?>" class="bld"><?php echo $courseName; ?></a>
                                                <?php } ?>
                                                <span class="Fnt11"><?php $com=0; if ($coursesarr[$ca]['duration'] != '' && $coursesarr[$ca]['duration'] != NULL){

                                                                    $com =1;
                                                                    $coursesarr[$ca]['duration']= trim($coursesarr[$ca]['duration']);
                                                                    $durar= explode(' ',$coursesarr[$ca]['duration']);
                                                                    $durar[1]= trim($durar[1]);
                                                                    $len= strlen(trim($durar[1]));
                                                                    $durar[0]= intval($durar[0]);
																	$durVal= $durar[0];
																	if($durVal == 0){
																		$com = 0;
																	}
                                                                    if($durar[0] > 1)
                                                                    {
                                                                             if(substr($durar[1],$len-1,$len-1) != 's')
                                                                                                                   $durar[1].= 's';

                                                                    }
                                                                    elseif($durar[0]  == 1)
                                                                    {
                                                                         if(substr($durar[1],$len-1,$len-1) == 's')
                                                                                                $durar[1] = substr($durar[1],0,$len-1);

                                                                    }
                                                                    $coursesarr[$ca]['duration']= implode(" ",$durar);
								    
								    if($durVal > 0) {	
                                                                    echo ' - '.$coursesarr[$ca]['duration'];}}

                                                                    // Below line has been added by Amit Kuksal for fixing bug id: 44464
                                                                    if($durVal == 0)   $com =0;

?>
                                                                    <?php $com2=0; if ($coursesarr[$ca]['course_type'] != '' && $coursesarr[$ca]['course_type'] != NULL){ $com2=1; if ($com == 1){echo ',';}else{ echo'-'; } echo ' '.$coursesarr[$ca]['course_type'];}?>


                                                                    <?php if( $coursesarr[$ca]['course_level'] != '' && $coursesarr[$ca]['course_level'] != NULL)
                                                                    {

                                                                           $tempCourseLevel= explode(' ',trim($coursesarr[$ca]['course_level']));
                                                                           $tcl='';

                                                                           for($k = 0; $k < count($tempCourseLevel) && $k < 2 ; $k++)
                                                                           {
                                                                                if($k != 0)
                                                                                    $tempCourseLevel[$k] = strtolower($tempCourseLevel[$k]);
                                                                                $tcl= $tcl.$tempCourseLevel[$k];
                                                                           }
                                                                           if ($com2 == 1)
                                                                                echo ',';
                                                                           elseif($com2 ==0 && $com == 1)
                                                                                echo ',';
                                                                           elseif($com2 ==0 && $com == 0)
                                                                                echo '-';

                                                                           /*if(trim($tcl) != trim($coursesarr[$ca]['course_level_1']))
                                                                           echo ' '.$tcl.' '.$coursesarr[$ca]['course_level_1'];
                                                                           else
                                                                           echo ' '.$coursesarr[$ca]['course_level_1'];
										*/

if(trim($tcl) != trim($coursesarr[$ca]['course_level_1'])) {
                                                                                // echo ' '.$tcl.' '.$coursesarr[$ca]['course_level_1'];
                                                                               // Updated by Amit
                                                                               if(strcmp(trim($coursesarr[$ca]['course_level_1']), "Dual Degree" == 0)) {
                                                                                   switch($countArray['selectedCluster']) {
                                                                                       CASE "UG Degree":
                                                                                            if(strpos(trim($coursesarr[$ca]['course_level']), "Under Graduate") !== false){
                                                                                                    echo ' '.$tcl.' '.$coursesarr[$ca]['course_level_1'];
                                                                                            } else {
                                                                                                echo ' Undergraduate Dual Degree';                                                                                                
                                                                                            }                                                                                                
                                                                                           break;

                                                                                       CASE "PG Degree":
                                                                                            if(strpos(trim($coursesarr[$ca]['course_level']), "Post Graduate") !== false){
                                                                                                    echo ' '.$tcl.' '.$coursesarr[$ca]['course_level_1'];
                                                                                            } else {
                                                                                                echo ' Postgraduate Dual Degree';
                                                                                            }
                                                                                           
                                                                                            break;
                                                                                       default:
                                                                                            echo ' '.$tcl.' '.$coursesarr[$ca]['course_level_1'];
                                                                                            break;
                                                                                   }

                                                                               } else  //*/
                                                                                echo ' '.$tcl.' '.$coursesarr[$ca]['course_level_1'];
                                                                               
                                                                               
                                                                           } else
                                                                           echo ' '.$coursesarr[$ca]['course_level_1'];
		

                                                                    }
                                                                    else {
                                                                            if ($com2 == 1)
                                                                                echo ',';
                                                                            elseif($com2 ==0 && $com == 1)
                                                                                echo ',';

                                                                            echo ' '.$coursesarr[$ca]['course_level_1'];
                                                                    }?>


                                                </span>

                                            </div>

                                        <?php }?>
                                            <?php if(count($coursesarr) > 3){

                                                $paramforurl= array();
                                                $paramforurl['instituteId']= $institutearr['id'];
                                                $paramforurl['type']=$institutearr['institute'];
                                                $paramforurl['instituteName']=htmlspecialchars($institutearr['institute_Name']); ;

                                                if($institutearr['abbrevation'] != '' &&  $institutearr['abbrevation'] != NULL)
                                                $paramforurl['abbrevation']=$institutearr['abbrevation'];

                                                ?>

                                            <div class="pt5"><a href="<?php echo listing_detail_course_url($paramforurl);?>" class="nC_arw">View All Courses</a></div>
                                            <?php } ?>

                                    </div>



                                <?php } ?>

                                </div>
								
								<?php if($institutearr['isSendQuery'] == 1): ?>
                                <div class="float_R">
									<?php if($recommendation_page == 1): ?>
										<div class="apply_confirmation" id="apply_confirmation<?php echo $institutearr['id']?>"
												<?php if(in_array($institutearr['id'],$recommendations_applied)) echo "style='display:block;'"; ?> >
											Applied
											<input type='hidden' id="apply_status<?php echo $institutearr['id']?>" value='<?php if(in_array($institutearr['id'],$recommendations_applied)) echo "1"; else echo "0"; ?>' />
										</div>
										
										<div class="pt6">
											<input onClick = "doAjaxApply('<?php echo $institutearr['id']?>','<?php echo $coursesarr[0]['course_id']?>')" class="applyBtn88x22_small<?php if(in_array($institutearr['id'],$recommendations_applied)) echo "_disabled"; ?> mr15" id="apply_button<?php echo $institutearr['id']?>" value="&nbsp;" type="button">
										</div>
									<?php else: ?>
										<div class="bgApply">
											<?php
											if($snippet_type == "full"){
											?>
											<div class="mb5">
											<?php
											}else{
											?>
											<div style="display:none" class="mb5">
											<?php
											}
											?>
												<input onclick="updateCompareList('compare<?php echo $institutearr['id'];?>');" type="checkbox" name="compare" id="compare<?php echo $institutearr['id'];?>" value="<?php echo $institutearr['id']."::".$coursesarr[0]['course_id']."::".$institutearr['headerImageUrl']."::".htmlspecialchars($institutearr['institute_Name']).", ".$institutearr['city'];?>"/> <a  href="#" style="cursor:pointer;color:#0065DE" onclick="toggleCompareCheckbox('compare<?php echo $institutearr['id'];?>');updateCompareList('compare<?php echo $institutearr['id'];?>');return false;">Compare</a>
											</div>
											<div class="padding-left: 4px;">
												<input onClick = "naurkiLearningPageTracking(); if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;}return multipleCourseApplyForCategoryPage(<?php echo $institutearr['id']?>,'HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO');" class="getFreeBrochure" value="Request E-brochure" type="button">
											</div>
										</div>
									<?php endif; ?>	
								</div>
								<?php endif; ?>
								
                                <div class="clear_B">&nbsp;</div>
                            </div>
                        


					</div>
						<div class="clear_B">&nbsp;</div>
                        <div class="ln">&nbsp;</div>
                    </div>
					<?php
					$middelBannerCourseArray = array('1','2','3');
					if($j == 7 && !$recommendation_page && in_array($course_key_id,$middelBannerCourseArray)){
					global	$criteriaArray; 
		
					echo '<div style="text-align: center; margin: 20px 0px;">';
					$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'EIGTH','shikshaCriteria' => $criteriaArray);
					$this->load->view('common/banner',$bannerProperties);
					echo '</div>';
					echo '<div class="ln">&nbsp;</div>';
					 }
					 
					 ?>
					
                                <?php } ?>
                                <!--End_Listing-->
				
				
				 <script>
                                <?php if($hideQuery): ?>
                                    if(typeof(document.getElementById('selectalldiv')) != 'undefined' && document.getElementById('selectalldiv') != null)
                                    document.getElementById('selectalldiv').style.display = 'none';
                                <?php endif; ?>
                                
                                window.onload = function(){
                                lazyLoadCategoryPageImages();
                                }
                                function lazyLoadCategoryPageImages(){
                                    var countCategoryPageImage= <?php echo $countInstitutesShown ;?> ;
                                    if(countCategoryPageImage > 5)
                                    {
                                        for(var i= 6;i <= countCategoryPageImage; i++)
                                        {

                                            var imgObj= document.getElementById('categoryPageImg'+i);
                                            var url= imgObj.name;
                                            imgObj.src= url;
                                            if(imgObj.complete)
                                            {
                                                imgObj.height= 100;
                                                imgObj.width= 124;
                                            }
                                         }
                                    }
                                }
                                
                                function naurkiLearningPageTracking(){
                                    
                                    <?php if($naukrshikshapage == 1): ?>
                                                    trackEventByGA('LinkClick','CATEGORY_APPLY_NAUKRI');
                                    <?php else: ?>
                                                    trackEventByGA('LinkClick','CATEGORY_APPLY_NORMAL');
                                    <?php endif; ?>
			                    }
			                    
								var currentPageName= 'Category Page';
								
								
								function doAjaxApply(institute_id,course_id)
								{
									trackEventByGA('LinkClick','CATEGORY_APPLY_RECO');
									var apply_status = document.getElementById("apply_status"+institute_id).value;
									
									if(apply_status == '0')
									{
										document.getElementById("apply_button"+institute_id).className = "applyBtn88x22_small_disabled mr15";
										
										var paraString = document.getElementById('params'+institute_id).value;
									
										var url = "/MultipleApply/MultipleApply/getBrochureRequest";
										new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){
											document.getElementById("apply_confirmation"+institute_id).style.display = 'block';
											document.getElementById("apply_status"+institute_id).value = '1';
											/*
											 Store value in cookie
											*/
											
											var recommendation_applied = getCookie('recommendation_applied');
											if(recommendation_applied)
											{
												recommendation_applied = recommendation_applied+','+institute_id;
											}
											else
											{
												recommendation_applied = institute_id;
											}
											setCookie('recommendation_applied',recommendation_applied);
										}});
									}
								}

                                </script>
