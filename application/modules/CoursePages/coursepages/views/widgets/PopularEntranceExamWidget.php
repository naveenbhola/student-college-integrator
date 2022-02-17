<div class="widget-wrap">
        <h2><?php echo $widgetObj->getWidgetHeading(); ?></h2>
    <div>
        <?php foreach ($widgets_data['popularEntranceExamWidget'] as $oneDataIndex => $oneData) { 
            $yearVal = (!empty($oneData['examYear']))?' '.$oneData['examYear']:'';
            $displayTitle = $oneData['examName'].$yearVal;
            ?>
            <div class="crs-hmCrd">
                <a href="<?php echo $oneData['urlKey']; ?>" class="exam-titl" title=""<?php echo $oneData['examName']; ?>""><?php echo strlen($displayTitle) >= 16 ? substr($displayTitle, 0, 13).'...': $displayTitle; ?></a>
                <p class="exam__name" title="<?php echo $oneData['examFullName']; ?>"><?php echo htmlentities(substr($oneData['examFullName'], 0, 52));if(strlen(htmlentities($oneData['examFullName']))>52){echo '...'; } ?></p>
                <a href="<?php echo $oneData['urlKey']; ?>" class="view-more-examdtls">View Exam Details >></a>
            </div>
        <?php } ?>
        
    </div>
</div>


