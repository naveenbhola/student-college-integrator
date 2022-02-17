<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils'),
      'title'			=> 'SUMS - Permission Denied',
      'product' 		=> '',
      'displayname'	=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<style>
   .sums_rowbg_b {float:left;width:170px;line-height:15px;background-color:#ccc;padding:5px 5px;font-weight:bold;margin:0 0 0 10px;}
   .sums_rowbg {float:left;width:170px;line-height:15px;background-color:#ccc;padding:5px 5px;margin:0 10px}
   .sums_row {float:left;width:170px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
</style>
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
   <div style="width:223px; float:left">
      <div class="raised_greenGradient">
	 <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
	 <?php 
			$this->load->view('/sums/leftPanelFor'.$prodId); 
	?>
	 <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
      </div>
   </div>
<div style="margin-left:233px">		
        <div>
            <div class="lineSpace_18">&nbsp;</div>
            <div class="lineSpace_18">&nbsp;</div>
            <center>
                <h2 class="arial">
		   You are not allowed to view/edit this page.
                </h2>
            </center>
        </div>
    </div>
</div>
