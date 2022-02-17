<title>Fb Leads Mapping</title>
<?php 
	$this->load->view('FBLeadResponseInterface/fbExceptionPanelHeader');
?>
<div class="mlr10">
<div class="container-fluid">
	<?php 
		$this->load->view('FBLeadResponseInterface/fbLeadInterfaceTabs',array('class_name'=>'listMap'));
	?>
	<div class="row">
    	<div class="col-12">
    	<?php if(count($listing) > 0) {?>
			<table class="table table-striped">
			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col" class="col-md-2">INSTITUTE ID</th>      
			      <th scope="col" class="col-md-2" width="127">FB FORM ID</th>      
			      <th scope="col">LOCATION</th>      
			      <th scope="col">DESCRIPTION</th>      
			      <th scope="col" class="col-md-2">CREATED ON</th>      
			      <th scope="col">ACTIONS</th>
			    </tr>
			  </thead>
			  <tbody>
				  <?php 				  
				  foreach ($listing as $key => $val) {?>
				  	<tr>
				      <th scope="row"><?=$val['id']?></th>
				      <td ><?=$val['institute_id']?></td>      
				      <td style="word-break: break-all;"><?=$val['fb_form_id']?></td>      
				      <td><?=($val['fb_form_type'] == 'location')?'Yes':'No';?></td>      
				      <td><?=$val['description']?></td>      
				      <td><?=$val['created_on']?></td>      
				      <td>
				      	<button type="button" fb_form_id = "<?=$val['fb_form_id']?>" description = "<?=base64_encode($val['description'])?>" main_id = "<?=$val['id']?>" class="btn btn-primary fbFormBtn">UPDATE</button>
				      	<button type="button" fb_form_id = "<?=$val['fb_form_id']?>" main_id = "<?=$val['id']?>" class="btn btn-primary fbCSVDownload">DOWNLOAD</button>
					  </td>					  
				    </tr>
				  <?php }?>
				        
  				</tbody>
			</table>
		<?php } else {?>
			<div style="margin:80px 0 80px 250px">
			<h3>No Fb Leads Mapping available</h3>	
			</div>
		<?php }?>	
		</div>
	</div>
</div>
</div>


<div class="modal fade" id="fbFormModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">UPDATE FB FORM ID</h5>        
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="fb_form_input" class="col-form-label">FB form Id:</label>
            <input type="number" class="form-control" id="fb_form_input">
          </div>          
          <div class="form-group">

            <label for="fb_form_input" class="col-form-label">Description:</label>
            <textarea class="form-control custom-control" rows="3" style="resize:none" id="fb_form_description_input"></textarea>            
          </div>          
      </div>
      <input type="hidden" name="mainTableId" id = "mainTableId">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary submitFbFormId">Save</button>
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

<form id="fbFormCSVForm" action="/enterprise/FBLeadResponseInterface/downloadCSV" method="post" style="display:none"> 
    <input type="hidden" name="tableMainId" id="csv_tableId">     
</form>
<script src="//<?php echo CSSURL; ?>/public/js/fbleadMapping.js"></script>


<?php $this->load->view('enterprise/footer'); ?>