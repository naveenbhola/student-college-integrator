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

$db['302']['hostname'] = "masterdb.shiksha.jsb9.net";
$db['302']['username'] = "shiksha";
$db['302']['password'] = "shiKm7Iv80l";
$db['302']['database'] = "shiksha";
$db['302']['dbdriver'] = "mysqli";
$db['302']['dbprefix'] = "";
$db['302']['pconnect'] = TRUE;
$db['302']['db_debug'] = FALSE;
$db['302']['cache_on'] = FALSE;
$db['302']['cachedir'] = "";
$db['302']['char_set'] = "utf8";
$db['302']['dbcollat'] = "utf8_general_ci";
$db['302']['machine'] = "192";

//Below line for Shifting 124 to 168
//$db['124']['hostname'] = "10.208.64.60";
$db['124']['hostname'] = "slave02.shiksha.jsb9.net";
$db['124']['username'] = "shiksha";
$db['124']['password'] = "shiKm7Iv80l";
$db['124']['database'] = "shiksha";
$db['124']['dbdriver'] = "mysqli";
$db['124']['dbprefix'] = "";
$db['124']['pconnect'] = TRUE;
$db['124']['db_debug'] = FALSE;
$db['124']['cache_on'] = FALSE;
$db['124']['cachedir'] = "";
$db['124']['char_set'] = "utf8";
$db['124']['dbcollat'] = "utf8_general_ci";
$db['124']['machine'] = "124";

$db['168']['hostname'] = "slave01.shiksha.jsb9.net";
$db['168']['username'] = "shiksha";
$db['168']['password'] = "shiKm7Iv80l";
$db['168']['database'] = "shiksha";
$db['168']['dbdriver'] = "mysqli";
$db['168']['dbprefix'] = "";
$db['168']['pconnect'] = TRUE;
$db['168']['db_debug'] = FALSE;
$db['168']['cache_on'] = FALSE;
$db['168']['cachedir'] = "";
$db['168']['char_set'] = "utf8";
$db['168']['dbcollat'] = "utf8_general_ci";
$db['168']['machine'] = "168";

$db['201']['hostname'] = "slave01.shiksha.jsb9.net";
$db['201']['username'] = "shiksha";
$db['201']['password'] = "shiKm7Iv80l";
$db['201']['database'] = "shiksha";
$db['201']['dbdriver'] = "mysqli";
$db['201']['dbprefix'] = "";
$db['201']['pconnect'] = TRUE;
$db['201']['db_debug'] = FALSE;
$db['201']['cache_on'] = FALSE;
$db['201']['cachedir'] = "";
$db['201']['char_set'] = "utf8";
$db['201']['dbcollat'] = "utf8_general_ci";
$db['201']['machine'] = "203";


$db['mailer']['hostname'] = "mailermaster.shiksha.jsb9.net";
$db['mailer']['username'] = "shiksha";
$db['mailer']['password'] = "shiKm7Iv80l";
$db['mailer']['database'] = "mailer";
$db['mailer']['dbdriver'] = "mysqli";
$db['mailer']['dbprefix'] = "";
$db['mailer']['pconnect'] = TRUE;
$db['mailer']['db_debug'] = FALSE;
$db['mailer']['cache_on'] = FALSE;
$db['mailer']['cachedir'] = "";
$db['mailer']['char_set'] = "utf8";
$db['mailer']['dbcollat'] = "utf8_general_ci";
//$db['mailer']['socket'] = "/tmp/mysql_06.sock";
//$db['mailer']['port'] = "3306";
$db['mailer']['machine'] = "203";

$db['mis']['hostname'] = "slave03.shiksha.jsb9.net";
$db['mis']['username'] = "shiksha";
$db['mis']['password'] = "shiKm7Iv80l";
$db['mis']['database'] = "shiksha";
$db['mis']['dbdriver'] = "mysqli";
$db['mis']['dbprefix'] = "";
$db['mis']['pconnect'] = TRUE;
$db['mis']['db_debug'] = FALSE;
$db['mis']['cache_on'] = FALSE;
$db['mis']['cachedir'] = "";
$db['mis']['char_set'] = "utf8";
$db['mis']['dbcollat'] = "utf8_general_ci";
$db['mis']['socket'] = "/tmp/mysql_08.sock";
$db['mis']['port'] = "3308";
$db['mis']['machine'] = "101";

$db['deferral']['hostname'] = "slave03.shiksha.jsb9.net";
$db['deferral']['username'] = "shiksha";
$db['deferral']['password'] = "shiKm7Iv80l";
$db['deferral']['database'] = "Deferral";
$db['deferral']['dbdriver'] = "mysqli";
$db['deferral']['dbprefix'] = "";
$db['deferral']['pconnect'] = TRUE;
$db['deferral']['db_debug'] = FALSE;
$db['deferral']['cache_on'] = FALSE;
$db['deferral']['cachedir'] = "";
$db['deferral']['char_set'] = "utf8";
$db['deferral']['dbcollat'] = "utf8_general_ci";
$db['deferral']['machine'] = "203";

