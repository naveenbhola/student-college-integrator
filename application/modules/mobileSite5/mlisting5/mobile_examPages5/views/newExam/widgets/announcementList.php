<ul class="cls-ul">
    <?php foreach($updates['updateList'] as $key=>$val){?>
           <li>
               <div>
                   <p class="color-3">


                   	<?php if(!empty($val['announce_url'])) {
	       				$followAttr = "";
	       				if(strpos($val['announce_url'], "shiksha.com") === FALSE){
	       					$followAttr = "rel='nofollow'";
	       				}
	       			?>
	       			<a href="<?=$val['announce_url'];?>" <?=$followAttr;?>><?=$val['update_text']?></a>
	       		<?php } else{
	       			echo $val['update_text'];
	       		  }
		       		?>
                   	
                   </p>
               </div>
           </li>
    <?php } ?>
</ul>