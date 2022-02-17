<div class="Thanku-dwnWidget">
    <p>
        <span class="checkmark">
            <span class="checkmark_circle"></span>
            <span class="checkmark_stem"></span>
            <span class="checkmark_kick"></span>
        </span>
        <strong>The guide has been sent to email:</strong>
    </p>
    <p><strong><?php echo $email; ?></strong></p>
    <a href="<?php echo SHIKSHA_STUDYABROAD_HOME.$customReferer; ?>"><i class="custm-arrw left"></i>&lt; Go back to <?php echo ($customReferer == '/'?'Home':$refererTitle); ?></a>
</div>
<?php if(count($relatedGuides)>0){ ?>
<div class="Thanku-dwnWidget noMargin">
    <p>
    <strong>Students who downloaded above student guide were also interested in:</strong>
    </p>
</div>
<?php foreach($relatedGuides as $guide){ ?>
<div class="Thanku-dwnWidget">
    <div class="guide-Box">
        <div class="guide-img"><img src="<?php echo $guide['contentImageURL']; ?>"></div>
        <div class="guide-info">
            <a href="<?php echo $guide['contentUrl']; ?>"><?php echo $guide['strip_title']; ?></a>
        </div>
    </div>
    <p><?php echo $guide['summary']; ?></p>
    <div class="guide-mailBtn">
        <a href="Javascript:void(0);" onclick="downloadGuidePDF('<?php echo $guide['contentId']; ?>',1945,'<?php echo $guide['contentType']; ?>',null,this);" referer="<?php echo $customReferer; ?>" refererTitle="<?php echo $refererTitle; ?>">Email guide</a>
    </div>
    <?php if($guide['downloadCount']>50){ ?>
    <span class="dnld-count"><?php echo $guide['downloadCount']; ?> people downloaded this guide.</span>
    <?php } ?>
</div>
<?php } 
} ?>