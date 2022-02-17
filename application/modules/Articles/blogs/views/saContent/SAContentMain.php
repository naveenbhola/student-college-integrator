<?php 
$this->load->view('saContent/articlePageHeader');
$this->load->view('saContent/articlePageContent');
$this->load->view('saContent/articlePageFooter');
?>
<img id = 'beacon_img' width=1 height=1 >
<?php 
$contentId = $content['data']['content_id'];
$contentType = $content['data']['type'];
?>
<script>
var contentId 	= '<?php echo $contentId?>';
var contentType = '<?php echo $contentType?>';
var email 		= '<?php echo base64_encode($content['data']['email']); ?>';
var name 		= '<?php echo base64_encode($content['data']['username']);?>';
var stripTitle 	= '<?php echo base64_encode($content['data']['strip_title']); ?>';
var contentUrl 	= '<?php echo base64_encode($content['data']['contentURL']);?>';
	

var img = document.getElementById('beacon_img');
var randNum = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011007/<?=$contentType?>+<?=$contentId?>';
var isContentPage = true;
</script>