<?php
    if($downloadMessageType == "scholarshipDownloadBrochure")
    {
        $topTitle = htmlentities($dlScholarshipBrochureData['scholarshipObj']->getName());
    }else{
        $topTitle = htmlentities($dlBrochureData['courseObj']->getName())." from ".htmlentities($dlBrochureData['courseObj']->getUniversityName()); 
    }
?>
<div class="Thanku-dwnWidget">
    <p>
        <span class="checkmark">
            <span class="checkmark_circle"></span>
            <span class="checkmark_stem"></span>
            <span class="checkmark_kick"></span>
        </span>
        <strong>Brochure of <?php echo $topTitle; ?> sent to email:</strong>
    </p>
    <p><strong><?php echo $email; ?></strong></p>
    <a href="<?php echo SHIKSHA_STUDYABROAD_HOME.$customReferer; ?>">&lt; Go back to <?php echo ($customReferer == '/'?'Home':$refererTitle); ?></a>
</div>
<?php if(!is_null($dlBrochureData['reco'])){ ?>
<div class="Thanku-dwnWidget noMargin">
    <p>
    <strong>Students who downloaded above brochure were also interested in:</strong>
    </p>
</div>
<?php echo $dlBrochureData['reco']; 
    } 
?>