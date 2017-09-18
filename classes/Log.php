<?php

class Log
{
	// ------ FORUM_POST TABLE ------
	private $id;
	private $userId;
	private $logId;
	private $data;
	private $info;

	/******************************************************************
	| @name: __construct
	| 
	| @desc: El constructor carrega un log si li passem un id o genera un
	|		de nou si no li passem cap. 
	|
	|
	******************************************************************/
	public function __construct($id='', $userId=NULL, $logId=NULL, $data=NULL)
	{
		if(isset($id) AND $id != '')
		{
			$this -> id = $id;
			$this -> loadLog();
		}
		else
		{ 
			$this -> user_id = $userId;
			$this -> logId   = $logId;
			$this -> data 	 = $data;
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

	public function getUserId()
	{
		return $this -> userId;
	}

	public function setUserId($userId)
	{
		$this -> userId = $userId;
	}

	public function getLogId()
	{
		return $this -> logId;
	}

	public function setLogId($logId)
	{
		$this -> logId = $logId;
	}

	public function getData()
	{
		return $this -> data;
	}

	public function setData($data)
	{
		$this -> data = $data;
	}

	public function getInfo()
	{
		return $this -> info;
	}

	public function setInfo($status, $info)
	{
		$this -> info['status'] = $status;
		$this -> info['info'] 	= $info;
	}
	// --------------- FI GETTERS i SETTERS -------------------

	/******************************************************************
	| Name: loadLog
	|
	| @desc: Funció que carrega un objecte Log.
	|
	******************************************************************/
	public function loadLog()
	{
		// Preparem paràmetres per la query
		$id = $this -> id;

		// Busquem el log que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM logs WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$logObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setid($logObj -> id);
			$this -> setUserId($logObj -> user_id);
			$this -> setLogId($logObj -> log_id);
			$this -> setData($logObj -> data);

			$logInfo = Log::getLogInfo($logObj -> log_id);
			$this -> setInfo($logInfo -> log_type, $logInfo -> description);
		}

		$stmt -> close();
	}

	/******************************************************************
	| Name: loadLogs
	|
	| @desc: Funció que carrega tots els logs en un array de objectes Log.
	|
	******************************************************************/
	public static function loadLogs()
	{
		$arrayLogs = array();
		$link 	   = connectarDataBase();
		$stmt 	   = $link -> prepare("SELECT * FROM logs;");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($rowLog = $result -> fetch_object())
			{
				$arrayLogs[] = new Log($rowLog -> id);
			}
		}

		$stmt -> close();

		return $arrayLogs;
	}

	/******************************************************************
	| Name: saveLog
	|
	| @desc: Guarda un nou log a la bbdd.
	|
	******************************************************************/
	public function saveLog()
	{
		// Preparem paràmetres per la query
		$userId = $this -> user_id;
		$logId  = $this -> logId;

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO logs (user_id, log_id, data) VALUES (?, ?, NOW())");
		$stmt -> bind_param("ii", $userId, $logId);

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
	| Name: getLogInfo
	|
	| @desc: Funció que carrega la info del log de la taula log a partir del log_id.
	|
	******************************************************************/
	public static function getLogInfo($logId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT log_type, description FROM log WHERE id = ?;");
		$stmt -> bind_param("i", $logId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$logInfo = $result -> fetch_object();
		}

		$stmt -> close();

		return $logInfo;
	}

	/******************************************************************
	| Name: getErrorLogs
	|
	| @desc: Funció que carrega tots els logs de tipus error.
	|
	******************************************************************/
	public static function getErrorLogs()
	{	
		$arrayErrorLogs = array();
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT logs.*, log.log_type, log.description FROM logs INNER JOIN log ON logs.log_id = log.id WHERE log.log_type = 'error' ORDER BY logs.data DESC");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayErrorLogs[] = new Log($row -> id);
			}
		}

		$stmt -> close();

		return $arrayErrorLogs;
	}

	/******************************************************************
	| Name: getAccessLog
	|
	| @desc: Funció que carrega tots els logs de tipus access.
	|
	******************************************************************/
	public static function getAccessLogs()
	{
		$arrayAccessLogs = array();
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT logs.*, log.log_type, log.description FROM logs INNER JOIN log ON logs.log_id = log.id WHERE log.log_type = 'error' ORDER BY logs.data DESC");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayAccessLogs[] = new Log($row -> id);
			}
		}

		$stmt -> close();

		return $arrayAccessLogs;
	}

	/******************************************************************
	| Name: getCustomLog
	|
	| @desc: Funció que carrega tots els logs de tipus accions.
	|
	******************************************************************/
	public static function getAccionsLogs()
	{
		$arrayCustomLogs = array();
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT logs.*, log.log_type, log.description FROM logs INNER JOIN log ON logs.log_id = log.id WHERE log.log_type = 'error' ORDER BY logs.data DESC");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayAccessLogs[] = new Log($row -> id);
			}
		}

		$stmt -> close();

		return $arrayAccessLogs;
	}
}