$db['sumsread']['hostname'] = "slave01.shiksha.jsb9.net";
$db['sumsread']['username'] = "shiksha";
$db['sumsread']['password'] = "shiKm7Iv80l";
$db['sumsread']['database'] = "SUMS";
$db['sumsread']['dbdriver'] = "mysqli";
$db['sumsread']['dbprefix'] = "";
$db['sumsread']['pconnect'] = TRUE;
$db['sumsread']['db_debug'] = FALSE;
$db['sumsread']['cache_on'] = FALSE;
$db['sumsread']['cachedir'] = "";
$db['sumsread']['char_set'] = "utf8";
$db['sumsread']['dbcollat'] = "utf8_general_ci";
$db['sumsread']['machine'] = "168";

// $db['sumswrite']['hostname'] = "10.208.66.240";
$db['sumswrite']['hostname'] = "masterdb.shiksha.jsb9.net";
$db['sumswrite']['username'] = "shiksha";
$db['sumswrite']['password'] = "shiKm7Iv80l";
$db['sumswrite']['database'] = "SUMS";
$db['sumswrite']['dbdriver'] = "mysqli";
$db['sumswrite']['dbprefix'] = "";
$db['sumswrite']['pconnect'] = TRUE;
$db['sumswrite']['db_debug'] = FALSE;
$db['sumswrite']['cache_on'] = FALSE;
$db['sumswrite']['cachedir'] = "";
$db['sumswrite']['char_set'] = "utf8";
$db['sumswrite']['dbcollat'] = "utf8_general_ci";
$db['sumswrite']['machine'] = "192";

//$db['ndnc']['hostname'] = "slave03.shiksha.jsb9.net";
$db['ndnc']['hostname'] = "mailermaster.shiksha.jsb9.net";
$db['ndnc']['username'] = "shiksha";
$db['ndnc']['password'] = "shiKm7Iv80l";
$db['ndnc']['database'] = "DNC";
$db['ndnc']['dbdriver'] = "mysqli";
$db['ndnc']['dbprefix'] = "";
$db['ndnc']['active_r'] = TRUE;
$db['ndnc']['pconnect'] = TRUE;
$db['ndnc']['db_debug'] = FALSE;
$db['ndnc']['cache_on'] = FALSE;
$db['ndnc']['cachedir'] = "";
$db['ndnc']['char_set'] = "utf8";
$db['ndnc']['dbcollat'] = "utf8_general_ci";
$db['ndnc']['port'] = "3307";
$db['ndnc']['socket'] = "/tmp/mysql_07.sock";
$db['ndnc']['machine'] = "203";


$db['qer']['hostname'] = "masterdb.shiksha.jsb9.net";
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
$db['qer']['machine'] = "192";

$db['qerSA']['hostname'] = "masterdb.shiksha.jsb9.net";
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
$db['qerSA']['machine'] = "192";

$db['crm']['hostname'] = "localhost";
$db['crm']['username'] = "root";
$db['crm']['password'] = "Km7Iv80l";
$db['crm']['database'] = "shikshaCRM";
$db['crm']['dbdriver'] = "mysqli";
$db['crm']['dbprefix'] = "";
$db['crm']['pconnect'] = TRUE;
$db['crm']['db_debug'] = FALSE;
$db['crm']['cache_on'] = FALSE;
$db['crm']['cachedir'] = "";
$db['crm']['char_set'] = "utf8";
$db['crm']['dbcollat'] = "utf8_general_ci";

$db['session']['hostname'] = "masterdb.shiksha.jsb9.net";
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


$db['mailbounce']['hostname'] = "172.10.22.56";
$db['mailbounce']['username'] = "bouncelog_read";
$db['mailbounce']['password'] = "b089!@#nyu";
$db['mailbounce']['database'] = "mailbouncelog";
$db['mailbounce']['dbdriver'] = "mysqli";
$db['mailbounce']['dbprefix'] = "";
$db['mailbounce']['pconnect'] = TRUE;
$db['mailbounce']['db_debug'] = TRUE;
$db['mailbounce']['cache_on'] = FALSE;
$db['mailbounce']['cachedir'] = "";
$db['mailbounce']['char_set'] = "utf8";
$db['mailbounce']['dbcollat'] = "utf8_general_ci";

$db['app_monitoring_read']['hostname'] = "slave03.shiksha.jsb9.net";
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
$db['app_monitoring_read']['socket']   = "/tmp/mysql_08.sock";
$db['app_monitoring_read']['port']     = "3308";
$db['app_monitoring_read']['machine']  = "101";

$db['app_monitoring_write']['hostname'] = "masterdb.shiksha.jsb9.net";
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
$db['app_monitoring_write']['machine'] = "AM81";

