<div class="ana-main-view" id="ana-main-view-<?php echo $courseId;?>">
    <?php  echo $this->load->view('CA/myshortlist_ask_form');?>
    <?php if(!empty($qna)){?>
    <div class="tab-content-section" id="previously-data-<?php echo $courseId;?>">
        <div class="ask-list-title">Previously Answered Questions</div>
        <ol class="quest-list" start="<?php if($pageNo >0){ echo ($pageNo * $pageSize + 1);}else{ echo 1;}?>">     
            <?php echo $this->load->view('CA/myshortlist_ana_Inner')?>
        </ol>
            <?php  // make pagination view
                    $data['paginateData']= array('totalResult'=>$total,'perPage'=>$pageSize,'pageNo'=>$pageNo,'fromPage'=>'myShortlistAnA','courseId'=>$courseId,'instituteId'=>$instituteId);
                    echo $this->load->view('CA/myshortlist_ana_pagination',$data);
            ?>
    </div>
    <?php }?>
</div>    
       