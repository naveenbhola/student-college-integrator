<title>Create FB Lead Mapping</title>
<?php 
	$this->load->view('FBLeadResponseInterface/fbExceptionPanelHeader');
?>

<div class="container-fluid">
	<?php 
		$this->load->view('FBLeadResponseInterface/fbLeadInterfaceTabs',array('class_name'=>'resMap'));
	?>

  <div class="row">
    <div class="col-sm-12">
	    	<div class="form-group row">
	    		<div class="col-sm-12">
	    			<h3>Create FB Lead Mapping</h3>
	    		</div>
	    	</div>
	    	<br/>
			<div class="form-group row">
				<label for="instituteId" class="col-sm-2 col-form-label">Institute Id</label>
				<div class="col-sm-2">
					<input type="number" min="0" class="form-control" id="instituteId">
				</div>
			</div>
			<div class="form-group row">
				<label  class="col-sm-2 col-form-label">Course / Form Type</label>
				<div class="col-sm-10">
					<label class="radio-inline">
					  <input type="radio" class="formType" name="form_type" id="fb_form_type" value="location"> Asked for Location in FB Lead Form
					</label>
					<label class="radio-inline">
					  <input type="radio" class="formType" name="form_type" id="fb_form_type_2" value="without_location"> Not Asked for Location in FB Lead Form		 
					</label>		
				</div>
			</div>
			<div class="form-group row cityRow"  style="display:none;">
				<label for="instituteCourseCity" class="col-sm-2 col-form-label">City</label>
				<div class="col-sm-3">
					<select id="instituteCourseCity" class="form-control">
			        	<option>Choose Locations</option>			        	
			      </select>
			    </div>
			</div>
			<div class="form-group row">
				<div class="col-sm-2">
					<button type="button" class="btn btn-primary showCourses" disabled="disabled">Show Courses</button>
				</div>
			</div>		
    </div>    

 </div>

    <div class="row coursesList" style="display:none;">
	    <div class="col-sm-12">
		    	<div class="form-group row">
		    		<div class="col-sm-12">
		    			<h3>Courses</h3>
		    		</div>
		    	</div>
		    	<div class="form-group">
		    		 <table class="table">
					  <thead class="tableHead">
					    
					  </thead>
					  <tbody class="tableBody">					    
					  </tbody>
					</table>
		    	</div>
		    	<div class="form-group row">
					<div class="col-sm-2">
						<button type="button" class="btn btn-primary fbSubmitBtn" data-toggle="modal">Submit</button>
					</div>
				</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="fbResponseSaveModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        Data has been saved successfully
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="location.href='/enterprise/FBLeadResponseInterface/listingResponseMapping';">View FB Mapping Listings</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="errorModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body errorMsgBody">
        Something went wrong
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="//<?php echo CSSURL; ?>/public/js/fbleadMapping.js"></script>

<?php $this->load->view('enterprise/footer'); ?>