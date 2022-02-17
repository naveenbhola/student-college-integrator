<?php
global $collegePredictorExams;
global $rankPredictorExams;
?>
<div class="full-rnk-predictor-widget clear-width">
                        <div class="shadow-sec-top"></div>
                        <div class="rank-predictor-widget">
                        	<div class="rank-predictor-head">
                            	<strong>Predict your rank and find colleges that you can apply to:</strong>
                            </div>
                            <div class="rank-predictor-widget-sec">
                            	<div class="rank-predictor-col-1">
                                	<label>Rank Predictors: </label>
                                    <ul>
                                    	<li>
                						    <?php $i=0; foreach($rankPredictorExams as $rankPredictorUrlData) {
                							if($i%2 == 0)
                							{
                								$rankClassCSS = flLt;
                							}
                							else
                							$rankClassCSS = flRt;
                							?>
                                        	<div class="<?=$rankClassCSS?> predictor-width">
                                            	<a href="<?=$rankPredictorUrlData['url'];?>" target="_blank";><?=$rankPredictorUrlData['name']?></a>
						                    </div>
                                            <?php $i++; } ?>
						              <div class="clearFix"></div>
                                </li>
                            </ul>
                        </div>
                        <div class="rank-predictor-col-1 last-col-1">
                        	<label>College Predictors: </label>
                            <ul>
                            	<li>
						<?php $j=0; foreach($collegePredictorExams as $collegePredictorUrlData) {
							if($j%2 == 0)
							{
								$collegeClassCSS = flLt;
							}
							else
							$collegeClassCSS = flRt;
							?>
                                        	<div class="<?=$collegeClassCSS?> predictor-width">
                                            	<a href="<?=$collegePredictorUrlData['url']?>" target="_blank";><?=$collegePredictorUrlData['name']?> </a>
                                            </div>
						<?php $j++; } ?>
                                            <div class="clearFix"></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="shadow-sec-bottom"></div>
                        </div>