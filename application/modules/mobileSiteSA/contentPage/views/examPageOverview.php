<?php
    $headerComponents = array(
	  'css'             					=> array('applyContentPageSA'),
	  'js'              					=> array('vendor/jquery.lazy.min','contentSA'),
	  'canonicalURL'    					=> $seoData['canonicalUrl'],
	  'title'           					=> $seoData['seoTitle'],
	  'metaDescription' 					=> $seoData['seoDescription'],
      'hideSeoRevisitFlag' 					=> true,
      'hideSeoRatingFlag' 					=> true,
      'hideSeoPragmaFlag' 					=> true,
      'hideSeoClassificationFlag' 			=> true,
      'articleImage' 						=> $imageUrl,
      'pgType'	        					=> $pgType,
      'robotsMetaTag' 						=> $robots,
      'metaKeywords'    					=> '');
    $this->load->view('commonModule/header',$headerComponents);
    echo jsb9recordServerTime('SA_MOB_EXAMPAGE',1);
    $this->load->view('examPageContents');
    $this->load->view('examPageFooter');
?>
<script>
	var contentId 		= '<?php echo $content['data']['content_id']; ?>';
	var contentType 	= '<?php echo $content['data']['type']; ?>';
	var sectionId 	    = '<?php echo $sectionData['sectionId']; ?>';
	//var email 			= '<?php echo base64_encode($content['data']['email']); ?>';
	//var name 			= '<?php echo base64_encode($content['data']['username']);?>';
	//var stripTitle 		= '<?php echo base64_encode($content['data']['strip_title']); ?>';
	//var contentUrl 		= '<?php echo base64_encode($content['data']['contentURL']);?>';
	//var totalDislikes 	= '<?php echo $content['rating']['totalLikesAnsDisLikes'] - $content['rating']['totalLikes']; ?>';
	//var totalLikes 		= '<?php echo $content['rating']['totalLikes']; ?>';
	var authorId		= '<?=$authorId?>';
		
</script>