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

$db['302']['hostname'] = "10.208.65.32";
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
$db['124']['hostname'] = "10.208.68.230";
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

$db['168']['hostname'] = "10.208.64.60";
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

$db['201']['hostname'] = "10.208.65.61";
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


$db['mailer']['hostname'] = "10.208.65.61";
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
$db['mailer']['machine'] = "203";
$db['mailer']['port'] = "3308";
$db['mailer']['socket'] = "/tmp/mysql8.sock";

$db['deferral']['hostname'] = "10.208.65.61";
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

$db['sumsread']['hostname'] = "10.208.64.60";
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
$db['sumswrite']['hostname'] = "10.208.65.32";
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

$db['ndnc']['hostname'] = "10.208.65.61";
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
$db['ndnc']['socket'] = "/tmp/mysql_dnc.sock";
$db['ndnc']['machine'] = "203";


$db['qer']['hostname'] = "10.208.65.32";
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

$db['qerSA']['hostname'] = "10.208.65.32";
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

$db['session']['hostname'] = "10.208.65.32";
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


$db['mailbounce']['hostname'] = "172.10.22.51";
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
