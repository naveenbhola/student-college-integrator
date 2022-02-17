					 <div class="raised_greenGradient_ww">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div class="boxcontent_greenGradient_ww">
								<div class="mar_full_10p">
										<div style="width:185px">
												<div class="lineSpace_5">&nbsp;</div>
												<div class="normaltxt_12p_blk bld OrgangeFont" style = "font-Size:14px">
													<span class="cssSprite_Icons" style="background-position:-90px -36px; font-size:13px;padding-left:17px;">&nbsp;</span>Shiksha Analytics
												</div>
												<div class="lineSpace_5">&nbsp;</div>
													  <?php 
													  if($viewCount > 1){
															$tempTxtVC = "<div class='cssSprite_Icons' style='background-position:-487px -99px;padding-left:10px'>This page is viewed <span class='OrgangeFont bld'> ".$viewCount."</span> times.</div>";
													  }else
														{
															$tempTxtVC = "<div class='cssSprite_Icons' style='background-position:-487px -99px;padding-left:10px'>This page is viewed <span class='OrgangeFont bld'> ".$viewCount."</span> time.</div>";
														}
													  ?>
													  <div class="normaltxt_12p_blk arial" style="font-Size:12px"><?php echo $tempTxtVC; ?> <br clear="left" /></div>
													  <div class="lineSpace_5">&nbsp;</div>
													  <?php 
													  if($summaryCount > 1){
															$tempTxt = "<div class='cssSprite_Icons' style='background-position:-487px -99px;padding-left:10px'>This $listing_type appeared in search results <span class='OrgangeFont bld'> ".$summaryCount."</span> times.</div>";
													  }else
														{
														  if($summaryCount == 1){
															$tempTxt = "<div class='cssSprite_Icons' style='background-position:-487px -99px;padding-left:10px'>This $listing_type appeared in search results <span class='OrgangeFont bld'> only ".$summaryCount." </span> time.</div>";
														  }else{
															$tempTxt = "<div class='arrowBullets'>This $listing_type has not appeared in search results so far.</div>";
														  }
														}

														?>
												<div class="normaltxt_12p_blk arial" style="font-Size:12px"><?php echo $tempTxt; ?><br clear="left" /></div>
										</div>
								</div>
						  </div>
						  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>

