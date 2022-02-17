<title><?php echo $title?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="dns-prefetch" href="//<?php echo JSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo CSSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo IMGURL; ?>">
<link rel="dns-prefetch" href="<?php echo MEDIAHOSTURL; ?>">
<link rel="preconnect" href="//<?php echo JSURL; ?>" crossorigin>
<link rel="preconnect" href="//<?php echo CSSURL; ?>" crossorigin>
<link rel="preconnect" href="<?php echo MEDIAHOSTURL; ?>" crossorigin>
<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ="/>
<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?htmlentities($metaDescription):''; ?>"/>
<?php if( ! (isset($doNotShowKeywords) && $doNotShowKeywords=="true") ){ ?>
<?php if(isset($metaKeywords) && $metaKeywords!=''){ ?>
<META NAME="Keywords" CONTENT="<?php echo isset($metaKeywords)?$metaKeywords:''; ?>"/>
<?php } ?>
<?php } ?>
<meta name="copyright" content="<?php echo date('Y') ;?> Shiksha.com" />
<meta http-equiv="content-language" content="en" />
<meta name="author" content="www.Shiksha.com" />
<?php	if(!(isset($hideSeoRevisitFlag) && $hideSeoRevisitFlag)) {
	if(in_array($beaconTrackData['pageIdentifier'], array('examPage','articleDetailPage','allArticlePage'))) { ?>
		<meta name="revisit-after" content="daily" />
	<?php } else{ ?>
		<meta name="revisit-after" content="1 day" />
	<?php }
	?>
<?php } ?>
<?php $requestUrl = (string)$_SERVER['REQUEST_URI'];
$check = (strrpos($requestUrl,"getTopicDetail")!='')?'found':(strrpos($requestUrl,"-qna-")!='')?'found':'notfound';
if(($check == 'found')&&($isMasterList == 'present')){
?>
<meta name="isMasterList" content="<?php echo $isMasterList?>"/>
<?php }?>
<?php /*<!-- Added for External articles Start -->*/ ?>
<?php if(isset($noIndexMetaTag) && $noIndexMetaTag){ ?>
<META NAME="ROBOTS" CONTENT="NOINDEX">
<?php } else if(isset($noIndexNoFollow) && $noIndexNoFollow){ ?>
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">
<?php } else if(isset($noIndexFollow) && $noIndexFollow){ ?>
<META NAME="ROBOTS" CONTENT="NOINDEX,FOLLOW">
<?php }	 else{ ?>
<meta name="robots" content="ALL" />
<?php } ?>
<?php if(isset($articleCreationDate) && $articleCreationDate!=''){ ?>
<meta name="DC.date.issued" content="<?php echo $articleCreationDate?>"/>
<?php } ?>
<?php /*<!-- Added for adding Publish date meta tag on Question detail pages -->*/ ?>
<?php if(isset($publishDate) && $publishDate!=''){ ?>
<meta name="publishDate" content="<?=$publishDate?>" />
<?php } ?>
<?php /*<!-- Added for Type Meta tag in ANA Start -->*/?>
<?php if(isset($typeOfEntity) && $typeOfEntity!=''){ ?>
<meta name="entityType" content="<?=$typeOfEntity?>" />
<?php } ?>
<?php /*<!-- Added for Type Meta tag in ANA End -->*/?>
<?php /*<!-- Added for FB Like button on Article detail pages -->*/ ?>
<meta property="og:locale" content="en_US" />

<meta property="og:title" content="<?php echo $title;?>" />
<meta property="og:url" content="<?php echo $canonicalURL;?>" />
<meta property="og:image" content="<?php echo ($sharethumbnailUrl) ? $sharethumbnailUrl : "https://images.shiksha.com/public/images/shareThumbnail.jpg";?>" />
<meta property="fb:app_id" content="<?php echo FACEBOOK_API_ID; ?>" />

<?php
if($app_linking['android_facebook']){
?>
<?php /*<!--Android facebook app meta tags   -->*/ ?>
<meta property="al:android:app_name" content="<?php echo $app_linking['android_facebook']['app_name']; ?>" />
<meta property="al:android:package" content="<?php echo $app_linking['android_facebook']['package']; ?>" />
<meta property="al:web:should_fallback" content="<?php echo $app_linking['android_facebook']['should_fallback']; ?>" />
<meta property="al:android:url" content="<?php echo $app_linking['android_facebook']['url']; ?>" /> 
<?php
}
if($app_linking['android_twitter']){
?>
<?php /*<!--Android twitter app meta tags   -->*/ ?>
<meta name="twitter:app:name:googleplay" content="<?php echo $app_linking['android_twitter']['app_name']; ?>"/>
<meta name="twitter:app:id:googleplay" content="<?php echo $app_linking['android_twitter']['app_id']; ?>"/>
<meta name="twitter:app:url:googleplay" content="<?php echo $app_linking['android_twitter']['url']; ?>"/>
<?php
}
?>
<?php /*<!-- Added for Canonical URL Start -->*/ ?>
<?php if(isset($canonicalURL) && $canonicalURL!=''){ ?>
<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
<?php } ?>
<?php /*<!-- Added for Canonical URL End -->*/ ?>
<?php /*<!-- Added for Pagination Previous Start -->*/ ?>
<?php if(isset($previousURL) && $previousURL!=''){ ?>
<link rel="prev" href="<?php echo $previousURL; ?>" />
<?php } ?>
<?php /*<!-- Added for Pagination Previous End -->*/ ?>
<?php /*<!-- Added for Pagination Next Start -->*/ ?>
<?php if(isset($nextURL) && $nextURL!=''){ ?>
<link rel="next" href="<?php echo $nextURL; ?>" />
<?php } ?>
<?php /*<!-- Added for Pagination Next End -->*/ ?>
<?php /*<!-- Added for APP integration Start -->*/ ?>
<?php if(isset($alternate) && $alternate!=''){ ?>
<link rel="alternate" href="<?php echo $alternate; ?>" />
<?php } ?>
<?php /*<!-- Added for APP integration end -->*/ ?>
<link rel="icon" href="//<?=IMGURL?>/pwa/public/images/apple-touch-icon-v1.png" type="image/x-icon" />
<link rel="shortcut icon" href="//<?=IMGURL?>/pwa/public/images/apple-touch-icon-v1.png" type="image/x-icon" />
<link rel="publisher" href="https://plus.google.com/+shiksha"/>
