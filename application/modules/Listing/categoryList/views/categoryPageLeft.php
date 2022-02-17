<div style="padding: 0 8px">
<?php
	$CI_INSTANCE->config->load('categoryPageConfig');
	$subcategoriesForRnR = $CI_INSTANCE->config->item('CP_SUB_CATEGORY_NAME_LIST');
	//echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $request->getSubCategoryId(), "Institutes", TRUE); 
?>
</div>	
	<!-- Left Column Starts-->
    <div id="cateLeftCol">
		<?php 	if($subcategoriesForRnR[$request->getSubCategoryId()])
				    $this->load->view('categoryList/categoryPageRnRTitle');
			else
				    $this->load->view('categoryList/categoryPageTitle'); ?>
        <!--Refine Starts here-->
			<?php $this->load->view('categoryList/categoryPageFilterBlock'); ?>
        <!--Refine Ends here-->
		<?php
		if(in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR) && (!$zeroResultFlag)){
		           		$this->load->view('categoryList/categoryPageSorters');
		}
		?>
		<div class="clearFix"></div>
        <div class="cateChildLeftCol">
        <!--Institute List Starts here-->
			<div id="categoryPageListings"><?php $this->load->view('categoryList/categoryPageListings');?></div>
			<div id="categoryPageListingsLoader" style="display:none"><img src="/public/images/loader.gif" align="absmiddle">&nbsp;&nbsp;Loading...</div> 
        <!--Institute List Ends here-->
        
        <!--other Courses Starts here-->
			<?php //$this->load->view('categoryList/categoryPageLocalityWidget.php') ?>
        <!--other Courses Ends here-->
        
        <!--Q and A Starts here-->
	    <?php if($output && $output['int_ext'] == 'external' && $subcategoriesForRnR[$request->getSubCategoryId()])
	    { ?>
			
			<div id="floatingRegister_new">
				    <script>
						var floatingRegistrationSource = 'CATEGORY_AnAFLOATINGWIDGETREGISTRATION';
						var jsForWidget = new Array();
						addWidgetToAjaxList('/FloatingRegistration/FloatingRegistration/catFloatingRegistration/true/2500/cateRightCol/""/true/0/category','floatingRegister_new',jsForWidget);
				    </script>    
			</div>
			<div id="landingUrlFloatingRegistration" style="display:none"><?=$coursePageUrlObj->getHomeTabUrl($request->getSubCategoryId())?></div>
	    <?php }
	    else
	    { ?>
			<div id="categoryPageBottom"  uniqueattr="CategoryPage/bottomAnAWidget">
				    <script>
					    addWidgetToAjaxList('/AskAQuestion/AskAQuestion/index/categoryPageBottom/0/<?php echo $request->getCategoryId(); ?>/<?php echo $request->getSubCategoryId(); ?>','categoryPageBottom',Array());
				    </script>
			</div>
	    <?php } ?>
        <!--Q and A Ends here-->
        <div class="clearFix"></div>
    </div>
    </div>
    <!-- Left Column Ends-->
