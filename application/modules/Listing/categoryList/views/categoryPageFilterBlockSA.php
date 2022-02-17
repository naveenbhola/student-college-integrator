<?php
			global $filters;
			global $appliedFilters;
			$filters = $categoryPage->getFilters();
			$appliedFilters = $request->getAppliedFilters();			 
			global $dataFromDBExistsOnPage;
			$dataFromDBExistsOnPage = 0;
			if((($categoryPage->getTotalNumberOfInstitutes() > 1)) || (isset($appliedFilters) && count($appliedFilters)>0)){
?>
<?php // As discussed with Aditya, this is not required now. 
// if(!($_COOKIE['ug-pg-phd-nav-click'] == 'YES' && !$institutes)):
$dataFromDBExistsOnPage = 1;
?>
<div class="refineContent">
<div class="refineCollegeLeft">
	<span class="refineBg">Refine Colleges</span>
</div>

<div class="refineDetailsBox" id="refineDetailsBox">
	<div class="clearFix"></div>
	<div class="refineSectionBlock">
		<div class="refineCols">
			<ul>
				<?php $this->load->view('categoryList/categoryPageFilterBlockLeftPanel');?>
			</ul>
		</div>
	
		<div class="refineCols2"  id="refineCols2">
			<?php $this->load->view('categoryList/categoryPageFilterBlockMiddle');?>
         </div>
	
		<div class="refineCols3"  id="refineCols3">
			<?php
			$viewRight = $this->load->view('categoryList/categoryPageFilterBlockRightSA','',true);
			if($viewRight){
				echo $viewRight;
			}else{
			?>
				<style>
							#refineCols2{
										width:390px;
										border:none;
							}
							#refineCols3{
										width:0px;
							}
							#moreLink,#lessLink{
										padding-right:20px !important;
							}
				</style>
			<?
			}
			?>			
		</div>
	</div>
	<div class="clearFix"></div>
		<style>
						.refine-button{background:#5d85c0;cursor:pointer; padding:2px 11px; color:#f6f9fd; font: 14px arial, Geneva, sans-serif; border-radius:9px;}
			        </style>
                    <div class="refineButtonRow"  id="refineLoader" style="display:none">
                    	&nbsp;&nbsp;<span class="refine-button" style="cursor:default" type="button" value="Refine" title="Refine">Refining <img align="absmiddle" src="/public/images/ajax-loader-blue.gif" width="18" /></span>
						&nbsp;<a href="#" onclick="clearAllFiltersOnCategoryPages();return false;">Clear all</a>
					</div>
			       <div class="refineButtonRow"  id="refineLoader1">
                    	<span class="refine-button" type="button" value="Refine" title="Refine" onclick="applyFiltersOnCategoryPages();">Refine</span>
						&nbsp;<a href="#" onclick="clearAllFiltersOnCategoryPages();return false;">Clear all</a>
					</div>
</div>
<div class="clearFix"></div>
</div>
<?php // endif;?>
<?php } ?>
