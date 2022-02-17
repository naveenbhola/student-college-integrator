<div class="base-frame">
	<div class="cutOff-det" id='mainContentDiv'>
		<?php $this->load->view('collegeCutoffTuples'); ?>
	</div>
</div>
<?php if($totalCount> 30){ ?>
<div class="base-frame ldMre-cutoffs">
    <a href="javascript:void(0)" id ='loadMore'>Load More Cut-Offs</a>
</div>		
<?php }	?>	
