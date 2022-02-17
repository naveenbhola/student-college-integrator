<?php

class WriteForUs extends MX_Controller {
    public function __construct() {
        parent::__construct();

        $this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }
    }

    function home() {
        $this->load->library('blog_client');

        $data['userId'] = $this->userid;
        $data['validateuser'] = $this->userStatus;
        $data['product'] = 'writeForUs';
        $data['metadata']['canonical'] = $_SERVER['SCRIPT_URI'];
        $data['metadata']['title'] = "Write for Shiksha - Submit Educational Articles @ Shiksha.com";
        $data['metadata']['description'] = "We invite all college students, working professionals and faculty members to share their knowledge, experience, success stories & more with millions of students by featuring their educational articles on Shiksha.com.";

        $blog_client = new Blog_client();
        $articlesList = $blog_client->getUserBlogs(12, 7679100, 0, 9);
        //$articlesList = $blog_client->getUserBlogs(12, 3135105, 0, 9);
        $data['articlesList'] = json_decode($articlesList, true);
        
        $this->load->view('writeForUs/writeForUsDesktop', $data);
    }
}