<?php
$this->load->view('mobileCategoryHeader');
if(!$institutes){
    if($request->getPageNumberForPagination() > 1){
        $urlRequest = clone $request;
        $urlRequest->setData(array('pageNumber'=>1));
        $url = $urlRequest->getURL();
        header("location:".$url);
    }
?>
        <h1 class="no-result">Sorry, no results were found.</h1>
<?php
}else{

    $this->load->view('mobileCategoryListings');
}
setcookie('current_cat_page',urlencode($this->shiksha_site_current_url),time() + 2592000 ,'/',COOKIEDOMAIN);
?>
<?php $this->load->view('/mcommon/footer');?>
