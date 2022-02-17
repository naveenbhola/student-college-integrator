<?php $this->load->view('cms/bannerHeader'); ?>
<?php $this->load->view('common/calendardiv'); ?>
<?php $this->load->view("home/cms/bannerTabs"); ?>

<?php if($pageType != 'banner') {
	$this->load->view("home/cms/previewLayer");
} ?>

<div class="cms-pane">
  <div class="cms-box">
		<?php
		if($action == 'add') { ?>
			<h1 class="cms-heading">Add new Testimonial:</h1>
		<?php
			$this->load->view('cms/addFormForTestimonials');
		} else {
			if(empty($slotData)){
				echo "<h1>Invalid slot.</h1>";
			} else {
				?>
				<h1 class="cms-heading">Edit Testimonial:</h1>
				<div style="float:right">
					<h2>Last modified at: <?php echo $slotData['modificationDate']; ?></h2>
				</div>
			<?php
				$this->load->view('cms/addFormForTestimonials');
			}
		}
		?>
		<?php $this->load->view('cms/testimonialList'); ?>
	</div>
</div>

<div class="spacer20 clearFix"></div>
<?php $this->load->view('common/footerNew'); ?>