<div class="exam-left-col" style="border:0 none;">
		    <?php	if(count($filterData)>0){
		    ?>
		    <ul class="customInputs all-country-list">
                        <li class="first-child">
                            <h2><span>Filter articles </span></h2>
                        </li>
                        <li style="border-bottom:0 none;">
					<input type="checkbox" id="filter-all" class="filter-all"/>
					<label class="" for="filter-all">
							    <span class="common-sprite"></span>All
					</label>
                            <ul class="sub-city-list" id="sub-filter-list">
                            	<?php	$i=0;
					foreach($filterData as $key=>$value){
							    if($value == 'all'){continue;}
							    ++$i;
				?>
				<li onmouseover="showOnlyFunction(this,'apply')" onmouseout="showOnlyFunction(this,'remove')" >
					<input type="checkbox" class="filter-value" name="filter[]" value="<?=$key?>" id="<?=$stageDetails['stageId'].'_'.$i?>"/>
					<label class="" for="<?=$stageDetails['stageId'].'_'.$i?>">
					<span class="common-sprite"></span>
					<a class="show-only" href="javascript:void(0)" onclick="checkOnlyCurrent(this,'<?=$stageDetails['stageId'].'_'.$i?>')" style="display: none">Only</a>
					<p style="width:97%"><?=$value?></p>
					</label>
                                </li>
				<?php }?>
                            </ul>
			    <div class="clearfix"></div>
                        </li>
                    </ul>
		    <input type="hidden" id="stage-id" value="<?=$stageDetails['stageId']?>"></input>
		    <script>
					function showOnlyFunction(elem,action) {
						if (action == "apply") {
							$j(elem).find(".show-only").css("display","block");
						}else if (action == 'remove') {
							$j(elem).find('.show-only').css("display","none");		
						}
					}
					
					function checkOnlyCurrent(elem,idToCheck) {
						if($j('#'+idToCheck).attr('checked') && $j(".filter-value:checked").length == 1){
							return;
						}else{
							$j(elem).closest("ul").find("input").prop('checked',false);
							setTimeout(function(){$j("#"+idToCheck).click()},20);
							 if ($j(".filter-value").length == 1) {
							    setTimeout(function(){$j(".filter-all").prop('checked',true)},50);
                                                        }
						}
					}					
		    </script>
		    <?php	}
		    ?>
</div>
