<div class="abroad-cms-content">
    <div class="abroad-cms-rt-box">
    <?php $this->load->view('manageTabs',array('tab'=>$activePage));?>
        <div>
            <h1 id="vcmsHeading" class="abroad-title">Video CMS</h1>
            <?php if($this->session->flashdata('sucMsg')){?>
            <span id="vsucMsg" style="position: relative;background-color: #b3ff99;font-size:13px;margin-left: 35%;top: -25px;padding: 5px 10px 5px 10px;width: 225px;"><?php echo $this->session->flashdata('sucMsg');?></span>
            <?php }?>
            <?php $this->load->view('videoCMS/videoList'); ?>
        </div>
	</div>
</div>
<script type="text/javascript">
if(document.getElementById('vsucMsg')){
	setTimeout(function(){document.getElementById('vsucMsg').style.display = 'none';},3000);
}
mobileSearch = 'true';
</script>
<?php $this->load->view('enterprise/footer'); ?>
<?php $this->load->view('enterprise/autoSuggestorV2ForCMS'); ?>