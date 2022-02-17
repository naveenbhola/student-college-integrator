<?php $this->load->view('cms/bannerHeader'); ?>
<?php $this->load->view('common/calendardiv'); ?>
<?php $this->load->view("home/cms/bannerTabs"); ?>



<div class="cms-sub-wrp">
	<div class="cms-box-layout">
		<?php
		if($action == 'add') { ?>
			<h1 class="cms-heading">Book new slot:</h1>
		<?php
			$this->load->view('cms/addFormForArticle');
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
				$this->load->view('cms/addFormForArticle');
			}
		}
		?>
		<?php $this->load->view('cms/bannerListForArticle'); ?>
	</div>
</div>

<div class="spacer20 clearFix"></div>
<?php $this->load->view('common/footerNew'); ?>