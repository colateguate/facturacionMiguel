<?php
session_start();

include_once("../config.php");

/******************************************************************
| Name: doLogin
| @param: $user -> nom d'usuari que intenta el login
| @param: $password -> password de l'usuari que intenta el login
| 
| @desc: Procediment que una vegada rep els paràmetres $user i $password
|       llença una conexió al server ldap amb l'usuari que es troba a dalt de
|       tot de l'arbre. Una vegada arriba l'array de connexió busquem a l'usuari
|       que intenta el login i tornem a logegar al server ldap, ara però, amb 
|       l'usuari que ha enviat el request.
|
******************************************************************/
function doLogin($user, $password)
{   
    $user           = new User($user);
    $adServer       = "ldap://192.168.1.10";
    $ldapconn       = ldap_connect($adServer);
    $ldaprdnGuest   = $_POST['username'];
    $password       = $_POST['password'];
    $ldaprdnMaster  = "uid=test,dc=metropolitana,dc=local";
    $guestDn        = 'noDn';

    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
    $bind = ldap_bind($ldapconn, $ldaprdnMaster, 'test');

    if($bind)
    {
        // Fem una cerca desde root i aqui busquem al user que intenta logar per retornar el seu dn i així poder conectar al ldap.
        $result = ldap_search($ldapconn,"dc=metropolitana,dc=local", "(cn=*)") or die ("Error in search query: ".ldap_error($ldapconn));
        $data = ldap_get_entries($ldapconn, $result);

        foreach($data as $key => $infoData)
        {
            if(is_array($infoData))
            {
                if(array_key_exists('uid', $infoData))
                {
                    if($ldaprdnGuest == $infoData['uid'][0])
                    {
                        $guestDn = $infoData['dn'];
                    }
                }
            }
        }

        if($guestDn != 'noDn') // Hem trobat l'usuari que intenta el login
        {
            // Fem el login amb l'user que envia el request
            $bindGuest  = ldap_bind($ldapconn, $guestDn, $password);
            $result     = ldap_search($ldapconn,$guestDn, "(cn=*)") or die ("Error in search query: ".ldap_error($ldapconn));
            
            if($bindGuest)
            {
                $accesLog = new Log('', $user -> getId(), 9, time());
                $accesLog -> saveLog();


                $_SESSION["GUEST_DN"]   = $guestDn;
                $_SESSION["USER"]       = serialize($user); 
                header("Location: ".URL."/dashboard/dashboard.php");
                die();
            }
            else
            {
                $accesLog = new Log('', $user -> getId(), 1, time());
                $accesLog -> saveLog();

                echo "
                <script type='text/javascript'>alert('Error intentant fer login');
                    window.location.href='".URL."';
                </script>";
                die();
            }
        }
        else
        {
            $accesLog = new Log('', $user -> getId(), 1, time());
            $accesLog -> saveLog();

            echo "
                <script type='text/javascript'>alert('Error al intentar connectar amb el servidor LDAP');
                window.location.href='".URL."';
                </script>";
            die();
        }
    }
    else
    {
        $accesLog = new Log('', $user -> getId(), 1, time());
        $accesLog -> saveLog();

        echo "
            <script type='text/javascript'>alert('mail o password incorrectes');
                window.location.href='".URL."';
            </script>";
        die();
    }
}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- FI FUNCIONS/INICI LOGIN -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

// Login desde index
if(isset($_POST['username']) && isset($_POST['password']))
{
    doLogin($_POST['username'], $_POST['password']);
}
elseif(isset($_POST['passwordLockScreen'])) // Login de LockedScreen
{
    $username = $_SESSION["USER"];
    $password = $_POST['passwordLockScreen'];

    doLogin($username, $password);
}
else
{
    $accesLog = new Log('', $user -> getId(), 10, time());
    $accesLog -> saveLog();

    echo "
            <script type='text/javascript'>alert('Falta ingresar mail o password');
                window.location.href='".URL."';
            </script>";
    die();
} 