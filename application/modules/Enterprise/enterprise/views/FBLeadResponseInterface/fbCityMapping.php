<title>Fb Leads Exception</title>

<?php 
	$this->load->view('FBLeadResponseInterface/fbExceptionPanelHeader');
?>

<div class="container-fluid">
	
	<?php 
		$this->load->view('FBLeadResponseInterface/fbLeadInterfaceTabs',array('class_name'=>'cityMap'));
	?>
	<div class="row">
    	<div class="col-12">
    	<table class="table table-striped">
			  
			  <tbody>
				
				<?php 
				$itr = 0;
				foreach ($exception_cities as $id => $city_val) { $itr++; ?>
				  	<tr>

				     	<td><label for="shikshaCity" class="col-sm-2 col-form-label "><?php echo $city_val?>
				     		
				     	<input class="form-check-input shkCity" value=<?php echo $id;?> type="hidden">

				     	</label></td>
						<td><div class="col-sm-3">
							<?php $this->load->view('FBLeadResponseInterface/allCityDropdown',array('old_value' => $id));?>
						</div></td>

				    </tr>
					
				<?php } ?>

				    
  				</tbody>
			</table>

			<?php if($itr<1){?>
			<div class="row">
    			<div class="col-12">
    				<label for="shikshaCity" class="col-sm-12 col-form-label" style="text-align: center;">Currently there is no city to update</label>
    				 	
    			</div>
    		</div>		
    		<?php }?>
		</div>
	</div>

	<?php if($itr>0){?>
		<button type="button" class="btn btn-primary updateCity">Update City Mapping</button>
	<?php }?>
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


<!-- Modal -->
<div class="modal fade" id="fbCityUpdateModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        Data has been updated successfully
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="location.href='/enterprise/FBLeadResponseInterface/showFBMapCity';">View City Exceptions</button>
      </div>
    </div>
  </div>
</div>

<script src="//<?php echo CSSURL; ?>/public/js/fbleadMapping.js"></script>

<?php $this->load->view('enterprise/footer'); ?>