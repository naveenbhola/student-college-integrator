<?php $this->load->view('header'); ?>
<div id='left_panel'>
    <form method="post" onsubmit="return submitLogin();">
    Login Email Id
    <input type="text" name="username" id="username" class='inputbox' />
    <div class='input_error' id='error_username'>Please enter user id</div>
    <div class='mt'>Password</div>
    <input type="password" name="password" id="password" class='inputbox' />
    <div class='input_error' id='error_password'>Please enter password</div>
    <div class="mt">
        <input type="submit" value="Login" class='inputbutton' />
    </div>
    <div class='input_error mt' id='error_login'></div>
    </form>
</div>
<div id='user_search_helptext'>Please login with your Shiksha.com user id/password to access support utilities.</div>
<?php $this->load->view('footer'); ?>