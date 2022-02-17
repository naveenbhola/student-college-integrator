<?php $this->load->view('myShortlist/myShortlistHeader'); ?>
<?php $this->load->view('myShortlist/myShortlistContent'); ?>
<?php $this->load->view('myShortlist/myShortlistFooter');?>
<script type="text/javascript">
$j(document).ready(function(){
    shortlistCourseFromSearch();
});
</script>

<div id="tags-layer" class="tags-layer"></div>
 <div class="an-layer an-layer-inner" id="an-layer">
      <?php $this->load->view('messageBoard/desktopNew/quesDiscPosting');?>
 </div>