<!-- -------- EXMAS -->
				<li>
				<?php ?>
                      	<div class="details">
						  <p class="criteria-icn-box"><i class="sprite-bg degree-icon"></i></p>
						  <p style="width:118px" class="title-txt2">Exam Required<span class="flRt">-</span></p>
						  <div class="criteria-content">
                          	<div id = "bebtechExmScrlr" class="scrollbar1">	
                            	<div class="scrollbar">
                                	<div class="track" >
                               	   		 <div class="thumb">
                                			<div class="end"></div>
                                		</div>
                                	</div>
                                </div>
                                <div class="viewport" style="height:300px;">
                                	<div class="overview">
                                	
                                	 <?php foreach ($cutoffRanks as $examName=> $cuttOffDetails){ ?>
                                	 	
                                	 	<div class="exam-detail">
                                	 	<p class="font-20 mb8"><?=$examName; ?></p>
                                	 	<?php foreach($cuttOffDetails as $roundNo=> $quotaWiseCutoff) {?>
                                	 	<p class="font-14">Counselling Round - <?=$roundNo ?></p>
                                	 	<table cellpadding="0" cellspacing="0" class="exam-detail-table">
                                	 	<tr>
                                	 	<th>Quota</th>
                                	 	<th>Category</th>
                                	 	<th>Closing Rank</th>
                                	 	</tr>
                                	 	<?php foreach ($quotaWiseCutoff as $nameOfQuota => $categoryWiseCuttOff) {?>
                                	 	<?php foreach ($categoryWiseCuttOff as $nameOfCategory =>$closingRank ) {?>
                                	 	<tr>
                                	 	<td><?=$nameOfQuota; ?></td>
                                	 	<td><?=$nameOfCategory; ?></td>
                                	 	<td><?= $closingRank ? $closingRank : "Not Offered" ; ?></td>
                                	 	</tr>
                                	 	<?php }
 											}?>
                                	 	</table>
                                	 	<?php }?>
                                	 	</div>
                                	 	
                                	  
                                <?php	 }
                                	
                                	  ?>
                                    	                      
                                      
                                    </div>
                                </div>
                            </div>
							
                          </div>
					    </div>
                      </li>
                 
				<!-- -------- EXMAS -->
				
				
