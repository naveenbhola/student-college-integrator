<?php
	$clientWidth =  ((isset($_COOKIE['client']) && $_COOKIE['client'] != '') ? $_COOKIE['client'] : 1024);
?>
<!--Start_featuredCategories-->
	<div style="width:100%" class="float_L">
	<div>
		<div class="normaltxt_11p_blk fontSize_13p bld OrgangeFont arial">
				<h5><span class="mar_left_10p myHeadingControl bld">Most Searched Institutes</span></h5>
		</div>
		<div style="line-height:5px">&nbsp;</div>
		<div id="blogTabContainer">
			<div id="blogNavigationTab" style="width:98%">
				<ul>
					<?php 
						$numTabs = 0;
                        $firstChild = '';
						foreach($featuredCategories as $featuredCategoryKey => $featuredCategory) {
							$tabName = "featuredCategory". $featuredCategoryKey;
							$categoryCaption = $featuredCategory['caption'];
							$categoryId = $featuredCategory['id'];
                            if($firstChild == '') {
                                $firstChild = 'featuredCollegeBlock'. $categoryId; 
                            }
							if($clientWidth < 1000 && $numTabs ==7 ) {
								break;
							}
							$numTabs++;
						
					?>
					<li container="featuredCategory" tabName="<?php echo $tabName ?>" onClick="return selectHomeTab('featuredCategory','<?php echo $featuredCategoryKey ?>','<?php echo $categoryId; ?>');">
						<a href="#" title="<?php echo $categoryCaption;?>"><?php echo $categoryCaption;?></a>
					</li>
					<?php 
						}
					?>
				</ul>
			</div>
			<div class="clear_L"></div>
		</div>
		<div class="raised_lgraynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG" style="height:155px">
				<div style="line-height:10px">&nbsp;</div>
				<div class="mar_full_10p" id="featuredCollegeBlock" children="<?php echo $firstChild; ?>">
                <div id="<?php echo $firstChild; ?>">
				<?php
					$truncateStrLength = (($clientWidth < 1000) ? 12 : 30);
					$featuredCollgeBlock = '<br class="lineSpace_10"/>';
					$featuredColleges = $featuredColleges[0];
					if(isset($featuredColleges['institutes']) && is_array($featuredColleges['institutes']))
					for($instituteCount = 0; $instituteCount < count($featuredColleges['institutes']); $instituteCount++) {
						$title = $featuredColleges['institutes'][$instituteCount]['title'];
						$url = $featuredColleges['institutes'][$instituteCount]['url'];
						$logo = $featuredColleges['institutes'][$instituteCount]['logo_link'];
						$cityName = $featuredColleges['institutes'][$instituteCount]['locationArr'][0]['city_name'];
						$countryName = $featuredColleges['institutes'][$instituteCount]['locationArr'][0]['country_name'];
				        $numOfCourses = count($featuredColleges['institutes'][$instituteCount]['courseArr']);
				        if($numOfCourses > 0 ){
				            $courseId	= $featuredColleges['institutes'][$instituteCount]['courseArr'][0]['courseId'];
				            $courseName	= $featuredColleges['institutes'][$instituteCount]['courseArr'][0]['title'];
				            $displayCourseName = strlen($courseName) > $truncateStrLength ? substr($courseName, 0, $truncateStrLength-3) .'...'  : $courseName;
				        }
						$collegeLocation = $cityName;
						if($cityName != '' && $countryName != '') {
							$collegeLocation .= ', '; 
						}
						$collegeLocation .= $countryName;		
						$logo = getSmallImage($logo);
						$titleDisplayText = strlen($title) > $truncateStrLength ? substr($title,0, $truncateStrLength-3)  .'...' : $title;
						$collegeLocationDisplayText = strlen($collegeLocation) > $truncateStrLength ? substr($collegeLocation,0, $truncateStrLength-3)  .'...': $collegeLocation;
				?>
				<div class="float_L" style="height:65px;width:47%;">
					<div class="float_L">
						<img src="<?php echo $logo ;?>" style="border:1px solid #E2DDDC;" alt="<?php echo $title ;?>" title="<?php echo $title ;?>" />
					</div>
					<div class="normaltxt_11p_blk arial float_L" style="margin-left:10px">
						<a class="fontSize_12p" href="<?php echo $url ;?>" title="<?php echo $title ;?>"><?php echo $titleDisplayText; ?></a>
						<br class="lineSpace_5" />
						<span style="color:#838487"><label title="<?php echo $collegeLocation; ?>"><?php echo $collegeLocationDisplayText; ?></label></span>
						<br class="lineSpace_5" />
						<?php 	if($numOfCourses > 0 ){    ?>
							<span style="color:#6D970F">Courses:</span>
							<label title="<?php echo $courseName; ?>"><?php echo $displayCourseName; ?></label>
		        		<?php }  ?>
					</div>
					<div class="clear_L"></div>
				</div>
				<?php }	?>
                </div>
				<div class="clear_L"></div>
				</div>				
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>		
	</div>
