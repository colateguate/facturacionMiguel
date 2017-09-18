<?php

class Idioma
{
	private $id;
	private $idioma;

	public function __construct($id=NULL, $idioma=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadIdioma();
		}
		else
		{
			$this -> idoma = $idioma;

			$idiomaId   = $this -> saveIdioma();
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

	public function getIdioma()
	{
		return $this -> idoma;
	}

	public function setIdioma($idioma)
	{
		$this -> idoma = $idioma;
	}

// ---------------- FI GETTERS i SETTERS ----------------------

	/******************************************************************
	|
	|@desc: Carrega la info de un idioma en l'objecte actual
	|
	******************************************************************/
	public function loadIdioma()
	{	
		// Preparem paràmetres per la query
		$id = $this -> getId();

		// Busquem el idoma que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM idioma WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$idiomaObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setIdioma($idiomaObj -> idioma);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els ids de la taula idiomes. Amb cada id recupera un objecte Idioma amb la info y ho guarda en un array de retorn
	|
	******************************************************************/
	public static function loadIdiomas()
	{
		$arrayIdiomes = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM idioma;");

		$stmt -> execute();
		$result = $stmt -> get_result();
		
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayIdiomes[] = new Idioma($row -> id);
			}
		}

		return $arrayIdiomes;
	}

	/******************************************************************
	|
	|@desc: Guarda les dades de un nou idioma
	|
	******************************************************************/
	public function saveIdioma()
	{
		// Preparem paràmetres per la query
		$idioma = $this -> getIdioma();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO idioma (idoma) VALUES (?);");
		$stmt -> bind_param("s", $idioma);

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







