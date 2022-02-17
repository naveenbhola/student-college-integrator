
<?php 
		$GA_Tap_On_Article = 'ARTICLE_TUPLE';
$i = 0;
$flag=0;
foreach($articleInfo as $articles){
	if($i == 10){
		echo $recoWidget;
	}
	if($i==3){
		$this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA1"));
        $flag=1;
	}
	if($i==6){
		$this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_AON"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_AON1"));
	}
?>
    <div class="lcard art-crd" ga-attr="<?=$GA_Tap_On_Article;?>">
        <a href="<?php echo $articles['url'];?>"><?php echo htmlentities($articles['blogTitle']);?></a>
        <p><?php echo htmlentities($articles['summary']);?></p>
    </div>
<?php 
$i++;
} 

if($flag==0){
	$this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA1"));
}

?>

<?php 
if(count($articleInfo)<=10){
	echo $recoWidget;
}
?>
