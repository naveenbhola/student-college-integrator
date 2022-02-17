</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('taggingcms'); ?>"></script>
<script>
        $j = jQuery.noConflict();

</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
 <script>
    bindAutoSuggestor('<?php echo $pageNameForSuggestor;?>');  
</script>
</html>