<?php if($widgetForPage != 'HOMEPAGE_DESKTOP' && $product != 'shiksha_analytics') { ?>
    <div>
        <?php echo Modules::run('common/GlobalShiksha/getHeaderSearch', true); ?>
    </div>
<?php } ?>

<?php 
    // added by akhter
    if(file_exists($gnbCache) && (time() - filemtime($gnbCache)) <= (30*24*60*60) && !$resetPage){
        echo file_get_contents($gnbCache);
    }else{
        ob_start();
        $html = $this->load->view('common/newGNBInner','',true);
        echo sanitize_output($html); 
        $pageContent = ob_get_contents();
        ob_end_clean();
        echo $pageContent;
        $fp=fopen($gnbCache,'w+');
        flock( $fp, LOCK_EX ); // exclusive lock
        fputs($fp,$pageContent);
        flock( $fp, LOCK_UN ); // release the lock
        fclose($fp);
    }
?>
<p class="clr"></p>

</div>
<script>
    var isShowGlobalNav = true;
</script>