$db['jsb9_read']['hostname'] = "slave03.shiksha.jsb9.net";
$db['jsb9_read']['username'] = "shiksha";
$db['jsb9_read']['password'] = "shiKm7Iv80l";
$db['jsb9_read']['database'] = "jsb9_tracking";
$db['jsb9_read']['dbdriver'] = "mysqli";
$db['jsb9_read']['dbprefix'] = "";
$db['jsb9_read']['pconnect'] = TRUE;
$db['jsb9_read']['db_debug'] = FALSE;
$db['jsb9_read']['cache_on'] = FALSE;
$db['jsb9_read']['cachedir'] = "";
$db['jsb9_read']['char_set'] = "utf8";
$db['jsb9_read']['dbcollat'] = "utf8_general_ci";
$db['jsb9_read']['port']     = "3309";
$db['jsb9_read']['socket']   = "/tmp/mysql_09.sock";
$db['jsb9_read']['machine']  = "101";

$db['va_machine']['hostname'] = "slave03.shiksha.jsb9.net";
$db['va_machine']['username'] = "shiksha";
$db['va_machine']['password'] = "shiKm7Iv80l";
$db['va_machine']['database'] = "shiksha";
$db['va_machine']['dbdriver'] = "mysqli";
$db['va_machine']['dbprefix'] = "";
$db['va_machine']['pconnect'] = TRUE;
$db['va_machine']['db_debug'] = FALSE;
$db['va_machine']['cache_on'] = FALSE;
$db['va_machine']['cachedir'] = "";
$db['va_machine']['char_set'] = "utf8";
$db['va_machine']['dbcollat'] = "utf8_general_ci";
$db['va_machine']['machine'] = "101";
$db['va_machine']['socket'] = "/tmp/mysql_06.sock";
$db['va_machine']['port'] = "3306";

$db['va_machine_SUMS']['hostname'] = "slave03.shiksha.jsb9.net";
$db['va_machine_SUMS']['username'] = "shiksha";
$db['va_machine_SUMS']['password'] = "shiKm7Iv80l";
$db['va_machine_SUMS']['database'] = "SUMS";
$db['va_machine_SUMS']['dbdriver'] = "mysqli";
$db['va_machine_SUMS']['dbprefix'] = "";
$db['va_machine_SUMS']['pconnect'] = TRUE;
$db['va_machine_SUMS']['db_debug'] = FALSE;
$db['va_machine_SUMS']['cache_on'] = FALSE;
$db['va_machine_SUMS']['cachedir'] = "";
$db['va_machine_SUMS']['char_set'] = "utf8";
$db['va_machine_SUMS']['dbcollat'] = "utf8_general_ci";
$db['va_machine_SUMS']['machine'] = "101";
$db['va_machine_SUMS']['socket'] = "/tmp/mysql_06.sock";
$db['va_machine_SUMS']['port'] = "3306";

$db['va_machine_sessions']['hostname'] = "slave03.shiksha.jsb9.net";
$db['va_machine_sessions']['username'] = "shiksha";
$db['va_machine_sessions']['password'] = "shiKm7Iv80l";
$db['va_machine_sessions']['database'] = "SESSION";
$db['va_machine_sessions']['dbdriver'] = "mysqli";
$db['va_machine_sessions']['dbprefix'] = "";
$db['va_machine_sessions']['pconnect'] = TRUE;
$db['va_machine_sessions']['db_debug'] = FALSE;
$db['va_machine_sessions']['cache_on'] = FALSE;
$db['va_machine_sessions']['cachedir'] = "";
$db['va_machine_sessions']['char_set'] = "utf8";
$db['va_machine_sessions']['dbcollat'] = "utf8_general_ci";
$db['va_machine_sessions']['machine'] = "101";
$db['va_machine_sessions']['socket'] = "/tmp/mysql_06.sock";
$db['va_machine_sessions']['port'] = "3306";

$db['va_machine_appmonitor']['hostname'] = "slave03.shiksha.jsb9.net";
$db['va_machine_appmonitor']['username'] = "shiksha";
$db['va_machine_appmonitor']['password'] = "shiKm7Iv80l";
$db['va_machine_appmonitor']['database'] = "app_monitoring";
$db['va_machine_appmonitor']['dbdriver'] = "mysqli";
$db['va_machine_appmonitor']['dbprefix'] = "";
$db['va_machine_appmonitor']['pconnect'] = TRUE;
$db['va_machine_appmonitor']['db_debug'] = FALSE;
$db['va_machine_appmonitor']['cache_on'] = FALSE;
$db['va_machine_appmonitor']['cachedir'] = "";
$db['va_machine_appmonitor']['char_set'] = "utf8";
$db['va_machine_appmonitor']['dbcollat'] = "utf8_general_ci";
$db['va_machine_appmonitor']['machine'] = "101";
$db['va_machine_appmonitor']['socket'] = "/tmp/mysql_06.sock";
$db['va_machine_appmonitor']['port'] = "3306";
