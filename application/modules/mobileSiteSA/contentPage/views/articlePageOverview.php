<?php
//$headerComponents = array(
//    'css'             		=> array('article-guidePageSA'),
//    'js'              		=> array('vendor/jquery.lazy.min','contentSA'),
//    'jsRequiredInHeader'		=> array("commonSA","registrationSA"),
//    'canonicalURL'    		=> $canonicalURL,
//    'title'           		=> $seoTitle,
//    'metaDescription' 		=> $metaDescription,
//    'hideSeoRevisitFlag' 					=> true,
//    'hideSeoRatingFlag' 					=> true,
//    'hideSeoPragmaFlag' 					=> true,
//    'hideSeoClassificationFlag' 			=> true,
//    'articleImage' 						=> $imageUrl,
//    'pgType'	        					=> $pgType,
//    'robotsMetaTag' 						=> $robots,
//    'metaKeywords'    					=> ''
//);
//$this->load->view('commonModule/header',$headerComponents);
$this->load->view('articlePageHeader');
echo jsb9recordServerTime('SA_MOB_ARTICLEPAGE',1);

$this->load->view('articlePageContents');

$this->load->view('articlePageFooter');
?>
<script>
    var contentId 		= '<?php echo $content['data']['content_id']; ?>';
    var contentType 	= '<?php echo $content['data']['type']; ?>';
    var email 			= '<?php echo base64_encode($content['data']['email']); ?>';
    var name 			= '<?php echo base64_encode($content['data']['username']);?>';
    var stripTitle 		= '<?php echo base64_encode($content['data']['strip_title']); ?>';
    var contentUrl 		= '<?php echo base64_encode($content['data']['contentURL']);?>';
    var totalDislikes 	= '<?php echo $content['rating']['totalLikesAnsDisLikes'] - $content['rating']['totalLikes']; ?>';
    var totalLikes 		= '<?php echo $content['rating']['totalLikes']; ?>';
    var authorId		= '<?=$content['data']["created_by"]?>';

</script>