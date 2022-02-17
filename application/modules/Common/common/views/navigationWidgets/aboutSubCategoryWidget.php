   <div class="nav-widgets">
                    	
                        <div class="nav-widget-wrap">
			  <?php if(count($articleWidgetsData!=0)){ ?>
                        	<h5>All about <?= $personalizedArray['category']->getName() ?></h5>
                        	<div class="figure"><img src="/public/images/thumb.jpg" height="75" width="90" alt="Full Time MBA/PGDM" /></div>
			    <ul class="details">
			    <?php foreach($articleWidgetsData as $articleData) { ?>
                            	<li><a href="<?php echo $articleData['articleURL']; ?>"><?=html_escape(formatArticleTitle($articleData['articleTitle'],60));?></a></li> <? } ?>                              
                            </ul>
			    <?php } ?>
                        </div>
                      
                         <div class="nav-widget-wrap" style="border:0 none; padding:0">
                        	<h5>Careers in Management</h5>
                        	<div class="figure"><img src="/public/images/management-avatar.jpg" alt="" /></div>
                           
                        	<ul class="details">	
				    
				     <li><a href="<?php echo SHIKSHA_HOME;?>/career-as-business-manager-cc-20">Business Manager</a></li>
				     <li><a href="<?php echo SHIKSHA_HOME;?>/career-as-marketing-manager-cc-71">Marketing Manager</a></li>
				     <li><a href="<?php echo SHIKSHA_HOME;?>/career-as-marketing-research-executive-cc-72">Marketing Research Executive</a></li>
				     <li><a href="<?php echo SHIKSHA_HOME;?>/career-as-personnel-manager-cc-86">Personnel Manager</a></li>
				     <li><a href="<?php echo SHIKSHA_HOME;?>/career-as-rural-manager-cc-96">Rural Manager</a></li>
		                     <li><a href="<?php echo SHIKSHA_HOME;?>/career-as-sales-manager-cc-97">Sales Manager</a></li>
				
				</ul>
                        </div>
			
		     <div class="nav-career-footer">
		     <p><span>Still not convinced, find out:</span><br />What's the right career for you</p>
                     <input type="button" value="Start here" class="orange-button" style="font-size:18px !important;margin-top: 4px" onclick="window.location.href='<?php echo CAREER_HOME_PAGE;?>'"/>
		     </div>
		     <div class="spacer10 clearFix"></div>
   </div>
                    
