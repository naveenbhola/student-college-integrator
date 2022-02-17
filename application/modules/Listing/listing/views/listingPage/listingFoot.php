            <div class="clearFix"></div>
<div style="width:685px">
<?php
$bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
$this->load->view('common/banner',$bannerProperties);
?>
</div>
</div>
<?php
$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.Placeholders"); ?>"></script>

<script>
if($('scrollbar-box')) {
	$j('#scrollbar-box').tinyscrollbar();
}
</script>
<script>
var pageContentLast = $j('#footer').offset().top;
setTimeout(function(){
		pageContentLast = $j('#footer').offset().top;
},2000);
window.onscroll = scrollListingHeader
setInterval(function(){window.onscroll = scrollListingHeader},2000);
(function($j) {
	loadFormsOnListingPage(<?=$typeId?>,'<?=$pageType?>','<?=$_REQUEST['city']?>','<?=$_REQUEST['locality']?>');
})($j);
var listings_with_localities = <?php echo $listings_with_localities; ?>;
<?php global $institutesWithoutUnified;if(in_array($institute->getId(),$institutesWithoutUnified)){ ?>
        var disableGlobalUnified = 1;
<?php } ?>
</script>

<?php if($documentDownload) { ?>

<script>
$j(document).ready(function(){
    setTimeout(function(){
        downloadFile('/listing/ListingPage/downloadCustomDocument/<?php echo $typeId; ?>');
    },100);
});

function downloadFile(url)
{
    var iframe;
    iframe = document.getElementById("download-container");
    if (iframe === null)
    {
        iframe = document.createElement('iframe');  
        iframe.id = "download-container";
        iframe.style.visibility = 'hidden';
        document.body.appendChild(iframe);
    }
    iframe.src = url;
	displayMessage('/common/loadOverlayContent/common-commonThankYouDownload',400,100,{"redirectAfterDownload":"<?php echo $redirectAfterDocumentDownload; ?>"});
}
</script>
<?php } ?>


<script>
//Download e-brochure when redirecing from mailer

var queryStringParameter = 'download';
var regularExp = new RegExp("[\\?&]" + queryStringParameter + "=([^&#]*)");
var queryStringSearchResults = regularExp.exec(location.search);
var startBrochureDownload = queryStringSearchResults == null ? "" : decodeURIComponent(queryStringSearchResults[1].replace(/\+/g, " "));

<?php
if(is_object($course) && get_class($course) == 'Course' && 
!empty($course)) {
?>
if(startBrochureDownload == '1') {
    startDownload(<?php echo $course->getId(); ?>);
}
<?php
}
?>
</script>
