<?php

class ForumThreat
{
	private $threatId;
	private $categoryId;
	private $title;
	private $threatPostContent;
	private $userId;
	private $threatDate;
	private $closed;
	private $groupOnly;
	private $groupId;
	private $posts;

	public function __construct($threatId = '', $categoryId = NULL, $title = NULL, $threatPostContent = NULL, $userId = NULL, $threatDate = NULL, $closed=0, $groupOnly = NULL, $groupId = NULL)
	{
		if(isset($threatId) AND $threatId != '')
		{
			$this -> threatId = $threatId;
			$this -> loadThreat();
		}
		else
		{
			$this -> categoryId 		= $categoryId;
			$this -> title 				= $title;
			$this -> threatPostContent 	= $threatPostContent;
			$this -> userId 			= $userId;
			$this -> threatDate 		= $threatDate;
			$this -> closed 			= $closed;
			$this -> groupOnly 			= $groupOnly;
			$this -> groupId 			= $groupId;

			$threatId = $this -> saveThreat();
			$this -> threatId = $threatId;
		}
	}

	// ------------------ GETTERS i SETTERS ----------------------

	public function getThreatId()
	{
		return $this -> threatId;
	}

	public function setThreatId($threatId)
	{
		$this -> threatId = $threatId;
	}

	public function getCategoryId()
	{
		return $this -> categoryId;
	}

	public function setCategoryId($categoryId)
	{
		$this -> categoryId = $categoryId;
	}

	public function getTitle()
	{
		return $this -> title;
	}

	public function setTitle($title)
	{
		$this -> title = $title;
	}

	public function getThreatPostContent()
	{
		return $this -> threatPostContent;
	}

	public function setThreatPostContent($threatPostContent)
	{
		$this -> threatPostContent = $threatPostContent;
	}

	public function getThreatDate()
	{
		return $this -> threatDate;
	}

	public function setThreatDate($threatDate)
	{
		$this -> threatDate = $threatDate;
	}

	public function getClosed()
	{
		return $this -> closed;
	}

	public function setClosed($closed)
	{
		$this -> closed = $closed;
	}

	public function getGroupOnly()
	{
		return $this -> groupOnly;
	}

	public function setGroupOnly($groupOnly)
	{
		$this -> groupOnly = $groupOnly;
	}

	public function getGroupId()
	{
		return $this -> groupId;
	}

	public function setGroupId($groupId)
	{
		$this -> groupId = $groupId;
	}

	public function getUserId()
	{
		return $this -> userId;
	}

	public function setUserId($userId)
	{
		$this -> userId = $userId;
	}

	public function getPosts()
	{
		return $this -> posts;
	}

	public function setPosts($posts)
	{
		$this -> posts = $posts;
	}

	// --------------- FI GETTERS i SETTERS -------------------

	/******************************************************************
	| Name: getThreadStaticInfo
	|
	| @param: $threadId -> id del thread del que volem saber la informació
	| 
	| @desc: Funció que carrega les dades bàsiques d'un thread de forma estàtica
	|
	******************************************************************/
	public static function getThreadStaticInfo($threadId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM forum_threats WHERE id = ?;");
		$stmt -> bind_param("i", $threadId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$infoThread = $result -> fetch_object();
		}

		$stmt -> close();

		return $infoThread;
	}

	/******************************************************************
	| Name: loadThreat
	| 
	| @desc: Funció que carrega a l'objecte les dades d'un threat concret
	|
	******************************************************************/
	public function loadThreat()
	{
		// Preparem paràmetres per a la query
		$threadId = $this -> threatId;
		// Busquem el threat que volem
		$link 			 = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM forum_threats WHERE id = ?;");
		$stmt -> bind_param("i", $threadId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$threadObj = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setThreatId($threadObj -> id);
			$this -> setCategoryId($threadObj -> category_id);
			$this -> setTitle($threadObj -> title);
			$this -> setThreatPostContent($threadObj -> threat_post_content);
			$this -> setUserId($threadObj -> user_id);
			$this -> setThreatDate($threadObj -> data);
			$this -> setClosed($threadObj -> closed);
			$this -> setGroupOnly($threadObj -> group_only);
			$this -> setGroupId($threadObj -> group_id);
		}

		$stmt -> close();
	}

	/******************************************************************
	| Name: loadThreats
	| @param: $categoryId -> id de la categoria que volem carregar els threats
	| 
	| @desc: Funció que rep un id de categoria com a paràmetre i busca tots els
	|		threats relacionats i els retorna en un array.
	|
	******************************************************************/
	public static function loadThreats($categoryId)
	{
		$arrayThreats 		= array();
		$link 				= connectarDataBase();

		$stmt = $link -> prepare("SELECT * FROM forum_threats WHERE category_id = ?;");
		$stmt -> bind_param("i", $categoryId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($rowThread = $result -> fetch_object())
			{
				$arrayThreats[] = new ForumThreat($rowThread -> id, $rowThread -> category_id, $rowThread -> title, $rowThread -> threat_post_content, $rowThread -> user_id, $rowThread -> data, $rowThread -> closed, $rowThread -> group_only, $rowThread -> group_id);
			}
		}

		$stmt -> close();

		return $arrayThreats;
	}

	/******************************************************************
	| Name: saveThreat
	| 
	| @desc: Funció que guarda un nou threat a la base de dades. Retorna l'id
	| 		en cas d'èxit, -1 si falla.
	|
	******************************************************************/
	public function saveThreat()
	{
		// Preparem paràmetres per la query
		$categoryId 		= $this -> getCategoryId();
		$title 				= $this -> getTitle();
		$threadPostContent 	= $this -> getThreatPostContent();
		$userId 			= $this -> getUserId();
		$closed 			= $this -> getClosed();
		$groupOnly 			= $this -> getGroupOnly();
		$groupId 			= $this -> getGroupId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO forum_threats (category_id, title, threat_post_content, user_id, data, closed, group_only, group_id) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?);");
		$stmt -> bind_param("issiiii", $categoryId, $title, $threadPostContent, $userId, $closed, $groupOnly, $groupId);


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