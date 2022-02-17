<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * | -------------------------------------------------------------------
 * | DATABASE CONNECTIVITY SETTINGS
 * | -------------------------------------------------------------------
 * | This file will contain the settings needed to access your database.
 * |
 * | For complete instructions please consult the "Database Connection"
 * | page of the User Guide.
 * |
 * | -------------------------------------------------------------------
 * | EXPLANATION OF VARIABLES
 * | -------------------------------------------------------------------
 * |
 */

$active_group = "302";
$active_record = TRUE;


$db['302']['hostname'] = "172.16.3.111";
$db['302']['username'] = "shiksha";
$db['302']['password'] = "shiKm7Iv80l";
$db['302']['database'] = "shiksha";
$db['302']['dbdriver'] = "mysqli";
$db['302']['dbprefix'] = "";
$db['302']['pconnect'] = TRUE;
$db['302']['db_debug'] = TRUE;
$db['302']['cache_on'] = FALSE;
$db['302']['cachedir'] = "";
$db['302']['char_set'] = "utf8";
$db['302']['dbcollat'] = "utf8_general_ci";

$db['124']['hostname'] = "172.16.3.111";
$db['124']['username'] = "shiksha";
$db['124']['password'] = "shiKm7Iv80l";
$db['124']['database'] = "shiksha";
$db['124']['dbdriver'] = "mysqli";
$db['124']['dbprefix'] = "";
$db['124']['pconnect'] = TRUE;
$db['124']['db_debug'] = TRUE;
$db['124']['cache_on'] = FALSE;
$db['124']['cachedir'] = "";
$db['124']['char_set'] = "utf8";
$db['124']['dbcollat'] = "utf8_general_ci";

$db['168']['hostname'] = "172.16.3.111";
$db['168']['username'] = "shiksha";
$db['168']['password'] = "shiKm7Iv80l";
$db['168']['database'] = "shiksha";
$db['168']['dbdriver'] = "mysqli";
$db['168']['dbprefix'] = "";
$db['168']['pconnect'] = TRUE;
$db['168']['db_debug'] = TRUE;
$db['168']['cache_on'] = FALSE;
$db['168']['cachedir'] = "";
$db['168']['char_set'] = "utf8";
$db['168']['dbcollat'] = "utf8_general_ci";

$db['201']['hostname'] = "172.16.3.111";
$db['201']['username'] = "shiksha";
$db['201']['password'] = "shiKm7Iv80l";
$db['201']['database'] = "shiksha";
$db['201']['dbdriver'] = "mysqli";
$db['201']['dbprefix'] = "";
$db['201']['pconnect'] = TRUE;
$db['201']['db_debug'] = TRUE;
$db['201']['cache_on'] = FALSE;
$db['201']['cachedir'] = "";
$db['201']['char_set'] = "utf8";
$db['201']['dbcollat'] = "utf8_general_ci";

$db['sumsread']['hostname'] = "172.16.3.111";
$db['sumsread']['username'] = "shiksha";
$db['sumsread']['password'] = "shiKm7Iv80l";
$db['sumsread']['database'] = "SUMS";
$db['sumsread']['dbdriver'] = "mysqli";
$db['sumsread']['dbprefix'] = "";
$db['sumsread']['pconnect'] = TRUE;
$db['sumsread']['db_debug'] = TRUE;
$db['sumsread']['cache_on'] = FALSE;
$db['sumsread']['cachedir'] = "";
$db['sumsread']['char_set'] = "utf8";
$db['sumsread']['dbcollat'] = "utf8_general_ci";


$db['mailer']['hostname'] = "172.16.3.111";
$db['mailer']['username'] = "shiksha";
$db['mailer']['password'] = "shiKm7Iv80l";
$db['mailer']['database'] = "mailer";
$db['mailer']['dbdriver'] = "mysqli";
$db['mailer']['dbprefix'] = "";
$db['mailer']['pconnect'] = TRUE;
$db['mailer']['db_debug'] = TRUE;
$db['mailer']['cache_on'] = FALSE;
$db['mailer']['cachedir'] = "";
$db['mailer']['char_set'] = "utf8";
$db['mailer']['dbcollat'] = "utf8_general_ci";
#$db['mailer']['socket'] = "/tmp/mysql_07.sock";
#$db['mailer']['port'] = "3307";

$db['sumswrite']['hostname'] = "172.16.3.111";
$db['sumswrite']['username'] = "shiksha";
$db['sumswrite']['password'] = "shiKm7Iv80l";
$db['sumswrite']['database'] = "SUMS";
$db['sumswrite']['dbdriver'] = "mysqli";
$db['sumswrite']['dbprefix'] = "";
$db['sumswrite']['pconnect'] = TRUE;
$db['sumswrite']['db_debug'] = TRUE;
$db['sumswrite']['cache_on'] = FALSE;
$db['sumswrite']['cachedir'] = "";
$db['sumswrite']['char_set'] = "utf8";
$db['sumswrite']['dbcollat'] = "utf8_general_ci";

$db['deferral']['hostname'] = "172.16.3.111";
$db['deferral']['username'] = "shiksha";
$db['deferral']['password'] = "shiKm7Iv80l";
$db['deferral']['database'] = "Deferral";
$db['deferral']['dbdriver'] = "mysqli";
$db['deferral']['dbprefix'] = "";
$db['deferral']['active_r'] = TRUE;
$db['deferral']['pconnect'] = TRUE;
$db['deferral']['db_debug'] = TRUE;
$db['deferral']['cache_on'] = FALSE;
$db['deferral']['cachedir'] = "";
$db['deferral']['char_set'] = "utf8";
$db['deferral']['dbcollat'] = "utf8_general_ci";

