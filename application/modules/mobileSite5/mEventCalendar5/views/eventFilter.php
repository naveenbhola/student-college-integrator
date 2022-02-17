<section class="cale-header2">
    <a class="cal-back-btn" href="javascript:void(0);" data-rel="back" id="goToHomePage"><i class="cale-sprite cl-backicn"></i></a>
    <h3 class="head-cal-txt">FILTER YOUR RESULTS</h3>
</section>
<section class="bgColrWhte setalrt-pg" style=" padding-top: 0px;">
    <ul class="cale-xmsLst" style="margin-top: 0px;">
         <li><p>Select Exams</p></li>
         <?php foreach($examNameList as $key=>$examName){ ?>
        <li><input type="checkbox" name="examId[]" id="<?php echo $key;?>" class="examIds" value="<?php echo $key;?>"/><label for="<?php echo $key;?>"><?php echo $examName;?></label></li>
        <?php } ?>
    </ul>
</section>
<div class="cale-fot-btns" style="position: fixed;  left: 0px;  bottom: 0px;  width: 100%;  padding-bottom: 0px !important;  background-color: #F2F2F2;  padding-top: 10px;">
    <ul style="padding-bottom: 8px;">
        <li><a class="cale-grybtn" href="javascript:void(0);" id="resetFilter">CLEAR ALL</a></li>
        <li><a class="cale-bluebtn" href="javascript:void(0);" id="applyFilter">APPLY</a></li>
    </ul>
</div>
</section>
<script>
    var eventCategoryName = '<?php echo $categoryName;?>';
    var randomnumber=Math.floor(Math.random()*11000);
    $('#resetFilter').bind('click',function(){$('.examIds').removeAttr('checked');});
    $('#applyFilter').bind('click',function(){
        $('#eventListing').html('<div style="margin: 20px; text-align: center;"><img src="/public/mobile5/images/ajax-loader.gif" border=0 /></div>');
        makeAjaxCall();
    });
</script>
