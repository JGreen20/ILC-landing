<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol']  = 'smtp';
$config['smtp_host'] = 'mail.ad-inspector.com';
//$config['smtp_host'] = 'ssl://smtp.gmail.com';
$config['smtp_user'] = 'no-reply@ad-inspector.com';
$config['smtp_pass'] = 'ABcd1234';
$config['smtp_port'] = 26;
//$config['smtp_port'] = 465;
$config['charset']   = 'utf-8';
$config['mailtype']  = 'html';
$config['wordwrap']  = TRUE;
$config['newline']   = "\r\n";