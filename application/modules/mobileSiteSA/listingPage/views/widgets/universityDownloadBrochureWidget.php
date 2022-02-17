<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 24/5/18
 * Time: 1:04 PM
 */
?>
<section class="detail-widget">

    <?php
    $brochureDataObj['trackingPageKeyId'] = 1617;
    $brochureDataObj['widget'] = 'email_link';
    ?>
    <div class="detail-widegt-sec">
        <div class="detail-info-sec dynamic-content">
            <strong class="help-strong">Email Brochure</strong>
            <p>Get important details of this university and its course fees, eligibility, living expenses by emailing the brochure to yourself.</p>
            <a id= "downloadBrochure" class="btn btn-primary btn-full mb15" href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?=base64_encode(json_encode($brochureDataObj))?>',this);" ><span class="vam">Email Brochure</span></a>
        </div>
    </div>
</section>
