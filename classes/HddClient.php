<?php

class HddClient
{
	private $id;
	private $client;
	private $descripcio;
	private $projecteDepartament;
	private $localitzacio;
	private $dataInsercio;

	private $estat;
	private $dataRetorn;

	public function __construct($id=NULL, $client=NULL, $descripcio=NULL, $projecteDepartament=NULL, $localitzacio=NULL, $dataInsercio=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadHdd();
		}
		else
		{
			$this -> client 			 = $client;
			$this -> descripcio 		 = $descripcio;
			$this -> projecteDepartament = $projecteDepartament;
			$this -> localitzacio 		 = $localitzacio;
			$this -> dataInsercio 		 = $dataInsercio;

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

	public function getClient()
	{
		return $this -> client;
	}

	public function setClient($client)
	{
		$this -> client = $client;
	}

	public function getDescripcio()
	{
		return $this -> descripcio;
	}

	public function setDescripcio($descripcio)
	{
		$this -> descripcio = $descripcio;
	}

	public function getProjecteDepartament()
	{
		return $this -> projecteDepartament;
	}

	public function setProjecteDepartament($projecteDepartament)
	{
		$this -> projecteDepartament = $projecteDepartament;
	}

	public function getLocalitzacio()
	{
		return $this -> localitzacio;
	}

	public function setLocalitzacio($localitzacio)
	{
		$this -> localitzacio = $localitzacio;
	}

	public function getDataInsercio()
	{
		return $this -> dataInsercio;
	}

	public function setDataInsercio($dataInsercio)
	{
		$this -> dataInsercio = $dataInsercio;
	}

	public function getEstat()
	{
		return $this -> estat;
	}

	public function setEstat($estat)
	{
		$this -> estat = $estat;
	}

	public function getDataRetorn()
	{
		return $this -> dataRetorn;
	}

	public function setDataRetorn($dataRetorn)
	{
		$this -> dataRetorn = $dataRetorn;
	}

// ---------------- FI GETTERS i SETTERS ----------------------

	/**
		@name: loadHdd
		@desc: Carrega en l'objecte que tenim les dades de un disc dur
	*/
	public function loadHdd()
	{
		// Preparem un paràmetre per la query
		$id = $this -> getId();

		// Busquem el hdd intern que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM hdds_clients WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$hddClientObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setClient($hddClientObj -> client);
			$this -> setDescripcio($hddClientObj -> descripcio);
			$this -> setProjecteDepartament($hddClientObj -> projecte_departament);
			$this -> setLocalitzacio($hddClientObj -> localitzacio);
			$this -> setDataInsercio($hddClientObj -> data_insercio);
			$this -> setEstat($hddClientObj -> estat);
			$this -> setDataRetorn($hddClientObj -> data_retorn);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els ids de la taula hdds_clients i recorrent aquest id's obte els objectes i els guarda en un array de retorn
	|
	******************************************************************/
	public static function loadHdds()
	{
		$arrayHdds = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM hdds_clients");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayHdds[] = new HddClient($row -> id);
			}

		}
		
		$stmt -> close();

		return $arrayHdds;
	}

	/******************************************************************
	|
	|@desc: Guarda un nou hdd de client en base de dades
	|
	******************************************************************/
	public function saveHdd()
	{
		// Preparem els parametres per a la query
		$client 			 = $this -> getClient();
		$descripcio 		 = $this -> getDescripcio();
		$projecteDepartament = $this -> getProjecteDepartament();
		$localitzacio 		 = $this -> getLocalitzacio();


		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO hdds_clients (client, descripcio, projecte_departament, localitzacio, data_insercio) VALUES (?, ?, ?, ?, NOW());");
		$stmt -> bind_param("isss", $client, $descripcio, $projecteDepartament, $localitzacio);

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
	|@desc: Elimina un disc dur de client de la base de dades
	|@param: hddId -> id del hdd de client que volem eliminar
	|
	******************************************************************/
	public static function deleteHddClient($hddId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("DELETE FROM hdds_clients WHERE id = ?;");
		$stmt -> bind_param("i", $hddId);

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
	|@desc: Actualitza l'objecte en bbdd
	|
	******************************************************************/
	public function updateHddClient()
	{
		// Preparem els paràmetres per la query
		$client 			 = $this -> getClient();
		$descripcio 		 = $this -> getDescripcio();
		$projecteDepartament = $this -> getProjecteDepartament();
		$localitzacio 		 = $this -> getLocalitzacio();
		$dataInsercio 		 = $this -> getDataInsercio();
		$estat 				 = $this -> getEstat();
		$dataRetorn 		 = $this -> getDataRetorn();
		$id 				 = $this -> getId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("UPDATE hdds_clients SET client = ?, descripcio = ?, projecte_departament = ?, localitzacio = ?, data_insercio = ?, estat = ?, data_retorn = ? WHERE id = ?;");
		$stmt -> bind_param("issssisi", $client, $descripcio, $projecteDepartament, $localitzacio, $dataInsercio, $estat, $dataRetorn, $id);

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







