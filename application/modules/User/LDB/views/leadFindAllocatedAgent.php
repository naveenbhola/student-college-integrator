<div class='searchForm'>
    <div class='subHead'>
    <?php if($type == 'leadAllocation') {?>
        <b><span >Find Allocated Search Agent ID</span></b>
     <?php }else if($type == 'leadDetails'){?>   
        <b><span >Get Lead Details</span></b>
     <?php } else if ($type == 'searchAgentDetails'){?>
         <b><span >Search Agent Details</span></b>
     <?php } else if($type == 'leadMatching'){?>
     <b><span >Get SALeadMatchingLog Entries</span></b>
     <?php } else if($type == 'allocatedGenie'){ ?>
     <b><span >Get Recent Allocated Leads</span></b>
     <?php }?>
    </div>

    <table>


    <tr>
     <?php if($type == 'searchAgentDetails'){?>   
        <td>Search Agent Id&nbsp;&nbsp;</td>
     <?php } else if($type == 'allocatedGenie') {?>  
        <td>searchAgentId&nbsp;&nbsp;</td> 
     <?php } else { ?>   
        <td>LeadId&nbsp;&nbsp;</td>
     <?php }?>
        <td><input id='leadId' val=''/></td>
    </tr>

    </table>
   
    <input type="button" onclick="<?php if($type == 'leadAllocation') {?> getAllocatedSearchAgents()<?php }else if($type == 'leadDetails'){?> getLeadDetails();<?php } else if ($type == 'searchAgentDetails'){?>getSearchAgentDetails();<?php } else if ($type == 'leadMatching'){?>getSALeadMatchingData()<?php } else if ($type == 'allocatedGenie'){?>getAllocatedLeads();<?php }?>" class="submit-btn" value="submit" />

</div>


<div>
    <div id='leadData'>

    </div>   
</div>
