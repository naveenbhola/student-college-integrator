<?php
$footerComponents = array(
                'commonJSV2'=>true,
                'loadLazyJSFile' => true,
                'js'=> array('scholarshipPageSA','scholarshipResponseSA'),
                'openSansFontFlag'=>true,
                'trackingPageKeyIdForReg' => $trackingPageKeyIdForReg,
                'pages'=> array('scholarshipDetailPage/widgets/intakeYearLayer',  'scholarshipDetailPage/widgets/countryLayer', 'scholarshipDetailPage/widgets/incorrectScholarshipFeedbackLayer')
            );
$this->load->view('commonModule/footerV2', $footerComponents);
?>
<script>
    <?php if($ctaClicked==true){?>
       var ctaId = '<?php echo $ctaId;?>';
    <?php } ?>
</script>