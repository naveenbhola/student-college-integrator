<?php

     $questionDocs = $googleRes['content'];
     foreach($questionDocs as $question)
     {
         $a['data'] = $question;
         $this->load->view('ask_question_search',$a);

     }
    

// $this->load->view('search_question_snippet',$a);

?>
