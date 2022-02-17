	<h2>Our Student Mentors</h2>
    <p class="flRt">Showing <?= ($pageNo*8)+1?> - <?= (($pageNo+1)*8) > $mentorCount ? $mentorCount : (($pageNo+1)*8) ?> of <?=$mentorCount?> Mentor<?php if($mentorCount>1) echo 's';?></p>
    <?php 
    $this->load->view('mentorship/studentMentorTupleList');
    ?>



<?php  // make pagination view
        
             $data['paginateData']= array('totalResult'=>$mentorCount,'perPage'=>8,'pageNo'=>$pageNo,'subcatId'=>$subcatId);
             echo $this->load->view('mentorship/studentMentorListPagination',$data);
?>



