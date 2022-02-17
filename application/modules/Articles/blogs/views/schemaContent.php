<?php
                        if(isset($blogInfo[0]['blogText'])) {
                            $blogDescription = json_decode($blogInfo[0]['blogText'], true);
                                                        switch($blogInfo[0]['blogLayout']){
                                                                case 'qna':                                                                
                                                                    $blogInfo[0]['blogQnA'] = json_decode($blogInfo[0]['blogQnA'],true);
                                                                    foreach($blogInfo[0]['blogQnA'] as $sn){
                                                                        if($sn['question']){ ?>
                                                                            <?=html_escape(strip_tags($sn['question']))?>
                                                                            <?=html_escape(strip_tags($sn['answer']))?>
                                                                        <?php }
                                                                        else{ ?>
                                                                            <?=html_escape(strip_tags($sn['answer']))?>
                                                                        <?php }
                                                                    }
                                                                    break;
                                                                case 'slideshow':
                                                                    $blogInfo[0]['blogSlideShow'] = json_decode($blogInfo[0]['blogSlideShow'],true);
                                                                    foreach($blogInfo[0]['blogSlideShow'] as $key=>$sn){
                                                                ?>
                                                                        <?=html_escape(strip_tags($sn['title']))?>
                                                                        <?=html_escape(strip_tags($sn['subTitle']))?>
                                                                        <?=html_escape(strip_tags($sn['description']))?>
                                                                <?php
                                                                    }
                                                                    break;
                                                                default: echo html_escape(strip_tags($blogDescription[0]['description']));
                                                        }
                        }
?>
