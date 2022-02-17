<?php

$config['fm_hosts'] = array(
                        '10.10.16.81',
                        '10.10.16.71',
                        '10.10.16.72',
                        '10.10.16.82',
                        '10.10.16.91',
                        '10.10.16.92',
                        '10.10.16.93'
                 );

$config['fm_services'] = array(
                        '10.10.16.81' => array(
                                                'Mysql Master Database',
                                                'Memcache',
                                                'Redis',
                                            ),
                        '10.10.16.71' => array(
                                                'Mysql Slave Database (Slave01)',
                                                'Memcache',
                                                'Tomcat/Solr (Main Search)',
                                                'Tomcat/QER'
                                            ),
                        '10.10.16.72' => array(
                                                'Mysql Slave Database (Slave02)',
                                                'ElasticSearch',
                                                'Tomcat/Solr (User Search)',
                                                'Tomcat/Solr (Tagging)'
                                            ),
                        '10.10.16.82' => array(
                                                'Mysql Mailer Database',
                                                'Mysql DNC Database',
                                                'RabbitMQ Broker',
                                                'AppMonitor Worker',
                                                'Personalization Worker',
                                                'Tomcat/Solr (AnA search and QER)'
                                            ),
                        '10.10.16.91' => array(
                                                'Apache/Nginx'
                                            ),
                        '10.10.16.92' => array(
                                                'Apache/Nginx'
                                            ),
                        '10.10.16.93' => array(
                                                'Apache/Nginx',
                                                'Mediadata'
                                            )
                 );

$config['fm_failureTypes'] = array(
                              'temporary' => 'Temporary',
                              'permanent' => 'Permanent'
                            );

$config['fm_outageTypes'] = array(
                              'full' => 'Full',
                              'partial' => 'Partial',
                              'none' => 'None',
                            );

$config['fm_failoverTypes'] = array(
                              'automatic' => 'Automatic',
                              'manual' => 'Manual',
                              'none' => 'None',
                            );