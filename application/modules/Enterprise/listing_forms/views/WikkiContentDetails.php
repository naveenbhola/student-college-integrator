<?php

/** check array length it shoud be have data */

if (count($wikiData) > 0 ) {

/** Now check listing type .. on the basis of this wikki content heading will be populated */

?>
	
	<div style="margin-left:200px">
	<?php
        // echo "<pre>"; print_r($wikiData); echo "</pre>";
	$j = 0;
        // echo "instituteType = ".$instituteType;
        //_P($wikiData);
	foreach ($wikiData as $data) {
                
		if($instituteType==1){
			if(in_array($data['key_name'], array('course_info','results'))){
                                // echo "<br>if key_name = ".$data['key_name'];
				continue;		
			}
		}
		if($instituteType==2){
			if(in_array($data['key_name'], array('placement_services','recruiting_companies','eligibility','admission_procedure','syllabus','course_desc'))){
                                // echo "<br> else key_name = ".$data['key_name'];
				continue;		
			}
		}
		if ($j == 0) {
		?>
				<div><b><?php echo $data['caption'];?></b> &nbsp; <span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'popupcontent_<?php echo $j;?>_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div>
				<div id="popupcontent_<?php echo $j;?>_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('popupcontent_<?php echo $j;?>_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<!--div><?php echo $data['example']; ?></div-->
					<ul>
                                       <?php if($type_of_check == 'institute'):?>
                                        <li>Contains information regarding institute history, offerings, associations, accreditations, associated institutes and highlights if any.</li>
                                         <li>Formatting should be like 3-4 lines and if required then bullet points for highlights.</li>
					 <li>Example: 
Central University of Jharkhand is one of the 16 new central universities inaugurated by the Honorable President of India. CUJ offers the students a wide range of 5 years integrated courses which are carefully selected to fulfill the requirements of Choice-based Credit System (CBCS). Admission to Central University of Jharkhand is done on the basis of the performance in Central Universities Common Entrance Test (CUCET). Listed below are the schools of CUJ approved by U.G.C/M.H.R.D/Visitor:<br/>
                                        <ul>
					<li>School for Management Sciences</li>
					<li>School for Management Sciences</li>
					<li>School for Languages</li>
					<li>School for Natural Sciences</li>
					<li>School for Engineering & Technologies</li>
					<li>School for Cultural Studies</li>
					<li>School for Natural Resource and Management</li>
					</ul>
					</li>
                                       <?php elseif($type_of_check == 'course'):?>
					<li>Contains information regarding course offerings, associations, accreditations, career opportunities and points like research development etc if available.</li>
					<li>Formatting should be like 3-4 lines and if required then bullet points for highlights/ Syllabus.</li>
					<li>Example: <br/>
					The whole course is split into 6 semesters spread over 3 years. Each semester has 4 subjects of 10 credits each.</li>
					<li>
					The subjects covered during the course are:<br/>
					<ul>
					<li>Information Technology </li>
					<li>Mathematics</li> 
					<li>Economics</li> 
					<li>Accounting</li> 
					<li>Company Law</li> 
					<li>Portfolio Management etc.</li> 
					<li>The last one year of the course is dedicated to the specialization you opt for.</li>
					</ul>
					</li>
					<?php endif;?>
                                        </ul>
				</div>
				<div class="spacer5 clearFix"></div>
				<div><textarea tip="listing_hostel_details" validate="validateStr" maxlength="10000" id="<?php echo $data['key_name'];?>" minlength="0" name="<?php echo $data['key_name'];?>" class='mceEditor' style="width:500px;height:100px"><?php echo $systemFieldsArr[$data['keyId']]['attributeValue']; ?></textarea></div>
				<div style="display:none"><div class="errorMsg" id="<?php echo $data['key_name'].'_error';?>"></div></div>
				<div class="spacer10 clearFix"></div>
		<?php
		} else {
		?>
			<div class="row">
				<div style="float:left">
				<div class="spacer10 clearFix"></div>
				<div><b><a id="wiki_placement_<?php echo $j; ?>" href="javascript:void(0);" onClick="return replaceInnerHTML(this,'wikki_<?php echo $data['key_name'];?>','<?php echo str_replace("\n", "", trim($data['caption']));?>');" >+ <?php echo $data['caption'];?></a></b>&nbsp; <span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'popupcontent_<?php echo $j;?>_help',obtainPostitionX(this)-5,obtainPostitionY(this)+15);" >view example</a> ]</span></div>                              
				<div id="popupcontent_<?php echo $j;?>_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('popupcontent_<?php echo $j;?>_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<!--div style="overflow:auto;"><?php echo $data['example']; ?></div-->
					<div style="overflow:auto;">
					<ul>
                    	<?php if($type_of_check == 'institute'):?>
						<?php if($data['key_name'] == 'rankings'):?>
						<li>This section will constitute the Rank information in Indian/ foreign associations or amongst its peers.</li>
						<li>Will have information of all major awards relevant from student’s point of view.</li>
						<li>Formatting should be with 1 line of header and then all awards in bullet points.</li>
						<li><p>Example:<br /> <u>Born to Succeed</u> That's our mantra. Our efforts have won us these prestigious awards:</p>
							<ul>
								<li>13th Best B-School in India, 2007 (Business World, 24-Dec 07 Issue)</li>
								<li>Category A Business School, 2006 (Business India Survey, 06)</li>
							</ul>
						</li>
                        
                        <?php elseif($data['key_name'] == 'placement_services'):?>
						<li>Consist of any information regarding the active placement cell within the institute and facts/ figures in %age about the number of placements happened in different offerings with few top recruiting companies.</li>
						<li>Example:<br />The institute has an independent placement cell that looks after the placements. The institute has performed well last year with 92% placements. The average annual package turned out to be 4.5 lakhs for B.Tech students.</li>
					<li>It can be merged with next section “Top Recruiting Companies”.</li>
 					<?php elseif($data['key_name'] == 'recruiting_companies'):?>
					<li>Will have name of all top recruiting companies.</li>
					<li>Formatting should be 1 line of a header and all names in bullet points.</li>
					<li>
                    	<p>Example:<br/>Major IT companies hired students from our institute last year.<br/>Few of those have been listed below:</p>
                        <ul>
                        	<li>Infosys</li>
							<li>Wipro</li>
							<li>HCL Technologies</li>
							<li>Satyam Computers etc</li>
						</ul>
					</li>
					<li>It can be merged with previous section “Placement Services”</li>
					<?php elseif($data['key_name'] == 'infrastructure'):?>
					<li>Will have all the information about the physical attributes/ offerings by the institute and different modes of teaching facilities.</li>
					<li>Formatting should be 1 line of header and major highlights in bullet points. However 2-3 lines can also be entertained about the institute if no major highlights are available to be in bullet points.</li>
					<li>
                    	<p>Example:<br/>IACT offers world class infrastructure and best environment-<br />
						<b>Classrooms</b>
                        <p>
						<ul>
						<li>Air-conditioned Classes</li>
						<li>Digital Projectors</li>
						</ul>
						<b>Digital Lab</b><br />
						<ul>
						<li>Air-conditioned labs</li>
						<li>Necessary software available</li>
						</ul>
						<b>Library</b><br/>
						<ul>
						<li>Institution member of British Council Library</li>
						<li>Institution member of American Library</li>
						</ul>
						<b>Seminar Hall</b><br />
						<ul>
						<li>Air-conditioned hall</li>
						<li>Projector & sound system</li>
						<li>Internet connection</li>
						</ul>
						<b>Media Studio</b><br/>
						<ul>
						<li>Its own studio floor with Blue Matte and an adjoining Digital Media Lab equipped with systems for Digital Video editing and compositing</li>
						<li>The studio was inaugurated by noted Film Producer and Oscar winner Mr. Barrie Osborne (Producer of The Matrix and LOTR)</li>	
</ul>
                                                <b>Student Lounge</b><br/>
					</li>
					<?php elseif($data['key_name'] == 'hostel_details'):?>
					<li>Formatting should be 1 line for header and rest facilities in bullet points.</li>
					<li>Example:<br />
					The institute has a hostel with 50 rooms. All rooms are semi-furnished.
					<p style="text-decoration:underline;">Hostel facilities </p>
					<ul>
					<li>Mess facility</li>
					<li>AC</li>
					<li>Hot water in winters</li>
					<li>Wi-Fi connection</li>					
					</ul>
					<p>There are separate hostel for females. Hostel fee is approximately INR 10,000 to 20,000 per month.</p>
					</li>
					<?php elseif($data['key_name'] == 'partner_institutes'):?>
					<li>Affiliations/ associations with different institutes.</li>
					<li>If there are more than 5 institutes, mention just top 5 and write “…10 More Institutes”.</li>
					<li>Example:<br />
					The institute shares affiliations with the following institutions worldwide:<br />
					<ul>
					<li>Skyline School of Management, Singapore</li>
					<li>Business School, Bangalore, India</li>
					<li>Mundhra Institute of Technology, Toronto</li>
					</ul>
					</li>
					<?php elseif($data['key_name'] == 'faculty'):?>
					<li>Should have some renowned names (if any).<br />
					<ul>
					<li>Formatting should be 1 line of header and names in bullet points.</li>
					<li>Should look for Qualification (highest), Title etc while adding more than one 
faculty or have many names as probable’s and their associations with some big names in the industry (if any).</li>
					<li>If any number is given like ‘No of Full Time Faculty, No of PhD Faculty’, then mention the numbers as well, else just names of directors/deans/HODs.</li>
					</ul>
					</li>
					<li>Where the information is good enough and you can give good and crisp things, use this format: <br/>
					<b>Example I:</b><br/>
					<b>Dr Kamal Mukherjee</b><br/>
          				B.E (SIBPUR), PGDM (XLRI),<br/>
					Fellow (XLRI),<br/>
             				<b>Teaching Experience</b> 2 years,<br/>
               				<b>Industry Experience</b> 30 years<br/>
					Use example 2 where information is less:<br/>
					<b>Example II:</b><br/>
					Our faculty includes:<br/>
					<ul>
					<li>Dr. P.K. Aggarwal MBA, MA (English) Dean (National College of Commerce, IP University) Shri Akash Garg MA (Eco)					</li>
					<li>Coordinator (Bombay Stock Exchange) Apart from this, our institute has visiting faculty from International School Of Business, Belgium</li>
					</ul>
					</li>
					<?php endif;?>
					<?php elseif($type_of_check == 'course'):?>
					<?php if($data['key_name'] == 'eligibility'):?>
					<li>This section will constitute the eligibility criteria for the course</li>
					<li>Should have all the information about eligibility but in a very crisp and simpler form</li>
					<li>Example:<br/>
					A candidate should be at least 21 years of age. He should have scored 65% in graduation.
					</li>
					<?php elseif($data['key_name'] == 'admission_procedure'):?>
					<li>It can be as descriptive as possible with absolute sentence structure and bullet points wherever required.</li>
					<li>Example:<br/>
					Admissions would be done on the following criteria:<br />
					<ul>	
					<li>Group Discussion</li>
					<li>Written Test</li>
					<li>Personal Interview</li>
					<li>A candidate is required to produce his original mark sheets of class XII during the time of the tests</li>					
					</ul>
					</li>
					<?php elseif($data['key_name'] == 'syllabus'):?>
					<li>Can be important from certificates/ short term courses point of view.</li>
					<li>Should not contain any short forms or abbreviations.</li>
					<li>Refrain using abbreviations like CBTs, M1 (module1) etc. which a student will have to think again or search for its meaning.</li>
					<li>Formatting should be 1 line of a header and all subjects in bullet points.</li> 
					<li>Example:<br />
					<p style="text-decoration:underline;">Syllabus</p>
					Semester 1<br />
					Subjects: IT, Mathematics, Communication Skills<br />
					Semester 2<br />
					Subjects: Advanced Mathematics, Physics, Chemistry<br />
					Semester 3<br />
					Subjects: Thermodynamics, Engineering Design, Kinetics Of Materials<br />
					Semester 4<br />
					Subjects: LAN, Mechanics of Materials, C++<br /> 
					</li>
					<?php elseif($data['key_name'] == 'faculty'):?>
					<li>Should have some renowned names (if any).</li>
					<li>Formatting should be 1 line of header and names in bullet points.</li>
					<li>If any number is given like ‘No of Full Time Faculty, No of PhD Faculty’, then 
mention the numbers as well, else just names of directors/deans/HODs.</li>
					<li>Should look for Qualification (highest), Title etc while adding more than one 
faculty or have many names as probable’s and their associations with some big names in the industry (if any).</li>
					<li>Example:<br />
					Our faculty includes:<br />
					<ul>
					<li>Dr. P.K. Aggarwal MBA, MA (English) Dean (National College of Commerce, IP University)</li>
					<li>Shri Akash Garg MA (Eco) Coordinator (Bombay Stock Exchange)</li>
					<li>Apart from this, our institute has visiting faculty from International School Of Business, Belgium</li>
					</ul>
					<?php elseif($data['key_name'] == 'results'):?>
					<p>Example:<br />
					We have trained over 50000 CAT aspirants in Delhi through classroom classes, study material and test series. 20 of our students made it to IIM A and 15 made it to IIM B.</li>
                                        <?php endif;?>
					<?php endif;?>
					</ul>
					</div>
				</div>
				<div class="spacer10 clearFix"></div>
                <div style="display:none" id="wikki_<?php echo $data['key_name'];?>" ><textarea validate="validateStr" maxlength="10000" minlength="0" class='mceEditor' name="<?php echo $data['key_name'];?>" id="wikki_textarea_<?php echo $data['key_name'];?>" style="width:500px;height:100px"><?php echo $systemFieldsArr[$data['keyId']]['attributeValue']; ?></textarea></div>
				<div style="display:none"><div class="errorMsg" id="wikki_textarea_<?php echo $data['key_name'];?>_error" ></div></div>
				</div>
				<div class="clearFix"></div>
			</div>
                                
				<?php
					if($systemFieldsArr[$data['keyId']]['attributeValue'] != NULL){ ?>
					<script>
						replaceInnerHTML($('wiki_placement_<?php echo $j; ?>'),'wikki_<?php echo $data['key_name'];?>','<?php echo str_replace("\n", "", trim($data['caption']));?>');
					</script>
					<?php
					}
				?>
		<?php
		}
		$j++;
	}?>
	</div>
<?php } ?>
