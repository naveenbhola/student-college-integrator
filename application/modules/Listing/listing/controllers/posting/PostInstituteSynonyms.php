<?php 

class PostInstituteSynonyms extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('listing/posting/postinstitutesynonymsmodel');
	}

    //funtion to check whether the user is cmsadmin and whether logged in or not
    function init(){
        $this->userStatus = $this->checkUserValidation();
        if($this->userStatus!='false'){
            $emailId = explode('|',$this->userStatus[0]['cookiestr']);
            if($emailId[0] != "cmsadmin@shiksha.com"){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
        }
        else{
            header("location:/");
            exit();
        }
        
    }
    //check whether a valid session user and load view
    function index(){
        
        $this->init();
        $this->load->view('listing/listingPage/postSynonymsViewHeader');
        $this->load->view('listing/listingPage/postSynonymsViewBody');
        $this->load->view('listing/listingPage/postSynonymsViewFooter');
    }
    //used by ajax request to fill in the text fields on clicking get
    function getSynonyms($instid){

        $instname=$this->postinstitutesynonymsmodel->getInstituteName($instid);
        
        if($instname=="no"){
            echo "This Institute does not exist";
            exit();
        }
        $instname=($instname=="no")?"This Institute does not exist": $instname;
        $row=$this->postinstitutesynonymsmodel->getSynonyms($instid);
        
        if($row=="no")
            echo "noresults".$instname;
        else{
            $row['instituteName']=$instname;
            echo json_encode($row);
        }
    }
    //check whether new entry is successfully inserted in the model
    function postSynonyms(){

        $this->init();
        $data['synonyms']=$this->input->post('synonyms');
        $data['acronyms']=$this->input->post('acronyms');
        $data['instid']=$this->input->post('instid');
        $data['userid']=$this->userStatus[0]['userid'];
        $data['modified']=date('Y-m-d H:i:s');

        $result=$this->postinstitutesynonymsmodel->postsyn($data);
        $data['insertionresult']=($result=='ok')? 'Insertion Success':'Insertion Failed';
        $this->load->view('listing/listingPage/postSynonymsViewBody',$data);
    }
}

?>