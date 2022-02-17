<!-- Load Country Home Header -->

<?php $this->load->view('CountryHomeHeader'); ?>

<!-- Country Home Bread Crumb File -->
<?php $this->load->view('countryHomeBreadcrumb');?>

    <div class="content-wrap clearfix">  
        <div class="country-wrapper clearwidth">
            
            <!-- Load title and change country layer  -->
            <?php $this->load->view('countryHomeTitle'); ?>
            
            
            <!-- Load Content Page containing widgets -->
            <div class="country-list-wrap">
                <?php $this->load->view('countryHomeContent')?>
            </div>
        </div>
    </div>

<?php
    // Country Home Footer File
    $this->load->view('CountryHomeFooter');
?>
