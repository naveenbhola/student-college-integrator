<?php
    $bannerProperties = array('pageId'=>'CAREER_CENTRAL_INTEREST', 'pageZone'=>'HEADER');
	$headerComponents = array(
		'js'=>array('common','json2'),
		'title'	=>	'Best Career Opportunities after 12th  - shiksha.com',
		'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
		'metaDescription'	=>'Turn your career interest into a full-fledged professional career opportunity with the help of Shiksha.com',	
		'showBottomMargin' => false,
		'product'=>'CareerProduct',
        'bannerProperties' => $bannerProperties,
		'canonicalURL' => $current_page_url
   );

   $this->load->view('common/header', $headerComponents);

?>
    	<!--Code Starts form here-->
    	<div class="career-wrapper">
        	<div class="sheet-wrapper">
        	<div class="sheet-top-effect">
            	<div class="sheet-bot-effect">
                    <?php
                $this->load->view('Careers/careerPageBreadcrumb',array('careerPage'=>'Opportunities'));
                ?>
                    	
                    	<div class="career-contents">
                        	<h2 class="stream-title">Your stream: <span><?php echo preg_replace('/Humanities/','Humanities/Arts',$stream);?></span>&nbsp;&nbsp;<a style="font-size:16px;" href="<?php echo CAREER_HOME_PAGE?>">Change</a></h2>
				
				
                            <h5 class="stream-sub-title">What's next?</h5>
                            <h1 class="choice-option">What are your career interests? <strong>Max 2</strong> choices:</h1>
                            
                            <div id="career-stream" class="career-stream-col">
                            	<ul id="stream-details" class="stream-details">
                               
				 <?php foreach($expressInterestDetails as $expressInterestDetail){ ?>
				  
			            <li>
                                    	<h2><label style="cursor:pointer"><input type="checkbox"  uniqueattr="CareerProduct/expressinterest<?php echo strtolower($expressInterestDetail['eiName']); ?>" id="<?php echo $expressInterestDetail['eiId']; ?>" onclick="checkOrUncheckExpressIntrest(this,'<?php echo base64_encode($expressInterestDetail[eiImage]); ?>','<?php echo base64_encode($expressInterestDetail[eiName]); ?>');"/> <?php echo $expressInterestDetail['eiName']; ?></label></h2>
                                        <div class="figure"><img src="<?php echo $expressInterestDetail['eiImage']; ?>" alt="Career in <?php echo $expressInterestDetail['eiName']; ?> activites." /></div>
                                        <div class="details"><strong>I prefer</strong> <?php echo $expressInterestDetail['eiDescription'];?>
											<div class="choice-cloud" id="<?php echo 'selectedChoiceTooltip'.$expressInterestDetail['eiId']; ?>" style="display:none">
                                        		<span>&nbsp;</span>
                                            	<div class="choice-details" id="<?php echo 'selectedChoiceTooltipValue'.$expressInterestDetail['eiId']; ?>"></div>
                                        	</div>
										</div>      
                       	</li>
				  
				 <?php  } ?>        
				    
                                </ul>
				
                            </div>
                            
			    <div class="career-selection-col">
                            <div class="career-selection-top" id="career-selection-widget" >
                            	<div class="career-sel-bot" >
                                    <div class="career-sel-content" >
                                    	<div class="contents" >
                                        	<h4>Your Selection</h4>
                                            <ul class="selection-items" id="">
                                            	
						<li style="height: auto"><h6 id="choice1">1st Choice</h6></li>
						<li class="drag"  id="1box" >
							<div class="figure">	
								<img id="img1" style="display:none"/>
								<div class="close" title="close" id="close1" style="display:none" onclick="closeFirstChoiceBoxOnRightPanel();return false;"></div>
							</div>   
                                                    <em id="ei1"></em>
						    
						</li>
						
                                                <li style="height: auto"><h6 id="choice2">2nd Choice <span>(Optional)</span></h6></li>
                                                
						<li class = "drag" id="2box" >
                                                	<div class="figure">		
								<img id="img2" style="display:none"/>
								<div class="close" title="close" id="close2" style="display:none" onclick="closeSecondChoiceBoxOnRightPanel();return false;"></div>
							</div>	
                                                    <em id="ei2"></em>
						</li>
						
                                                <li class="button-row">		
							<input id="button" type="button" value="Continue" class="gray-button" onclick="onSubmitExpressInterestPage(); return false;"/>
						</li>
						
					    </ul>
					    <div class="user-tips" id="swapToolTip" style="display:none">
                                            	You can swap the interests by dragging them up & down.
                                            </div>
                                        </div>
					</div>		
                                    </div>
                                </div>
                            </div>
			               <div class="career-offers">
                                 <p class="source-by">Content on this page is by Career Expert</p> 
                                 <p class="source-name">Mrs. Kum Kum Tandon</p> 
                                 <p class="source-dtls">MA (Psychology), M.Ed, Diploma in Educational Psychology, Vocational Guidance & Counseling (NCERT, Delhi) | <a href='<?=SHIKSHA_HOME?>/shikshaHelp/ShikshaHelp/kumkum'>View Complete Proﬁle</a></p>     
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Code Ends here-->
        

<script id="_webengage_script_tag" type="text/javascript">
  var _weq = _weq || {};
  _weq['webengage.licenseCode'] = '311c4922';
  _weq['webengage.widgetVersion'] = "4.0";
  (function(d){
    var _we = d.createElement('script');
    _we.type = 'text/javascript';
    _we.async = true;
    _we.src = (d.location.protocol == 'https:' ? "https://ssl.widgets.webengage.com" : "http://cdn.widgets.webengage.com") + "/js/widget/webengage-min-v-4.0.js";
    var _sNode = d.getElementById('_webengage_script_tag');
    _sNode.parentNode.insertBefore(_we, _sNode);
  })(document);
</script>        

 <?php
        $this->load->view('common/footer');
    ?>
    
<script> var stream= '<?php echo $stream;?>'</script>
<script type="text/javascript" src="/public/js/<?php echo getJSWithVersion("careers"); ?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>


