    <h2>User Data</h2>
    <table class="userTable" border="1">
        <tr>
                <th>User Field</th>
                <th>User Data</th>
            </tr>
        <?php foreach ($userDetails as $key => $value) {
        ?>
            <tr>
                <td><?php echo $key;?> </td>
                <td><?php echo $value;?></td>
            </tr>
        <?php
        }
        ?>
    </table>

    <h2>Home-Feed Data</h2>
    <?php foreach ($homepageData['homeFeed'] as $key => $value) {
    ?>  
        <table border="1" style="width:90%;border-collapse: collapse;">
        <tr style="background: #747171;color: white;"><td colspan="2">Feed Number : <?php echo $key+1;?></td></tr>
    <?php
        $value['Sorting'] = $homepageData['debugData']['sorting'][$value['id']];
        if($value['uniqueId'] == 2)
            $value['threadTypeText'] = 'Discusssion';
        else if($value['uniqueId'] == 1)
            $value['threadTypeText'] = 'Question';
        
        $mapping = array("threadTypeText"     => "Thread Type",
                         "id"       => "Thread Id",
                         "heading"  => "Feed Reason",
                         "answerId" => "Answer Id",
                         "title"    => "Title",
                         "URL"      => "Url",
                         "tags"     => "Tags",
                         "Sorting"  => "Sorting Params");

        foreach ($mapping as $key1 => $value1) {
        ?>
            <tr>
                <td width="20%"><?php echo $mapping[$key1];?> </td>
                <td width="80%"><?php 
                    if(is_array($value[$key1])){
                        echo "<ul>";
                        foreach ($value[$key1] as $v) {
                            if($key1 == "tags"){
                                echo "<li>".$v['tagName']." - ".$v['tagId']."</li>";
                            }
                            else{
                                echo "<li>".$v."</li>";
                            }
                        }
                        echo "</ul>";
                    }
                    else{
                        echo $value[$key1];
                    }
                ?></td>
            </tr>
    <?php
        }
    ?>
        </table><br/>
    <?php
        }
        ?>
