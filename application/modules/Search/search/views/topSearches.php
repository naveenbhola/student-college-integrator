<?php  		
		$headerComponents = array(
						//'css'	=>	array('mainStyle'),
						'css'	=> array('search'),
						'jsFooter'=>    array('common'),
						'title'	=>	'top education searches',
						'tabName' =>	'Top Searches',
						'taburl' =>  site_url(),
						'metaKeywords'	=>'Top Education searches.',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
					    'bannerProperties' => array('pageId'=>'', 'pageZone'=>''),
						'product'	=>'Top Searched',
						'callShiksha'=>1
					);
		$this->load->view('common/header', $headerComponents);
	
?>
<div class="mar_full_10p normaltxt_11p_blk">
<?php
	foreach ($Result['resultSet'] as $temp) { ?>
		<div class="lineSpace_28 bld fontSize_14p"><a href="<?php echo $temp['urlForKeyWord']; ?>"><?php echo $temp['urlForKeyWord']; ?></a></div>
	<?php }
?>
		<div class="lineSpace_28">&nbsp;</div>
		<div class="pagingID lineSpace_28"><?php echo $paginationHTML; ?></div>
		<div class="lineSpace_28">&nbsp;</div>
</div>
<?php
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties); 
?>
