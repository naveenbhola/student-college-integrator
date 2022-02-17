<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="content-language" content="EN" />
<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
<title><?php echo $title;?></title>
<?php
if(isset($css) && is_array($css)) : ?>
<?php foreach($css as $cssFile) :
?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
<?php 
endforeach;
endif;

?>
<?php if(isset($js) && is_array($js)) :?>
<?php
        $alreadyAddedJs = array('header');
        if(!isset($js)){
           $js = array();
        }
        $js = array_unique(array_merge($alreadyAddedJs, $js));
    ?>
<?php foreach($js as $jsFile): ?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
<?php endforeach; ?>
<?php endif; ?>
<script type="text/javascript" src="//<?php echo $_SERVER['SERVER_NAME']; ?>/public/js/tinymce4/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
<?php if ((isset($displayname)) && !empty($displayname)): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>

</script>

</head><body>
