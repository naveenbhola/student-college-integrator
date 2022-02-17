<?php
ob_start('compressHTML');
$preMetaString = $startOffSet == 1 ? '' :'Page '.$startOffSet." - ";
$tempJsArray = array('common');
$tempCssArray = array('ana');
$bannerProperties = array('pageId'=>'TAGS', 'pageZone'=>'HEADER');
$headerComponents = array(
                'css'   =>  array(),
                'js' => array(),
                'jsFooter'=>    $tempJsArray,
                'cssFooter' => $tempCssArray,
                'showBottomMargin' => false,
                'product' => 'anaDesktopV2',
                'title' =>      $preMetaString."Popular Tags - Shiksha.com",
                'metaDescription' => $preMetaString."Browse list of education topics to find questions, answers, and discussions on colleges, courses, exams, careers, admission, and more.",
                'canonicalURL' =>$canonicalURL,
		'bannerProperties' => $bannerProperties,
    'lazyLoadJsFiles' => true
);
$this->load->view('common/header', $headerComponents);
?>
<style type="text/css">
    * {
      margin: 0 auto;
      padding: 0;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
      font-family: "Open Sans",sans-serif; }
    .ana-main-wrapper {
      display: block;
      background: #f1f1f1; }
    .ana-main-wrapper .ana-container {
      padding-right: 15px;
      padding-left: 15px;
      margin-right: auto;
      margin-left: auto;
      width: 1170px; }
    .ana-main-wrapper .ana-container .row {
      margin-right: -15px;
      margin-left: -15px; }

    .breadcrumb3 {
      display: block;
      padding: 20px 0;
      position: relative; }
    .breadcrumb3 a {
      text-decoration: none;
      font-size: 12px;
      text-transform: capitalize;
      color: #00a5b5; }
    .breadcrumb3 .breadcrumb-arrow {
      margin: 0 2px 0 2px;
      font-size: 16px;
      line-height: 9px;
      overflow: hidden;
      height: 10px;
      display: inline-block;
      color: #00a5b5; }
    .breadcrumb3 .page-t {
      font-size: 12px;
      color: #5a595c; }

    /* view All Tags Css*/

    .alltags-div {
      display: block;
      background: #fff;
      padding: 0 0 30px;
      text-align: center; 
      min-height: 600px;}
    .alltags-div .alltag-h3 {
      display: block;
      text-align: left;
      padding: 20px 20px 15px;
      color: #595a5c;
      font-weight: 600;
      font-size: 18px;
      margin-bottom: -9px; }

    .alltags-div .alltag-box .Tab-col {
      display: inline-block;
      width: 700px;
      position: relative;}
    .alltags-div .alltag-box .Tab-col .srch-input {
      display: block;
      width: 100%;
      padding: 10px;
      border: 1px solid #e5e6e6;
      position: relative;
      outline: none; }
    .alltags-div .alltag-box .Tab-col .srch-input::-webkit-input-placeholder {
      color: #a2a9ae; }
    .alltags-div .alltag-box .Tab-col .srch-input::-moz-placeholder {
      color: #a2a9ae; }
    .alltags-div .alltag-box .Tab-col .srch-input:-ms-input-placeholder {
      color: #a2a9ae; }
    .alltags-div .alltag-box .Tab-col .srch-input:focus {
      outline: none; }

    .alltags-div .all-tag-col {
        display: block;
        margin: 40px 0 0;
        background: #fff;
        padding: 0 140px; }
    .alltags-div .all-tag-col ul {
        list-style: none;
        width: 276px;
        margin-left: 30px;
        text-align: left; }
    .alltags-div .all-tag-col ul:first-child {
        margin-left: 0; }

      .alltags-div .all-tag-col ul li:last-child {
        padding-bottom: 0; }
      .alltags-div .all-tag-col ul li a {
        color: #5a595c;
        text-decoration: none;
        position: relative;
        font-size: 12px;
        font-weight: 400;
        padding: 4px 0 9px 15px;
        display: block;
        line-height: 20px; }
      .alltags-div .all-tag-col ul li a:after {
        content: '';
        height: 4px;
        width: 4px;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -webkit-border-radius: 50%;
        background-color: #d0d1d1;
        position: absolute;
        left: 0px;
        top: 11px; }
      .alltags-div .all-tag-col ul li a:hover {
        color: #00a4b4;
        text-decoration: none; }
      .head-2{display: block;text-align: left;margin-left: 19px;margin-bottom: 21px;font-weight: normal;color: #595a5c;}

    /*pagination*/
    .n-pagination {
        margin: 40px 0 10px 0;
        text-align: center; 
        position:relative;
    }         
    .n-pagination ul {
        display: inline-block; }
    .n-pagination ul li {
        float: left; }
    .n-pagination ul li a {
        background-color: #fff;
        font-size: 14px;
        margin-right: 10px;
        font-weight: normal;
        color: #999;
        border: 1px solid #dfdfdf;
        display: block;
        cursor: pointer;
        padding: 4px 6px; }
      
    .n-pagination ul li.actvpage a {
        color: #4c4c4c;
        background-color: #f1f1f2; }    

    .n-pagination ul li.prev a,
    .n-pagination ul li.next a {
        border: 0;
        background:transparent;
       }
</style>
    <div class="ana-main-wrapper">
       <div class="ana-container">
          <div class="breadcrumb3">
                <span><a href="<?php echo SHIKSHA_HOME;?>"><span><i class="icons ic_brdcrm homeType1"></i></span></a></span>
                <span class="breadcrumb-arrow">›</span>
                <span class="page-t">Tags Page</span>
                            
          </div>
          <div class="row"> 
              <div class="alltags-div">
                <h1 class="alltag-h3">All Tags</h1>
                <h2 class="head-2">Tags ordered based on popularity</h2>
                <?php $this->load->view('desktop/tagSuggestor');?>
                <div class="all-tag-col" style="-moz-column-count: 3;-webkit-column-count: 3;column-count: 3;-moz-column-fill:balance;-ms-column-count:3">
                <ul>
                <?php

              foreach ($tags as $key => $value) 
                {?>
                  
                      
                    
                      <li><a href="<?php echo $value['url'];?>" id="<?php echo $value['tag_id'];?>" onclick="gaTrackEventCustom('ALL TAG PAGE','TAG_ALLTAG_DESKAnA','<?php echo $GA_userLevel;?>');"><?php echo $value['tag_name'];?></a></li>
                    
                      <?php }  ?>
                      </ul>
                                

                           <p class="clr"></p>
                         </div>
                     <p class="clr"></p>
                     <div class="n-pagination">
                                    <ul>
                                         <?php echo $paginationHTMLForGoogle;?>
                                    </ul>
                                </div>
                     
                </div>
          </div> 
          
        <p class="clr"></p>
       </div>
       
    </div>
<script>
var GA_userLevel_AllTags = '<?php echo $GA_userLevel;?>'; 
  function LazyLoadAnADesktopCallback(){
        $LAB
      .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                    '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_desktop");?>',
                    '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>'
                  )
      .wait(function(){
        initializeTagAutoSuggestor();
      });
    }
</script>
<?php 
    $this->load->view('common/footer');
    echo includeJSFiles('shikshaDesktopWebsiteTour');

ob_end_flush();
?>
<script>
    //Load website tour
    $j(document).ready(function(){
        window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        initializeWebsiteTour('cta','anaDesktopV2',contentMapping);
    });
</script>