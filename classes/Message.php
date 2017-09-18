<?php

class Message
{
	private $id;
	private $writtenBy;
	private $receiver;
	private $message;
	private $data;
	private $saw;

	/******************************************************************
	| @name: __construct
	| @param: $username -> nom de l'usuari que volem obtenir
	| @desc: El constructor busca l'usuari que li pasem i després el carrega
	|		com a objecte i el retorna.
	|
	|
	******************************************************************/
	public function __construct($messageId='', $writtenBy=NULL, $receiver=NULL, $message=NULL, $data=NULL, $saw=NULL)
	{
		if(isset($messageId) AND $messageId != '')
		{
			$this -> id = $messageId;
			$this -> loadMessage();
		}
		else
		{
			$this -> writtenBy = $writtenBy;
			$this -> receiver  = $receiver;
			$this -> message   = $message;
			$this -> data 	   = time();
			$this -> saw 	   = $saw;


			$messageId = $this -> saveMessage();
			$this -> id = $messageId;
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

	public function getWrittenBy()
	{
		return $this -> writtenBy;
	}

	public function setWrittenBy($writtenBy)
	{
		$this -> writtenBy = $writtenBy;
	}

	public function getReceiver()
	{
		return $this -> receiver;
	}

	public function setReceiver($receiver)
	{
		$this -> receiver = $receiver;
	}

	public function getMessage()
	{
		return $this -> message;
	}

	public function setMessage($message)
	{
		$this -> message = $message;
	}

	public function getData()
	{
		return $this -> data;
	}

	public function setData($data)
	{
		$this -> data = $data;
	}

	public function getSaw()
	{
		return $this -> saw;
	}

	public function setSaw($saw)
	{
		$this -> saw = $saw;
	}
	
	// ---------------- FI GETTERS i SETTERS ----------------------

	/**
	| Name: saveMessage
	| 
	| @desc: Funció que guarda un nou missatge intern a la base de dades. Retorna l'id
	| 		en cas d'èxit, -1 si falla.
	|
	*/
	public function saveMessage()
	{
		// Preparem els paràmetres
		$writtenBy 	= $this -> getWrittenBy();
		$receiver 	= $this -> getReceiver();
		$message 	= $this -> getMessage();
		$saw 		= $this -> getSaw();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO internal_messages (written_by, receiver, message, data, saw) VALUES (?, ?, ?, NOW(), ?);");
		$stmt -> bind_param("iisi", $writtenBy, $receiver, $message, $saw);

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

	/**
	| Name: loadMessage
	| 
	| @desc: Funció que carrega un nou missatge intern
	|
	*/
	public function loadMessage()
	{
		// Preparem paràmetres per la query
		$id = $this -> getId();

		// Busquem el missatge intern que volem
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM internal_messages WHERE id = ?;");
		$stmt -> bind_param("i", $id);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$messageObj = $result -> fetch_object(); 

			// Assignem les dades de la bd a l'objecte.
			$this -> setWrittenBy($messageObj -> written_by);
			$this -> setReceiver($messageObj -> receiver);
			$this -> setMessage($messageObj -> message);
			$this -> setData($messageObj -> data);
			$this -> setSaw($messageObj -> saw);
		}
		
		$stmt -> close();
	}

	public function getWrittenByInfo()
	{
		// Preparem els paràmetres per la query
		$writtenBy = $this -> getWrittenBy();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT login FROM users WHERE id = ?;");
		$stmt -> bind_param("i", $writtenBy);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$writtenByInfo = $result -> fetch_object();
		}

		$stmt -> close();
		$stmt -> close();

		return $writtenByInfo -> login;
	}

	/**
	| Name: loadUserMessages
	| 
	| @desc: Funció que carrega els missatges d'un usuari en concret en un array amb objectes Message
	|
	*/
	public static function loadUserMessages($userId)
	{
		$arrayMessages = array();
		$link 		   = connectarDataBase();
		$stmt 		   = $link -> prepare("SELECT * FROM internal_messages WHERE receiver = ? ORDER BY data DESC LIMIT 10;");
		$stmt -> bind_param("i", $userId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($rowMessage = $result -> fetch_object())
			{
				$arrayMessages[] = new Message($rowMessage -> id);
			}

		}

		$stmt -> close();

		return $arrayMessages;
	}

	/**
	| Name: getUnreadedMessages
	| 
	| @desc: Funció que retorna el núm de missatges no llegits per a un usuari
	|
	*/
	public static function getUnreadedMessages($userId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT COUNT(*) as unreadedMessages FROM internal_messages WHERE saw = 0 AND receiver = ? ORDER BY data DESC;");
		$stmt -> bind_param("i", $userId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			$numUnreadedMessages = $result -> fetch_object();	
		}

		$stmt -> close();

		return $numUnreadedMessages -> unreadedMessages;
	}

}