</div>
<div class="clear_L"></div>
                        <script>
<?php 
                        if(COLLECT_HOME_PAGE_DATA == 1) { ?>

                            var IE = document.all?true:false;
                            var recordData = document.body.offsetHeight+"|"+document.body.offsetWidth;
                            var recordTime = 0;
                            function getMouseXY(e) {
                                if (IE) { // grab the x-y pos.s if browser is IE
                                    tempX = event.clientX;
                                    tempY = event.clientY;
                                } else {  // grab the x-y pos.s if browser is NS
                                    tempX = e.pageX - window.pageXOffset;
                                    tempY = e.pageY - window.pageYOffset;
                                }  
                                // catch possible negative values in NS4
                                if (tempX < 0){tempX = 0;}
                                if (tempY < 0){tempY = 0;}  
                                // show the position values in the form named Show
                                // in the text fields named MouseX and MouseY
                                return true;
                            }
                            function displayCoords(e) {
                                recordFunction(2);
                            }

                            function recordFunction(flag) {

                                var addChar = "";
                                if(flag==2) {
                                    addChar = "C";
                                }
                                if((recordTime < 500)||(flag==1)||(flag==2)){
                                    if(window.pageYOffset != undefined) {
                                        recordData += ":"+addChar+(window.pageYOffset+tempY);
                                        recordData += "|"+addChar+(window.pageXOffset+tempX);
                                        recordTime++;
                                    }else {
                                        if(document.body.scrollTop!=undefined) {
                                            recordData += ":"+addChar+(document.body.scrollTop+tempY);
                                            recordData += "|"+addChar+(document.body.scrollLeft+tempX);
                                            recordTime++;
                                        }else {
                                            if(document.getElementById("google_keyword").scrollTop!=undefined) {
                                                recordData += ":"+addChar+(document.getElementById("google_keyword").scrollTop+tempY);
                                                recordData += "|"+addChar+(document.getElementById("google_keyword").scrollLeft+tempX);
                                                recordTime++;
                                            }
                                        }

                                    }
                                }
                            }

                            function myOnunload() {
                                recordFunction(1);
                                var xmlHttpPageTrack=getXMLHTTPObject();
                                var url="/payment/newPage/registerPageData/"+recordData;
                                xmlHttpPageTrack.open("POST",url,true); 
                                xmlHttpPageTrack.send(null);
                            }
                            window.onunload = myOnunload;



                            if (!IE) document.captureEvents(Event.MOUSEMOVE);

                            document.onmousemove = getMouseXY;



                            if(!IE){ window.captureEvents(Event.CLICK);
                                window.onclick= displayCoords;
                            }else {
                                document.onclick= displayCoords;
                            }




                            // Temporary variables to hold mouse x-y pos.s
                            var tempX = 0;
                            var tempY = 0;
                            // Main function to retrieve mouse x-y pos.s

                        
                            setInterval('recordFunction(0)',2000);
                            
                        <?php }?>
                        </script>

<!--End_featuredCategories-->
