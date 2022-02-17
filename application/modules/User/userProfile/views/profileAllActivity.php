<?php 
	foreach ($activities as $key => $activity) {
        if($activity['type'] == 'Q' || $activity['type'] == 'D'){
            $this->load->view('questionDiscussionTuple',$activity);
        }else if($activity['type'] == 'T'){
            $this->load->view('tagTuple',$activity);
        }else if($activity['type'] == 'U'){
            $this->load->view('followTuple',$activity);  
        }
    }
 ?>