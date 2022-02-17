        <?php
        $i = 0;
        foreach($mentorListData as $mentorList)
        {
                $imageURL = ($mentorList['imageURL']=="")?"public/mobile5/images/mentor-default-image.jpg":$mentorList['imageURL'];
                $name = $totalMentor['mentorInformation'][$mentorList['userId']]['firstname'].' '.$totalMentor['mentorInformation'][$mentorList['userId']]['lastname'];
                if($mentorList['semester'] == '1' || $mentorList['semester'] == '2')
                {
                         $yearPersuing =  1;
                         $superscript = 'st';
                }
                elseif ($mentorList['semester'] == '3' || $mentorList['semester'] == '4') {
                         $yearPersuing =  2;
                         $superscript = 'nd';
                }
                elseif ($mentorList['semester'] == '5' || $mentorList['semester'] == '6') {
                         $yearPersuing =  3;
                         $superscript = 'rd';
                }
                elseif ($mentorList['semester'] == '7' || $mentorList['semester'] == '8')
                {
                         $yearPersuing =  4;
                         $superscript = 'th';
                }

        ?>

                        <li>
                                <div class="clearfix">
                                <div class="student-image"><img width="83" height="115" alt="Mentor Image" src="<?=$imageURL?>"></div>
                                <div class="student-info">
                                        <a class="student-link" style="text-decoration:none;"><?= strlen($name)>25 ? substr($name, 0, 25).'..' : $name; ?></a> 
					<?php if($yearPersuing!='' && $yearPersuing!=0){ ?><span class="year-tag"><?= $yearPersuing != '' ?$yearPersuing : ''?><sup><?= $superscript != '' ?$superscript : ''?></sup> Yr</span><?php } ?>
                                    <ul>
                                        <li>
                                                <label>College:</label>
                                                <a href="<?=$mentorList['instituteURL']?>" class="mentor-info" target="_blank"><?= strlen($mentorList['instituteName'])>40 ? substr($mentorList['instituteName'], 0, 40).'..' : $mentorList['instituteName']?></a>
                                        </li>
                                        <li>
                                                <label>Branch:</label>
                                                <a class="mentor-info" href="<?=$mentorList['courseURL']?>" target="_blank"><?= strlen($mentorList['courseName'])>40 ? substr($mentorList['courseName'], 0, 40).'..' : $mentorList['courseName']?></a>
                                        </li>
                                        <li>
                                                <label>City:</label>
                                                <p class="mentor-info-2"><?=$mentorList['city']?></p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mentee-answer-sec">
                                <ul>
                                    	<li><span class="count-clr"><?=$mentorList['menteeCount']?></span>  <strong><?php if($mentorList['menteeCount']=='1') echo 'Mentee'; else echo 'Mentees';?></strong></li>
                                    <li class="last"><span class="count-clr"><?= $totalMentor['mentorAnsCount'][$mentorList['userId']]['totalAns'] >0 ? $totalMentor['mentorAnsCount'][$mentorList['userId']]['totalAns'] : 0?></span> <strong><?php if($totalMentor['mentorAnsCount'][$mentorList['userId']]['totalAns']==1) echo 'Answer'; else echo 'Answers';?></strong></li>
                                </ul>
                            </div>
                        </li>




        <?php }?>

