                                <?php if((count($blogs['ugtestexam'][0]) > 0) || (count($blogs['pgtestexam'][0]) > 0) || (count($blogs['englishtestexam'][0]) > 0) || count($blogs['doctoraltestexam'][0]) > 0) { ?>
                                <div class="wdh100">
                                    <div class="shik_skyBorder">                                	
                                        <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><span class="Fnt14" style="padding-left:10px"><b>Entrance Exams Required</b></span></div>
                                        <div class="mlr10">
                                        	<div class="lh10"></div>
                                          <?php
                                          if(count($blogs['ugtestexam'][0]) > 0) { ?> 
                                        	<div class="mb10 brdbottom">
                                        		<div class="fcOrg bld fs13">Under Graduate</div>
                                        		<ul class="faqSA_ul">
                                               <?php 
                                               for($i = 0;$i<count($blogs['ugtestexam'][0]);$i++) { ?>
                                                	<li class="mtb10">
                                                    	<a href="<?php echo $blogs['ugtestexam'][0][$i]['url']?>" title="<?php echo $blogs['ugtestexam'][0][$i]['blogTitle']?>"><?php echo $blogs['ugtestexam'][0][$i]['blogTitle']?></a><br />
<!--                                                        <span class="fs11">For entry into undergraduate programs at Australian universities (If Applicable)</span>-->
                                                        <span class="fs11"><?php echo strip_tags($blogs['ugtestexam'][0][$i]['blogtext'])?></span>
                                                    </li>
                                                <?php } ?>    
                                                </ul>                                        	
											</div>
                                            <?php } ?>
                                          <?php 
                                          if(count($blogs['pgtestexam'][0]) > 0) { ?> 
                                            <div class="mb10 brdbottom">
                                        		<div class="fcOrg bld fs13">Post Graduate</div>
                                        		<ul class="faqSA_ul">
                                               <?php for($i = 0;$i<count($blogs['pgtestexam'][0]);$i++) { ?>
                                                	<li class="mtb10">
                                                    	<a href="<?php echo $blogs['pgtestexam'][0][$i]['url']?>" title="<?php echo $blogs['pgtestexam'][0][$i]['blogTitle']?>"><?php echo $blogs['pgtestexam'][0][$i]['blogTitle']?></a><br />
   <!--                                                     <span class="fs11">For entry into undergraduate programs at Australian universities (If Applicable)</span>-->
                                                        <span class="fs11"><?php echo strip_tags($blogs['pgtestexam'][0][$i]['blogtext'])?></span>
                                                    </li>
                                                    <?php } ?>
                                                </ul>                                        	
											</div>
                                            <?php } ?>
                                          <?php 
                                          if(count($blogs['doctoraltestexam'][0]) > 0) { ?> 
                                            <div class="mb10 brdbottom">
                                        		<div class="fcOrg bld fs13">Doctoral Degrees</div>
                                        		<ul class="faqSA_ul">
                                               <?php for($i = 0;$i<count($blogs['doctoraltestexam'][0]);$i++) { ?>
                                                	<li class="mtb10">
                                                    	<a href="<?php echo $blogs['doctoraltestexam'][0][$i]['url']?>" title="<?php echo $blogs['doctoraltestexam'][0][$i]['blogTitle']?>"><?php echo $blogs['doctoraltestexam'][0][$i]['blogTitle']?></a><br />
<!--                                                        <span class="fs11">For entry into undergraduate programs at Australian universities (If Applicable)</span>-->
                                                        <span class="fs11"><?php echo strip_tags($blogs['doctoraltestexam'][0][$i]['blogtext'])?></span>
                                                    </li>
                                                    <?php } ?>
                                                </ul>                                        	
											</div>
                                            <?php } ?>
                                          <?php 
                                          if(count($blogs['englishtestexam'][0]) > 0) { ?> 
                                            <div class="mb10">
                                        		<div class="fcOrg bld fs13">English Test</div>
                                        		<ul class="faqSA_ul">
                                               <?php for($i = 0;$i<count($blogs['englishtestexam'][0]);$i++) { ?>
                                                	<li class="mtb10">
                                                    	<a href="<?php echo $blogs['englishtestexam'][0][$i]['url']?>" title="<?php echo $blogs['englishtestexam'][0][$i]['blogTitle']?>"><?php echo $blogs['englishtestexam'][0][$i]['blogTitle']?></a><br />
                                                  <!--      <span class="fs11">For entry into undergraduate programs at Australian universities (If Applicable)</span>-->
                                                        <span class="fs11"><?php echo strip_tags($blogs['englishtestexam'][0][$i]['blogtext'])?></span>
                                                    </li>
                                                    <?php } ?>
                                                </ul>                                       	
											</div>
                                            <?php } ?>
                                        </div>
                                        <div class="lh10"></div>
                                    </div>
                                </div>
                                <?php } ?>