$db['ndnc']['hostname'] = "172.16.3.111"; // change with NDNC MYSQL SERVER IP
$db['ndnc']['username'] = "shiksha";
$db['ndnc']['password'] = "shiKm7Iv80l";
$db['ndnc']['database'] = "DNC";
$db['ndnc']['dbdriver'] = "mysqli";
$db['ndnc']['dbprefix'] = "";
$db['ndnc']['active_r'] = TRUE;
$db['ndnc']['pconnect'] = TRUE;
$db['ndnc']['db_debug'] = TRUE;
$db['ndnc']['cache_on'] = FALSE;
$db['ndnc']['cachedir'] = "";
$db['ndnc']['char_set'] = "utf8";
$db['ndnc']['dbcollat'] = "utf8_general_ci";

$db['qer']['hostname'] = "172.16.3.111";
$db['qer']['username'] = "shiksha";
$db['qer']['password'] = "shiKm7Iv80l";
$db['qer']['database'] = "query_entity_recognition";
$db['qer']['dbdriver'] = "mysqli";
$db['qer']['dbprefix'] = "";
$db['qer']['pconnect'] = TRUE;
$db['qer']['db_debug'] = FALSE;
$db['qer']['cache_on'] = FALSE;
$db['qer']['cachedir'] = "";
$db['qer']['char_set'] = "utf8";
$db['qer']['dbcollat'] = "utf8_general_ci";

$db['qerSA']['hostname'] = "172.16.3.111";
$db['qerSA']['username'] = "shiksha";
$db['qerSA']['password'] = "shiKm7Iv80l";
$db['qerSA']['database'] = "query_entity_recognition_studyabroad";
$db['qerSA']['dbdriver'] = "mysqli";
$db['qerSA']['dbprefix'] = "";
$db['qerSA']['pconnect'] = TRUE;
$db['qerSA']['db_debug'] = FALSE;
$db['qerSA']['cache_on'] = FALSE;
$db['qerSA']['cachedir'] = "";
$db['qerSA']['char_set'] = "utf8";
$db['qerSA']['dbcollat'] = "utf8_general_ci";

$db['session']['hostname'] = "172.16.3.111";
$db['session']['username'] = "shiksha";
$db['session']['password'] = "shiKm7Iv80l";
$db['session']['database'] = "SESSION";
$db['session']['dbdriver'] = "mysqli";
$db['session']['dbprefix'] = "";
$db['session']['pconnect'] = TRUE;
$db['session']['db_debug'] = FALSE;
$db['session']['cache_on'] = FALSE;
$db['session']['cachedir'] = "";
$db['session']['char_set'] = "utf8";
$db['session']['dbcollat'] = "utf8_general_ci";
$db['session']['machine'] = "192";


$db['mailbounce']['hostname'] = "172.16.3.111";
$db['mailbounce']['username'] = "shiksha";
$db['mailbounce']['password'] = "shiKm7Iv80l";
$db['mailbounce']['database'] = "shiksha";
$db['mailbounce']['dbdriver'] = "mysqli";
$db['mailbounce']['dbprefix'] = "";
$db['mailbounce']['pconnect'] = TRUE;
$db['mailbounce']['db_debug'] = TRUE;
$db['mailbounce']['cache_on'] = FALSE;
$db['mailbounce']['cachedir'] = "";
$db['mailbounce']['char_set'] = "utf8";
$db['mailbounce']['dbcollat'] = "utf8_general_ci";

$db['app_monitoring_read']['hostname'] = "172.16.3.111";
$db['app_monitoring_read']['username'] = "shiksha";
$db['app_monitoring_read']['password'] = "shiKm7Iv80l";
$db['app_monitoring_read']['database'] = "app_monitoring";
$db['app_monitoring_read']['dbdriver'] = "mysqli";
$db['app_monitoring_read']['dbprefix'] = "";
$db['app_monitoring_read']['pconnect'] = TRUE;
$db['app_monitoring_read']['db_debug'] = FALSE;
$db['app_monitoring_read']['cache_on'] = FALSE;
$db['app_monitoring_read']['cachedir'] = "";
$db['app_monitoring_read']['char_set'] = "utf8";
$db['app_monitoring_read']['dbcollat'] = "utf8_general_ci";

$db['app_monitoring_write']['hostname'] = "172.16.3.111";
$db['app_monitoring_write']['username'] = "shiksha";
$db['app_monitoring_write']['password'] = "shiKm7Iv80l";
$db['app_monitoring_write']['database'] = "app_monitoring";
$db['app_monitoring_write']['dbdriver'] = "mysqli";
$db['app_monitoring_write']['dbprefix'] = "";
$db['app_monitoring_write']['pconnect'] = TRUE;
$db['app_monitoring_write']['db_debug'] = FALSE;
$db['app_monitoring_write']['cache_on'] = FALSE;
$db['app_monitoring_write']['cachedir'] = "";
$db['app_monitoring_write']['char_set'] = "utf8";
$db['app_monitoring_write']['dbcollat'] = "utf8_general_ci";

$db['mis']['hostname'] = "172.16.3.111";
$db['mis']['username'] = "shiksha";
$db['mis']['password'] = "shiKm7Iv80l";
$db['mis']['database'] = "shiksha";
$db['mis']['dbdriver'] = "mysqli";
$db['mis']['dbprefix'] = "";
$db['mis']['pconnect'] = TRUE;
$db['mis']['db_debug'] = TRUE;
$db['mis']['cache_on'] = FALSE;
$db['mis']['cachedir'] = "";
$db['mis']['char_set'] = "utf8";
$db['mis']['dbcollat'] = "utf8_general_ci";
