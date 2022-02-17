<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>
<div style='padding:10px;'>
    <h1 style="font-size: 16px;">
        <?php echo $pageId ? "Edit Page: <span style='color:#666;'>".$page['name']."</span>" : "Create New Landing Page"; ?>
    </h1>
    
    <div style="font-size:15px; margin-top: 10px;border-top:0px solid orange; background:#f6f6f6; padding:20px 20px 50px 20px;">
        <form name="clientLandingPageForm" id="clientLandingPageForm" action="/marketing/ClientLandingPage/savePage" method="POST">
        <div style="float:left; width:200px; text-align: right; padding-top:6px;">Page Name:</div>
        <div style="float:left; width:700px; margin-left: 20px;">
            <input type="text" id="pageName" name="name" style="width:650px; border:1px solid #ccc; padding:5px; font-size:15px;" maxlength="100" value='<?php echo html_escape($page['name']); ?>' />
            <div id="pageName_error" style='color:red; font-size:12px; display: none;'>Please enter page name</div>
        </div>
        <div style="clear:both; padding:10px;"></div>
        
        
        <div style="float:left; width:200px; text-align: right; padding-top:2px;">Page HTML:</div>
        <div style="float:left; width:700px; margin-left: 20px;">
            <textarea id="pageHTML" name="html" style="width:650px; height:200px; border:1px solid #ccc; padding:5px; font-size:15px;"><?php echo html_escape($page['html']); ?></textarea>
            <div id="pageHTML_error" style='color:red; font-size:12px; display: none'>Please enter page HTML</div>
        </div>
        <div style="clear:both; padding:10px;"></div>
        
        <div style="float:left; width:200px; text-align: right; padding-top:2px;"></div>
        <div style="float:left; width:700px; margin-left: 20px;">
            <input type="button" value="Submit" style="padding:5px; font-size:15px;" onclick="submitCreateLandingPageForm()" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" value="Preview" style="padding:5px; font-size:15px;" onclick="previewPage()" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href='/marketing/ClientLandingPage/listPages' style='font-size:12px;'>Cancel</a>
        </div>
        <div style="clear:both;"></div>
        <input type='hidden' name='pageId' value='<?php echo $page['id']; ?>' />
        </form>
    </div>
</div>

<script>
    function submitCreateLandingPageForm()
    {
        var error = false;
        var pageName = trim($('pageName').value);
        var pageHTML = trim($('pageHTML').value);
        if (pageName == '') {
            $('pageName_error').style.display = '';
            $('pageName').value = '';
            $('pageName').focus();
            error = true;
        }
        else {
            $('pageName_error').style.display = 'none';
        }
        
        if (pageHTML == '') {
            $('pageHTML_error').style.display = '';
            $('pageHTML').value = '';
            if (pageName != '') {
                $('pageHTML').focus();
            }
            error = true;
        }
        else {
            $('pageHTML_error').style.display = 'none';
        }
        
        if (!error) {
            $('clientLandingPageForm').submit();
        }
    }
    
    function previewPage()
    {
        myWindow=window.open('','');
        var pageHTML = $('pageHTML').value;
        pageHTML = pageHTML.replace(/&gt;/gi, '>').replace(/&lt;/gi, '<')
        myWindow.document.write(pageHTML);
        myWindow.focus();    
    }
</script>

<?php $this->load->view('common/footer');?>

