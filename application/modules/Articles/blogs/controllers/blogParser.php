<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of blogParser
 *
 * @author aman
 */
class BlogParser extends MX_Controller {
      
    function index($type)
    {
 
	     set_time_limit(1800);

        $this->load->database(); // init db connection, already in code
        $this->db->save_queries = false; // ADD THIS LINE TO SOLVE ISSUE

      
        
       
        $this->load->library('blog_client');
        if($type == 'article')
            $this->blog_client->getAllBlogArticle();
        elseif($type == 'slideshow')
            $this->blog_client->getAllBlogSlideshow();
        elseif($type == 'qna')
            $this->blog_client->getAllBlogQna();
        elseif($type == 'image')
            $this->blog_client->getAllBlogImages();
        elseif($type == 'saimage')
            $this->blog_client->getStudyAbroadImages();
        elseif($type == 'sasection')
            $this->blog_client->getStudyAbroadArticleImages();
        elseif($type == 'backup')
            $this->blog_client->doArticleBackup4Images();

        
       echo 'all done';
       exit;
  
	
    }

    
}
