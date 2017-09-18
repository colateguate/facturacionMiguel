<?php

class Localitzacio
{
	private $id;
	private $lloc;
	private $descripció;

	public function __construct($id=NULL, $lloc=NULL, $descripcio=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadLocalitzacio();
		}
		else
		{
			$this -> lloc 		= $lloc;
			$this -> descripcio = $descripcio;

			$idiomaId   = $this -> saveLocalitzacio();
			$this -> id = $idiomaId;
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

	public function getLloc()
	{
		return ucfirst($this -> lloc);
	}

	public function setLloc($lloc)
	{
		$this -> lloc = $lloc;
	}

	public function getDescripcio()
	{
		return ucfirst($this -> descripcio);
	}

	public function setDescripcio($descripcio)
	{
		$this -> descricpio = strtolower($descricpio);
	}
// ---------------- FI GETTERS i SETTERS ----------------------

	/******************************************************************
	|
	|@desc: Carrega la info de un idioma en l'objecte actual
	|
	******************************************************************/
	public function loadLocalitzacio()
	{	
		// Preparem paràmetres per la query
		$id = $this -> getId();

		// Busquem el idoma que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM localitzacions WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$localitzacioObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setLloc($localitzacioObj -> idioma);
			$this -> setDescripcio($localitzacioObj -> descripcio);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els ids de la taula idiomes. Amb cada id recupera un objecte Idioma amb la info y ho guarda en un array de retorn
	|
	******************************************************************/
	public static function loadLocalitzacions()
	{
		$arrayIdiomes = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM localitzacions;");

		$stmt -> execute();
		$result = $stmt -> get_result();
		
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayIdiomes[] = new Localitzacio($row -> id);
			}
		}

		return $arrayIdiomes;
	}

	/******************************************************************
	|
	|@desc: Guarda les dades de un nou idioma
	|
	******************************************************************/
	public function saveLocalitzacio()
	{
		// Preparem paràmetres per la query
		$idioma 	= $this -> getLloc();
		$descripcio = $this -> getDescripcio();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO localitzacions (lloc, descripcio) VALUES (?, ?);");
		$stmt -> bind_param("ss", $idioma, $descripcio);

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







