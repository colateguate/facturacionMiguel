<?php
session_start();
require_once('../config.php');

switch($_POST['action'])
{
	case 'unclockScreen':
		$password = $_POST["passwordLockScreen"];

		$adServer       = "ldap://192.168.1.10";
		$ldapconn       = ldap_connect($adServer);
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

		$bindGuest = ldap_bind($ldapconn, $_SESSION["GUEST_DN"], $password);

		if($bindGuest)
		{
			setcookie('pageLock', 0, 0, '/', 'localhost');
			echo "1";
		}
		else
		{
			echo "0";
		}

		break;

	case 'setCookiePreventRefresh':
		ob_start();
		setcookie('pageLock', 1, 0, '/', 'localhost');
		echo "1";
		ob_end_flush();
		break;
}