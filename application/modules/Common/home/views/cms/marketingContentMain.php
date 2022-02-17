<?php $this->load->view('cms/bannerHeader'); ?>
<?php $this->load->view('common/calendardiv'); ?>
<?php $this->load->view("home/cms/bannerTabs"); ?>

<div class="cms-sub-wrp">
	<div class="cms-box-layout">
		<?php
		if($action == 'add') { ?>
			<h1 class="cms-heading">Create new content</h1>
		<?php
			$this->load->view('cms/addFormForMarketingContent');
		} else {
			if(empty($slotData)){
				echo "<h1>Invalid slot.</h1>";
			} else {
				?>
				<h1 class="cms-heading">Edit slot:</h1>
				<div style="float:right">
					<h2>Last modified at: <?php echo $slotData['modificationDate']; ?></h2>
				</div>
			<?php
				$this->load->view('cms/addFormForMarketingContent');
			}
		}
		?>
		<?php $this->load->view('cms/marketingContentList'); ?>
	</div>
</div>

<div class="spacer20 clearFix"></div>
<?php $this->load->view('common/footerNew'); ?>