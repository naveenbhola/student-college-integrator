<?php
$selectedCriteriaText = '';
$countryClusterClass = ($selectedCountry != 1) ? 'selectedLink' : '';
$categoryClusterClass = ($selectedCategory != 1) ? 'selectedLink' : '';
$examClusterClass = ($selectedExam!= "") ? 'selectedLink' : '';
?>
<div class="careerOptionPanelBrd">
		    <div class="careerOptionPanelHeaderBg"><span class="blackFont">Refine <?php echo $productName;?> by</span></div>
			<div class="lineSpace_5">&nbsp;</div>
			<ul class="refine">
			    <li class="<?php echo $categoryClusterClass; ?>">
				    <div><a href="?categoryId=1&c=<?php echo rand();?>" onclick="//return showCluster();"><?php echo $productName;?> on Career Options</a></div>
				    <ul class="subLink" id="categoryCluster">
                        <?php
                            foreach($categoryTree as $categoryNodeId => $categoryNode) {
                            
                                $categoryNodeName = $categoryNode['categoryName'];
                                $categoryNodeUrlName = $categoryNode['urlName'];
                                $categoryNodeLeafs = $categoryNode['subCategories'];
                                $selectedParentClass=($selectedCategory==$categoryNodeId)?'selectedSubLink':'';
                                $selectedCriteriaText = ($selectedParentClass != '' && $selectedCriteriaText=='') ? $categoryNodeName :$selectedCriteriaText;
                        ?>
                        <li class="<?php echo $selectedParentClass; ?>" id="cat_<?php echo $categoryNodeId; ?>">
						    <div><a href="?categoryId=<?php echo $categoryNodeId; ?>&c=<?php echo rand();?>" title="<?php echo $categoryNodeName; ?>"><?php echo $categoryNodeName; ?></a></div>
							<ul class="subsubLink">
                               <?php
                                    foreach($categoryNodeLeafs as $categoryNodeLeafId => $categoryNodeLeaf) {
                                        $categoryNodeLeafName = $categoryNodeLeaf['categoryName'];
                                        $categoryNodeLeafUrlName = $categoryNodeLeaf['urlName'];
                                        $selectedChildClass=($selectedCategory==$categoryNodeLeafId)?'selectedSubSubLink':'';
                                        $selectedCriteriaText = ($selectedChildClass != '' &&($selectedCriteriaText=='All' ||  $selectedCriteriaText=='')) ? $categoryNodeName .' : <span class="fontSize_12p">'. $categoryNodeLeafName .'</span>' :$selectedCriteriaText;
                                if($selectedChildClass!= '') {
                            ?>
                                    <script>document.getElementById('cat_<?php echo $categoryNodeId; ?>').className = 'selectedSubLink';</script>
                            <?php
                                        }
                                ?>
							    <li class="<?php echo $selectedChildClass; ?>">
                                    <div><a href="?categoryId=<?php echo $categoryNodeLeafId; ?>&c=<?php echo rand();?>" title="<?php echo $categoryNodeLeafName; ?>"><?php echo $categoryNodeLeafName; ?></a></div>
                                </li>
                                <?php
                                    }
                                ?>
							</ul>
						</li>
                        <?php
                            }
                        ?>
					</ul>
				</li>
				<li class="<?php echo $countryClusterClass; ?>">
                    <div><a href=""><?php echo $productName;?> on Foreign Education</a></div>
				    <ul class="subLink">
                        <?php
                            foreach($countryList as $country) {
                                $countryId = $country['countryID'];
                                $countryName = $country['countryName'];
                                if($countryId == 1 ) {continue; }
                                $selectedParentClass=($selectedCountry==$countryId)?'selectedSubLink':'';
                                $selectedCriteriaText = ($selectedParentClass != '' &&($selectedCriteriaText=='All' ||  $selectedCriteriaText=='')) ? $countryName :$selectedCriteriaText;
                            ?>

						<li class="<?php echo $selectedParentClass; ?>">
						    <div><a href="?countryId=<?php echo $countryId; ?>&c=<?php echo rand();?>" title="<?php echo $countryName; ?>"><?php echo $countryName; ?></a></div>
                            </li>
                        <?php 
                            }
                        ?>
                            </ul>
						
                </li>
				<?php
                    if($productName == 'Articles') {
                ?>
				<li class="<?php echo $examClusterClass; ?>">
                    <div><a href=""><?php echo $productName;?> on Test Preperation</a></div>
				    <ul class="subLink">
                        <?php
                            foreach($exams as $exam) {
                                $examName= $exam['acronym'];
                                $examTitle= $exam['blogTitle'];
                                $examName = ($examName == '') ? $examTitle : $examName;
                                $examId = $exam['blogId'];
                                $selectedParentClass=($selectedExam==$examId)?'selectedSubLink':'';
                                $selectedCriteriaText = ($selectedParentClass != '' &&($selectedCriteriaText=='All' ||  $selectedCriteriaText=='')) ? $examTitle :$selectedCriteriaText;
                       ?>

						<li class="<?php echo $selectedParentClass; ?>">
						    <div><a href="?exam=<?php echo $examId; ?>&c=<?php echo rand();?>" title="<?php echo $examTitle; ?>"><?php echo $examName; ?></a></div>
                            </li>
                        <?php 
                            }
                        ?>
                            </ul>
						
                </li>
                <?php
                    }
                ?>
			</ul>
		</div>
        <script>
            function updateClusterHeights(){
                var categoryCluster = document.getElementById('categoryCluster');
                categoryCluster.style.height = categoryCluster.offsetHeight;
                categoryCluster.style.display = 'none';
            }
            function showCluster(){
                var categoryCluster = document.getElementById('categoryCluster');
                var categoryClusterHeight = categoryCluster.style.height;
                categoryCluster.style.height = '0px';
                categoryCluster.style.display= 'inline';
                categoryCluster.offsetHeight= 0;
                setTimeout ("updateClusterHeight()", 2000);
                return false;
            }

            function updateClusterHeight(){
                var categoryCluster = document.getElementById('categoryCluster');
                categoryCluster.style.height = (categoryCluster.offsetHeight + 1 ) +'px';
            }
            document.getElementById('criteriaLabel').innerHTML = '<?php echo $selectedCriteriaText; ?>';
            //updateClusterHeights();
        </script>
