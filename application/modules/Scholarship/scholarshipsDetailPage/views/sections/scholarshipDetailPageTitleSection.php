<?php
    if($scholarshipObj->getCategory() == 'external'){
        $universityName = $scholarshipObj->getOrganisationName();
        $organisationLogo = $scholarshipObj->getOrganisationLogo();
    } 
?>
<div class="col-start">
<?php $this->load->view('listing/abroad/widget/breadCrumbs'); ?>
    <div class="wrap-c">
        <div class="top-sidebar">
            <img src="<?php echo $organisationLogo; ?>">
        </div>
        <div class="right-bar">
          <h1 class="page-titl"><?php echo $scholarshipObj->getName(); ?></h1>
          <?php if($scholarshipObj->getCategory() == 'internal'){ ?>
            <p class="p-fnt">Awarded by : <a href="<?php echo $universityUrl ?>"><?php echo $universityName; ?></a></p>    
          <?php } else { ?>
            <p class="p-fnt">Awarded by : <?php echo $universityName; ?></p>
          <?php } ?>
        </div> 
        <div class="cleart"></div>
    </div>
</div> 