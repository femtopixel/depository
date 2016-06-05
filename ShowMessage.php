<?php
require_once('head.php');

$message['defaut'] = 'Pas de message associ&eacute;...';
$message['droit'] = 'Vous ne disposez pas des droits suffisant &agrave; cette fonction';
$message['security'] = 'Erreur de s&eacute;curit&eacute;';
$message['password'] = 'Les passwords ne correspondent pas';
$message['notlogged'] = 'Vous n\'&ecirc;tes pas connect&eacute;, vous ne pouvez donc pas acceder &agrave; cette fonction';
$message['pseudoexist'] = 'Ce pseudo &eacute;xiste d&eacute;j&agrave;, merci d\'en choisir un autre';

$smarty->assign('message', (isset($message[$_GET['mess']])) ? $message[$_GET['mess']] : $message['defaut']);
$smarty->display('ShowMessage.tpl');

require_once('foot.php');
?>