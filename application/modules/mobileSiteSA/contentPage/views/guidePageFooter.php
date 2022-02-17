<?php 
$footerComponents = array(
                'commonJSV2'=>true,
                'loadLazyJSFile'=>true,
                'js'=> array('contentSA'),
                'jqueryUIJsAsync'=>false,
                'trackingPageKeyIdForReg' => 485,
                'pages'=> array('contentUserRegistrationPanel')
            );
$this->load->view('commonModule/footerV2', $footerComponents);
$contentId = $content['data']['content_id'];
$contentType = $content['data']['type'];
?>
<script>
var BEACON_URL = '<?php echo BEACON_URL; ?>';
var upVoteStatus = '<?php echo $status; ?>';
</script>