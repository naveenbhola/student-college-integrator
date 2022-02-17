<tr>
    <td valign="top" align="center">
        <table align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
            <tr>
                <td valign="top" height="23"></td>
            </tr>
            <tr>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333; text-align:left;">Hi <?php echo $firstName; ?>,</td>
            </tr>
            <tr>
                <td valign="top" height="2"></td>
            </tr>
            <tr>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:19px;">You showed interest in <strong><?php echo $instituteName;echo !empty($abbreviation) ? ' ('.$abbreviation.')':''; ?></strong>.</td>
            </tr>
            <tr>
                <td valign="top" height="10"></td>
            </tr>
            <tr>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:19px;">To give you more insights about this institute, here are a curated list of resources with
                    links below:
                </td>
            </tr>
            <tr>
                <td valign="top" height="5"></td>
            </tr>
            <?php 
            if(!empty($popularCourseData) && !empty($reviewLinks)){
                ?>
                <tr>
                    <td valign="top">
                        <table width="280" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tr>
                                <td valign="top" width="270">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; 
                                        border:1px solid #e3e3e3">
                                        <tr>
                                            <td valign="top" align="center">
                                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                    <tr>
                                                        <td width="10"></td>
                                                        <td valign="top">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                <tr>
                                                                    <td valign="top" height="8"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:19px;">Popular Courses</td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" height="5"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <?php 
                                                                            $i = 1;
                                                                            foreach ($popularCourseData as $row) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png" /></td>
                                                                                    <td valign="top"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['name']; ?></a></td>
                                                                                    <td valign="top" width="10" align="right"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png" /></a></td>
                                                                                </tr>
                                                                                <?php 
                                                                                if($i < count($popularCourseData)){
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td valign="top" height="6" colspan="3"></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <?php
                                                                                $i++;
                                                                            }
                                                                            ?>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" height="13"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <?php 
                                                                        if($courseCount > 3){
                                                                            ?>
                                                                            <a href="<?php echo $allCoursesUrl.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#03818d; text-align:left;
                                                                            text-decoration:none; ">View all <?php echo $courseCount; ?> Course<?php echo ($courseCount > 1) ? 's':''; ?></a>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" height="9"></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td width="10"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="10"></td>
                            </tr>
                            <tr>
                                <td valign="top" height="10" colspan="2"></td>
                            </tr>
                        </table>
                        <table width="270" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tr>
                                <td valign="top" width="270">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; 
                                        border:1px solid #e3e3e3">
                                        <tr>
                                            <td valign="top" align="center">
                                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                    <tr>
                                                        <td width="10"></td>
                                                        <td valign="top">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                <tr>
                                                                    <td valign="top" height="8"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:19px;">Course Reviews</td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" height="5"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <?php 
                                                                            $i = 1;
                                                                            foreach ($reviewLinks as $row) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png" /></td>
                                                                                    <td valign="top"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['text']; ?></a></td>
                                                                                    <td valign="top" width="10" align="right"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png" /></a></td>
                                                                                </tr>
                                                                                <?php 
                                                                                if($i < count($reviewLinks)){
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td valign="top" height="6" colspan="3"></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                $i++;
                                                                            }
                                                                            ?>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" height="13"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <?php 
                                                                        if($reviewCount > 3){
                                                                            ?>
                                                                            <a href="<?php echo $allReviewsUrl.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#03818d; text-align:left;
                                                                            text-decoration:none; ">Read all <?php echo $reviewCount; ?> Reviews</a>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" height="9"></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td width="10"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" height="10"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
            }
            else{
                ?>
                <tr>
                    <td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #e3e3e3; max-width:550px">
                            <tr>
                                <td width="13"></td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                        <tr>
                                            <td valign="top" height="7"></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:18px;">Popular Courses</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" height="9"></td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                    <?php 
                                                    $i = 1;
                                                    foreach ($popularCourseData as $row) {
                                                        ?>
                                                        <tr>
                                                            <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png" /></td>
                                                            <td valign="top"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['name']; ?></a>
                                                            </td>
                                                            <td valign="top" width="10" align="right"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png" /></a></td>
                                                        </tr>
                                                        <?php 
                                                        if($i < count($popularCourseData)){
                                                            ?>
                                                            <tr>
                                                                <td valign="top" height="6" colspan="3"></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    if($courseCount > 3){
                                                        ?>
                                                        <tr>
                                                            <td valign="top" height="13" colspan="3"></td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" align="left" width="8"></td>
                                                            <td valign="top"><a href="<?php echo $allCoursesUrl.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#03818d; text-align:left;
                                                                text-decoration:none; ">View all <?php echo $courseCount; ?> Courses</a></td>
                                                            <td valign="top" width="10" align="right"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" height="9"></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="13"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" height="10"></td>
                </tr>
                <?php
            }
            if(!empty($questionsData)){
                ?>
                <tr>
                    <td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #e3e3e3; max-width:550px">
                            <tr>
                                <td width="13"></td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                        <tr>
                                            <td valign="top" height="7"></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:18px;">Important Q&A </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" height="9"></td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                    <?php 
                                                    $i = 1;
                                                    foreach ($questionsData as $row) {
                                                        ?>
                                                        <tr>
                                                            <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png" /></td>
                                                            <td valign="top"><a href="<?php echo $row['URL'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['title']; ?></a>
                                                            </td>
                                                            <td valign="top" width="10" align="right"><a href="<?php echo $row['URL'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png" /></a></td>
                                                        </tr>
                                                        <?php 
                                                        if($i < count($questionsData)){
                                                            ?>
                                                            <tr>
                                                                <td valign="top" height="6" colspan="3"></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    if($questionCount > 3){
                                                        ?>
                                                        <tr>
                                                            <td valign="top" height="13" colspan="3"></td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" align="left" width="8"></td>
                                                            <td valign="top"><a href="<?php echo $allQuestionURL.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#03818d; text-align:left;
                                                                text-decoration:none; ">View all <?php echo $questionCount; ?> Questions</a></td>
                                                            <td valign="top" width="10" align="right"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" height="9"></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="13"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" height="10"></td>
                </tr>
                <?php
            }
            if(!empty($articleData) || !empty($examList)){
                if(!empty($articleData) && !empty($examList)){
                    ?>
                    <tr>
                        <td valign="top">
                            <table width="280" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                <tr>
                                    <td valign="top" width="270">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; 
                                            border:1px solid #e3e3e3">
                                            <tr>
                                                <td valign="top" align="center">
                                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td width="10" height="172"></td>
                                                            <td valign="top">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                    <tr>
                                                                        <td valign="top" height="8"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:19px;">Important Articles</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" height="5"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top">
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                <?php 
                                                                                $i = 1;
                                                                                foreach ($articleData as $row) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png" /></td>
                                                                                        <td valign="top"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['blogTitle']; ?></a></td>
                                                                                        <td valign="top" width="10" align="right"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png" /></a></td>
                                                                                    </tr>
                                                                                    <?php 
                                                                                    if($i < count($articleData)){
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td valign="top" height="5" colspan="3"></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    $i++;
                                                                                }
                                                                                ?>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" height="12"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top">
                                                                            <?php 
                                                                            if($articlesCount > 3){
                                                                                ?>
                                                                                <a href="<?php echo $allArticleURL.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#03818d; text-align:left;
                                                                                text-decoration:none; ">View all <?php echo $articlesCount; ?> Article<?php echo $articlesCount>1 ? 's':''; ?></a>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" height="9"></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td width="10"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="10"></td>
                                </tr>
                                <tr>
                                    <td valign="top" height="10" colspan="2"></td>
                                </tr>
                            </table>
                            <table width="270" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                <tr>
                                    <td valign="top" width="270">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; 
                                            border:1px solid #e3e3e3"">
                                            <tr>
                                                <td valign="top" align="center">
                                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td width="10" height="172"></td>
                                                            <td valign="top">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                    <tr>
                                                                        <td valign="top" height="8"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:19px;">Exams Accepted</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" height="8"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top">
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                <?php 
                                                                                $i = 1;
                                                                                foreach ($examList as $row) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png" /></td>
                                                                                        <td valign="top"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['name']; ?></a></td>
                                                                                        <td valign="top" width="10" align="right"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png" /></a></td>
                                                                                    </tr>
                                                                                    <?php 
                                                                                    if($i < count($examList)){
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td valign="top" height="14" colspan="3"></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    $i++;
                                                                                }
                                                                                ?>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td width="10"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" height="10"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
                }
                else{
                    if(!empty($articleData)){
                        ?>
                        <tr>
                            <td valign="top">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #e3e3e3; max-width:550px">
                                    <tbody>
                                        <tr>
                                            <td width="13"></td>
                                            <td valign="top">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" height="7"></td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:18px;">Important Articles </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" height="9"></td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                    <tbody>
                                                                        <?php 
                                                                        $i = 1;
                                                                        foreach ($articleData as $row) {
                                                                            ?>
                                                                            <tr>
                                                                                <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png"></td>
                                                                                <td valign="top"><a href="<?php echo $row['url']; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['blogTitle']; ?></a></td>
                                                                                <td valign="top" width="10" align="right"><a href="<?php echo $row['url']; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png"></a></td>
                                                                            </tr>
                                                                            <?php 
                                                                            if($i < count($articleData)){
                                                                                ?>
                                                                                <tr>
                                                                                    <td valign="top" height="6" colspan="3"></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            $i++;
                                                                        }
                                                                        if($articlesCount > 3){
                                                                            ?>
                                                                            <tr>
                                                                                <td valign="top" height="13" colspan="3"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" align="left" width="8"></td>
                                                                                <td valign="top"><a href="<?php echo $allArticleURL.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#03818d; text-align:left;
                                                                                    text-decoration:none; ">View all <?php echo $articlesCount; ?> Article<?php echo $articlesCount > 1 ? 's':''; ?></a></td>
                                                                                <td valign="top" width="10" align="right"></td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" height="9"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td width="13"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php 
                        if(!empty($similarColleges)){
                            ?>
                            <tr>
                                <td valign="top" height="10"></td>
                            </tr>
                            <?php
                        }
                    }
                    else{
                        ?>
                        <tr>
                            <td valign="top">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #e3e3e3; max-width:550px">
                                    <tbody>
                                        <tr>
                                            <td width="13"></td>
                                            <td valign="top">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top" height="7"></td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:18px;">Exams Accepted </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" height="9"></td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                    <tbody>
                                                                        <?php 
                                                                        $i = 1;
                                                                        foreach ($examList as $row) {
                                                                            ?>
                                                                            <tr>
                                                                                <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png"></td>
                                                                                <td valign="top"><a href="<?php echo $row['url']; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['name']; ?></a></td>
                                                                                <td valign="top" width="10" align="right"><a href="<?php echo $row['url']; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png"></a></td>
                                                                            </tr>
                                                                            <?php 
                                                                            if($i < count($examList)){
                                                                                ?>
                                                                                <tr>
                                                                                    <td valign="top" height="6" colspan="3"></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            $i++;
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" height="9"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td width="13"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php 
                        if(!empty($similarColleges)){
                            ?>
                            <tr>
                                <td valign="top" height="10"></td>
                            </tr>
                            <?php
                        }
                    }
                }
            }
            if(!empty($similarColleges)){
                ?>
                <tr>
                    <td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #e3e3e3; max-width:550px">
                            <tr>
                                <td width="13"></td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                        <tr>
                                            <td valign="top" height="7"></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; font-weight:bold; text-align:left; line-height:18px;">Similar Colleges</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" height="9"></td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                    <?php 
                                                    $i = 1;
                                                    foreach ($similarColleges as $row) {
                                                        ?>
                                                        <tr>
                                                            <td valign="top" align="left" width="8"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/bullet.png" /></td>
                                                            <td valign="top"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#333333; text-align:left; text-decoration:none;"><?php echo $row['name']; ?></a></td>
                                                            <td valign="top" width="10" align="right"><a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank"><img src="<?php echo IMGURL_SECURE ?>/public/images/mailer/arrow.png" /></a></td>
                                                        </tr>

                                                        <?php 
                                                        if($i < count($similarColleges)){
                                                            ?>
                                                            <tr>
                                                                <td valign="top" height="6" colspan="3"></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        $i++;
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" height="9"></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="13"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </td>
</tr>
<tr>
    <td valign="top" height="12"></td>
</tr>