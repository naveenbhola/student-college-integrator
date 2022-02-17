<?php if($total>0){foreach($instituteObjs as $institute){
    ?>
      <li instituteid="<?php echo $institute->getId();?>" onclick="getInstituteDetail(this);trackEventByGA('COLLEGE_SELECT',' CareerCompass_course');">
        <a href="javascript:void(0);" title="<?php echo $institute->getName();?>"><?php echo strlen($institute->getName()) > 28 ? substr($institute->getName(), 0, 28).'...' : $institute->getName();?><br> <span>
        <?php echo $institute->getMainLocation()?$institute->getMainLocation()->getCityName():'';?>
        	
        </span></a>
        <i class="common-sprite widget-pointer"></i>
      </li>
<?php }}else{?>
      <li class="no-results">
      Sorry! No results found for your selected options. Please modify your search
      </li>
<?php }?>
<script>
var totalInstitutes = '<?php echo $total;?>';
var perPage = '<?php echo $pageSize;?>';
</script>
