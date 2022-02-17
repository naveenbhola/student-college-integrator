<!--below link is mandatory, if you have both non-amp page and amp page  give non-amp page url otherwise give it self url in href attribute-->
<?php if(!empty($m_canonical_url)){ ?>
<link rel="canonical" href="<?php echo $m_canonical_url;?>" />
<?php } ?>
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="//<?php echo IMGURL; ?>/public/mobile/images/touch/apple-touch-icon-144x144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="//<?php echo IMGURL; ?>/public/mobile/images/touch/apple-touch-icon-114x114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="//<?php echo IMGURL; ?>/public/mobile/images/apple-touch-icon-72x72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="//<?php echo IMGURL; ?>/public/mobile/images/apple-touch-icon-57x57-precomposed.png">
<link rel="shortcut icon" href="//<?php echo IMGURL; ?>/pwa/public/images/apple-touch-icon-v1.png">
 <?php if(!empty($dns_prefetch)) { 
            foreach($dns_prefetch as $link) { ?>
                <link rel="dns-prefetch" href="<?php echo $link;?>">
        <?php } 
} else {?>
          <link rel="dns-prefetch" href="//ask.shiksha.com">
          <?php } ?>

<?php if(isset($previousURL) && $previousURL!=''){ ?>
<!-- Added for Pagination Previous Start -->
<link rel="prev" href="<?php echo $previousURL; ?>" />
<!-- Added for Pagination Previous End -->
<?php } ?>

   <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">