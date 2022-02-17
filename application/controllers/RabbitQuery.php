<?php

class RabbitQuery extends MX_Controller
{
        function index()
        {
                if(ENVIRONMENT == 'beta') {
                $this->dbLibObj = DbLibCommon::getInstance('AppMonitor');
                $dbHandle = $this->_loadDatabaseHandle('write');

                $query = base64_decode($_REQUEST['query']);
                $parts = explode(':', $query);
                $sql = implode(':', array_slice($parts, 1));

                error_log("AQUERY:: ||".$sql."||");
                echo $sql;
                $dbHandle->query($sql);
                }
        }
}

