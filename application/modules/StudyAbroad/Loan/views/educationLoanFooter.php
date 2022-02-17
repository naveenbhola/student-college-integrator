<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 24/10/18
 * Time: 11:24 AM
 */
if($isMobile)
{
    $footerComponents = array(
        'js'                      => array('saEducationLoan','validation'),
        'responsiveJSFile'        => array('saEducationLoan','validation'),
        'commonJSV2'              => true,
        'trackingPageKeyIdForReg' => 1915,
        'pages'                   => array(),
    );
    $footerView = 'commonModule/footerV2';
}
else
{

    $footerComponents = array(
        'nonAsyncJSBundle'  => 'sa-education-loan-page',
        'asyncJSBundle'     => 'async-sa-education-loan-page'
    );
    $footerView = 'studyAbroadCommon/saFooter';
}
$this->load->view($footerView,$footerComponents);
?>
<script type="text/javascript">
	var pageIdentifier = '<?php echo $beaconTrackData['pageIdentifier']?>';
    var isMobile = <?php echo $isMobile?1:0;?>;
</script>