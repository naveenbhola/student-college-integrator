<?php
    $headerComponents = array(
        'jsFooter'      =>      array('common'),
        'title' =>      'Authors contributing actively on Shiksha',
        'taburl' =>  $_SERVER['REQUEST_URI'],
        'product'       =>'forums',
        'doNotShowKeywords' => true,
        'shikshaProduct' => 'Articles',
        'bannerProperties' => array('pageId'=>'AUTHORS_PROFILE_PAGE', 'pageZone'=>'HEADER'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'canonicalURL' => SHIKSHA_HOME.'/shiksha-authors',
        'tabName' =>    'Discussion',
        'metaDescription'=>'Shiksha.com authors specialise in varied streams, with a strong background in content creation. ',
);
$this->load->view('common/header', $headerComponents);
?>
    <div class="wrapperFxd">
    	<div id="content-child-wrap">
        <!--Article Code Starts From Here-->
        	
            <div class="authors-profile">
                <h4>Author Profiles</h4>
                    <?php 
                        foreach($author_user_info as $userId =>$data){
                            $userName = $data['firstname'].' '.$data['lastname'];?>
                            <div class="profile-details author-profle-detail">
                                <div class="author-pic">
                                <?php $image = ($data['avtarimageurl'] == '')?('/public/images/default_author.gif'):"https://".MEDIA_SERVER_IP.$data['avtarimageurl'] ; ?>
                                     <img src="<?= $image;?>" width="104" height="78" alt="<?=$userName;?>" />
                                </div>
                                <div class="author-detail">
                                    <h5><?=$userName; ?></h5>
                                    <p class="about-head">About me</p>
                                    <p><strong>Current Position: </strong><?=$author_details_array[$userId]['about_me_current_position']?></p>
                                    <p><strong>Specialities in : </strong><?=$author_details_array[$userId]['specialisation'];?></p>
                                    <p><a href="<?=SHIKSHA_HOME.'/author/'.$data['displayname'];?>" class="knw-more-btn">Know more</a></p>
                                </div>
                            </div>
                            <div class="clearFix"></div>
                    <?php } ?>
        	 </div>  
            
         </div>
    </div>

<?php $this->load->view('common/footer');  ?>

