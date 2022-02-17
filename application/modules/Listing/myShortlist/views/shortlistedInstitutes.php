<div class="shortlist-content" style="margin-bottom:20px; padding:0; border-bottom:0 none">
	<?php if($validateuser !== 'false') { ?>
			<h1  class="shortlist-inner-title">My Shortlist</h1>		
	<?php	}else{ ?>
			<div class="shortlist-inner-title">My Shortlist</div>
	<?php	}	?>
    
    
    <table class="myshortlist-table" cellpadding="0" cellspacing="0">
        <tbody>
            <?php $this->load->view('myShortlist/shortlistedInstitutesHeader'); ?>
            <?php $this->load->view('myShortlist/shortlistedInstitutesTuple'); ?>
        </tbody>
    </table>
</div>