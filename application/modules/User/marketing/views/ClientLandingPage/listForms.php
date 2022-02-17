<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style','mmm_styles'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog','footer','ajax-api'),
	'jsFooter'         => array('scriptaculous','utils'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>''
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    $this->load->view('common/calendardiv');
?>
</head>
<body>

<style>
#tbl_mpcId th{background:#E5EECC; font-weight:bold; text-align:left}
#tbl_mpcId tr.alt-row{background:#f5f7ee}
a.htmllink {display:block; background:#DCE5FA; padding:5px; float:left; color:#666;}
a.htmllink:hover {text-decoration:none;}
a.mislink {display:block; background:#CDF7BE; padding:5px; float:left; color:#666;}
a.mislink:hover {text-decoration:none;}
</style>

<div class="mar_full_10p" style="margin-top:15px; min-height:600px;">
<?php
$this->load->view('mailer/left-panel-top');
?>

<div style='padding:10px; padding-top: 0; min-height: 400px;'>
	
	<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px; float:left;"><strong>Form Mailers</strong></div>
	
	<div class="new_mmp_button_container" id="new_mmp_button_container" style="float: right;">
		<button class="btn-submit5 " onclick="window.location = '/mailer/MarketingFormMailer/createForm';">
			<div class="btn-submit5">
				<p class="btn-submit6">Create New Form Mailer</p>
			</div>
		</button>
	</div>
	<div class="clearFix"></div>
	
	<?php if($msg == 'formCreationSuccessful' || $msg == 'formUpdationSuccessful') { ?>
		<div style='background: #E5F5DF; padding:10px; margin:10px 0; font-size: 13px; color:#148215'>
			<?php echo $msg == 'formCreationSuccessful' ? 'New form has been successfully created.' : 'Form has been successfully updated.'; ?>
		</div>
	<?php } else if($msg == 'editFormDoesNotExist') { ?>
		<div style='background: #FFEBEB; padding:10px; margin:10px 0; font-size: 13px; color:red'>
			The form you are trying to edit does not exist.
		</div>
	<?php } ?>
	
    <table cellpadding="8" cellspacing="0" border="1" bordercolor="#D6DBDF" style="font-size:12px; border:1px solid #D6DBDF; border-collapse:collapse; margin-top: 10px;" width="100%" id="tbl_mpcId">
	<tbody><tr>
		<th width='250'>Name</th>
		<th width='150'>Created On</th>
		<th width='50'>MIS</th>
		<th width='100'>HTML Snippet</th>
		<th width='50'>Edit</th>
	</tr>
        
    <?php $counter = 1; foreach ($forms as $form):?>
        <tr <?php if ($counter%2 == 0) {echo 'class="alt-row"';} $counter++; ?>>
            <td><?php echo $form['name']; ?></td>
            <td><?php echo date('M j Y H:i:s',strtotime($form['createdOn'])); ?></td>
            <td><a href='/clientLandingPage-<?php echo $form['id']; ?>' target='_blank' class='mislink'>MIS</a></td>
			<td><a href='/mailer/MarketingFormMailer/HtmlSnippet/<?php echo $form['id']; ?>' class='htmllink'>HTML</a></td>
			<td><a href='/mailer/MarketingFormMailer/EditForm/<?php echo $form['id']; ?>'><img src='<?php echo SHIKSHA_HOME; ?>/public/images/edit-icn.gif' /></a></td>
			
        </tr>
    <?php endforeach; ?>    
    </tbody>
    </table>
    
    <br /><br /><br />
</div>

<?php
$this->load->view('mailer/left-panel-bottom');
?>
</div>