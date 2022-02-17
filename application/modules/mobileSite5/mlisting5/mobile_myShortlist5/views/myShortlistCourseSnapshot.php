<?php
      $this->load->view('mobile_myShortlist5/myShortlistAssetsLoader');
?>


<div id="wrapper" data-role="page" class="of-hide">
    <?php
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    <?php $this->load->view('mobile_myShortlist5/myShortlistHeader'); ?>
   
    <?php $this->load->view('mobile_myShortlist5/myShortlistCourseDetailSnapshot'); ?>
           
              
</div>
<div data-role="dialog" id="walkthroughHTML" data-enhance="false" class="of-hide" style="background-color: #3B3B34;"></div>
<?php 
    $this->load->view('mcommon5/footer'); 
    $this->load->view('mobile_myShortlist5/myShortlistFooterScript');
?>
