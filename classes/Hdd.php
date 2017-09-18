<?php

class Hdd
{
	private $id;
	private $metropolitanaId;
	private $nom;
	private $estat;

	public function __construct($id=NULL, $metropolitanaId=NULL, $nom=NULL, $estat=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadHdd();
		}
		else
		{
			$this -> metropolitanaId = $metropolitanaId;
			$this -> nom 			 = $nom;
			$this -> estat 			 = $estat;

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

	public function getMetropolitanaId()
	{
		return $this -> metropolitanaId;
	}

	public function setMetropolitanaId($metropolitanaId)
	{
		$this -> metropolitanaId = $metropolitanaId;
	}

	public function getNom()
	{
		return $this -> nom;
	}

	public function setNom($nom)
	{
		$this -> nom = $nom;
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
	|@desc: Carrega la info d'un hdd en l'objecte actual
	|
	******************************************************************/
	public function loadHdd()
	{
		// Preparem parametres per la query
		$id = $this -> getId();

		// Busquem el hdd intern que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM hdds WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$hddObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setMetropolitanaId($hddObj -> metropolitana_id);
			$this -> setNom($hddObj -> nom);
			$this -> setEstat($hddObj -> estat);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els discs durs de backup.
	|
	******************************************************************/
	public static function loadHdds()
	{
		$arrayHdds 	   = array();
		
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM hdds;");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($loadedHdd = $result -> fetch_object())
			{
				$arrayHdds[] = new Hdd($loadedHdd -> id);
			}
		}

		$stmt -> close();

		return $arrayHdds; 
	}

	/******************************************************************
	|
	|@desc: Guarda un nou disc dur a base de dades
	|
	******************************************************************/
	public function saveHdd()
	{
		// Preparem variables per la query
		$metropolitanaId = $this -> getMetropolitanaId();
		$nom 			 = $this -> getNom();
		$estat 			 = $this -> getEstat();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO hdds (metropolitana_id, nom, estat) VALUES (?, ?, ?);");
		$stmt -> bind_param("ssi", $metropolitanaId, $nom, $estat);

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
	|
	|@desc: Elimina un nou disc dur a base de dades
	|
	******************************************************************/
	public function deleteHdd()
	{
		// Preparem paràmetres per la query
		$id = $this -> getId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("DELETE FROM hdds WHERE id = ?;");
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
	|@desc: Actualitza un disc dur de backup
	|
	******************************************************************/
	public function updateHdd()
	{
		// Preparem paràmetres per la query
		$metropolitanaId = $this -> getMetropolitanaId();
		$nom 			 = $this -> getNom();
		$estat 			 = $this -> getEstat();
		$id 			 = $this -> getId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("UPDATE hdds SET metropolitana_id = ?, nom = ?, estat = ? WHERE id = ?;");
		$stmt -> bind_param("ssii");

		$stmt -> execute();

		if($stmt -> affected_rows)
		{
			return 1;
		}
		else
		{
			return -1;
		}
	}

}







