<body bgcolor="#E6E6FA">
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>

<div class='searchForm'>
    <table>

    <tr>
        <td>LeadId&nbsp;&nbsp;</td>
        <td><input id='leadId' val=''/></td>
    </tr>

    <tr>
        <td>Search Agent Id&nbsp;&nbsp;</td>
        <td><input id='searchAgentId' val=''/></td>
    </tr>

    </table>
    <input type="button" onclick="getLeadDetails()" class="submit-btn" value="submit" />

</div>

<div>
    <div id='leadData'>

    </div>   
</div>

</body>

//NOT used anywhere to be delete

<script>
    function getLeadDetails(){
        
        //var leadId = $('#leadId').val();        //2678531
        var leadId = 2678531;
        //var genieId = $('#searchAgentId').val();  //18443
        var searchAgentId = 23; 
    
        var ajaxUrl = '/LDB/LDBLeadTracking/findMissingCriteria';

        $.ajax({
            url:ajaxUrl,
            type:'POST',
            data:{
                'leadId':leadId,
                'searchAgentId':searchAgentId
            },
            success:function(response){
                $('#leadData').html(response);
            }
        });
    }
</script>