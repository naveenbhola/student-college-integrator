<?php 
if($_REQUEST['loadFromStatic']) {
	echo Modules::run('/shiksha/getHomepageFeaturedTextAds', 'html');
}
?>
<div id="homepageMiddlePanel" class="main_content reset">
    <div class="bdL">
    	<!--?php $this->load->view('home/homepageRedesign/homepage_v3/infoFold'); ?-->
        <?php $this->load->view('home/homepageRedesign/homepage_v3/categoryFold'); ?>
        <?php $this->load->view('home/homepageRedesign/homepage_v3/featuredFold'); ?>
        <?php $this->load->view('home/homepageRedesign/homepage_v3/expertFold'); ?>
        <?php $this->load->view('home/homepageRedesign/homepage_v3/articleFold'); ?>
        <?php $this->load->view('home/homepageRedesign/homepage_v3/marketingFold'); ?>
    </div>
</div>