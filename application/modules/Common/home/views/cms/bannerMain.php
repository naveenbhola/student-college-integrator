<?php $this->load->view('cms/bannerHeader'); ?>
<?php $this->load->view('common/calendardiv'); ?>
<?php $this->load->view("home/cms/bannerTabs"); ?>

<?php if($pageType != 'banner') {
	$this->load->view("home/cms/previewLayer");
} ?>

<div class="cms-sub-wrp">
	<div class="cms-box-layout">
		<?php
		if($action == 'add') { ?>
			<h1 class="cms-heading">Book new slot:</h1>
		<?php
			$this->load->view('cms/addForm');
		} else {
			if(empty($slotData)){
				echo "<h1>Invalid slot.</h1>";
			} else {
				?>
				<h1 class="cms-heading">Edit slot:</h1>
				<div style="float:right">
					<h2>Last modified at: <?php echo $slotData['last_modified']; ?>, </h2>
					<h2>Last modified by: <?php echo $slotData['lastModifiedByName']; ?></h2>
				</div>
			<?php
				$this->load->view('cms/addForm');
			}
		}
		?>
		<?php $this->load->view('cms/bannerList'); ?>
	</div>
</div>

<div class="spacer20 clearFix"></div>
<?php $this->load->view('common/footerNew'); ?>