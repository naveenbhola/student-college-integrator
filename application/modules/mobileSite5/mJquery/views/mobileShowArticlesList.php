<?php ob_start('compress');
if($blogType != 'examPage'){
   $headerComponents = array(
   'm_meta_title'=>'Education Articles - Shiksha.com',
   'm_meta_description'=>'Read the latest education articles on colleges, courses, universities, institutes, results, admissions at Shiksha.com',
   'pageType' => 'articlePage',
   'jsMobileFooter'=>array('nmArticleNew')
   );
}
else{
   $headerComponents = array(); 
}
?>
<?php $this->load->view('/mcommon5/header',$headerComponents);?>
 <div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px;">
  <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
  ?>
<!-- Show the Category page Header -->    
<?php $this->load->view('mobileArticleHeader');?>

        <div data-role="content">
         
         <?php $this->load->view('mobileArticleSubHeader');?>
         
            <div data-enhance="false">
 

<?php if($blogType  != 'news' && $blogType != 'examPage' && $blogType != 'kumkum'):?>            
<?php $this->load->view('mobileArticleTabs');?>    
<?php endif;?>

<?php $this->load->view('mobileArticleListData');?>    

<?php $this->load->view('/mcommon5/footerLinks');?>
</div>
</div>
 </div>
 <div id="popupBasicBack" data-enhance='false'></div>
<div data-role="page" id="subcategoryDiv" data-enhance="false"><!-- dialog--> 
</div>
 <?php $this->load->view('/mcommon5/footer');?>
 <div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>
 <div data-role="page" id="subcategoryDivForArticles" data-enhance="false"><!-- dialog-->
    <?php  //$this->load->view('articleCategoryLayer');
    //$this->load->view('subcategoryLayerForArticles');?>
</div>
 
<script>
var articleNewObj = new articleNewClass();
$(document).ready(function() {
  articleNewObj.domReadyCalls();        
});
$(window).load(function(){
  articleNewObj.windowLoadCalls();
});
</script>
<?php ob_end_flush(); ?>




