    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #D9D9D9;">
      <tr>
        <td colspan="3" background="<?php echo SHIKSHA_HOME ?>/public/images/bg.gif" style="background:url(<?php echo SHIKSHA_HOME ?>/public/images/bg.gif)">
        <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding:10px 35px 10px 35px; line-height:20px;">
        <br />

    <br/>
    Dear <?= $firstName?>,<br/><br/>

    <?php //check if either both were attached/ any one attached (exclusively...i.e. no link at all)
            if($attachmentAvailable){ ?>
                Your requested downloadable guide is <u>attached</u> in this email:<br/>

                - <?= htmlentities($guideName)?> <br/>
    <?php   }else { // when links are involved
    ?>
                Your requested downloadable guide :<br/>
                <br/>
    <?php
                echo 'Guide - <a href = "'.$downloadLink.'">click here to download</a><br>';
    ?>
    <?php   }
    ?>
    <br/>
    You can also read the contents of this guide online by <a href = "<?=$guideSeoUrl;?>">visiting this link</a>
    <br/><br/>Good luck for your career :-)
    <br/><span style="color:rgb(246,137,54);font-weight:bold;">Shiksha</span> <span style="color:rgb(60,84,164);font-weight:bold;">Study Abroad</span>
    <br/>
    <br/>
    Looking for study abroad? Visit us at <a href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>">studyabroad.shiksha.com</a><br />
    <br/><br/>P.S. - We will love to hear from you, write to us at <a href="mailto:studyabroad@shiksha.com">studyabroad@shiksha.com</a><br/>
    You can also reach us on our study abroad student helpline at <?php echo ABROAD_STUDENT_HELPLINE; ?><br/>
(09:30 AM to 06:30 PM, Monday to Friday)<br/>
    </div></td>
      </tr>
    </table>
    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
        <?php $this->load->view('studyAbroadCommon/mailerFooter', array('whiteBanner' => true)); ?>
      <tr>
        <td><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:10px; padding:10px; color:#666666; line-height:14px;">You are receiving this email because you have requested this information for your email id <span style="text-decoration:underline; color:#231E19"><a href="#"><?php echo $userEmailId?></a></span>.

    <br />
    Copyright &copy; <?php echo date('Y')?> Shiksha.com. All rights reserved.<br />
            <a href="<?php echo SHIKSHA_HOME; ?>" target="_blank" style="color:#666666">Shiksha.com</a> is located at A-88, Sector-2, NOIDA, UP 201301, INDIA</div>

        </td>
      </tr>
    </table>