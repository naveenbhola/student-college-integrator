	<?php 
		$url = "/events/Events/index/1/";
	?>
	<!--Start_RIGHT_Panel-->
	<div class="lineSpace_10">&nbsp;</div>
	<div style="display:block;width:200px;margin:0 0 0 5px;float:right">
			<div class="lineSpace_25">&nbsp;</div>
			<?php if(!empty($relatedEvents)){ ?>
			<div class="wdh100">
			<div class="raised_greenGradient_ww">
                                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<?php 
                                        if($relatedEvents !== ''){
                                                        foreach($relatedEvents as $tempo)
                                                        {
                                                                        $categories = $tempo['categories'];
                                                                        $categories="Other Events in ".$categories;
                                                                        break;
                                                        }
                                                                }
                                                                if(strlen($categories)>35){
                                                                $widgetHeading=substr($categories,0,35)."....";
                                                                }else{
                                                                $widgetHeading=$categories;
                                                                }
                                         ?>
                        	<div class="boxcontent_greenGradient_ww" title="<?php echo $categories; ?>">
					<h2><div class="bgOthEnt"><?php echo $widgetHeading; ?>
					</div></h2>
                        		<div class="lineSpace_10">&nbsp;</div>
                        		<div>
                                		<ul class="entList">
                                			<!--Start_Repeating_Row-->
							 <?php
                                                if($relatedEvents !== ''){
                                                        foreach($relatedEvents as $temp)
                                                        {
                                                                        $startEvent = $temp['start_date'];
									$endEvent  =  $temp['end_date'];
									$titleEvent = $temp['title'];
									$idOfEvent = $temp['id'];
									$addDetails = $temp['description'];
									$fromOthers = $temp['fromOthers'];
                                                ?>
	                               			<li>
                                        			<div class="rw1">
										<?php if(date("jS M,y",strtotime($startEvent))!=date("jS M,y",strtotime($endEvent))){
										$currentDate=date("Y-m-d h:m:s");
										if($startEvent>$currentDate){ ?>
                                        				<div class="Fnt10">starts on</div>
                                        				<div class="sdtBg">
                                                				<div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
                                            					<div><?php echo date("j",strtotime($startEvent));?></div>
                                        				</div>
										<?php }else{  ?>
									<div class="Fnt10">upto</div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($endEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($endEvent));?></div>
                                                                        </div>
										<?php }
                                                                                }else{
                                                                                ?>
                                                                        <div class="Fnt10"></div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($startEvent));?></div>
                                                                        </div>
                                                                        <?php } ?>
                                    				</div>
								<?php
								if(strlen($titleEvent)>50){
                                                                $titleEvent=substr($titleEvent,0,50)."...";
                                                                }else{
                                                                $titleEvent=$titleEvent;
                                                                }
								?>
                                    				<div class="rw2">
                                            				<div><a href="<?php echo getSeoUrl($idOfEvent,'event'); ?>" title="<?php echo $temp['title']; ?>"><?php echo insertWbr($titleEvent,4); ?></a></div>
                                    				</div>
                                    				<div class="clear_B">&nbsp;</div>
                                			</li>
							<?php
							 }
                                                }
							?>
                                			<!--End_Repeating_Row-->
                            			</ul>
                        		</div>
                    		</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			</div>
			<?php } ?>
			<div class="lineSpace_5">&nbsp;</div>
<!--			<div class="row">
                        <div class="raised_greenGradient">
                            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                            <div class="boxcontent_greenGradient">
                                <div>
                                    <div class="lineSpace_5">&nbsp;</div>
                                    <div class="normaltxt_11p_blk arial">
                                        <span class="fontSize_12p bld float_L">&nbsp;Invite Friends From</span>
                                        <br clear="left" />
                                    </div>
                                    <div class="lineSpace_12">&nbsp;</div>
                                    <div class="normaltxt_11p_blk_arial">
                                       <div>
                                        <?php
                                            $base64url =  base64_encode($_SERVER['REQUEST_URI']);
                                            if(!(is_array($validateuser) && $validateuser !=  "false")) {
                                                $onClick = "showuserLoginOverLay(this,'EVENTS_EVENTSDETAIL_RIGHT_INVITEFRIENDS','jsfunction','showInviteFriends');";
                                            } else {
                                                if($validateuser[0]['quicksignuser'] == 1) {
                                                    $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
                                                } else {
                                                    $onClick = "showInviteFriends();";
                                                }
                                            }
                                        ?>
                                            <div>
                                                <a href="#" onClick = "<?php echo $onClick?>">
                                                    <img hspace="5" border="0" src="/public/images/inviteEventDetail.jpg"/>
                                                </a>
                                            </div>
											 <div class="lineSpace_10">&nbsp;</div>
											 <div class="fontSize_12p bld">&nbsp;&amp; Earn Shiksha Point</div>
                                        </div>
                                        <div class = "clear_L"></div>
                                    </div>
                                </div>
                            </div>
                            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
                        </div>
                    </div>-->
                    <div class="lineSpace_10">&nbsp;</div> 
					<div class="row">
						<?php
							$bannerProperties =  array('pageId'=>'EVENTS_DETAILS', 'pageZone'=>'SIDE');
							$this->load->view('common/banner', $bannerProperties);
						?>
					</div>
					<!--<div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>             
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>			
			</div>-->
			<!--End_Course_TYPE-->
	</div>
   	<!--End_RIGHT_Panel-->
