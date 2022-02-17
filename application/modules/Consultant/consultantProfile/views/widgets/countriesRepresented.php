<?php
 //_p($countriesRepresentedTabData);
?>
<div class="overview-details flLt countriesRepresented-tab" style="width:100%;">   <!--display:none; has been removed-->
    <h2>Countries where <?php echo htmlentities($consultantObj->getName());?> has sent students</h2>
    <div class="cons-scrollbar1 clearwidth scrollbar1 soft-scroller">
  	<div class="cons-scrollbar scrollbar" style="visibility:hidden; left: 8px;">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:400px">
            <div class="overview" style="width: 100%">
                <ul class="consultant-country-list">
                        <li>
                            <div class="country-sec">
                                <strong>Country name</strong>
                            </div>
                            <div class="detail-sec">
                                <strong>No of colleges represented</strong>
                            </div>
                        </li>
                    <?php foreach($countriesRepresentedTabData['countriesRepresented'] as $k => $v){ ?>
                    
                    <li>
                        <div class="country-sec">
                            <i class="flags <?php echo str_replace(" ",'',(strtolower($v))); ?> flLt" title="<?php echo $v; ?>"></i>
                            <span><?php echo $v; ?></span>
                        </div>
                        <div class="detail-sec">
                            <p><?php  echo ($countriesRepresentedTabData['universitiesCount'][$k]>1)? $countriesRepresentedTabData['universitiesCount'][$k]." Colleges" :$countriesRepresentedTabData['universitiesCount'][$k]." College"; ?></p>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>