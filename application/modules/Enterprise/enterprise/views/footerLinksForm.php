<p style="padding:5px 0px 10px;font-weight:600">Customized Footer Links (For Desktop, Mobile Site & AMP Footer)</p>
        <div style="position:relative;margin-top:10px;">
            <table width="90%" border=1 cellspacing=0 cellpadding=2>
                <tr style="background-color:lightgray;">
                    <td>Label Name</td>
                    <td>URL</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                <?php foreach ($footerLinks as $link){ ?>
                <tr>
                    <td id="linkName<?=$link['id']?>"><?=$link['name']?></td>
                    <td id="linkURL<?=$link['id']?>"><?=$link['URL']?></td>
                    <td><a href="javascript:void(0);" onClick="editLink('<?=$link['id']?>')">Edit</a></td>
                    <td><a href="javascript:void(0);" onClick="deleteLink('<?=$link['id']?>')">Delete</a></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <div style="position:relative;margin-top:15px;">
            <input type="button" value="Add New Link" onClick="addLink();"></input>
        </div>

        <div style="display:none;margin-top:15px;" id="linkForm">
            Label Name <input type="text" id="nameVal" name="nameVal"></input><br/><br/>
            URL <input type="text" id="urlVal" name="urlVal"></input><br/><br/>
            <input type="hidden" id="idVal" name="idVal" value="">
            <input type="hidden" id="action" name="action" value="add">
            <input onClick="submitFormFooter();" type="button" value="Submit"/>&nbsp;<input onClick="cancelForm()" type="button" value="Cancel"/>
        </div>

        <div class="clearFix"></div>
        <script>window.scrollTo(0,0);</script>


