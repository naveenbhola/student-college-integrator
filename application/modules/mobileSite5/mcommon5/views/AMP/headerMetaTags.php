<meta charset="utf-8">
<!--below line is used for to disable safari broswer to creating links for string of digits -->
<meta name="format-detection" content="telephone=no"> 
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> -->
<title><?php if ($m_meta_title) { echo $m_meta_title; } else { echo "Higher Education in India | Shiksha.com"; } ?></title>
<meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- below line is causing problem in iphone so commenting this line-->
<!-- <base href="/" /> -->
<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->
<meta name = "format-detection" content = "telephone=no" />
<meta name = "format-detection" content = "address=no" />
<meta name="description" content="<?php  if (!empty($m_meta_description)) { echo htmlentities($m_meta_description); } else
{ echo "Explore thousands of colleges and courses on India's leading higher education portal - Shiksha.com. See details like fees, admission process, reviews and much more."; } ?>"/>
<?php if($pageType!='articlePage'): ?>
<meta name="keywords" content="<?php if ($m_meta_keywords) { echo $m_meta_keywords; } else { echo
"Shiksha, education, colleges,universities, institutes,career, career options, career prospects,
engineering, mba, medical, mbbs,study abroad, foreign education, college, university, institute,
courses, coaching, technical education, higher education,forum, community, education career experts,
ask experts, admissions,results, events,scholarships"; } ?>"/>
<?php endif;?>
<?php if($addNoFollow=="true"){ ?>
<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
<?php }elseif(!empty($noIndexMetaTag)){?>
<META NAME="ROBOTS" CONTENT="NOINDEX">
<?php } else if(isset($noIndexNoFollow) && $noIndexNoFollow){ ?>
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">
 <?php } else { ?>
<meta name="robots" content="ALL" />
<?php } ?>
<meta name="copyright" content="Shiksha.com" />
<meta name="resource-type" content="document" />
<meta name="pragma" content="no-cache" />
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320"/>
<!-- <meta http-equiv="cleartype" content="on">
<meta http-equiv="x-dns-prefetch-control" content="off"> -->
<?php if(!(isset($doNotLoadMobileFiles) && $doNotLoadMobileFiles=='true') && $pageName=='collegeReview'){ ?>
    <meta property="og:image" content="//<?php echo IMGURL; ?>/public/mobile5/images/cr.jpg" />
<?php }
	if($app_linking['android_facebook']){
	?>
	<!--Android facebook app meta tags   -->
	<meta property="al:android:app_name" content="<?php echo $app_linking['android_facebook']['app_name']; ?>" />
	<meta property="al:android:package" content="<?php echo $app_linking['android_facebook']['package']; ?>" />
	<meta property="al:web:should_fallback" content="<?php echo $app_linking['android_facebook']['should_fallback']; ?>" />
	<meta property="al:android:url" content="<?php echo $app_linking['android_facebook']['url']; ?>" /> 
	<?php
	}
	if($app_linking['android_twitter']){
	?>
	<!--Android twitter app meta tags   -->
	<meta name="twitter:card" content="summary"><!--summary-->
	<meta name="twitter:site" content="@shiksha_com">
	<meta name="twitter:title" content="{{pageMetaTitle}}">
	<meta name="twitter:description" content="{{pageMetaDescription}}">
	<meta name="twitter:image" content="{{pageOgImage}}">
	<meta name="twitter:app:name:googleplay" content="<?php echo $app_linking['android_twitter']['app_name']; ?>"/>
	<meta name="twitter:app:id:googleplay" content="<?php echo $app_linking['android_twitter']['app_id']; ?>"/>
	<meta name="twitter:app:url:googleplay" content="<?php echo $app_linking['android_twitter']['url']; ?>"/>
	<?php
	}
	?>

<!-- Added for APP integration Start -->
<?php if(isset($alternate) && $alternate!=''){ ?>
<link rel="alternate" href="<?php echo $alternate; ?>" />
<?php } ?>
<!-- Added for APP integration end -->

<link rel="publisher" href="https://plus.google.com/+shiksha"/>

<!-- Added for prev canonical url -->
<?php if(isset($prevURL) && $prevURL!=''){ ?>
<link rel="prev" href="<?php echo $prevURL;?>" />
<?php } ?>
<!-- Added for next canonical url -->
<?php if(isset($nextURL) && $nextURL!=''){ ?>
<link rel="next" href="<?php echo $nextURL;?>" />
<?php } ?>