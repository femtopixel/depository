<?php
session_start();
foreach($_SESSION as $key => $value)
{
	unset($_SESSION[$key]);
}
session_destroy();
header('Location:./index.php');
die('<script type=\'text/javascript\'>window.location = "./index.php";</script>');
?>