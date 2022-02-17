<?php
		$headerComponents = array(
						//'css'	=>	array('raised_all','mainStyle','header'),
						'css' => array('static'),
						'js'	=>	array('common'),
						'title'	=>	'Shiksha Summons/Notices',
						'metaKeywords'	=>'Shiksha Summons/Notices',
						'product'	=>'help',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                              //  'callShiksha'=>1
					      'canonicalURL' => $current_page_url,
					);
		$this->load->view('common/header', $headerComponents);
?>
<iframe marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" bordercolor="#000000" height="1400" width="980" src="https://w5.naukri.com/fdbck/main/complaint_iframe.php?src=shiksha.com">
</iframe>
<?php $this->load->view('common/footer');  ?>
