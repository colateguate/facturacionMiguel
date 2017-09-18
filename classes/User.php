<?php

class User
{
	private $login;
	private $password;
	private $uid;
	private $id;
	private $gid;
	private $dir;
	private $quotaFiles;
	private $quotaSize;
	private $ulRatio;
	private $dlRatio;
	private $ulBandWidth;
	private $dlBandWidth;
	private $status;
	private $descripcio;

	/******************************************************************
	| @name: __construct
	| @param: $username -> nom de l'usuari que volem obtenir
	| @desc: El constructor busca l'usuari que li pasem i després el carrega
	|		com a objecte i el retorna.
	|
	|
	******************************************************************/
	public function __construct($username)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM users WHERE Login = ?;");
		$stmt -> bind_param("s", $username);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$u 	  = $result -> fetch_object();
			$user = $this -> loadUser($u);
		}

		$stmt -> close();

		if($user)
			return $this;
		else
			return false;
	}


	// ------------------ GETTERS i SETTERS ----------------------
	public function getLogin()
	{
		return $this -> login;
	}

	public function setLogin($login)
	{
		$this -> login = $login;
	}

	public function getPassword()
	{
		return $this -> password;
	}

	public function setPassword($password)
	{
		$this -> password = $password;
	}

	public function getUid()
	{
		return $this -> uid;
	}

	public function setUid($uid)
	{
		$this -> uid = $uid;
	}

	public function getId()
	{
		return $this -> id;
	}

	public function setId($id)
	{
		$this -> id = $id;
	}

	public function getGid()
	{
		return $this -> gid;
	}

	public function setGid($gid)
	{
		$this -> gid = $gid;
	}

	public function getDir()
	{
		return $this -> dir;
	}

	public function setDir($dir)
	{
		$this -> dir = $dir;
	}

	public function getQuotaFiles()
	{
		return $this -> quotaFiles;
	}

	public function setQuotaFiles($quotaFiles)
	{
		$this -> quotaFiles = $quotaFiles;
	}

	public function getQuotaSize()
	{
		return $this -> quotaSize;
	}

	public function setQuotaSize($quotaSize)
	{
		$this -> quotaSize = $quotaSize;
	}

	public function getUlRatio()
	{
		return $this -> ulRatio;
	}

	public function setUlRatio($ulRatio)
	{
		$this -> ulRatio = $ulRatio;
	}

	public function getDlRatio()
	{
		return $this -> dlRatio;
	}

	public function setDlRatio($dlRatio)
	{
		$this -> dlRatio = $dlRatio;
	}

	public function getUlBandWidth()
	{
		return $this -> ulBandWidth;
	}

	public function setUlBandWidth($ulBandWidth)
	{
		$this -> ulBandWidth = $ulBandWidth;
	}

	public function getDlBandWidth()
	{
		return $this -> getDlBandWidth;
	}

	public function setDlBandWidth($dlBandWidth)
	{
		$this -> dlBandWidth = $dlBandWidth;
	}

	public function getStatus()
	{
		return $this -> status;
	}

	public function setStatus($status)
	{
		$this -> status = $status;
	}

	public function getDescripcio()
	{
		return $this -> descripcio;
	}

	public function setDescripcio($descripcio)
	{
		$this -> descripcio = $descripcio;
	}

	// ---------------- FI GETTERS i SETTERS ---------------------



	/******************************************************************
	| Name: loadUser
	| @param: $user -> obj Mysqli que conté la info del usuari loggat
	| 
	| @desc: Funció que crrega un nou usuari a la plataforma.
	|
	******************************************************************/
	protected function loadUser($user)
	{
		$this -> login 			= $user -> Login;
		$this -> password 		= $user -> Password;
		$this -> uid 			= $user -> Uid;
		$this -> id 			= $user -> id;
		$this -> gid 			= $user -> Gid;
		$this -> dir 			= $user -> Dir;
		$this -> quotaFiles 	= $user -> QuotaFiles;
		$this -> quotaSize 		= $user -> QuotaSize;
		$this -> ulRatio 		= $user -> ULRatio;
		$this -> dlRatio 		= $user -> DLRatio;
		$this -> ulBandWidth 	= $user -> ULBandwidth;
		$this -> dlBandWidth 	= $user -> DLBandwidth;
		$this -> status 		= $user -> status;
		$this -> descripcio 	= $user -> descripcio; 

		return true;
	}

	/******************************************************************
	| Name: createUser
	| @param: $username -> nom del nou usuari
	| @param: $password -> password del nou usuari
	| @param: $uid -> 
	| @param: $gid -> 
	| @param: $dir -> 
	| @param: $quotaFiles -> 
	| @param: $quotaSize -> 
	| @param: $ulRatio -> 
	| @param: $dlRatio -> 
	| @param: $ulBandWidth -> 
	| @param: $dlBandWidth -> 
	| @param: $status -> 
	| @param: $descripcio -> 
	| 
	| @desc: Funció estàtica que crea un nou usuari a la base de dades només.
	|			NO EL CREA AL SERVIDOR LDAP!
	|
	******************************************************************/
	public static function createUser($username, $password, $uid, $id, $gid, $dir, $quotaFiles, $quotaSize, $ulRatio, $dlRatio, $ulBandWidth, $dlBandWidth, $status, $descripcio)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO users (Login, Password, Uid, id, Gid, Dir, QuotaFiles, QuotaSize, ULRatio, DLRatio, ULBandwidth, DLBandwidth, status, descripcio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
		$stmt -> bind_param("ssiiisiiiiiiis", $username, $password, $uid, $id, $gid, $dir, $quotaFiles, $quotaSize, $ulRatio, $dlRatio, $ulBandWidth, $dlBandWidth, $status, $descripcio);

		$stmt -> execute();
		
		if($stmt -> insert_id)
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
	| Name: getUserName
	| @param: $userId -> id de l'usuari del que volem saber el nom
	| 
	| @desc: Funció estàtica que retorna el nom d'un usuari.
	|
	******************************************************************/
	public static function getUserName($userId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT Login FROM users WHERE id = ?;");
		$stmt -> bind_param("i", $userId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$userName = $result -> fetch_object();
		}
		
		$stmt -> close();

		return $userName -> Login;
	}
}