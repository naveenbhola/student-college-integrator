		<?php
            if(isset($exams) && is_array($exams)) {
        ?>
        <div style="display:inline; float:left; width:100%">
		    <div class="normaltxt_11p_blk fontSize_13p bld OrgangeFont h22">Examination Sections</div>
		    <div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
				    <div class="row">
                        <?php
                            foreach($exams as $exam) {
                                $examCategoryName = $exam['blogTitle'];
                                $examCategoryDesc = $exam['summary'];
                                $examCategoryUrl = $exam['url'];
                                $examCategoryImage = str_replace('_m','_s',$exam['blogImageURL']);
                                $examCategoryExams = $exam['exam'];
                        ?>
							<div style="background:url(<?php echo $examCategoryImage; ?>) no-repeat left top; padding-left:72px; margin:0 15px">
									<div class="fontSize_12p bld"><?php echo $examCategoryName; ?></div>	
									<div class="lineSpace_5">&nbsp;</div>
									<div><a href="<?php echo $examCategoryUrl; ?>" class="fontSize_12p"><?php echo $examCategoryDesc; ?></a></div>
									<div class="lineSpace_10">&nbsp;</div>
									<?php if(is_array($examCategoryExams) && (count($examCategoryExams) > 0) ) { ?>
										<div class="bld fontSize_12p" style="color:#91908B">Popular Exams:</div>			
										<div>
												<?php
													foreach($examCategoryExams as $examCategoryExam) {
														$examName = $examCategoryExam['acronym'];
														if($examName==''){
															continue;
														}
														$examUrl = $examCategoryExam['url'];														
												?>
												<div class="quesAnsBullets float_L" style="width:40%;padding-left:17px"><a href="<?php echo $examUrl; ?>" class="fontSize_12p "><?php echo $examName; ?></a>&nbsp;</div>
												<?php
													}
												?>
												<div class="clear_L"></div>
										</div>
									<?php }	?>
									<div style="line-height:40px">&nbsp;</div>
							</div>						        							    					
                        <?php } ?>
		    		</div>
		        </div>
			    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
    	</div>
		<?php
            }
        ?>
