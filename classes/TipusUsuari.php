<?php

class TipusUsuari
{
	private $id;
	private $descripcio;

	public function __construct($id=NULL, $descripcio=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadTipusUsuari();
		}
		else
		{
			$this -> descripcio = $descripcio;

			$tipusUsuariId = $this -> saveTipusUsuari();
			$this -> id    = $tipusUsuariId;
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

	public function getDescripcio()
	{
		return $this -> descripcio;
	}

	public function setDescripcio($descripcio)
	{
		$this -> descripcio = $descripcio;
	}

// ---------------- FI GETTERS i SETTERS ----------------------

	/******************************************************************
	|
	|@desc: Carrega la info d'un tipusUsuari en l'objecte actual
	|
	******************************************************************/
	public function loadTipusUsuari()
	{
		// Preparem el paràmetre per la query
		$id = $this -> getId();

		// Busquem el tipus usuari que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM tipus_usuari WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$tipusUsuariObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setDescripcio($tipusUsuariObj -> descripcio);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els id's de tipus usuari i es passa al constructor per carrega la info de un tipus usuari en un objecte. Cada objecte és guardat en un array de retorn
	|
	******************************************************************/
	public static function loadTipusUsuaris()
	{
		$arrayTipusUsuari = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM tipus_usuari;");
		
		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayTipusUsuari[] = new TipusUsuari($row -> id);
			}
		}

		$stmt -> close();

		return $arrayTipusUsuari;
	}

	/******************************************************************
	|
	|@desc: Guarda un nou tipus usuari a la base de dades
	|
	******************************************************************/
	public function saveTipusUsuari()
	{
		// Preparem parametres per la query
		$descripcio = $this -> getDescripcio();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO tipus_usuari (descripcio) VALUES (?);");
		$stmt -> bind_param("s", $descripcio);

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







