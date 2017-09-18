<?php

class Projecte
{
	private $id;
	private $hddId;
	private $operador;
	private $sistema;
	private $projecte;
	private $suport;
	private $data;
	private $comentaris;
	private $clientProjecte;
	private $rutaMaster;
	private $classificat;

	public function __construct($id=NULL, $hddId=NULL, $operador=NULL, $sistema=NULL, $projecte=NULL, $suport=NULL, $data=NULL, $comentaris=NULL, $clientProjecte=NULL, $rutaMaster=NULL, $classificat=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadProjecte();
		}
		else
		{
			$this -> hddId 			= $hddId;
			$this -> operador 		= $operador;
			$this -> sistema 		= $sistema;
			$this -> projecte 		= $projecte;
			$this -> suport 		= $suport;
			$this -> data 			= $data;
			$this -> comentaris 	= $comentaris;
			$this -> clientProjecte = $clientProjecte;
			$this -> rutaMaster 	= $rutaMaster;
			$this -> classificat 	= $classificat;

			$projcteId = $this -> saveProjecte();
			$this -> id = $projcteId;
		}

		return $this;
	}

// ------------------ GETTERS i SETTERS ----------------------

	public function getId()
	{
		return $this -> id;
	}

	public function setId($id)
	{
		$this -> id = $id;
	}

	public function getHddId()
	{
		return $this -> hddId;
	}

	public function setHddId($hddId)
	{
		$this -> hddId = $hddId;
	}

	public function getOperador()
	{
		return $this -> operador;
	}

	public function setOperador($operador)
	{
		$this -> operador = $operador;
	}

	public function getSistema()
	{
		return $this -> sistema;
	}

	public function setSistema($sistema)
	{
		$this -> sistema = $sistema;
	}

	public function getProjecte()
	{
		return $this -> projecte;
	}

	public function setProjecte($projecte)
	{
		$this -> projecte = $projecte;
	}

	public function getSuport()
	{
		return $this -> suport;
	}

	public function setSuport($suport)
	{
		$this -> suport = $suport;
	}

	public function getData()
	{
		return $this -> data;
	}

	public function setData($data)
	{
		$this -> data = $data;
	}

	public function getComentaris()
	{
		return ucfirst($this -> comentaris);
	}

	public function setComentaris($comentaris)
	{
		$this -> comentaris = $comentaris;
	}

	public function getClientProjecte()
	{
		return $this -> clientProjecte;
	}

	public function setClientProjecte($clientProjecte)
	{
		$this -> clientProjecte = $clientProjecte;
	}

	public function getRutaMaster()
	{
		return $this -> rutaMaster;
	}

	public function setRutaMaster($rutaMaster)
	{
		$this -> rutaMaster = $rutaMaster;
	}

	public function getClassificat()
	{
		return $this -> classificat;
	}

	public function setClassificat($classificat)
	{
		$this -> classificat = $classificat;
	}

