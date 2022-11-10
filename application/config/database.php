<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------
  | DATABASE CONNECTIVITY SETTINGS
  | -------------------------------------------------------------------
  | This file will contain the settings needed to access your database.
  |
  | For complete instructions please consult the 'Database Connection'
  | page of the User Guide.
  |
  | -------------------------------------------------------------------
  | EXPLANATION OF VARIABLES
  | -------------------------------------------------------------------
  |
  |	['hostname'] The hostname of your database server.
  |	['username'] The username used to connect to the database
  |	['password'] The password used to connect to the database
  |	['database'] The name of the database you want to connect to
  |	['dbdriver'] The database type. ie: mysql.  Currently supported:
  mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
  |	['dbprefix'] You can add an optional prefix, which will be added
  |				 to the table name when using the  Active Record class
  |	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
  |	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
  |	['cache_on'] TRUE/FALSE - Enables/disables query caching
  |	['cachedir'] The path to the folder where cache files should be stored
  |	['char_set'] The character set used in communicating with the database
  |	['dbcollat'] The character collation used in communicating with the database
  |				 NOTE: For MySQL and MySQLi databases, this setting is only used
  | 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
  |				 (and in table creation queries made with DB Forge).
  | 				 There is an incompatibility in PHP with mysql_real_escape_string() which
  | 				 can make your site vulnerable to SQL injection if you are using a
  | 				 multi-byte character set and are running versions lower than these.
  | 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
  |	['swap_pre'] A default table prefix that should be swapped with the dbprefix
  |	['autoinit'] Whether or not to automatically initialize the database.
  |	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
  |							- good for ensuring strict SQL while developing
  |
  | The $active_group variable lets you choose which connection group to
  | make active.  By default there is only one group (the 'default' group).
  |
  | The $active_record variables lets you determine whether or not to load
  | the active record class
 */

$active_group = 'default';
$active_record = TRUE;
ob_start();
ob_end_clean();
$CI = &get_instance();
$CI->load->library('session');
//$CI->session->unset_userdata('db_session');
$ses = $CI->session->userdata;
//pr($ses);exit;

if (($_SERVER['HTTP_HOST'] == ('topsdemo.co.in')) || $_SERVER['HTTP_HOST'] == ('www.topsdemo.co.in'))
{

    if (!empty($ses['db_session']) && !empty($ses['db_session']['host_name']) && !empty($ses['db_session']['db_user_name']) && !empty($ses['db_session']['db_name']))
    {
        $db['default']['hostname'] = $ses['db_session']['host_name'];
        $db['default']['username'] = $ses['db_session']['db_user_name'];
        $db['default']['password'] = $ses['db_session']['db_user_password'];
        $db['default']['database'] = $ses['db_session']['db_name'];
    } else
    {
        /* $db['default']['hostname'] = 'localhost';
          $db['default']['username'] = 'root';
          $db['default']['password'] = 'DnH[k5E[0=GT';
          $db['default']['database'] = 'topscoin_upsell_saas'; */
        $db['default']['hostname'] = 'localhost';
        $db['default']['username'] = 'topscoin_master';
        $db['default']['password'] = 'Iu2NBxPD2FJe';
        $db['default']['database'] = 'topscoin_upsell';  //developer
        //$db['default']['database'] = 'topscoin_spain_crm_client_v_1_1'; //client
    }
} else if ($_SERVER['HTTP_HOST'] == 'smartcartupsellbundle.com')
{
    $db['default']['hostname'] = 'localhost';
    $db['default']['username'] = 'root';
    $db['default']['password'] = 'b8EjRdGffTdoa';
    $db['default']['database'] = 'smart_cart_upsell';
} else if ($_SERVER['HTTP_HOST'] == 'dev.smartcartupsellbundle.com')
{
    $db['default']['hostname'] = 'localhost';
    $db['default']['username'] = 'root';
    $db['default']['password'] = 'b8EjRdGffTdoa';
    $db['default']['database'] = 'smart_cart_upsell_dev';
} else
{
    if (!empty($ses['db_session']) && !empty($ses['db_session']['host_name']) && !empty($ses['db_session']['db_user_name']) && !empty($ses['db_session']['db_name']))
    {
        $db['default']['hostname'] = $ses['db_session']['host_name'];
        $db['default']['username'] = $ses['db_session']['db_user_name'];
        $db['default']['password'] = $ses['db_session']['db_user_password'];
        $db['default']['database'] = $ses['db_session']['db_name'];
    } else
    {
        $db['default']['hostname'] = 'localhost';
        $db['default']['username'] = 'root';
        $db['default']['password'] = '';
        $db['default']['database'] = 'tops_upsell';
    }
}


$db['default']['dbdriver'] = 'mysqli';
//$db['default']['dbprefix'] = 'sl_';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
