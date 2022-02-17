<div class="content-wrap clearfix" style="clear:both">
<div id = "categoryPage_banner_cont" style = "width:943px;margin-left: 13px;" ></div>

<?php
    $this->load->view('categoryList/abroad/widget/categoryPageTitle');
    $this->load->view('categoryList/abroad/widget/categoryPageFilters');
    ?>
    <div id="scrollToThisDiv" class="clearwidth"></div>
    <div id="nav-bar-sticky" class="shortlist-nav-bar clearwidth"> <?php
        $this->load->view('categoryList/abroad/widget/categoryPageYourSelection');
        $this->load->view('categoryList/abroad/widget/categoryPageNavSection');?>
    </div>
	<div id = "categoryPageListingsSec">
    	<?php
		//$this->load->view('categoryList/abroad/widget/categoryPageCompareSec');
		$this->load->view('categoryList/abroad/widget/categoryPageListing');
        /*this is a widget to show exam guide download and content on category page.The exams are static as per requirement
        if(!empty($examPageWidgetData))
        {
            $this->load->view('categoryList/abroad/widget/categoryPageExamWidget');    
        }*/
		//$this->load->view('categoryList/abroad/widget/categoryPageSimilarSec');
		$this->load->view('categoryList/abroad/widget/categoryPagePagination');
        $this->load->view('categoryList/abroad/widget/bottomBanners');
        $this->load->view('categoryList/abroad/widget/popularSpecialisations');        
		?>                
	</div>
    <?php $this->load->view('categoryList/abroad/widget/userShortListedListingsSec'); ?> 
<input type="hidden" id="ajaxurl" name="ajaxurl" value="<?=$ajaxurl?>" />
<div id="loadingOverlay" style=" background-color: #000000;display: none;left: 0;opacity: 0.4;position: absolute;top: 0;z-index: 1000;" ><img id="loadingImage"  style="position:absolute;" src="" width="50" height="50"/></div>
<div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('//<?php echo IMGURL; ?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>
</div>
