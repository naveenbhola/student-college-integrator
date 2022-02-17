<?php

    if($breadCrumb[1]){
        $crumb = '<a href="'.$breadCrumb[1]['url'].'">'.$breadCrumb[1]['name'].'</a> > ';
    }else{
        $crumb = '<a href="'.SHIKSHA_HOME.'">Home</a> > ';
    }
    $crumb .= '<a href="'.$breadCrumb[0]['url'].'">'.$breadCrumb[0]['name'].'</a> > '.addslashes(str_replace("\n",' ',$details['title'])).'';

?>
<script>
var crumb_url = '<?php echo $crumb; ?>';
</script>

