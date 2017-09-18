<?php
session_start();
require_once("../config.php");

// ------------ FUNCIONS AUXILIARS ----------------
function getDirContents($dir, &$results = array()){
	
    $files = scandir($dir);

    foreach($files as $key => $value)
    {
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        if(!is_dir($path))
        {
        	$pathExploded = explode("/", $path);
        	$fitxer = end($pathExploded);

        	$fitxerExploded = explode('.', $fitxer);
        	if($fitxerExploded[1] == "mov")
        	{
        		$results[] = $fitxer;
        	}
        } 
        else if($value != "." && $value != "..")
        {
            getDirContents($path, $results);
        }
    }

    return $results;
}

// ------------ FI  FUNCIONS AUXILIARS --------------

/*
	Script de recepció de totes les crides ajax del modul de hddBackups.php i hddClients.php
*/

$user = unserialize($_SESSION["USER"]); // Necessitem $user per guardar l'usuari als logs

switch($_POST['action'])
{
	// case 'insertProject':
	// 	$_SESSION['newHdd']   = 0; // Insertarem un projecte en un hdd, el que vol dir que no canviarem d'hdd fins que es marqui com a finalitzat (plè). 
	// 	$idHdd 				  = $_POST['idHdd'];
	// 	$nomProjecte 		  = $_POST['nomProjecte'];
	// 	$clientProjecte 	  = $_POST['clientProjecte'];
	// 	$operadorProjecte     = '';
	// 	$sistemaProjecte 	  = $_POST['sistemaProjecte'];
	// 	$observacionsProjecte = $_POST['observacionsProjecte'];

	// 	$suport = '';

	// 	// Si hi ha més d'un operador els concatenem en un string separat per ','
	// 	if(isset($_POST['operadorProjecte']))
	// 	{
	// 		$totalOperadors = count($_POST['operadorProjecte']);
	// 		for($i=0; $i<$totalOperadors; $i++)
	// 		{
	// 			$operadorProjecte .= $_POST['operadorProjecte'][$i].',';
	// 		}

	// 		$operadorProjecte = substr($operadorProjecte, 0, -1);
	// 	}

	// 	$projecte = new Projecte('', $idHdd, $operadorProjecte, $sistemaProjecte, $nomProjecte, $suport, time(), $observacionsProjecte, $clientProjecte);

	// 	if($projecte)
	// 	{
	// 		$accesLog = new Log('', $user -> getId(), 21, time());
	// 		$accesLog -> saveLog();

	// 		echo "1";
	// 	}
	// 	else
	// 	{
	// 		$accesLog = new Log('', $user -> getId(), 22, time());
	// 		$accesLog -> saveLog();

	// 		echo "0";
	// 	}

	// 	break;

	/*
	Marca un hdd de backup com a ple
	*/
	case 'finishHdd':

		$hddId 	= $_POST['idHdd'];
		$hdd 	= new Hdd($hddId);

		$link 			 = connectarDataBase();
		$sqlMarkAsFilled = "UPDATE hdds SET es_ple = 1 WHERE id = ".$hdd -> getId().";";
		$link -> query($sqlMarkAsFilled);

		if($link -> affected_rows == 1)
		{
			$accesLog = new Log('', $user -> getId(), 23, time());
			$accesLog -> saveLog();

			echo "1";
		}
		else
		{
			$accesLog = new Log('', $user -> getId(), 24, time());
			$accesLog -> saveLog();

			echo "0";
		}
		
		break;

	/*
		Carrega la direcció postal del client seleccionat
	*/
	case 'loadAdrecaClient':
		$clientId = $_POST['clientId'];

		$client = new Client($clientId);
		$adreca = $client -> getAdreca();
		
		echo $adreca;
		break;

	/*
		Inserta un now HDD de client a la base de dades
	*/
	case 'insertClientHdd':
		$client 			 = $_POST['client'];
		$descripcio 		 = $_POST['descripcio'];
		$projecteDepartament = $_POST['projecte'];
		$localitzacio 		 = $_POST['localitzacio'];

		$hddClient = new HddClient('', $client, $descripcio, $projecteDepartament, $localitzacio, time());

		if(null !== $hddClient -> getId())
		{
			$accesLog = new Log('', $user -> getId(), 25, time());
			$accesLog -> saveLog();

			echo "1";
		}
		else
		{
			$accesLog = new Log('', $user -> getId(), 26, time());
			$accesLog -> saveLog();

			echo "0";
		}

		break;

	/*
		Obté les dades del hdd que volem carregar al modal
	*/
	case 'loadHddModal':
		$hddId 			= $_POST['hddId'];
		$jsonResponse 	= array();
		$hddClient 		= new HddClient($hddId);
		$client 		= new Client($hddClient -> getClient());
		$estat 			= new TipusEstat($hddClient -> getEstat());

		if($hddClient)
		{
			$jsonResponse['id'] 				 = $hddClient -> getId();
			$jsonResponse['client'] 			 = $hddClient -> getClient();
			$jsonResponse['descripcio'] 		 = $hddClient -> getDescripcio();
			$jsonResponse['adreca'] 			 = $client -> getAdreca();
			$jsonResponse['projecteDepartament'] = $hddClient -> getProjecteDepartament();
			$jsonResponse['localitzacio'] 		 = $hddClient -> getLocalitzacio();
			$jsonResponse['dataInsercio'] 		 = $hddClient -> getDataInsercio();
			$jsonResponse['estat'] 				 = $estat -> getNom();
			$jsonResponse['dataRetorn'] 		 = $hddClient -> getDataRetorn();

			echo json_encode($jsonResponse);
		}

		break;

	/*
		Obté les dades del usuariClient que volem carregar al modal
	*/
	case 'loadUserClientModal':
		$userClientId 	= $_POST['userClientId'];
		$jsonResponse 	= array();
		$userClient 	= new UsersClient($userClientId);
		//$client 		= new Client($userClient -> getClient());

		if($userClient)
		{
			$jsonResponse['id'] 		 = $userClient -> getId();
			$jsonResponse['nom'] 		 = $userClient -> getNom();
			$jsonResponse['cognom1'] 	 = $userClient -> getPrimerCognom();
			$jsonResponse['cognom2'] 	 = $userClient -> getSegonCognom();
			$jsonResponse['email'] 		 = $userClient -> getEmail();
			$jsonResponse['telefon'] 	 = $userClient -> getTelefon();
			$jsonResponse['client'] 	 = $userClient -> getClient();
			$jsonResponse['tipusUsuari'] = $userClient -> getTipusUsuari();
			$jsonResponse['idioma'] 	 = $userClient -> getIdioma();
			$jsonResponse['notificacio'] = $userClient -> getNotificacio();

			echo json_encode($jsonResponse);
		}

		break;

	/*
		Obté les dades del client que volem carregar al modal
	*/
	case 'loadClientModal':
		$userClientId 	= $_POST['clientId'];
		$jsonResponse 	= array();
		$client 		= new Client($userClientId);

		if($client)
		{
			$jsonResponse['id'] 		 = $client -> getId();
			$jsonResponse['nom'] 		 = $client -> getNom();
			$jsonResponse['adreca'] 	 = $client -> getAdreca();

			echo json_encode($jsonResponse);
		}


		break;

	/*
		Carrega la info del tipus usuari seleccionat
	*/
	case 'loadTipusUsuari':
		$tipusUsuariId = $_POST['tipusUsuariId'];

		$tipusUsuari = new TipusUsuari($tipusUsuariId);
		$id = $tipusUsuari -> getId();
		
		echo $id;
		break;

	/*
		Carrega la info del idioma seleccionat
	*/
	case 'loadIdioma':
		$idiomaId = $_POST['idiomaId'];

		$idiomaObj = new Idioma($idiomaId);
		$idiomaId  = $idiomaObj -> getId();
		
		echo $idiomaId;
		break;

	/*
		Carrega la info del client seleccionat
	*/
	case 'loadClients':
		$clientId = $_POST['clientId'];

		$client = new Client($clientId);
		$id = $client -> getId();
		
		echo $id;
		break;
		break;

	/*
		Inserta en bbdd un nou usariClient a la bbdd
	*/
	case 'insertUsuariClient':
		$nom 		 = $_POST['nom'];
		$cognom1 	 = $_POST['cognom1'];
		$cognom2 	 = $_POST['cognom2'];
		$email 		 = $_POST['email'];
		$telefon 	 = $_POST['telefon'];
		$client 	 = $_POST['client'];
		$tipusUsuari = $_POST['tipusUsuari'];
		$idioma 	 = $_POST['idioma'];
		$notificacio = $_POST['notificacio'];


		$userClient = new UsersClient('', $nom, $cognom1, $cognom2, $email, $telefon, $client, $tipusUsuari, $idioma, $notificacio);

		if(null !== $userClient -> getId())
		{
			$accesLog = new Log('', $user -> getId(), 31, time());
			$accesLog -> saveLog();

			echo "1";
		}
		else
		{
			$accesLog = new Log('', $user -> getId(), 32, time());
			$accesLog -> saveLog();

			echo "0";
		}
		break;

	/*
		Inserta un nou client a la bbdd
	*/
	case 'insertNewClient':
		$nom 	= $_POST['nom'];
		$adreca = $_POST['adreca'];

		$client = new Client('',$nom, $adreca);

		if(null !== $client -> getId())
		{
			$accesLog = new Log('', $user -> getId(), 37, time());
			$accesLog -> saveLog();

			echo "1";
		}
		else
		{
			$accesLog = new Log('', $user -> getId(), 38, time());
			$accesLog -> saveLog();

			echo "0";
		}

		break;

	/*
	Inserta un nou disc dur de backup a la base de dades
	*/
	case 'insertNewHddBackup':
		$metropolitanaId = $_POST["newHddMetropolitanaId"];
		$nom 			 = $_POST["newHddNom"];
		$estat 			 = $_POST["newHddEstat"];

		$hdd = new Hdd('', $metropolitanaId, $nom, $estat);

		if(null !== $hdd -> getId())
		{
			$accesLog = new Log('', $user -> getId(), 43, time());
			$accesLog -> saveLog();

			echo "1";
		}
		else
		{
			$accesLog = new Log('', $user -> getId(), 44, time());
			$accesLog -> saveLog();

			echo "0";
		}

		break;

	case 'insertProjectIntoHddBackup':
		$nomProjecte 	= $_POST["nomProjecte"];
		$operador 		= $_POST["operador"];
		$client 		= $_POST["client"]; 
		$comentaris 	= $_POST["comentaris"]; 
		$hdd 			= $_POST["hdd"];
		$rutaMaster 	= $_POST["rutaMaster"];

		$operadorProjecte = '';
		$operadorsSuport  = '';
		$sistemes 		  = '';

		// Comprobem la ruta i scanejem en busca de masters
		$scanPathRoot = "/Volumes/san/Arxiu/Entrada/TEST_INTRANET/";
		
		// Array amb tots els masters del projecte
		$arrayRutaMasteres = getDirContents($scanPathRoot, $arrayRutaMasteres);
		$rutaMaster = implode(',', $arrayRutaMasteres);

		// Si hi ha més d'un operador els concatenem en un string separat per ','
		if(isset($_POST['operador']))
		{
			$totalOperadors = count($_POST['operador']);
			for($i=0; $i<$totalOperadors; $i++)
			{
				$operadorProjecte .= $_POST['operador'][$i].',';
			}

			$operadorProjecte = substr($operadorProjecte, 0, -1);
		}

		// Si hi ha més d'un operador suport els concatenem en un string separat per ','
		if(isset($_POST['operadorSuport']))
		{
			$totalOperadorsSuport = count($_POST['operadorSuport']);
			for($i=0; $i<$totalOperadorsSuport; $i++)
			{
				$operadorsSuport .= $_POST['operadorSuport'][$i].',';
			}

			$operadorsSuport = substr($operadorsSuport, 0, -1);
		}

		// Si hi ha més d'un sistema els concatenem en un string separat per ','
		if(isset($_POST['sistema']))
		{
			$totalSistemes = count($_POST['sistema']);
			for($i=0; $i<$totalSistemes; $i++)
			{
				$sistemes .= $_POST['sistema'][$i].',';
			}

			$sistemes = substr($sistemes, 0, -1);
		}

		$projecte = new Projecte('', $hdd[0], $operadorProjecte, $sistemes, $nomProjecte, $operadorsSuport, time(), $comentaris, $client, $rutaMaster, 0);

		if(null !== $projecte -> getId())
		{
			$accesLog = new Log('', $user -> getId(), 21, time());
			$accesLog -> saveLog();

			echo "1";
		}
		else
		{
			$accesLog = new Log('', $user -> getId(), 22, time());
			$accesLog -> saveLog();

			echo "0";
		}
		break;

	case 'loadHddBackupModal':
		$hddId 			= $_POST['hddId'];
		$jsonResponse 	= array();
		$hdd 			= new Hdd($hddId);
		$estat 	  		= new TipusEstat($hdd -> getEstat());

		if($hdd)
		{
			$jsonResponse['id'] 			 = $hdd -> getId();
			$jsonResponse['metropolitanaId'] = $hdd -> getMetropolitanaId();
			$jsonResponse['nom'] 			 = $hdd -> getNom();
			$jsonResponse['estat'] 			 = $estat -> getId();

			echo json_encode($jsonResponse);
		}

		break;

	case 'loadProjectHddBackupModal':
		$projectId 		= $_POST['projectId'];
		$jsonResponse 	= array();
		$projecte 		= new Projecte($projectId);
		$client 		= new Client($projecte -> getClientProjecte());
		
		if($projecte)
		{
			$jsonResponse['id'] 			= $projecte -> getId();
			$jsonResponse['hddId'] 			= $projecte -> getHddId();
			$jsonResponse['operador'] 		= $projecte -> getStringOperador();
			$jsonResponse['sistema'] 		= $projecte -> getStringSistema();
			$jsonResponse['projecte'] 		= $projecte -> getProjecte();
			$jsonResponse['suport'] 		= $projecte -> getStringOperadorAuxiliar();
			$jsonResponse['data'] 			= $projecte -> getData();
			$jsonResponse['comentaris'] 	= $projecte -> getComentaris();
			$jsonResponse['clientProjecte'] = $client -> getNom();
			$jsonResponse['rutaMaster'] 	= $projecte -> getRutaMaster();

			echo json_encode($jsonResponse);
		}

		break;
}
