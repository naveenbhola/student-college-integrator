<!DOCTYPE html>
<html>
<head>
    <title>HomeFeed</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <style type="text/css">
    table tr:nth-child(even){background-color: #F7F7F7; }
    table td{padding: 4px 5px;}
    .userTable{border-collapse: collapse;width: 40%;font-size: 14px;}
    .userTable td{padding: 4px 5px;}
</style>
</head>
<body>
<h1>User Home-Feed Data</h1>
<form id="form1">
    <label>UserId : </label><input id="userId" name="userId" placeholder="User-Id"/>
    <label>Feed Type : </label>
    <select name="feedType">
        <option value="home">HomeFeed</option>
        <option value="unanswered">Unanswered Question</option>
        <option value="discussion">Discussion</option>
    </select>
    <input type="hidden" name="isAjax" value="1" />

    <button onclick="showFeed();return false;">Show Feed</button>

    <div id="dataDiv" style="padding: 0px 20px;">
        
    </div>
</form>
<script>
    function showFeed(){
        
        var num = $("#userId").val();
        if(num == "" && num === parseInt(num, 10)){
            alert("Please enter a valid userId first.");
            return false;
        }

        $.ajax({
            type : "POST",
            URL : "/Tagging/PersonalizationReport/showUserHomeFeed",
            data : $("#form1").serialize(),
            beforeSend: function(){
                $("#dataDiv").html("Loading...");        
            },
            success : function(response){
                $("#dataDiv").html(response);        
            }
        });
    }
</script>
</body>
</html>