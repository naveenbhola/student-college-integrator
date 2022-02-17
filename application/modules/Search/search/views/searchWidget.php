<html>
<head>
<style>
body {margin:0;padding:0;background-color:White;font-family:Arial, Helvetica, sans-serif;font-size:12px;}
.searchButtonAllWidget{background:transparent url(/public/images/searchWidgetBtn.gif) no-repeat scroll; border:0 none;cursor:pointer;color:#FFFFFF;font-family:Arial;font-size:16px;font-weight:bold;height:31px;margin:0 0 0 0px;vertical-align:bottom;width:97px;}
</style>
</head>
<body>
<script>
function validateShikshaSearchFields()
{
    if(document.getElementById('keyword').value =="")
    {
        alert("Enter Institute, Course, Exam Name, Question etc.");
        return false;
    }
}
function parentRedirect()
{
        window.open('http://www.shiksha.com','_parent');
}
</script>
<div style="width:240px; height:242px; border:1px solid #a5d042; background-color:#fdfff2; padding:4px 5px">
    <a href="http://www.shiksha.com/index.php/shiksha/index/searchWidget/<?php echo $partner; ?>" onClick="parentRedirect()"><img src="/public/images/widgetShikshaLogo.gif" alt="Shiksha.com" border="0"/></a>
    <div style="padding-top:10px;font-size:11px; font-weight:700">Search for thousands of colleges and courses around the world.</div>
    <form target="_parent" method="GET" action="/search/index" onSubmit="return validateShikshaSearchFields();">
    <div style="padding-left:11px; font-size:11px; padding-top:10px">
        Enter Institute, Course, Exam Name, Que.<br />
        <input type="text" style="border:1px solid #a5d042; width:204px; height:22px" id="keyword" name="keyword" value=""/><br />
        Eg. XLRI, MCA, or GMAT
    </div>
    <div style="padding-left:11px; font-size:11px; padding-top:13px">
        Enter Country, City etc<br />
        <input type="text" style="border:1px solid #a5d042; width:204px; height:22px" id="location" name="location" value=""/><br />
        Eg. Australia, Karnataka or Delhi
    </div>
    <input type="hidden" name="partnerId" id="partnerId" value="<?php echo $partner;?>" />
    <input type="hidden" name="searchType" id="searchType" value="<?php echo $searchType;?>" />
    <div align="center" style="padding-top:6px">
        <input type="submit" class="searchButtonAllWidget" value="Search" />
    </div>
    </form>
</div>
</body>
</html>
