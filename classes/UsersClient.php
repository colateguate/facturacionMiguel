<?php

class UsersClient
{
	private $id;
	private $nom;
	private $primerCognom;
	private $segonCognom;
	private $email;
	private $telefon;
	private $client;
	private $tipusUsuari;
	private $idioma;
	private $notificacio;

	public function __construct($id=NULL, $nom=NULL, $primerCognom=NULL, $segonCognom=NULL, $email=NULL, $telefon=NULL, $client=NULL, $tipusUsuari=NULL, $idioma=NULL, $notificacio=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadUserClient();
		}
		else
		{
			$this -> nom 		  = $nom;
			$this -> primerCognom = $primerCognom;
			$this -> segonCognom  = $segonCognom;
			$this -> email 		  = $email;
			$this -> telefon 	  = $telefon;
			$this -> client 	  = $client;
			$this -> tipusUsuari  = $tipusUsuari;
			$this -> idioma 	  = $idioma;
			$this -> notificacio  = $notificacio;

			$userClient = $this -> saveUserClient();
			$this -> id = $userClient;
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

	public function getPrimerCognom()
	{
		return $this -> primerCognom;
	}

	public function setPrimerCognom($primerCognom)
	{
		$this -> primerCognom = $primerCognom;
	}

	public function getSegonCognom()
	{
		return $this -> segonCognom;
	}

	public function setSegonCognom($segonCognom)
	{
		$this -> segonCognom = $segonCognom;
	}

	public function getEmail()
	{
		return $this -> email;
	}

	public function setEmail($email)
	{
		$this -> email = $email;
	}

	public function getTelefon()
	{
		return $this -> telefon;
	}

	public function setTelefon($telefon)
	{
		$this -> telefon = $telefon;
	}

	public function getClient()
	{
		return $this -> client;
	}

	public function setClient($client)
	{
		$this -> client = $client;
	}

	public function getTipusUsuari()
	{
		return $this -> tipusUsuari;
	}

	public function setTipusUsuari($tipusUsuari)
	{
		$this -> tipusUsuari = $tipusUsuari;
	}

	public function getIdioma()
	{
		return $this -> idioma;
	}

	public function setIdioma($idioma)
	{
		$this -> idioma = $idioma;
	}

	public function getNotificacio()
	{
		return $this -> notificacio;
	}

	public function setNotificacio($notificacio)
	{
		$this -> notificacio = $notificacio;
	}



// ---------------- FI GETTERS i SETTERS ----------------------

	/******************************************************************
	|
	|@desc: Carrega la info d'un userClient en l'objecte actual
	|
	******************************************************************/
	public function loadUserClient()
	{
		// Preparem paràmetres per la query
		$id = $this -> getId();

		// Busquem el hdd intern que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM users_clients WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$clientObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setNom($clientObj -> nom);
			$this -> setPrimerCognom($clientObj -> primer_cognom);
			$this -> setSegonCognom($clientObj -> segon_cognom);
			$this -> setEmail($clientObj -> email);
			$this -> setTelefon($clientObj -> telefon);
			$this -> setClient($clientObj -> client);
			$this -> setTipusUsuari($clientObj -> tipus_usuari);
			$this -> setIdioma($clientObj -> idioma);
			$this -> setNotificacio($clientObj -> notificacio);
		}

		$stmt -> close();
	}

	/******************************************************************
	|
	|@desc: Carrega tots els ids de usersClients i els recorre. Cada id es passat al constructor per crear un objecte amb la info d'aquest usuerClient. Cada objecte es guardat en un array de retorn
	|
	******************************************************************/
	public static function loadUserClients()
	{
		$arrayClients = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM users_clients;");
		
		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayClients[] = new UsersClient($row -> id);
			}
		}
		
		$stmt -> close();

		return $arrayClients;
	}

	/******************************************************************
	|
	|@desc: Guarda un nou usuari client a la base de dades
	|
	******************************************************************/
	public function saveUserClient()
	{
		// Preparem els paràmetres per la query
		$nom 			= $this -> getNom();
		$primerCognom 	= $this -> getPrimerCognom();
		$segonCognom 	= $this -> segonCognom();
		$email 			= $this -> getEmail();
		$telefon 		= $this -> getTelefon();
		$client 		= $this -> getClient();
		$tipusUsuari 	= $this -> getTipusUsuari();
		$idioma 		= $this -> getIdioma();
		$notificacio 	= $this -> getNotificacio();


		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO users_clients (nom, primer_cognom, segon_cognom, email, telefon, client, tipus_usuari, idioma, notificacio) VALUES ('".$this -> getNom()."', '".$this -> getPrimerCognom()."', '".$this -> getSegonCognom()."', '".$this -> getEmail()."', '".$this -> getTelefon()."', ".$this -> getClient().", ".$this -> getTipusUsuari().", ".$this -> getIdioma().", ".$this -> getNotificacio().");");
		$stmt -> bind_param("sssssiiii", $nom, $primerCognom, $segonCognom, $email, $telefon, $client, $tipusUsuari, $idioma, $notificacio);
		
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
	|@desc: Elimina un usuariClient de la base de dades
	|
	******************************************************************/
	public static function deleteUserClient($userClientId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("DELETE FROM users_clients WHERE id = ?;");
		$stmt -> bind_param("i", $userClientId);

		$stmt -> execute();

		if($stmt -> affected_rows)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	/******************************************************************
	|
	|@desc: Actualitza la informació de un usuari client
	|
	******************************************************************/
	public function updateUserClient()
	{
		// Preparem paràmetres per la query
		$nom 		  = $this -> getNom();
		$primerCognom = $this -> getPrimerCognom();
		$segonCognom  = $this -> getSegonCognom();
		$email 		  = $this -> getEmail();
		$telefon 	  = $this -> getTelefon();
		$client 	  = $this -> getClient();
		$tipusUsuari  = $this -> getTipusUsuari();
		$idioma 	  = $this -> getIdioma();
		$notificacio  = $this -> getNotificacio();
		$id 		  = $this -> getId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("UPDATE users_clients SET nom = ?, primer_cognom = ?, segon_cognom = ?, email = ?, telefon = ?, client = ?, tipus_usuari = ?, idioma = ?, notificacio = ? WHERE id = ?;");
		$stmt -> bind_param("sssssiiiii");

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







