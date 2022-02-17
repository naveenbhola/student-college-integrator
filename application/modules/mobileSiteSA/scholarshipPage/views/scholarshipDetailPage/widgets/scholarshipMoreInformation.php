<?php 
$faqLink      = $scholarshipObj->getApplicationData()->getFaqLink();
$contactEmail = $scholarshipObj->getApplicationData()->getContactEmail();
$contactPhone = $scholarshipObj->getApplicationData()->getContactPhone();

if(!empty($faqLink) || !empty($contactEmail) || !empty($contactPhone) || $scholarshipObj->getDeadline()->getAdditionalInfo() != ''){
?>
    <h2 class="titl-main">More Information</h2>
    <div class="info-det">
        <?php 
        if(!empty($faqLink)){
        ?>
        <div class="info-dv">
            <label>FAQs Link</label>
            <a target="_blank" href="<?=$faqLink?>"><?=(strlen($faqLink) > 33) ? substr($faqLink, 0, 30).'...':$faqLink;?><i class="sprite arrow-icon"></i></a>
        </div>
        <?php 
        }
        if(!empty($contactEmail)){
        ?>
        <div class="info-dv">
            <label>Contact email</label>
            <a href="mailto:<?=$contactEmail?>"><?=$contactEmail?></a>
        </div>
        <?php 
        }
        if(!empty($contactPhone)){
        ?>
        <div class="info-dv">
            <label>Phone number</label>
            <a href="tel:<?=$contactPhone?>"><?=$contactPhone?></a>
        </div>
        <?php 
        }
        ?>
    </div>
<?php 
    echo $scholarshipObj->getDeadline()->getAdditionalInfo();
}
?>