<?php
ob_start('compressHTML');
$tempJsArray = array('common');
$tempCssArray = array();
//$bannerProperties = array('pageId'=>'TAGS', 'pageZone'=>'HEADER');
$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
$headerComponents = array(
                'css'   =>  array('ana'),
                'js' => array(),
                'jsFooter'=>    $tempJsArray,
                'cssFooter' => $tempCssArray,
                'showBottomMargin' => false,
                'product' => 'anaDesktopV2',
                'title' =>      $seoTitle,
                'metaDescription' => $seoDescription,
                'canonicalURL' =>$canonicalURL,
                'metaKeywords' => '',
                'lazyLoadJsFiles' => true,
    'bannerProperties' => $bannerProperties
);
$this->load->view('common/header', $headerComponents);
?>
    <div class="ana-main-wrapper">
       <div class="ana-container">
           <div class="row">
                <!--breadcombs start--> 
                 <div class="breadcrumb3">
                        <span><a href="<?php echo SHIKSHA_HOME;?>"><span><i class="icons ic_brdcrm homeType1"></i></span></a></span>
                        <span class="breadcrumb-arrow">›</span>
                        <span><a href="<?php echo SHIKSHA_ASK_HOME;?>"><span>Ask &amp; Answer</span></a></span>
                        <span class="breadcrumb-arrow">›</span>
                        <span class="page-t">Experts Panel</span>
                 </div>    
                 <!---->
                 
                 <div class="panel-container">
                 <!--panel heading-->
                     <div class="panel-div"> 
                         <h1>ASK & ANSWER : Panel of Experts</h1>
                         <p class="panel-des">
                           At the core of the Shiksha Ask & Answer community you'll find some self-motivated, accomplished and exceptional people who make an extra effort to help students with answer to their critical career related queries and thus enable them to make a better decision. In an endeavor to recognize these people for their noble contribution we showcase them in our "Expert Panel".
                         </p>
                     </div>
                   <!--panel heading end-->
                   
                   <!--All tabs start-->
                   <div class="ana-section panel-col">
                      <!--all tabs- -->
                        <div class="tabSection">
                              <ul class="">
                              <?php if($expertsLevelPage == 'All'){
                                $activeClass = 'active';
                              }
                              else
                                $activeClass = ''; 
                              ?>
                              <li class="<?php echo $activeClass;?>" data-index="1"><a href="<?php echo SHIKSHA_ASK_HOME_URL.'/experts';?>"><h3>All</h3></a></li>
                              <?php 
                              foreach ($expertsCountPerLevel as $key => $value) { 
                                ?>
                                <?php if($expertsLevelPage == $key){
                                $activeClass = 'active';
                              }
                              else
                                $activeClass = ''; 
                              ?>
                                  <li class="<?php echo $activeClass;?>" data-index="1"><a href="<?php echo SHIKSHA_ASK_HOME_URL.'/experts/Level-'.$key;?>"><h3> Level <?php echo $key;?></h3></a></li>
                              <?php }
                              ?>                               
                              </ul>
                           </div>
                           
                           <div class="tabs-content">
                               <div class="active show-col" data-index="1" id="expertsPaneldiv">
                                  <?php $this->load->view('desktopNew/expertsPanelUsers');?>
                                </div>

                                <div id="loadingNew" style="text-align: center; margin-top: 10px;display:none;">
                                    <img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader">
                                </div>
                   <!--tabs section end-->
                 </div>
            </div>

            <p class="clr"></p>
       </div>
    </div>
<script>
  function LazyLoadAnADesktopCallback(){
        $LAB
      .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                    '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_desktop");?>'
                  )
      .wait(function(){
        initializePanelExpertsPage();
      });
    }
</script>

<?php 
    $this->load->view('messageBoard/desktopNew/toastLayer');
    $this->load->view('common/footer');
    echo includeJSFiles('shikshaDesktopWebsiteTour');
?>
<script>
  lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
    //Load website tour
    $j(document).ready(function(){
        window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        initializeWebsiteTour('cta','anaDesktopV2',contentMapping);
    });
</script>
<?php ob_end_flush();?>
