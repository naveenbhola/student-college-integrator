<?php 
$seoTitle =     $content['data']['strip_title'];
if($content['data']['seo_title'] != '') {
    $seoTitle = $content['data']['seo_title'];
}

$canonicalURL = $content['data']['contentURL'];
if($content['data']['seo_description'] == '') {
    $text = strip_tags($content['data']['summary']);
}else {
    $text = strip_tags($content['data']['seo_description']);
}
$text = str_replace('&nbsp;',' ',trim($text));
if(strlen($text) > 150) {
    $newText = substr($text,150,160);
    $spaceAfter150 = stripos($newText,' ');
    $text = substr($text,0,150+$spaceAfter150);
}else {
    $text = substr($text, 0, 150);
}

$metaDescription = $text;
$imageUrl = str_replace('_s','',$content['data']['contentImageURL']);
$pgType = ($content['data']['type'] == 'guide') ? 'guidePage' : 'articlePage';

$robots = 'ALL';
if($content['data']['content_id']==246){
        $robots = 'NOINDEX';
}

$headerComponents = array(
        'cssBundle'         => 'sa-article-page',
        'title'             => $seoTitle,
        'canonicalURL'      => $canonicalURL,
        'metaDescription'   => $metaDescription,
        'pageIdentifier'    => $beaconTrackData['pageIdentifier'],
        'articleImage'      => $imageUrl,
        'pgType'            => $pgType,
        'robotsMetaTag'     => $robots,
        'skipCompareCode'   => true,
        'trackingPageKeyId' => 470,
        'firstFoldCssPath'  => 'saContent/css/articleFirstFoldCss',
        'deferCSS'          => true
);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
echo jsb9recordServerTime('SA_CONTENT_PAGE',1);
?>