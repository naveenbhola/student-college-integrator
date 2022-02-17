<div class="col-bar">
	         	<h2 class="titl-main">Application Process for this scholarship</h2>
	         	<?php if(!empty($docsRequired)){ ?>
	         	<h3 class="sub-titl f14-fnt">Application Documents</h3>
	         	<table class="col-table doc-btm">
	         	  	<thead class="thead-default">
	         	  		<tr>
	         	  			<th>Type of documents</th>
	         	  			<th>Indicates if this type of document is required to be submitted</th>
	         	  		</tr>
	         	  	</thead>
	         	  	<tbody class="tbody-default ">
	         	  	<?php foreach ($docsRequired as $name => $value) { ?>
	         	  		<tr>
	         	  			<td><?php echo $name; ?></td>
	         	  			<td>Required</td>
	         	  		</tr>
	         	  	<?php } ?>
	         	  	</tbody>
	         	  </table>
	         	  <?php } 
	         	  	if($scholarshipObj->getApplicationData()->getDocsDescription() != ''){ ?>
	         	  		<p><?php echo $scholarshipObj->getApplicationData()->getDocsDescription(); ?></p>
	         	  <?php }
                          
                          if ($scholarshipObj->externalApplicationRequired() == 'no') { 
                               ?>
                              <h3 class="sub-titl f14-fnt">Scholarship Application</h3>
                              <p>Students will be auto-considered for this scholarship when they apply for the courses on which this scholarship is applicable. No separate application is needed.</p>
                          <?php } 
                          else if ($scholarshipObj->externalApplicationRequired() == 'yes') { 
                              ?>
                                <h3 class="sub-titl f14-fnt">Scholarship Application</h3>
                                <p>This scholarship needs a separate external application.</p>
                         <?php
                           }
                          
                           
	         	  if(!empty($finalImpDateData)){ ?>
                 <h3 class="sub-titl f14-fnt">Important Dates</h3>
                  <table class="col-table">
	         	  	<thead class="thead-default">
	         	  		<tr>
	         	  			<th width="20%"></th>
	         	  			<th width="20%">Date</th>
	         	  			<th width="60%">Additional Details</th>
	         	  		</tr>
	         	  	</thead>
                                
	         	  	<tbody class="tbody-default">
	         	  		<?php foreach ($finalImpDateData as $value) {
	         	  			?>
	         	  		<tr>
	         	  			<td><?php echo $value['heading'] ? $value['heading'] : 'Not Available' ?></td>
	         	  			<td><?php echo $value['timestamp'] > 0 ? date('F d, Y', $value['timestamp'] ) : 'Not Available'; ?></td>
	         	  			<td><?php echo $value['description'] ? $value['description'] : 'Not Available'; ?></td>
	         	  		</tr>
	         	  		<?php } ?>
	         	  	</tbody>
	         	  </table>
	         	 <p class="view-p">Please note that these dates could be in local country times and may not be IST (Indian standard time).</p>
	         	 <?php } if($scholarshipObj->getApplicationData()->getFaqLink() != '' || $scholarshipObj->getApplicationData()->getContactEmail() != '' || $scholarshipObj->getApplicationData()->getContactPhone() != '' || $scholarshipObj->getApplicationData()->getBrochureUrl() != ''){ ?>
	         	 <table class="col-table">
	         	  	<tbody class="tbody-default">
	         	  	<?php if($scholarshipObj->getApplicationData()->getFaqLink() != ''){ ?>
	         	  		<tr>
	         	  			<td>FAQ Link</td>
	         	  			<td><a class="a-link" target="blank" href="<?php echo $scholarshipObj->getApplicationData()->getFaqLink(); ?>"><?php echo $scholarshipObj->getApplicationData()->getFaqLink(); ?><i class="common-sprite ex-link-icon"></i></a></td>
	         	  		</tr>
	         	  	<?php } 
	         	  	if($scholarshipObj->getApplicationData()->getContactEmail() != ''){ ?>
	         	  		<tr>
	         	  			<td>Contact email</td>
	         	  			<td><a class="a-link" href="mailto:<?php echo $scholarshipObj->getApplicationData()->getContactEmail(); ?>"><?php echo $scholarshipObj->getApplicationData()->getContactEmail(); ?><i class="common-sprite ex-link-icon"></i></a></td>
	         	  		</tr>
	         	  	<?php } 
	         	  	if($scholarshipObj->getApplicationData()->getContactPhone() != ''){ ?>
	         	  		<tr>
	         	  			<td>Phone number</td>
	         	  			<td><?php echo $scholarshipObj->getApplicationData()->getContactPhone(); ?></td>
	         	  		</tr>
	         	  	<?php } ?>
	         	  	</tbody>
	         	  </table>
	         	    <?php } 
	         	  	if($scholarshipObj->getDeadline()->getAdditionalInfo() != ''){ ?>
	                  	<div class="col-bar">
	                      <h3 class="sub-titl f14-fnt">Additional Information</h3>
	                      <p><?php echo $scholarshipObj->getDeadline()->getAdditionalInfo(); ?></p>
		         		</div>
	         		<?php } ?>
</div>
