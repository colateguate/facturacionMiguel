<?php

class Operador
{
	private $id;
	private $nom;
	private $tipusOperador;
	private $userId;

	public function __construct($id=NULL, $nom=NULL, $tipusOperador=NULL, $userId=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadOperador();
		}
		else
		{
			$this -> nom 			= $nom;
			$this -> tipusOperador 	= $tipusOperador;
			$this -> userId 		= $userId;

			$operadorId 	= $this -> saveOperador();
			$this -> id    	= $operadorId;
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

	public function getTipusOperador()
	{
		return $this -> tipusOperador;
	}

	public function setTipusOperador($tipusOperador)
	{
		$this -> tipusOperador = $tipusOperador;
	}

	public function getUserId()
	{
		return $this -> userId;
	}

	public function setUserId($userId)
	{
		$this -> userId = $userId;
	}

// ---------------- FI GETTERS i SETTERS ----------------------

	/******************************************************************
	|
	|@desc: Carrega la info d'un operador en l'objecte actual
	|
	******************************************************************/
	public function loadOperador()
	{
		// Preparem paràmetres per la query
		$id = $this -> getId();

		// Busquem el tipus usuari que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM operadors WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$operadorObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setNom($operadorObj -> nom);
			$this -> setTipusOperador($operadorObj -> tipus_operador);
			$this -> setUserId($operadorObj -> user_id);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els id's d'operadors i es passa al constructor per carrega la info de un operador en un objecte. Cada objecte és guardat en un array de retorn
	|
	******************************************************************/
	public static function loadOperadors()
	{
		$arrayOperadors = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM operadors ORDER BY tipus_operador DESC;");
		
		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayOperadors[] = new Operador($row -> id);
			}
		}

		return $arrayOperadors;
	}

	/******************************************************************
	|
	|@desc: Guarda un nou tipus operador a la base de dades
	|
	******************************************************************/
	public function saveOperador()
	{
		// Preparem els paràmetres per la query
		$nom 			= $this -> getNom();
		$tipusOperador  = $this -> getTipusOperador();
		$userId 		= $this -> getUserId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO operadors (nom, tipus_operador, user_id) VALUES (?, ?, ?);");
		$stmt -> bind_param("ssi", $nom, $tipusOperador, $userId);

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







