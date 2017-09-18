<?php

class Client
{
	private $id;
	private $nom;
	private $adreca;

	public function __construct($id=NULL, $nom=NULL, $adreca=NULL)
	{
		// Si tenim id carreguem el client que ens demanen sino en creem un de nou amb les dades que ens arriben
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadClient();
		}
		else
		{
			$this -> nom 	= $nom;
			$this -> adreca = $adreca;

			$hddId = $this -> saveClient();
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
		return $this -> nom;
	}

	public function setNom($nom)
	{
		$this -> nom = $nom;
	}

	public function getAdreca()
	{
		return $this -> adreca;
	}

	public function setAdreca($adreca)
	{
		$this -> adreca = $adreca;
	}

// ---------------- FI GETTERS i SETTERS ----------------------

	/*
		@desc: Carrega la info d'un client en l'objecte actual
	*/
	public function loadClient()
	{
		$idSearch  = $this -> getId();

		// Busquem el hdd intern que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM clients WHERE id = ?;");
		$stmt -> bind_param("i", $idSearch);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$row = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setNom($row -> nom);
			$this -> setAdreca($row -> adreca);
		}
		
		$stmt -> close();
	}

	/*
		@desc: Recupera tots els ids de la taula clients i amb l'id construeix els objectes en un array
	*/
	public static function loadClients()
	{
		$arrayClients = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM clients;");
		
		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayClients[] = new Client($row -> id);
			}
		}

		$stmt -> close();

		return $arrayClients;
	}

	/*
		@desc: Guarda un nou client a la base de dades
	*/
	public function saveClient()
	{
		$link = connectarDataBase();

		// Preparem parametres per la query
		$nom 	= $this -> getNom();
		$adreca = $this -> getAdreca();

		$stmt = $link -> prepare("INSERT INTO clients (nom, adreca) VALUES (?, ?);");
		$stmt -> bind_param("ss", $nom, $adreca);

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

	/*
		@desc: Elimina un client a la base de dades
		@param: clientId -> id del client que volem eliminar
	*/
	public static function deleteClient($clientId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("DELETE FROM clients WHERE id = ?;");
		$stmt -> bind_param("i", $clientId);

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

	/*
		@desc: Actualitza un client de la base de dades
	*/
	public function updateClient()
	{
		$link = connectarDataBase();

		//Preparem paràmetres per la query
		$nom 	= $this -> getNom();
		$adreca = $this -> getAdreca();
		$id 	= $this -> getId();

		$stmt = $link -> prepare("UPDATE clients SET nom = ?, adreca = ? WHERE id = ?;");
		$stmt -> bind_param("ssi", $nom, $adreca, $id);

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
}







