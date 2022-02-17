<?php
    // if we need to add other elements having data-role = page it can be passed as below
    $footerComponents = array(
                                'pages'=>array(),
                                'commonJSV2'=>true,
                                'js' => array('homePageSA'),
    							'trackingPageKeyIdForReg' => 349
    						);
    $this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
<?php if($recentCourses['showRecentTab']==1){?>
var showRecentFlag = true;
<?php }else{?>
var showRecentFlag = false;
<?php }?>
</script>