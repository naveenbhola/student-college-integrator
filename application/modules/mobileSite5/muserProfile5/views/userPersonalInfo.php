<div class="profile-col" id="personalInfo">
    <div class="profile-col-heading">PERSONAL INFO</div>
    <?php if(!empty($personalInfo['Mobile'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-phone-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo $personalInfo['Mobile']; ?></p>
                </div>
                <div class="flRt">
                    <i class="profile-sprite eye-icon"></i>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <?php if(!empty($personalInfo['Email'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-mail-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo $personalInfo['Email']; ?></p>
                </div>
                <div class="flRt">
                    <i class="profile-sprite eye-icon"></i>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <?php if(!empty($additionalInfo['StudentEmail'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-mail-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo $additionalInfo['StudentEmail']; ?></p>
                </div>
                <div class="flRt">
                    <i class="profile-sprite eye-icon"></i>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <?php if(!empty($personalInfo['Country'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-loc-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p>
                        <?php 
                          if($personalInfo['Country'] != '2'){
                            echo $userCountry;
                          }else if(!empty($userlocationData['locality'])){
                            echo $userlocationData['locality'].', '.$userlocationData['city'].', '.$userCountry;
                          }else if(!empty($userlocationData['city'])){
                            echo $userlocationData['city'].', '.$userCountry;
                          }else{
                            echo $userCountry;
                          } 
                        ?>
                    </p>
                </div>
                <div class="flRt">
                    <i class="profile-sprite eye-icon"></i>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <?php if(!empty($additionalInfo['Bio'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-user-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo $additionalInfo['Bio']; ?></p>
                </div>
                <div class="flRt">
                    <i class="profile-sprite eye-icon"></i>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <div class="profile-info-col" style="padding-top:0;">
        <?php
            if(empty($personalInfo['DateOfBirth']) || empty($personalInfo['Country']) || empty($additionalInfo['StudentEmail']) || empty($personalInfo['Mobile']) || empty($additionalInfo['Bio']) ) { ?>
        <div>
            <i class="profile-sprite plus-icon flLt"></i>
        </div>
        <div class="profile-info-detail borderNone">
            <a href="#pagetwo" data-transition="slideup" id="personalInfoEdit" class="public-profile-link font-12" style="margin:0;">Add your personal details</a>
        </div>
        <?php } ?>
    </div>
</div>