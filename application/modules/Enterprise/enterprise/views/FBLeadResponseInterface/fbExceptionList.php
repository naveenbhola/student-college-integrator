<title>Fb Leads Exception</title>
<?php 
	$this->load->view('FBLeadResponseInterface/fbExceptionPanelHeader');
?>

<div class="container-fluid">
	
	<?php 
		$this->load->view('FBLeadResponseInterface/fbLeadInterfaceTabs',array('class_name'=>'fbExptn'));
	?>
	<div class="row">
    	<div class="col-12">
    	<table class="table table-striped">
			  
			  <tbody>
				
				  	<tr>
				      <td >Total Leads</td>      
				      <td ><?=$total_leads_count?></td>
				    </tr>

				    <tr>
				      <td >Total Responses</td>      
				      <td ><?=$total_response_count?></td>
				    </tr>
				    
				    <tr>
				      <td >Total Exceptions</td>      
				      <td ><?=$total_exception_count?></td>
				    </tr>

				    <tr>
				      <td >City Exceptions</td>      
				      <td ><?=$total_city_excpetion?></td>
				    </tr>
				        
  				</tbody>
			</table>
		</div>
	</div>
</div>


<?php $this->load->view('enterprise/footer'); ?>