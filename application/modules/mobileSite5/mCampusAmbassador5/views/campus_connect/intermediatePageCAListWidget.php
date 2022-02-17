<?php $count=0; ?>
              <?php if(!empty($campusRepForInstData)) { ?>

<section class="content-wrap clearfix" style="border-radius:0; box-shadow:none; margin:15px 0; background: #fff;">
        	<header class="content-inner content-header clearfix">
            	<h2 style="margin-right:98px; padding:4px 0 0;color: #4b4a4a;" class="title-txt">Current students</h2>
            </header>
            
            
            <div class="campus-college-sub-container">
                  <ul class="current-students-list" id="campusRepList">
                  <?php foreach($campusRepForInstData as $result){
                  $presentUserId = $result['userId'];
                  $caobj = $courseRepository->find($result['courseId']);?>
                    <li style="background:#f6f6f6;">
                      <div class="stu-figure flLt" style="padding: 5px;">
                          <?php if(!empty($result['imageURL'])){ ?>
                          <img src="<?=$result['imageURL']?>" width="73" height="95" alt="student-image">
                        <?php } else {?>
                        <?php $url = SHIKSHA_HOME."/public/images/photoNotAvailable_s.gif" ; ?>
                        <img src="<?=$url?>" width="73" height="96" />
                        <?php } ?>
                      </div>
                        <div class="student-detail" >
                            <div style="height:49px; overflow:hidden">
                                <strong class="student-name"><?=$result['displayName']?></strong>
                                <?php if($result['badge'] == 'CurrentStudent'){ ?>
                                <p class="badge-title">current student</p>
                                 <?php } else {?>
                                       <p class="badge-title"><?=$result['badge']?></p>
                                <?php } ?>
                            </div>
                            <div class="student-course-info">
                            	<p><span>Course: </span><strong><?php echo $caobj->getName();?></strong></p>
                                <?php if(!empty($totalAnsCount[$presentUserId])){ ?>
                                <p><strong><?php echo $totalAnsCount[$presentUserId]['count']; ?> answers <?php } else { ?><?php } ?></strong></p>
                            </div>
                        </div>
                    </li>
                     <?php } ?>

                </ul>
                <?php } ?>
           <?php if(count($campusRepForInstData) > 2){ ?><a href="javascript:void(0)" onclick="$('#campusRepList li').slideDown(500);$(this).hide();">View all Current students</a> <?php } ?>
                
			</div>
</section>

<script>
$(document).ready(function(){
  $('#campusRepList li').each(function(e){
     if (e<=1) {
      $(this).show();
     }else{
      $(this).hide();
     }
  });
});
</script>
        