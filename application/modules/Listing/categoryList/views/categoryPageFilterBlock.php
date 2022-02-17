<div id="page-top">
<?php
global $pageHeading;
global $filters;
global $appliedFilters;
global $sortingCriteria;
global $exam_list;

$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
$sortingCriteria = $request->getSortingCriteria();
$tagText = "Courses";
if($request->isTestPrep()){
			if(stripos($pageHeading,"Coaching") > 0){
				$tagText = "Classes";
			}else{
			    $tagText = "Coaching Classes";
			}
}
?>
<?php if((($categoryPage->getTotalNumberOfInstitutes() > 1)) || (isset($appliedFilters) && count($appliedFilters)>0)){
?>
<div class="cateRefineBlock" id="cateRefineBlock">
        	<h2><?php echo $request->getCategoryId() == 3 ? 'Colleges' : 'Institutes'; ?> offering <?=$pageHeading?> <?=$tagText?></h2>
            <div class="refineContent">
            	<div class="refineLeft">
                	<span class="refineBg">Refine by</span>
                </div>
                <div class="refineDetailsBox">
                	<div class="refineSectionBlock" id="refineDetailsBox">
                        <div class="refineCols">
                            <ul>
								<?php $this->load->view('categoryList/categoryPageFilterBlockLeftPanel');?>
                            </ul>
                        </div>
                    
                        <div class="refineCols2" id="refineCols2">
						        <?php
								if(in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR)){
									$this->load->view('categoryList/categoryPageFilterBlockMiddleMBA');
								} else {
									$this->load->view('categoryList/categoryPageFilterBlockMiddle');
								}
								?>
								
                        </div>
						
                        <div class="refineCols3" id="refineCols3">
						        <?php
								$viewRight = $this->load->view('categoryList/categoryPageFilterBlockRight','',true);
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
								<?php
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
						<span class="refine-button" type="button" value="Refine" title="Refine" onclick="jumpTo('cateSearchBlock'); applyFiltersOnCategoryPages(); showHideResetLinks();">Refine</span>&nbsp;
						<a href="#" onclick="clearAllFiltersOnCategoryPages(); showHideResetLinks(); return false;">Clear all</a>
					</div>

                </div>
                <div class="clearFix"></div>
            </div>
			<?php
			if(in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR) && false){
				$this->load->view('categoryList/userSelectedCategoryFiltersBar');
			}
			?>
            <div id="compareFiller" style="height:46px;display:none">
						&nbsp;
			</div>
            <div class="compareBlock" id ="compareBlock" style="position:relative;width:638px;">
            	<div class="compareSection">
                	<!--<p class="compareTitle"><strong>Compare</strong><br /><span>upto 4 institutes</span></p>
                    <div class="compareListCol" id="compareList"></div>-->
                    
                </div>
				<?php
				if(!in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR)){
				?>
					<div class="sortByCol" id="sortByCol">
						Sort by:
						<select onchange="sortInstitutes();" id="categorySorter">
							<option value="none">
										All Institutes
							</option>
							<option value="lowfees">
										Lowest Fees
							</option>
							<option value="highfees">
										Highest Fees
							</option>
							<option value="longduration">
										Longest Duration
							</option>
							<option value="shortduration">
										Shortest Duration
							</option>
							<!--option value="viewCount">
										Popularity
							</option-->
							<?php if($showSortOnRanking) { ?>
							<option value="topInstitutes">
										Ranking
							</option>
							<?php } ?>
						</select>
						<script>
							$("categorySorter").value = "<?=$request->getSortOrder()?>";
						</script>
					</div>
				<?php
				}
				?>
            </div>
        </div>
<?php }else{?>
<div class="cateRefineBlock">
        	<h3>Institutes offering <?=$pageHeading?> <?=$tagText?></h3>
</div>
<?php } ?>
</div>

<script>
var compareBoxPostionT = 0;//obtainPostitionY($('compareBlock'));
var compareBoxPostionL = obtainPostitionX($('page-top'));
if (navigator.userAgent.indexOf("Windows")>=0){
			if(navigator.userAgent.indexOf("MSIE") >= 0){
						compareBoxPostionL -= 0;
			}else{
						compareBoxPostionL -= 9;
			}
}else if(navigator.userAgent.indexOf("Firefox")<0){
			compareBoxPostionL -= 9;
}
</script>

<script>
function jumpTo(id){
    var divid = document.getElementById(id);
    divid.style.display = 'block';
    divid.scrollIntoView(true);
    return false;
}
</script>
