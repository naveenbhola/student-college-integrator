<?php 
$GA_Tap_On_Exam = "VIEW_EXAM";
?>
<div class="data-card m20">
       	       	     	<h2 class="fnt4 grey-fnt"><?=ucfirst($listing_type);?> Exams</h2>
       	       	     	<div class="examsTiny" id="examsTiny">
							 <div class="scrollbar" style="">
								<div class="track" style="">
									<div class="thumb" style="">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport">
							  <div class="overview" style="top:0px;">
								 <ul class="exams-ol">
								 <?php foreach($examList as $examKey => $examValue) { 
									$examURL = addingDomainNameToUrl(array('url' => $examValue['url'] , 'domainName' =>SHIKSHA_HOME));
									$examYear = ($examValue['year']!='')?' '.$examValue['year']:'';
								 	?>
									<li>
									 <i></i>
										<div class="" ga-attr="<?=$GA_Tap_On_Exam;?>">
												<a href="<?php echo $examURL;?>">
													<p class="exam-titl"><?php echo htmlentities($examValue['name'],ENT_QUOTES);?><?=$examYear?></p>
													<?php if(trim($examValue['description'])) { 
							                            $examValue['description'] = trim($examValue['description']);
							                            if(strlen($examValue['description']) > 72 )
							                            {
							                                $examValue['description'] = substr($examValue['description'], 0,69).'...';
							                            } ?>
							                            <p class="test-dtls"><?php echo htmlentities($examValue['description'],ENT_QUOTES);?></p>
							                        <?php } ?>
													
												</a>
											</div>
									</li>
									<?php } ?>
								</ul>
					</div>
            </div>
		</div> 
		<input type="hidden" id="examsMapped" name="examsMapped" value="<?php echo count($examList);?>">
</div>
