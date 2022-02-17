<div class="widget-wrap clearwidth acco-details dyanamic-content">
    <h2>Accommodation details</h2>
    <?php if(!empty($universityAccomodationDetails)) {?>
    <?php echo $universityAccomodationDetails; ?><?php } ?>
    <?php if(($universityAccomodationURL)) { ?>
    <p><a  target="_blank" rel="nofollow" href="<?=$universityAccomodationURL?>" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'outgoingLink');"><?php echo (!empty($universityAccomodationDetails)? " More about accommodation ":"Accommodation ");?> details on university website<i class="common-sprite ex-link-icon"></i></a></p>
    <?php } ?>
</div>