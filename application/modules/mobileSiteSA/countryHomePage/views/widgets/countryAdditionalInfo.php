<div class="cntry-subWidgetDetail clear">
    <h2 class="country-mainTitle" style="margin:0;">About <?php echo $countryObj->getName(); ?></h2>
    <div class="clearfix"></div>
</div>
<?php
$this->load->view('widgets/countryVisaRelatedInfo');
$this->load->view('widgets/countryStudyAndWorkRelatedInfo');
$this->load->view('widgets/countryEconomicOverviewInfo');
?> 
