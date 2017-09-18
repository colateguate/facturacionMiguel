<?php

class TipusEstat
{
	private $id;
	private $nom;
	private $descripcio;

	public function __construct($id=NULL, $nom=NULL, $descripcio=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadTipusEstat();
		}
		else
		{
			$this -> nom = $nom;
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
	|@desc: Carrega la info d'un tipus d'estat en l'objecte actual
	|
	******************************************************************/
	public function loadTipusEstat()
	{
		// Preparem paràmetre per la query
		$id = $this -> getId();

		// Busquem el tipus d'esat que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM tipus_estats WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$tipusEstatObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setNom($tipusEstatObj -> nom);
			$this -> setDescripcio($tipusEstatObj -> descripcio);

		}
			
		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els tipus d'estat en un array d'opbjectes.
	|
	******************************************************************/
	public static function loadTipusEsats()
	{
		$arrayTipusEstat = array();
		$link 		   	 = connectarDataBase();
		$stmt 			 = $link -> prepare("SELECT id FROM tipus_estats;");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($loadedTipusEstat = $result -> fetch_object())
			{
				$arrayTipusEstat[] = new TipusEstat($loadedTipusEstat -> id);
			}
		}

		$stmt -> close();

		return $arrayTipusEstat; 
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
		$stmt = $link -> prepare("INSERT INTO tipus_estats (nom, descripcio) VALUES (?, ?);");
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







