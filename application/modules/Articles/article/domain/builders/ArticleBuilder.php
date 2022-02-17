<?php
class ArticleBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getArticleRepository(){
        $dao = '';
	    $this->CI->load->repository('ArticleRepository','article');
        $this->CI->articlemodel = $this->CI->load->model('article/articlenewmodel','',TRUE);
	    $model = $this->CI->articlemodel;
        $articleRepository = new ArticleRepository($dao,$cache,$model);
        return $articleRepository;
    }

    
}
?>
