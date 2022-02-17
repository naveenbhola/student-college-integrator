
<a href="javascript:void(0);" class="nav__back" style="display:none;"> <i></i> </a>
    <ul class="clear__space nav-bar-list">
    <?php 
        if(empty($activeSectionName))
        {
            $activeSectionName = 'homepage';
        }
    foreach($snippetUrl as $sectionKey=>$url){
        $className = '';
        if($activeSectionName == $sectionKey)
        {
            $className = 'active';
            $sectionUrl = 'javascript:void(0)';
        }
        else
        {
            $sectionUrl = $url;
        }
        ?>
    <li class="ps__rl"> <a href="<?php echo $sectionUrl;?>" class="<?=$className;?>" elementtofocus="<?php echo $sectionKey;?>" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$sectionKey]));?>_NAVIGATION"><?php echo str_replace('Summary','Overview',$sectionNameMapping[$sectionKey]);?> </a> </li>
    <?php } ?>
    </ul>
<a href="javascript:void(0);" class="nav__nxt" style="display:none;"> <i></i> </a>
