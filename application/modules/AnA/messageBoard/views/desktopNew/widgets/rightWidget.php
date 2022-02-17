<div class="ana-col-md-4">
 <div class="ana-right" id="rightSideWidgets">

  <?php
      if( isset($pageName) && ($pageName == 'QDP' || $pageName == 'tagDetailPage') ){
        if($userStatus == 'false'){
                $this->load->view("messageBoard/desktopNew/widgets/rightRegistrationWidgetNew");
        }
      }
      if($pageType == 'question'){
          echo Modules::run("Interlinking/InterlinkingFactory/getEntityRHSWidget", $tagRHSwidgetData,'questionDetailPage');
      }
  ?>

 <div id="rightStickyWidgetQDP">
  <?php  
      if(isset($pageName) && $pageName == 'QDP'){
        $this->load->view("messageBoard/desktopNew/widgets/rightAskSearchTagWidget");
      }
      if($userStatus == 'false' && $showRightRegirationWidget)
      $this->load->view("messageBoard/desktopNew/widgets/rightRegistrationWidget");

      if($showExpertsPanelLink)
      $this->load->view("messageBoard/desktopNew/widgets/rightExpertsPanelLink");

      if(isset($pageName) && $pageName == 'QDP'){
        $this->load->view("messageBoard/desktopNew/widgets/rightSideLinks");
      }else{
        $this->load->view("messageBoard/desktopNew/widgets/rightUserWidget");
      }

  ?>
  </div>

  <?php
	if( isset($pageName) && ($pageName == 'QDP' || $pageName == 'tagDetailPage' || $pageName == 'ANAHomepage') ){
		$this->load->view('dfp/dfpCommonRPBanner',array('bannerPlace' => 'RP','bannerType'=>"rightPanel"));
	}
  ?>
  </div>
</div>
<script type="text/javascript">
<?php if( $pageType == 'question') { ?>
    var lazydBRecolayerCSS = '//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('searchTuple'); ?>';  
<?php  } ?>
</script>