// ---------------- FI GETTERS i SETTERS ----------------------

	/******************************************************************
	|
	|@desc: Carrega la info d'un Projecte en l'objecte actual
	|
	******************************************************************/
	public function loadProjecte()
	{
		// Preparem parametre per la query
		$id = $this -> getId();

		// Busquem el projecte intern que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM hdd_backup_projectes WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$projecteObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setHddId($projecteObj -> id);
			$this -> setOperador($projecteObj -> operador);
			$this -> setSistema($projecteObj -> sistema);
			$this -> setProjecte($projecteObj -> projecte);
			$this -> setSuport($projecteObj -> suport);
			$this -> setData($projecteObj -> data);
			$this -> setComentaris($projecteObj -> comentaris);
			$this -> setClientProjecte($projecteObj -> client_projecte);
			$this -> setRutaMaster($projecteObj -> ruta_master);
			$this -> setClassificat($projecteObj -> classificat);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Cerca tots el projectes i passa el id al constructor per carregar tota la info de un projecte en un objecte. Cada objecte que generem el guardem en un array de retorn
	|
	******************************************************************/
	public static function loadProjectes()
	{
		$arrayProjectes = array();
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM hdd_backup_projectes");

		$stmt -> execute();
		$result = $stmt -> get_result();
		
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayProjectes[] = new Projecte($row -> id);
			}
		}

		$stmt -> close();

		return $arrayProjectes;
	}

	/******************************************************************
	|
	|@desc: Cerca tots el projectes classificats i passa el id al constructor per carregar tota la info de un projecte en un objecte. Cada objecte que generem el guardem en un array de retorn
	|
	******************************************************************/
	public static function loadProjectesClassificats()
	{
		$arrayProjectes = array();
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM hdd_backup_projectes WHERE classificat = 1");

		$stmt -> execute();
		$result = $stmt -> get_result();
		
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayProjectes[] = new Projecte($row -> id);
			}
		}

		$stmt -> close();

		return $arrayProjectes;
	}

	/******************************************************************
	|
	|@desc: Cerca tots el projectes classificats i passa el id al constructor per carregar tota la info de un projecte en un objecte. Cada objecte que generem el guardem en un array de retorn
	|
	******************************************************************/
	public static function loadProjectesNoClassificats()
	{
		$arrayProjectes = array();
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM hdd_backup_projectes WHERE classificat = 0");

		$stmt -> execute();
		$result = $stmt -> get_result();
		
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayProjectes[] = new Projecte($row -> id);
			}
		}

		$stmt -> close();

		return $arrayProjectes;
	}

	/******************************************************************
	|
	|@desc: Elimina un projecte a la bbdd
	|
	******************************************************************/
	public function deleteProject()
	{
		// Preparem parametre per la query
		$id = $this -> getId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("DELETE FROM hdd_backup_projectes WHERE id = ?;");
		$stmt -> bind_param("i", $id);
		
		$stmt -> execute();

		if($stmt -> affected_rows)
		{
			$stmt -> close();

			return 1;
		}
		else
		{
			$stmt -> close();

			return 0;
		}
	}

	/******************************************************************
	|
	|@desc: Guarda un nou projecte a la bbdd
	|
	******************************************************************/
	public function saveProjecte()
	{
		// Preparem paràmetres per la query
		$hddId 			= $this -> getHddId();
		$operador 		= $this -> getOperador();
		$sistema 		= $this -> getSistema();
		$projecte 		= $this -> getProjecte();
		$suport 		= $this -> getSuport();
		$comentaris 	= $this -> getComentaris();
		$clientProjecte = $this -> getClientProjecte();
		$rutaMaster 	= $this -> getRutaMaster();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO hdd_backup_projectes (hdd_id, operador, sistema, projecte, suport, data, comentaris, client_projecte, ruta_master, classificat) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, 0);");
		$stmt -> bind_param("isssssis", $hddId, $operador, $sistema, $projecte, $suport, $comentaris, $clientProjecte, $rutaMaster);

		$stmt -> execute();

		if($stmt -> insert_id)
		{
			$insertId = $stmt -> insert_id;
			$stmt -> close();

			return $insertId;
		}
		else
		{
			$stmt -> close();

			return -1;
		}
	}


	/******************************************************************
	| @name: getCurrentHddAndProjectId
	| 
	| @desc: Funció que torna el id de disc dur que toca omplir. Troba l'últim
	| 		id de hdd insertat i l'últim id de projecte i torna el valor+1 que seria el id actual
	|
	|
	******************************************************************/
	public static function getCurrentHddId()
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM hdds ORDER BY id DESC LIMIT 1;");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$hdd = $result -> fetch_object();

			$stmt -> close();


			// Si $hdd = NULL vol dir que no hi ha cap que compleixi. O bé tots estan plens o és el primer disc dur
			if(is_null($hdd))
			{
				$newHdd = new Hdd('', 1, 0, 0);

				return $newHdd -> getId();
			}
			else
			{
				return $hdd -> id;
			}
		}		
	}

	/******************************************************************
	|
	|@desc: Fucnió que recupera el sistema (string) en que s'ha fet un projecte 
	|
	******************************************************************/
	public function getStringSistema()
	{
		$sistemaString = '';

		$stringSistemaIds 	= $this -> getSistema();
		$idsExploded 		= explode(',', $stringSistemaIds);

		foreach ($idsExploded as $index => $sistemaId)
		{
			$sistema = new TipusSistema($sistemaId);
			$sistemaString .= $sistema -> getNom().', ';
		}

		$sistemes = substr($sistemaString, 0, -2);

		return $sistemes; 
	}

	/******************************************************************
	|
	|@desc: Fucnió que recupera l'operador (string) que s'ha fet un projecte 
	|
	******************************************************************/
	public function getStringOperador()
	{
		$operadorsString = '';

		$stringOperadorsIds = $this -> getOperador();
		$idsExploded 		= explode(',', $stringOperadorsIds);

		foreach ($idsExploded as $index => $operadorId)
		{
			$operador = new Operador($operadorId);
			$operadorsString .= $operador -> getNom().', ';
		}

		$operadors = substr($operadorsString, 0, -2);

		return $operadors; 
	}

	/******************************************************************
	|
	|@desc: Fucnió que recupera l'operador auxiliar (string) que s'ha fet un projecte 
	|
	******************************************************************/
	public function getStringOperadorAuxiliar()
	{
		$operadorsString = '';

		$stringOperadorsIds = $this -> getSuport();
		$idsExploded 		= explode(',', $stringOperadorsIds);

		foreach ($idsExploded as $index => $operadorId)
		{
			$operador = new Operador($operadorId);
			$operadorsString .= $operador -> getNom().', ';
		}

		$operadors = substr($operadorsString, 0, -2);

		return $operadors; 
	}
}




