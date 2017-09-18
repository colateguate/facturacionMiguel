<?php

class TipusSistema
{
	private $id;
	private $nom;
	private $descripcio;

	public function __construct($id=NULL, $nom=NULL, $descripcio=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadTipusSistema();
		}
		else
		{
			$this -> nom 		= $nom;
			$this -> descripcio = $descripcio;

			$hddId = $this -> saveHdd();
			$this -> id = $hddId;
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

	public function getNom()
	{
		return ucfirst($this -> nom);
	}

	public function setNom($nom)
	{
		$this -> nom = $nom;
	}

	public function getDescripcio()
	{
		return ucfirst($this -> descripcio);
	}

	public function setDescripcio($descripcio)
	{
		$this -> descripcio = $descripcio;
	}

	public function getEstat()
	{
		return $this -> estat;
	}

	public function setEstat($estat)
	{
		$this -> estat = $estat;
	}	

// ---------------- FI GETTERS i SETTERS ----------------------

	/******************************************************************
	|
	|@desc: Carrega la info d'un tipus de sistema en l'objecte actual
	|
	******************************************************************/
	public function loadTipusSistema()
	{
		// Preparem paràmetres per la query
		$id = $this -> getId();

		// Busquem el tipus d'esat que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM tipus_sistema WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$tipusSistemaObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setNom($tipusSistemaObj -> nom);
			$this -> setDescripcio($tipusSistemaObj -> descripcio);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els tipus d'estat en un array d'opbjectes.
	|
	******************************************************************/
	public static function loadTipusSistemes()
	{
		$arrayTipusSistema = array();
		$link 		   	   = connectarDataBase();
		$stmt 			   = $link -> prepare("SELECT id FROM tipus_sistema;");
		
		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($loadedTipusSistema = $result -> fetch_object())
			{
				$arrayTipusSistema[] = new TipusSistema($loadedTipusSistema -> id);
			}
		}

		$stmt -> close();

		return $arrayTipusSistema; 
	}

	/******************************************************************
	|
	|@desc: Guarda un nou tipu d'estat a base de dades
	|
	******************************************************************/
	public function saveHdd()
	{
		// Preparem paràmetres per la query
		$nom 		= $this -> getNom();
		$descripcio = $this -> getDescripcio();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO tipus_sistema (nom, descripcio) VALUES (?, ?);");
		$stmt -> bind_param("ss", $nom, $descripcio);

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
}







