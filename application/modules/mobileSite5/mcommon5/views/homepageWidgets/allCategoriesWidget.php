<section class="stdBnr">
    <div class="_container">
        <?php $this->load->view('homepageWidgets/userCategoryTabs'); ?>
        <div class="tbc stdyc">
        	<div data-index="1" id="mba">
        	<?php $this->load->view('homepageWidgets/mbaTab');?>
        	</div>
        	<div data-index="2" id="engg">
        	<?php $this->load->view('homepageWidgets/enggTab');?>
        	</div>
        	<div data-index="3" id="design">
        	<?php $this->load->view('homepageWidgets/designTab');?>
        	</div>
        	<div data-index="4" id="law">
        	<?php $this->load->view('homepageWidgets/lawTab');?>
        	</div>
        	<div data-index="5" id="other">
                <div class="loader">Loading...</div>
		    </div>
        </div>
    </div>
</section>
