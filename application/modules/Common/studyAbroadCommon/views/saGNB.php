<?php
$globalNavClass = 'main-header navheaderP navRelative';
if(isset($hideHeader) && $hideHeader)
{
    $globalNavClass .= " hide";
}
if(isset($beaconTrackData['pageIdentifier']) &&  $beaconTrackData['pageIdentifier'] != 'homePage')
{
    $globalNavClass .= " innerPageHeader";
}
?>

<div id="genericTopStky"></div>
<div  id="header" class="<?php echo $globalNavClass;?>" data-enhance="false">
    <header class="abrd-hdr" data-role="header">
        <div class="logo">
            <a href="<?=(SHIKSHA_STUDYABROAD_HOME)?>" alt="studyabroad.shiksha.com" aria-label="studyabroad.shiksha.com">
                <i class="logo-icn"></i>
            </a>
        </div>
        <?php
        if(!(isset($hideGNB) && $hideGNB))
        {
            ?>
            <div id="seachTextBox" class="search-tabContent openSearchLyr">
                Enter course, college, country or exam
                <button type="button" name="button" class="SearchBtn ">Search</button>
            </div>
            <?php
        }
        ?>
        <?php if($hideLoginSignupBar != 'true'){ ?>
        <?php if($userData != false){
                    $userFormattedFirstName = strtok($userData['name'],' ');
                    if(strlen($userFormattedFirstName) > 12){
                            $userFormattedFirstName = substr($userFormattedFirstName,0,9)."...";
                    }

                    if($userData['avtarurl']){
                        $displayPic = addingDomainNameToUrl(array('url' => $userData['avtarurl'] , 'domainName' =>MEDIA_SERVER));
                        $displayPic = getImageUrlBySize($displayPic,"small");
                    }
                ?>
            <div class="lggd-sgnDiv">
                
                <a onclick="fetchUserNotificationLayer();" class="user-hdr-icon">
                    <?php if(isset($displayPic)){?>
                    <img class="small-pp-img" src="<?=$displayPic?>">
                    <?php } else { ?>
                        <i class="user-icon"></i>
                        <?php
                    }
                        if($_COOKIE['rightDrawerOpened'] !== '1')
                        {
                            ?>
                            <span class="newTag">NEW</span>
                            <?php
                        }

                     ?>
                    <?=$userFormattedFirstName?>
                </a>
            </div>
            <?php } ?>
            <?php if($userData == false) {?>
            <div class="lgn-sgnDiv">
                <span class="a-loginSgnup">
                    <i class="blank-pp-icon icons"></i>
                    <a href="javascript:void(0);" onclick="showLoginPage();" action="login">Login</a>
                    <span class="registerPipe">|</span>
                    <a href="javascript:void(0);" onclick="loadHeaderSignUpForm('signUp',<?php echo $trackingPageKeyIdForSignUp; ?>);">Sign Up</a>
                </span>
            </div>
            <?php } ?>
         <?php } ?>

        <?php if(!($hideRightMenu == true)){?>
        <div class="src-fldDiv">
            <div class="header-shortlist tac hide">
                <a href="<?=SHIKSHA_STUDYABROAD_HOME?>/my-saved-courses?shortlistTab=1">
                    <i class="shortlist-icon"></i>
                    <strong><span id="shortlistHeaderCount">0</span></strong>
                </a>
            </div>
            <a id="searchLayerContainerLink" href="#searchLayerContainer" data-rel="dialog" data-transition="slide" class="search-hdr-icon" alt="search" aria-label="search"><i class="src-icon"></i></a>

            <?php if($userData!=false){?>

            <a href="#myrightpanel" id="shortlistHeadLocation" class="user-hdr-icon" alt="login" aria-label="login" onclick = "fetchUserNotificationLayer(<?=($userData[0]['userid'])?>);"><i class="user-icon"></i>
                <?php
                if($_COOKIE['rightDrawerOpened'] !== '1')
                {
                    ?>
                    <span class="newTag">NEW</span>
                    <?php
                }
                ?>
            </a>

            <?php } else { ?>

            <a id="registrationHeaderLink" class="<?php if($hideRegisterLink ==true){ echo "hide";}?> user-hdr-icon" href="#register" data-rel="dialog" data-transition="slide"><i class="user-icon"></i></a>

            <?php }?>
        </div>
        <?php }?>
    </header>

    <nav id="menu" <?php echo $hideGNB?'class="hide"':'';?>>
        <a href="#mypanel" class="panel-anchor">
            <label for="tm" id="toggle-menu">
                <p class="drop-icon"></p>
                <p class="menu-cls">Menu</p>
            </label>
        </a>
        <input type="checkbox" id="tm">

        <?php
          if(isset($saGNBNavigationHTML)){
            echo $saGNBNavigationHTML;
          }else{
            $gnbNavigation        = $this->load->view('studyAbroadCommon/gnbNavigation',array(),true);
            $gnbNavigationContent = sanitize_output($gnbNavigation);
            echo $gnbNavigationContent;
            $fp=fopen($gnbNavigationCache,'w+');
            flock( $fp, LOCK_EX ); // exclusive lock
            fputs($fp,$gnbNavigationContent);
            flock( $fp, LOCK_UN ); // release the lock
            fclose($fp);
          }

        ?>
    </nav>
</div>
