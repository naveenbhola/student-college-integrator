<?php
//Convert the excel file to csv and replace all the shiksha urls with the new ones
$row = 1;
            $array1 = array();
$handle = fopen("naukrishiksha.csv", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
//        echo "<p> $num fields in line $row: <br /></p>\n";
            $row++;
                $i = 0;
                for ($c=0; $c < $num; $c++) {
                if($c == 0)
                {
                foreach($array1 as $key=>$value)
                {
                if($key == $data[$c])
                {
                    $i = count($array1[$key]);
                    break;
                }
                else
                {
                    $i = 0;
                }
                }
                }
                if($c == 1)
                $array1[$data[0]][$i]['name'] = $data[$c];
                if($c == 2)
                $array1[$data[0]][$i]['url'] = $data[$c];
                            }
                            }
                            fclose($handle);
                            print_r($array1);
                            $handle = fopen("bestplacesarray.php","w");
                            fwrite($handle,json_encode($array1));
                            fclose($handle);
                            ?>
