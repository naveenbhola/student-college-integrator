<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','prototype','utils','common'),
        'jsFooter'         =>      array('prototype','scriptaculous','utils'),
        'title'      =>        'SUMS - Client profile addition Success Page',
        'tabName'          =>        'Register',
        'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => '',
        'search'=>false,
	'displayname'=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
        'callShiksha'=>1
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>

<div class="mar_full_10p">
    <div style="width:223px; float:left">
        <?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	 ?>
    </div>
    <div style="margin-left:233px">		
        <div>
            <div class="lineSpace_18">&nbsp;</div>
            <div class="lineSpace_18">&nbsp;</div>
            <?php if(isset($userDetails)) { ?>
            <center>
                <h2 class="arial">
                    Profile of <font class="OrgangeFont"><?php echo $busiCollegeName; ?></font>  has been added successfully with Client-Email <font class="OrgangeFont"><?php echo $userDetails['email']; ?></font> and Client-Id : <font class="OrgangeFont"><?php echo $userid;?> </font>
                </h2>
            </center>

            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
