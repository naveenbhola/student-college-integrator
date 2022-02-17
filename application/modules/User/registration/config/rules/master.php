<?php

$masterRules = array(
                        array(
                            'conditions' => array(
                                                    array('field' => 'graduationStatus','value' => 'Completed')
                                                ),
                            'actions' => array(
                                                    array('field' => 'graduationDetails', 'attribute' => 'visibility', 'value' => 'yes'),
                                                    array('field' => 'graduationMarks', 'attribute' => 'visibility', 'value' => 'yes')
                                            )
                        ),
                        array(
                            'conditions' => array(
                                                    array('field' => 'graduationStatus','value' => 'Pursuing')
                                                ),
                            'actions' => array(
                                                    array('field' => 'graduationDetails', 'attribute' => 'visibility', 'value' => 'yes')
                                            )
                        ),
                        array(
                            'conditions' => array(
                                                    array('field' => 'desiredGraduationLevel','value' => 'UG')
                                                ),
                            'actions' => array(
                                                    array('field' => 'xiiStream', 'attribute' => 'visibility', 'value' => 'yes'),
                                                    array('field' => 'xiiYear', 'attribute' => 'visibility', 'value' => 'yes'),
                                                    array('field' => 'xiiMarks', 'attribute' => 'visibility', 'value' => 'yes')
                                            )
                        ),
                        array(
                            'conditions' => array(
                                                    array('field' => 'desiredGraduationLevel','value' => 'PG')
                                                ),
                            'actions' => array(
                                                    array('field' => 'graduationStatus', 'attribute' => 'visibility', 'value' => 'yes'),
                                                    array('field' => 'graduationDetails', 'attribute' => 'visibility', 'value' => 'yes'),
                                                    array('field' => 'graduationMarks', 'attribute' => 'visibility', 'value' => 'yes'),
                                                    array('field' => 'graduationCompletionYear', 'attribute' => 'visibility', 'value' => 'yes'),
                                            )
                        )
);