<div <?php if(!empty($id)){echo 'id="'.$id.'"';} ?> class="subcat-layer <?php echo ($layerToShow == $id)? 'zero-marginleft':''; ?>">
    <div class="subcat-heading">
       <a class="hamburger-back" href="javascript:void(0);"></a>
        <div class="r-table">
            <div class="subcat-p"><?php echo $layerHeading; ?></div>
            <div class="subcat-dtls">
                <?php if(!empty($headingCaption)) { ?>
                    <span class="clgs-stream"><?php echo $headingCaption; ?></span>
                <?php }
                else{ ?>
                    <span class="clgs-stream initial_hide"></span>
                <?php }?>
                <p class="clr"></p>
            </div>
        </div>   
    </div>
    <!--subchild nav--> 
    <div class="subcat-child">
        <div class="childwrap">
            <?php foreach($links as $link) {
                echo $link;
            } ?>
        </div>
    </div>
</div>