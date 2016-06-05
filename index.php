<?php
session_start();
if (file_exists(getcwd() . '/install.php'))
{
	header('Location:./install.php');
	die("<script type='text/javascript'>window.location = './install.php';</script>");
}
if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
{
	header('Location:./Login.php');
	die("<script type='text/javascript'>window.location = './Login.php';</script>");
}
else
{
	header('Location:./Work.php');
	die("<script type='text/javascript'>window.location = './Work.php';</script>");
}
?>