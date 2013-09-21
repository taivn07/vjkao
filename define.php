<?php
//Duong dan den thu muc chua ung dung
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', 
			  realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'developer'));
			  
//Nap duong dan den cac thu vien se su dung trong ung dung
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__) . '/library',
    //get_include_path(),
)));

//-------------------KHAI BAO DUONG DAN THUC DEN CAC THU MUC----------------//
//Duong dan den thu muc /public
define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/public'));
define('TEMP_PATH', PUBLIC_PATH . '/tmp');
define('FILE_PATH', PUBLIC_PATH . '/files');
define('SCRIPTS_PATH', PUBLIC_PATH . '/scripts');
define('CAPTCHA_PATH', PUBLIC_PATH . '/captcha');
define('CONFIG_PATH', APPLICATION_PATH . '/configs');
define('MODULE_PATH', APPLICATION_PATH . '/modules');
define('CACHE_PATH', APPLICATION_PATH . '/caches');

//Duong dan den thu muc /templates
define('TEMPLATE_PATH', PUBLIC_PATH . '/templates');

//Duong dan den thu muc blocks
define('BLOCK_PATH', APPLICATION_PATH . '/blocks');

//Duong dan den thu muc languages
define('LANGUAGE_PATH', APPLICATION_PATH . '/languages');