<!--Code Starts form here-->
    	<div id="career-cms-wrapper">
<?php $this->load->view('CareerProductEnterprise/subtabsCareers');?>
<div class="cms-section">
            	<div class="sectoion-title">
            		<h2 style="color:#cacaca"><a href= "/CareerProductEnterprise/CareerEnterprise/addOrEditCareers/add">Add Career</a> &nbsp;| &nbsp;
			<a href= "/CareerProductEnterprise/CareerEnterprise/addOrEditCareers/edit">Edit Career</a></h2>
                </div>
		
            	<form method="post" action="/CareerProductEnterprise/Careers/createCareerToStreamExpInterestMapping">
            	<ul>  
		   <?php if($subTabType=='edit'):?>
			<li>
				<label>Select Career:</label>
				<div class="career-fields">
					<select id="careerid" name="stream[]" class="universal-select" style="width:210px" onChange="careerObj.getDataForSelectedCareer(this.value);">
						<option value="">Select</option>
					<?php foreach ($careerList as  $careerLis) {?>	
						<option value="<?php echo $careerLis['careerId'];?>"> <?php echo $careerLis['name']; ?></option>
					<?php } ?>
					</select>
				<div id="careerid_error" class="errorMsg">&nbsp;</div>
				</div>
			</li>
		    <?php endif;?>
                	<li>
				<label>Careers Name:</label>
				<div class="career-fields">
					<input type="text" class="universal-txt-field" style="width:300px;" id="careername"/>
				<div id="careername_error" class="errorMsg">&nbsp;</div>
				</div>
			
			</li>
			<li>
				<label>Stream:</label>
				<div class="career-fields">
					<select id="stream" name="stream[]" multiple="true" size="3" class="universal-select" style="width:210px">
						<?php	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
						  if (!preg_match('/MSIE/i', $user_agent)) { ?>
						     <option value="All" >Any</option><?php }?>
				        <?php for($i=0; $i<$streamArrayLength; $i++) { ?>
						<option value="<?php echo $streamArray[$i];?>"><?php echo $streamArray[$i];?></option>
				        <?php } ?>		
					</select>
				<div id="stream_error" class="errorMsg">&nbsp;</div>
				</div>
			</li>
			
			<li>
				<label>Express Interest 1:</label>
				<div class="career-fields">
				
					<select id="expressInterest1" name="expressInterest1[]" multiple="multiple" size="5" class="universal-select" style="width:210px">
						<?php foreach($expressInterestList as $expressInterestLis){ ?>
						<option value="<?php echo $expressInterestLis['eiId']?>"><?php echo $expressInterestLis['eiName']?></option>
						<?php }?>
					</select>
				<div id="expressInterest1_error" class="errorMsg">&nbsp;</div>
				</div>
			</li>
                    
                    <li>
                    	<label>Express Interest 2:</label>
                        <div class="career-fields">
			<?php $i=0; ?>
                        	<select id="expressInterest2" name="expressInterest2[]" multiple="multiple" size="5" class="universal-select" style="width:210px">
					<?php foreach($expressInterestList as $expressInterestLis){ ?>
						<option value="<?php echo $expressInterestLis['eiId']?>"><?php echo $expressInterestLis['eiName'];?></option>
						<?php }?>
				</select>
                        </div>
                    </li>
                    
                    <li>
                    	<label>Challenge for entry:</label>
                        <div class="career-fields">
                        	<select name="difficultyLevel" id="difficultyLevel" class="universal-select" style="width:210px">
					<option value=''>Select</option>
					<option value="High">High</option>
					<option value="Medium">Medium</option>
					<option value="Low">Low</option>
				</select>
			<div id="difficultylevel_error" class="errorMsg">&nbsp;</div>
                        </div>
                    </li>
                    <li>
                    	<label>Mandatory Subject(s):</label>
                        <div class="career-fields">
                        	<select name="mandatorySubject" id="mandatorySubject" multiple="multiple" size="5"  class="universal-select" style="width:210px">
					<option value="Agriculture">Agriculture</option>
					<option value="Biology">Biology</option>
					<option value="Chemistry">Chemistry</option>
					<option value="Economics">Economics</option>
					<option value="History">History</option>
					<option value="Home Science">Home Science</option>
					<option value="Mathematics">Mathematics</option>
					<option value="Physics">Physics</option>
					<option value="Psychology">Psychology</option>
					<option value="Sociology">Sociology</option>
					<option value="Geography">Geography</option>
				</select>
                        </div>
                    </li>
                </ul>
		<?php if($subTabType!='edit'){?>
                <div class="btn-cont"><input type="button" value="Add" class="orange-button" onClick="careerObj.createMappingCareerToExpressInterest();return false;"/></div>
		 <?php } if ($subTabType=='edit') { ?>
		 <div class="btn-cont">
				<input type="button" value="Update" class="orange-button" onClick="careerObj.createMappingCareerToExpressInterest('edit');return false;"/>
				<input type="button" value="Delete" class="orange-button" onClick="careerObj.createMappingCareerToExpressInterest('delete');return false;"/>
		 </div>
		<?php }?>
		</form>
            </div>
            <div class="clearFix"></div>
            <div id="successMessage" class="errorMsg" style="font-size: 14px">&nbsp;</div>
        </div>
        <!--Code Ends here-->
        
        


