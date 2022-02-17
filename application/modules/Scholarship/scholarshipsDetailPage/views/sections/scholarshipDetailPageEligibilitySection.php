<div class="col-bar">
	         	<h2 class="titl-main">Scholarship Eligibility</h2>
		        <?php

                        if (empty($exams) && empty($education) && $workEx =='' && $interview == '') { ?>
                            <p>No specific eligibility requirement mentioned on the Scholarship site, student profile will be evaluated on a case by case basis.</p>
                        <?php
                        }
                        
	         	 if(!empty($restrictions) || !empty($restrictionsDescription)){ ?>
		         	<h3 class="sub-titl f14-fnt">Special Restrictions </h3>
		         	<ul class="view-list">
		         		<?php foreach($restrictions as $key=>$restriction){ ?>
			         		<li>
			         			<p><?php echo $specialRestrictions[$restriction]; ?></p>
			         	    </li>
		         	   <?php } ?>
		         	</ul>
                                <p><?php echo ($restrictionsDescription);?></p>
		        <?php } 
	         	 if(!empty($exams) || !empty($education) || $workEx > 0 || $interview > 0){ ?>
	         				
	         	<h3 class="f14-fnt mt10">Exams Details</h3>
	         	<table class="col-table">
	         	  	<thead class="thead-default">
	         	  		<tr>
	         	  			<th>Exam name</th>
	         	  			<th>Indicates if exam is required and cutoff (if any)</th>
	         	  		</tr>
	         	  	</thead>
	         	  	<tbody class="tbody-default">
	         			<?php foreach ($exams as $key => $examData) { ?>
		         	  		<tr>
		         	  			<td><?php echo $examMasterList[$examData['examId']]; ?></td>
		         	  			<td>Required<?php if($examData['cutoff'] > 0){ echo " (minimum score ".$examData['cutoff'].')'; } ?></td>
		         	  		</tr>
	         	  		<?php } ?>
	         	  		<?php foreach ($education as $key => $eduData) { ?>
		         	  		<tr>
		         	  			<td><?php echo $eduData['educationLevel']; ?></td>
		         	  			<td>Required<?php if($eduData['score'] > 0){ echo " (minimum score ".$eduData['score']; echo (($eduData['scoreType'] == 'gpa') ? ' GPA' : '%').')'; } ?></td>
		         	  		</tr>
	         	  		<?php } ?>
	         	  		<?php if($workEx > 0) { ?>
		         	  		<tr>
		         	  			<td><?php echo 'Work Experience'; ?></td>
		         	  			<td>Required<?php if($workExYears > 0){ echo " (minimum ".$workExYears.' years)'; } ?></td>
		         	  		</tr>
	         	  		<?php } ?>
	         	  		<?php if($interview > 0) { ?>
		         	  		<tr>
		         	  			<td><?php echo 'Interview'; ?></td>
		         	  			<td>Required</td>
		         	  		</tr>
	         	  		<?php } ?>
	         	  	</tbody>
	         	  </table>
	         	  <?php } 
	         	  	if($desc != ''){
	         	  ?>
                            <h3 class="sub-titl f14-fnt mtop-15">Eligibility</h3>
	         	  <p><?php echo $desc; ?></p>
                          
	         	  <?php } 
	         	  	if($pref != ''){
	         	  ?>
	         	  
                          <h3 class="sub-titl f14-fnt mtop-15">Preference will be given to following candidates:</h3>
		         	<p><?php echo $pref; ?></p>
		         <?php } 
		         if(!empty($applicableNationalities)){
                 ?>
                 <div class="mtop-15">
                    <h2 class="titl-main">Eligible student nationality</h2>
                        <p>This scholarship is applicable for students from <span id="allNationalityList"><?php echo $applicableNationalities[0]; ?></span><?php if(count($applicableNationalities) > 1){ ?><a class="appNat" href="javascript:void(0);"> +<?php echo (count($applicableNationalities)-1); ?> more </a><?php } ?></p>
                 </div>
                 <?php } ?>
</div>
