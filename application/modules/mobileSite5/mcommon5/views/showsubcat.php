<?php $this->load->view('header'); ?>
<?php
foreach ($HomePageData as $keyN=>$value) {
    if ($keyN == $key) {
        $headline = $value['name'];
        $subcatArray = $value['subcats'];
    }
}
?>
<div id="head-sep"></div>
<div id="head-title">
<h4><?php echo $headline; ?></h4>
    <span>&nbsp;</span>
</div>
<?php
		
	if (getTempUserData('confirmation_message')){?>
		<div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
		 <?php echo getTempUserData('confirmation_message'); ?>
		</div> 
	<?php } 
?>
<div id="content-wrap">
    <div id="contents">
        <ul>
        <?php
        foreach($subcatArray as $subcatdetail) {
        ?>
    	<li>
    		<a href = "<?php echo $subcatdetail['url']; ?>" >
    		<div class="cate-list"><?php echo $subcatdetail['name'];?></div>
    		</a>
    	</li>
        <?php } ?>
        </ul>
    </div>
        <?php 
    deleteTempUserData('confirmation_message');
    ?>
    <?php $this->load->view('ANA/smartphones/cafe_widget'); ?>
    <?php $this->load->view('footer'); ?>
