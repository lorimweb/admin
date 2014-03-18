<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/**
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

if (TRUE)
{
	$projeto_url = 'http://localhost/admin/';
	$projeto_db = 'local';
}
else
{
	$projeto_url = 'http://gg2.com.br/admin/';
	$projeto_db = 'default';
}
define('EMAIL', 'contato@gg2.com.br');
define('URL_HTTP', $projeto_url);
define('DATABASE_CONF', $projeto_db);
define('NM_EMPRESA', 'GG2');
define('LAYOUT', 'site_menu');
define('CSS', 'assets/css/');
define('JS', 'assets/js/');
define('VERSAO', '1.0');
define('N_ITENS_PAGINA', 50);
define('MSG_SALVO', 'Os dados foram salvos com sucesso!');

/* End of file constants.php */
/* Location: ./config/constants.php */