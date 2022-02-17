<?php

$masterRules = array(
                        array(
                            'conditions' => array(
                                                    array('field' => 'mode','value' => 'partTime')
                                                ),
                            'actions' => array(
                                                    array('field' => 'whenPlanToStart', 'attribute' => 'visibility', 'value' => 'yes'),
                                                    array('field' => 'whenPlanToStart', 'attribute' => 'value', 'value' => '')
                                            )
                        )
);