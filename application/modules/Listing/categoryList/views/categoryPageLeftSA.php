<div id="SAContents">
    <div id="SALeftCol">
    <div id = 'cateTitleBlock'>
    	<!-- Carousel Starts-->
		<?php
			echo Modules::run('articleWidgets/articleWidgets/getStudyAbroadSnippetWidget', $request->getCategoryId(), $request->getRegionId(), $request->getCountryId());
		?>
        <!-- Carousel Ends-->
        <!--Search Result Starts here-->
<?php $zeroResultPage = 0; if(count($institutes)==0) $zeroResultPage=1; ?>
        <div class="cateRefineBlock" id="page-top">
	<?php	if($zeroResultPage){
				if($request->getPageNumberForPagination() > 1){
					$urlRequest = clone $request;
					$urlRequest->setData(array('pageNumber'=>1));
					$url = $urlRequest->getURL();
					header("location:".$url);
				}
			?>
		<?php }else {?>
        	<h2>Institutes offering <?=($request->getSubCategoryId()<=1)?$categoryPage->getCategory()->getName():$categoryPage->getSubCategory()->getName()?> courses in <?php global $pageName; echo $categoryPage->getCountry()->getName()=="All"?$pageName:$categoryPage->getCountry()->getName(); }?></h2>
			<div class="findTitle">
				<?php if($zeroResultPage) echo "Please change your desired";?>
				<span>
					&nbsp;[&nbsp;<a href="#" onclick="showChangeCategorySA(this); return false;" onmouseout="dissolveOverlayHackForIE();$('choosecatgory').style.display='none';"><?php if(!$zeroResultPage){?> Change<?php }?> Category <b class="orangeColor">&#9660;</b></a>&nbsp;]
				</span>
				&nbsp;&nbsp; <?php if($zeroResultPage) echo "or"?> &nbsp; 
				<span>
					&nbsp;[&nbsp;<a href="#" onclick="showLocationLayerSA(this);return false;"><?php if(!$zeroResultPage){?> Change<?php }?> Location <b class="orangeColor">&#9660;</b></a>&nbsp;]
				</span>
				<div>
				</div>
			</div>
            <div class="findCollegeCont">
            	<!--Refine Section Starts here-->
					<?php  $this->load->view('categoryList/categoryPageFilterBlockSA'); ?>
                <!--Refine Section Ends here-->
            	
                <!--Find Results Section Starts here-->
            	<div class="findCollegeChildCont">
                	<!--Institute List Starts here-->
					<?php $this->load->view('categoryList/categoryPageSortBlockSA'); ?>
                	<div id="categoryPageListings"><?php $this->load->view('categoryList/categoryPageListingsSA');?></div>
					<div id="categoryPageListingsLoader" style="display:none"><img src="//<?php echo IMGURL; ?>/public/images/loader.gif" align="absmiddle">&nbsp;&nbsp;Loading...</div> 
                	<!--Institute List Ends here-->
            	</div>
            	<div class="clearFix"></div>
        	</div>
        	<div class="clearFix"></div>
        </div>
        <!--Search Result Ends here-->
        <div class="clearFix spacer20"></div>
        
        <!--Find Consultant Form Starts Here-->
		<?php
			global $pageName;
			global $countries;
			 echo Modules::run('user/Register_StudyAbroad/index', $request->getCategoryId(), $countries,$pageName);
		?>
        <!--Find Consultant Form Ends Here-->
        
        <!--Steps to Study Starts Here-->
		<div id="StepstoStudy" uniqueattr="StudyAbroadPage/stepsToStudyWidget">
			<script>
				addWidgetToAjaxList('/articleWidgets/articleWidgets/getStudyAbroadStepsWidget/<?=$request->getCategoryId()?>/<?=$request->getRegionId()?>/<?=$request->getCountryId()?>/<?=$pageName?>/<?=$categoryPage->getCategory()->getName()?>/','StepstoStudy',Array());
			</script>
       </div>               
    	<!--Steps to Study Ends Here-->
        
        <?php if (!(is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid']))) { ?>
        <div class="stepsToStudyMainCol">
            <div class="clearfix spacer20"></div>
            <div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="260" data-show-faces="true" data-border-color="#C8D6E8" data-stream="false" data-header="false"></div>
        </div>
        <?php } ?>
        <div class="clearFix"></div>
    </div>
</div>
