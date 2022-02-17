<?php
$tests_preparation = json_decode(htmlspecialchars_decode($details['tests_preparation']),true);
$tests_preparation_other = json_decode(htmlspecialchars_decode($details['tests_preparation_other']),true);
?>
                    <div class="courseBT bld fontSize_14p">Course</div>
                    <div class="lineSpace_7">&nbsp;</div>
                    <div>
                        <ul class="cdBT" type="square" style="margin:0 5px;padding:0 13px">
                      
                            <?php
                                if($details['title']!='') {
                                ?>
                            <li>                         
                                <div class="OrgangeFont bld">Course Name:</div>
                                <div><?php echo $details['title']; ?></div>
                            </li>
                            <?php } ?>

                            <?php
                                if($details['approvedBy']!='' && $details['approvedBy'] !='0') {
                                ?>
                            <li>                         
                                <div class="OrgangeFont bld">Aproved/Granted by:</div>
                                <div><?php echo $details['approvedBy']; ?></div>
                            </li>
                            <?php } ?>
 
                            <?php
                                if($details['course_type']!='') {
                                ?>
                            <li>                         
                                <div class="OrgangeFont bld">Mode of Learning:</div>
                                <div><?php echo $details['course_type']; ?></div>
                            </li>
                            <?php } ?>

                            <?php
                                if($details['course_level']!='') {
                                ?>
                            <li>                         
                            <div class="OrgangeFont bld">Course Level:</div>
                                <div><?php echo $details['course_level']; ?><?php echo (isset($details['course_level_1']) && ($details['course_level_1'] !=''))?": ".$details['course_level_1']:""; ?>
                                    <?php if($details['course_level_2'] !=''){
                                            echo ' , '.$details['course_level_2'];
                                    } ?>
                                <?php if(count($tests_preparation) > 0 || count($tests_preparation_other) > 0) { ?>
                                    <?php 
                                        if(count($tests_preparation) > 0){
                                            echo "<a href='".$tests_preparation[0]['url']."'>".$tests_preparation[0]['blogTitle']."</a>"; 
                                            for($i = 1;$i< count($tests_preparation); $i++){
                                                echo ",<a href='".$tests_preparation[$i]['url']."'>".$tests_preparation[$i]['blogTitle']."</a>"; 
                                            }
                                        }
                                    if(count($tests_preparation_other) > 0){
                                        echo (count($tests_preparation) > 0)?", ":"";
                                        echo "&nbsp;";
                                        echo $tests_preparation_other[0]['exam_name'];
                                        for($i = 1;$i< count($tests_preparation_other); $i++){
                                            echo $tests_preparation_other[$i]['exam_name']; 
                                        }
                                    }

                                } ?>

                                </div>
                            </li>                            
                            <!--</li>-->
                            <?php } ?>
                            
                            <?php
                                if($details['duration_value']!='') {
                                ?>
                            <li>                         
                                <div class="OrgangeFont bld">Duration:</div>
                                <div><?php echo $details['duration_value']; ?> <?php echo $details['duration_unit']; ?></div>
                            </li>
                            <?php } ?>
                            
                            <?php
                                if($details['fees_value']!='') {
                                ?>
                            <li>                         
                                <div class="OrgangeFont bld">Course Fees:</div>
                                <div><?php echo $details['fees_unit']; ?> <?php echo $details['fees_value']; ?></div>
                            </li>
                            <?php } ?>
                            
                            <?php
                                ($details['seats_general']!='')? $tmp1=true: $tmp1=false; 
                                ($details['seats_reserved']!='')? $tmp2=true: $tmp2=false; 
                                ($details['seats_management']!='')? $tmp3=true: $tmp3=false; 
                                if( $tmp1 || $tmp2 || $tmp3) { 
                                ?>
                            <li>                         
                                <div class="OrgangeFont bld">Number of seats:</div>
                                <div>
                                    <?php if($tmp1){ ?>
                                    General: <?php echo $details['seats_general']; ?><br />
                                    <?php } ?>
                                    <?php if($tmp2){ ?>
                                    Reserved(SC/ST/OBC): <?php echo $details['seats_reserved']; ?><br />
                                    <?php } ?>
                                    <?php if($tmp3){ ?>
                                    Management: <?php echo $details['seats_management']; ?><br />
                                    <?php } ?>
                                </div>
                            </li>
                            <?php } ?>
                            
                            <?php 

                                $tmp1 =explode(" ",$details['date_form_submission']); 
                                $tmp2 =explode(" ",$details['date_result_declaration']); 
                                $tmp3 =explode(" ",$details['date_course_comencement']);
                                ($tmp1[0] !="" &&  $tmp1[0]!="0000-00-00" && $tmp1[0] !="1970-01-01") ? $tmp1 = true: $tmp1 = false;
                                ($tmp2[0] !="" && $tmp2[0]!="0000-00-00" && $tmp2[0] !="1970-01-01") ? $tmp2 = true: $tmp2 = false;
                                ($tmp3[0] !="" && $tmp3[0]!="0000-00-00" && $tmp3[0] !="1970-01-01") ? $tmp3 = true: $tmp3 = false;
                                if( $tmp1 || $tmp2 || $tmp3) { 
                            ?>
                            <li>                         
                                <div class="OrgangeFont bld">Important dates:</div>
                                <div>
                                    <?php if($tmp1){ ?>
                                    Form Submission: <?php echo date('M j, Y',strtotime($details['date_form_submission'])); ?><br />
                                    <?php } ?>
                                    <?php if($tmp2){ ?>
                                    Declaration of Results: <?php echo date('M j, Y',strtotime($details['date_result_declaration'])); ?><br />
                                    <?php } ?>
                                    <?php if($tmp3){ ?>
                                    Course Commencement: <?php echo date('M j, Y',strtotime($details['date_course_comencement'])); ?><br />
                                    <?php } ?>
                                </div>
                            </li>
                            <?php } ?>

                            <?php
                                if($details['application_form_url']!='') {
                                ?>
                                <li>                         
                                <div class="OrgangeFont bld">Application Form:</div>
                                <div>
                                <?php 
                                if ($details['form_upload'] == 'yes' ) {
                                // file downlod
                                ?>
                                    <a                                   href="/enterprise/ShowForms/downloadfile/<?php echo base64_encode($details['application_form_url']);?>"
                                    >Download application form here</a>
                                <?php    
                                } elseif ($details['form_upload'] == 'no' ) {
                                    // open url in another window
                                        $url_check = parse_url($details['application_form_url']);
                                    if (empty($url_check['scheme'])) {
                                        $url  = 'http://' . $details['application_form_url'];   
                                    } else {
                                        $url =   $details['application_form_url'];
                                    }  
                                ?>
                                <a href="#" onclick="javascript:window.open('<?php echo $url; ?>','','resizable=yes,scrollbars=yes,status=yes');">Download application form here</a>
                                <?php    
                                }
                                ?>
                                </div>
                                </li>
                                <?php } ?>
                            </ul>
                  </div>
