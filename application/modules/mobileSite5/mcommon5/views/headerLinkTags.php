<?php if(!empty($m_canonical_url)){ ?>
<link rel="canonical" href="<?php echo $m_canonical_url;?>" />
<?php }else if(isset($canonicalURL) && $canonicalURL!=''){ ?>
<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
<?php } ?>
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
<?php if(!empty($ampUrl)) {?>
	<link rel="amphtml" href="<?=$ampUrl;?>"/>
<?php } ?>