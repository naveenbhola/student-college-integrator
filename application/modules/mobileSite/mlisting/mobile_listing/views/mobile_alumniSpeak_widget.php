<?php if(!empty($alumniReviews)){?>
<?php
$countPlacement =0;
$placement = 0;
$countFaculty =0;
$faculty = 0;
$countInfrastructure =0;
$infrastructure = 0;
$countOverall =0;
$overall = 0;
foreach($alumniReviews as $alumniReview ){
    if($alumniReview['status']== 'published'){
        if($alumniReview['criteria_id'] == 1){
            $countInfrastructure += $alumniReview['criteria_rating'];
            if($alumniReview['criteria_rating']!=0)
                $infrastructure ++;
        }
        if($alumniReview['criteria_id'] == 2){
            $countFaculty += $alumniReview['criteria_rating'];
            if($alumniReview['criteria_rating']!=0)
                $faculty++;	
        }
        if($alumniReview['criteria_id'] == 3){
            $countPlacement += $alumniReview['criteria_rating'];
            if($alumniReview['criteria_rating']!=0)
                $placement++;
        }
        if($alumniReview['criteria_id'] == 4){
            $countOverall += $alumniReview['criteria_rating'];
            if($alumniReview['criteria_rating']!=0)
                $overall++;
        }
    }
}
if($infrastructure!=0)
    $countInfrastructure = round($countInfrastructure/$infrastructure);
if($placement!=0)
    $countPlacement = round($countPlacement/$placement);
if($faculty!=0)
    $countFaculty = round($countFaculty/$faculty);
if($overall!=0)
    $countOverall = round($countOverall/$overall);
?>
                                    <?php if(($countInfrastructure!=0)||($countPlacement!=0)||($countFaculty!=0)||($countOverall!=0)){?>
                                        <div class="round-box"> 
                            <div id="alumini-speak">
                                <h2>Alumni Speak</h2>   
                    <ul>
                                            <li>

                            <?php if($countPlacement!=0){?>
                                <label>Placements</label>
                            <div>
                            <?php for($i=0;$i<$countPlacement;$i++){?>
                                                            <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                                    <?php  } ?>
                            <?php for($i=5;$i>$countPlacement;$i--){?>
                                                        <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                     <?php }?>

                            <?php echo $countPlacement; ?>/5 </div><?php }?>
                        </li>
                        <li>

                                    <?php if($countInfrastructure!=0){?>   
                                <label>Infrastructure/ Facilities</label>	 
                                                   <div>	<?php for($i=0;$i<$countInfrastructure;$i++){?>
                                                            <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                                        <?php  } ?>
                                                        <?php for($i=5;$i>$countInfrastructure;$i--){?>
                                                            <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                        <?php }?>
                                                        <?php echo $countInfrastructure; ?>/5

                                               </div><?php }?>
                    </li>

                     <li>

                                        <div>
                                    <?php if($countFaculty!=0){?>
                                                        <label>Faculty</label>
                                                    <?php for($i=0;$i<$countFaculty;$i++){?>
                                                                 <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                                        <?php  } ?>
                                                        <?php for($i=5;$i>$countFaculty;$i--){?>
                                                            <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                        <?php }?>
                                                        <?php echo $countFaculty; ?>/5

                                                </div>
                                                    <?php }?>						

                    </li>
                    <li>

                                    <div>  
                            <?php if($countOverall!=0){?>
                                                    <label>Overall Feedback</label>
                                                    <?php for($i=0;$i<$countOverall;$i++){?>
                                                            <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                                    <?php  } ?>
                                                    <?php for($i=5;$i>$countOverall;$i--){?>
                                                        <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                    <?php }?>
                                                     <?php echo $countOverall; ?>/5

                                            </div>
                                                <?php }?>
                            </li>
                  </ul>
                    </div>
                    </div> 
                        <?php } }?>








