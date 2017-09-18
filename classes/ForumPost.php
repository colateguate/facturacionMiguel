<?php

class ForumPost
{
	// ------ FORUM_POST TABLE ------
	private $postId;
	private $threatId;
	private $repliedToPostId;
	private $postContent;
	private $userId;
	private $positiveVote;
	private $negativeVote;
	private $postDate;
	private $imgUploadPath;


	/******************************************************************
	| @name: __construct
	| @param: $postThreatCategory -> Si volem crear un comentari, crear un fil o una 
	|		categoria.
	| @desc: 
	|
	|
	******************************************************************/
	public function __construct($postId='', $threatId=NULL, $repliedToPostId=NULL, $postContent=NULL, $userId=NULL, $positiveVote=NULL, $negativeVote=NULL, $postDate=NULL, $imgUploadPath=NULL)
	{
		if(isset($postId) AND $postId != '')
		{
			$this -> postId = $postId;
			$this -> loadPost();
		}
		else
		{
			$this -> threatId 		 = $threatId;
			$this -> repliedToPostId = $repliedToPostId;
			$this -> postContent 	 = $postContent;
			$this -> userId 		 = $userId;
			$this -> positiveVote 	 = $positiveVote;
			$this -> negativeVote 	 = $negativeVote;
			$this -> data 			 = $postDate;
			$this -> imgUploadPath 	 = $imgUploadPath;

			$postId = $this -> savePost();
			$this -> postId = $postId;
		}

		return $this;
	}

	// ------------------ GETTERS i SETTERS ----------------------
	public function getPostId()
	{
		return $this -> postId;
	}

	public function setPostId($postId)
	{
		$this -> postId = $postId;
	}

	public function getRepliedToPostId()
	{
		return $this -> repliedToPostId;
	}

	public function setRepliedToPostId($replyPostId)
	{
		$this -> repliedToPostId = $replyPostId;
	}

	public function getThreatId()
	{
		return $this -> threatId;
	}

	public function setThreatId($threatId)
	{
		$this -> threatId = $threatId;
	}

	public function getPostContent()
	{
		return $this -> postContent;
	}

	public function setPostContent($postContent)
	{
		$this -> postContent = $postContent;
	}

	public function getUserId()
	{
		return $this -> userId;
	}

	public function setUserId($userId)
	{
		$this -> userId = $userId;
	}

	public function getPositiveVote()
	{
		return $this -> positiveVote;
	}

	public function setPositiveVote($posVote)
	{
		$this -> positiveVote = $posVote;
	}

	public function getNegativeVote()
	{
		return $this -> negativeVote;
	}

	public function setNegativeVotes($negVote)
	{
		$this -> negativeVote = $negVote;
	}

	public function getPostDate()
	{
		return $this -> postDate;
	}

	public function setPostDate($postDate)
	{
		$this -> postDate = $postDate;
	}

	public function getImgUploadPath()
	{
		return $this -> imgUploadPath;
	}

	public function setImgUploadPath($imgUploadPath)
	{
		$this -> imgUploadPath = $imgUploadPath;
	}
	
	// --------------- FI GETTERS i SETTERS -------------------

	/******************************************************************
	| Name: getPostReplies
	| 
	| @param: $postId: Id del post del que volem carregar les rèpliques
	|
	| @desc: Funció que carrega les rèpliques d'un post en un objecte.
	|
	******************************************************************/
	public function getPostReplies($postId)
	{
		$arrayPostReplies = array();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM forum_post WHERE replied_to_post_id = ?;");
		$stmt -> bind_param("i", $postId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayPostReplies[] = new ForumPost($row -> id);
			}
		}

		$stmt -> close();

		return $arrayPostReplies;
	}

	/******************************************************************
	| Name: loadPost
	| 
	| @desc: Funció que carrega a l'objecte les dades d'un Post concret
	|
	******************************************************************/
	public function loadPost()
	{
		// Preparem paràmetres per a la query
		$postId = $this -> getPostId();

		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM forum_post WHERE id = ?;");
		$stmt -> bind_param("i", $postId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$row = $result -> fetch_object();

			// Assignem les dades de la bd a l'objecte.
			$this -> setThreatId($row -> thread_id);
			$this -> setRepliedToPostId($row -> replied_to_post_id);
			$this -> setPostContent($row -> post_content);
			$this -> setUserId($row -> user_id);
			$this -> setPositiveVote($row -> positive_vote);
			$this -> setNegativeVotes($row -> negative_vote);
			$this -> setPostDate($row -> data);
			$this -> setImgUploadPath($row -> img_upload_path);
		}

		$stmt -> close();
	}

	/******************************************************************
	| Name: loadPosts
	| @param: $threatId -> id del threat que volem carregar els posts
	| 
	| @desc: Funció que rep un id de threat com a paràmetre i busca tots els
	|		posts relacionats i els retorna en un array.
	|
	******************************************************************/
	public static function loadPosts($threatId)
	{
		$arrayPosts = array();
		$link 		= connectarDataBase();
		$stmt 		= $link -> prepare("SELECT * FROM forum_post WHERE replied_to_post_id = 0 AND thread_id = ?;");
		$stmt -> bind_param("i", $threatId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($rowPost = $result -> fetch_object())
			{
				$arrayPosts[] = new ForumPost($rowPost -> id, $rowPost -> thread_id, $rowPost -> replied_to_post_id, $rowPost -> post_content, $rowPost -> user_id, $rowPost -> positive_vote, $rowPost -> negative_vote, $rowPost -> data, $rowPost -> img_upload_path);
			}
		}

		$stmt -> close();

		return $arrayPosts;
	}

	/******************************************************************
	| Name: savePost
	| 
	| @desc: Funció que guarda un nou post a la base de dades. Retorna l'id
	| 		en cas d'èxit, -1 si falla.
	|
	******************************************************************/
	public function savePost()
	{
		// Preparem variables per a la query
		$threadId 		 = $this -> getThreatId();
		$repliedToPostId = $this -> getRepliedToPostId();
		$postContent 	 = $this -> getPostContent();
		$userId 		 = $this -> getUserId();
		$positiveVote 	 = $this -> getPositiveVote();
		$negativeVote 	 = $this -> getNegativeVote();
		$imgUploadPath 	 = $this -> getImgUploadPath();

		$link 			= connectarDataBase();
		$stmt = $link -> prepare("INSERT INTO forum_post (thread_id, replied_to_post_id, post_content, user_id, positive_vote, negative_vote, data, img_upload_path) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?);");

		$stmt -> bind_param("iisiiis", $threadId, $repliedToPostId, $postContent, $userId, $positiveVote, $negativeVote, $imgUploadPath);

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
	| Name: setNewPositiveVote
	| 
	| @param: $postId -> id del post al que volem donar un vot positiu
	|
	| @desc: Funció que guarda un nou post a la base de dades. Retorna l'id
	| 		en cas d'èxit, -1 si falla.
	|
	******************************************************************/
	public function setNewPositiveVote($postId)
	{

	}

	/******************************************************************
	| Name: setNewNegativeVote
	| 
	| @param: $postId -> id del post al que volem donar un vot negatiu
	|
	| @desc: Funció que guarda un nou post a la base de dades. Retorna l'id
	| 		en cas d'èxit, -1 si falla.
	|
	******************************************************************/
	public function setNewNegativeVote($postId)
	{

	}
}