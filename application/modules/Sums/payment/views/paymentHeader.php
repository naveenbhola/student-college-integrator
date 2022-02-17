        <span class="normaltxt_11p_blk darkgray disBlock txt_align_r">Hi
<?php if((isset($displayname))&& !empty($displayname))
{
        echo $displayname; ?>
                &nbsp;
        <?php $url = base64_encode("/");?>
                <a href="/user/login/signOut/<?php echo $url?>">Sign out</a>

                <?php
}
else
{ ?>
        Guest &nbsp;
        <a href="/user/Userregistration">Join Now</a> |       <a href="#" id = "signin" name = "signin" onClick = "showuserLoginOverLay('signin')">Sign in</a> |

                <?php
}
if(isset($callShiksha) && $callShiksha)
{
        ?>
                &nbsp;<a href="#">Call Shiksha expert</a>
                <?php
}
?>
&nbsp;&nbsp;</span>


