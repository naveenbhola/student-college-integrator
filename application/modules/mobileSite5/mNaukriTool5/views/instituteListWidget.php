<section class="content-section">
    <div class="naukri-ins-list">
    <ul>
<?php
if($total > 0){
    $pageTotal = count($instituteObjs);
    $i = 0;
    foreach($instituteObjs as $institute){
        $i++;
    ?>
        <a href="#naukri-widget-right-col" style="display:none;" id="instituteLayerButton-<?php echo $institute->getId()?>" data-rel="dialog" data-inline="true" data-transition="slide" title="<?php echo $institute->getName()?>">&nbsp;</a>
        <li class="<?php echo ($i == $pageTotal)?'last':''?>" instituteid="<?php echo $institute->getId()?>" onclick="NaukriToolComponent.openInstituteDetailLayer(this, '<?php echo $institute->getId()?>','<?=$trackingPageKeyId?>');">
            
            <div class="ins-detail">
                <strong style="color:#006FA2;"><?php echo $institute->getName()?></strong>
                <span><?php echo $institute->getMainLocation() ?$institute->getMainLocation()->getCityName():'';?></span>
                <p class="salary-info">Average Alumni Salary: <strong><?php echo number_format($ctc50[$institute->getId()], 1, '.', '')?> L</strong></p>
            </div>
            <!----shortlist-course---->
				
            <?php
            
            $data['courseId'] = $courseIdForShorlist[$institute->getId()];
            $data['listing_id'] = $institute->getId();
            $data['listing_type'] = $institute->getType();
            $data['pageType'] = 'MOB_CareerCompass_Shortlist';
            $data['tracking_keyid'] = $shortlistTrackingPageKeyId;
            if($data['courseId'] > 0 && $data['courseId'] != ''){
                $this->load->view('widgets/shortlistStar',$data);
            }
            
            ?>
            
            <!-----end-shortlist------>
        </li>
    <?php
    }
}
else{
?>
    <li>Sorry! No results found for your selected options. Please modify your search.</li>
<?php
}
?>
    </ul>
    </div>
</section>
<?php
if($pageNo == 0)
{
?>
    <script>
        NaukriToolComponent.totalInsts = '<?php echo $total;?>';
        NaukriToolComponent.pageSize   = '<?php echo $pageSize;?>';
        $('#totalCount').html(NaukriToolComponent.totalInsts);
    </script>
<?php
}
?>