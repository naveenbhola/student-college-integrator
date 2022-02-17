<?php
if($identifier=='institute'){
$viewCount =  $details['viewCount'];
$summaryCount =  $details['summaryCount'];
}
if($identifier=='course'){
$viewCount =  $details['courseDetails']['0']['viewCount'];
$summaryCount =  $details['summaryCount'];
}
?>
<div class="wdh100">
                            <div class="wdh100">
                                <div class="nlt_head Fnt14 bld mb10">Shiksha Analytics</div>
                                <div class="mlr5">
                                    <ul class="rndBlts">
                                        <?php
                                        if($details['packType']=='1'||$details['packType']=='2'){if($countResponseFormDetails>0){
                                        $message = ($countResponseFormDetails>1)?" people have already applied":" person has already applied";
                                        ?>
                                        <li class="mb6 Fnt11" id="studentsApplied"><?php echo $countResponseFormDetails; echo $message?> </li>
                                        <?php }}?>
                                        <li class="mb6 Fnt11" id="studentsViewCount">The <?php echo $identifier?> has been viewed <?php echo $viewCount; ?> times</li>
                                        <li class="mb6 Fnt11" id="studentsSearchCount">The institute has appeared in <?php echo $summaryCount; ?> searches</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
 <div class="lineSpace_20">&nbsp;</div>
