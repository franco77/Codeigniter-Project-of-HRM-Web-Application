<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Email Settings
| -------------------------------------------------------------------
| Configuration of outgoing mail server.
| */

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'polosoftech.com';
$config['smtp_port'] = '465';
$config['smtp_timeout'] = '30';
$config['smtp_user'] = 'info@polosoftech.com';
$config['smtp_pass'] = '#loombands@123';
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n"; 
$config = Array(
    'protocol' => 'smtp',
    'smtp_host' => 'polosoftech.com',
    'smtp_port' => 465,
    'smtp_user' => 'info@polosoftech.com',
    'smtp_pass' => '#loombands@123',
	'mailtype' => 'html',
    'charset'   => 'iso-8859-1'
);
