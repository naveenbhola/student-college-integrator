<?php
  

 foreach ($courses as $key => $courseObj) { 

    $courseId = $courseObj->getId();
    $data['tupleCourseCount'] = ++$loadedCourseCount;
    $data['course'] = $courseObj;
    $data['reviewCount'] = $courseObj->getReviewCount();
    $data['aggregateReviewInfo'] = $aggregateReviewsData[$courseId];
    //_p($data['aggregateReviewInfo']);
        
     ?>
    <p class="clr"></p>
    <p class="tupl-separator"></p>
    <section class="tpl-curse-dtls more_<?php echo $courseObj->getInstituteId()."_".$key; ?>" style='display:none'>
        <?php $this->load->view('common/gridTuple/tupleMiddle', $data); ?>
        <p class="clr"></p>
        <?php 

            $this->load->view('common/gridTuple/tupleBottom',$displayData);
                   // die('aaaaaaaaaaa');        
        ?>
        <input type='hidden' id='morecoursesnum_<?php echo $courseObj->getId(); ?>' value='<?php echo $data['tupleCourseCount']; ?>'>
    </section>
<?php } ?>