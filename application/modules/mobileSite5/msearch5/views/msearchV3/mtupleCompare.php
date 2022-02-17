<?php if(!empty($trackingIds['compare'])){ ?>
	<a href="javascript:void(0);"  data-trackid='<?php echo $trackingIds['compare'];?>' pagenum='<?php echo $pageNumber; ?>' product="<?php echo $product; ?>" track="on" data-instid='<?php echo $institute->getId();?>' data-courseid='<?php echo $course->getId();?>' class="compare-site-tour srp-clg-compare btnCmpGlobal<?php echo $course->getId();?> tupleCompareButton"  id="compare<?php echo $course->getId();?>">
		<strong id="plus-icon<?=$courseId?>" style="visibility:hidden" class="plus-icon"></strong>
		<i class="compare-Icn"></i>
		<span>
			<?php if(!empty($comparedData[$course->getId()])){
				echo "Added";
				echo '<i class="added-mark"></i>';
			}else{
				echo "Compare";
			}
			?>
		</span>
	</a>
<?php } ?>