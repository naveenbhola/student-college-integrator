<?php $this->load->view('header'); ?>
<div id="wrapper" data-role="page" style="min-height:413px;padding-top:40px;">

   <?php
	 echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	 echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true, '', 'termCondition');?>
    </header>
    <?php
        $this->load->view('mcommon5/dfpBannerCode');
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>


    <script>if (history.length<=1) { $('#backButton').hide();}</script>
    <style>
       <?php $this->load->view('shikshaHelp/policyCSS',array());?>
    </style>
    <?php $this->load->view('shikshaHelp/termConditionContent');?>
    <?php $this->load->view('footerLinks'); ?>


<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('footer'); ?>
