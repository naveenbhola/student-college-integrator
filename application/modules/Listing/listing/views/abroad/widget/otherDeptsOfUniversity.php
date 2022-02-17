<?php if(!empty($otherDepartments)){?>
				<div id="otherDepartmentsWidget" class="widget-wrap clearwidth">
								<h2 class="font18">Other Departments at <?=$departmentObj->getUniversityName()?></h2>
								<ul class="other-dept">
												<?php
												$odString = "flLt";
												foreach($otherDepartments as $dept){
												?>
																<li class="<?=$odString?>">
																				<a href="<?=$dept['url']?>"><?=$dept['name']?></a>
																				<p>Offering <?php echo $dept['courseCount']==1?$dept['courseCount'].' course':$dept['courseCount'].' courses'; ?></p>
																</li>
												<?php
																$odString = ($odString == "flLt")?"flRt":"flLt";
																if($odString == "flLt"){
																				echo "<div class='clearfix'></div>";
																}
												}
												?>
												
												
								</ul>
								<a class="flRt font-14" href="<?=$universityObj->getURL()?>?showDepts=1">View all departments <span style="font-size:17px">></span></a>
				</div>
<?php
}
?>