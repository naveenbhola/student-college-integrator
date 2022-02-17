        <?php if(!empty($links)):?>
        <div class="old-discussions clear-width">
            <h2 class="old-discuss-title">Previous year discussions
            </h2>
            <ul class="bullet-items">
   
            	<?php foreach ($links as $id => $data):?>
            	<?php if($_GET['link_id'] == $data['id']):?>
                <li class="flLt" style="color:#666 !important"><?php echo $data["historyLink"];?></li>
                <?php else:?>
                <!--<li class="flLt"><a href="javascript:void(0);" onclick="setLinkCookie('<?php //echo $data["id"]?>')" ><?php //echo $data["historyLink"];?></a></li>-->
		<li class="flLt"><a href="<?php echo SHIKSHA_HOME.preg_replace('/\?.*$/', '',$_SERVER['REQUEST_URI']).'?link_id='.$data['id'].'#ca_aqf'?>" ><?php echo $data["historyLink"];?></a></li>
                <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
    	<?php endif;?>
    
    
   <!-- 
        <div class="old-discussions clear-width">
            <h2 class="old-discuss-title">View discussion for other Courses</h2>
            <ul class="bullet-items">
                <li class="flLt"><a href="#">M. Tech. Automotive Engineering</a></li>
                <li class="flRt"><a href="#">B. Tech. Mechanical Engineering</a></li>
                <li class="flLt"><a href="#">M. Tech. CAD / CAM</a></li>
                <li class="flRt"><a href="#">Btech bioinformatics </a></li>
            </ul>
        </div>
    -->
