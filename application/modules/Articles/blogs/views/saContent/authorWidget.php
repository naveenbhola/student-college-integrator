<?php
if($authorDataUser):
       if($authorDataUser['gender']=='male')
	      $prefix = 'him';
       else if($authorDataUser['gender']=='female')
	      $prefix = 'her';
       else $prefix = 'them';
       $gplusprofile = $authorDataUser['gplusprofile'];
       $fprofile = $authorDataUser['fprofile'];
       $tprofile = $authorDataUser['tprofile'];

?>

	<?php if($avatarimageurl=='') $avatarimageurl = '/public/images/photoNotAvailable.gif';?>

	 <div class="author-section clearwidth">
            	<h2>About the author</h2>
                <div class="author-content">
                	<div class="author-image flLt">
			<img src="<?=$avatarimageurl;?>" width="104" height="78" alt="<?=$authoruserName ;?>"/>
			</div>
                    <div class="abt-author">
                    <div class="athr-details">
                        <strong class="authr-name">
			    <?= $authoruserName;?>
			</strong>
                        <p class="author-subtitle"><?= $authorDataUser['about_me_current_position'];?></p>
                        <p>
			     <?= $authorDataUser['about_me_education'];?>
			</p>
                     </div>
		     <?php if(($authorDataUser['fprofile']!='') || ($authorDataUser['gplusprofile']!='') || ($authorDataUser['tprofile']!='' )):?>
                    <p class="author-subtitle">Follow <?=$prefix;?> on</p>
                    <p class="font-12">
		      <?php if($authorDataUser['fprofile']!=''):?><a href="<?=$fprofile;?>" class="mR15">Facebook</a>  <?php endif;?>
		     <?php if($authorDataUser['gplusprofile']!=''):?> <a href="<?=$gplusprofile;?>" class="mR15">Google+</a> <?php endif;?>
		      <?php if($authorDataUser['tprofile']!=''):?><a href="<?=$tprofile;?>">Twitter</a><?php endif;?>

		    </p>  <?php endif;?>
                    </div>

               </div>
            </div>

<script>
       var is_processed = true;
       var is_processed1 = true;
       var article_index_HPRDGN =0;
       var total_article_element = '<?php echo $noOfSlides;?>';
       var index_article = 0;
</script>
<?php endif;?>
