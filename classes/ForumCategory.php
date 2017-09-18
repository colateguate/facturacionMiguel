<?php

class ForumCategory
{
	private $categoryId;
	private $title;
	private $threats;

	public function __construct($categoryId=NULL, $title=NULL)
	{
		if(isset($categoryId) AND $categoryId != '')
		{
			$this -> categoryId = $categoryId;
			$this -> loadCategory($categoryId);
		}
		else
		{
			$this -> title 		= $title;
			$categoryId 		= $this -> saveCategory();
			$this -> categoryId = $categoryId;
		}		
	}

	// ------------------ GETTERS i SETTERS ----------------------

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

	public function getThreats()
	{
		return $this -> threats;
	}

	public function setThreats($threats)
	{
		$this -> threats = $threats;
	}

	// ---------------FI  GETTERS i SETTERS -----------------

	/******************************************************************
	| Name: loadCategory
	| @param: $categoryId -> id de la categoria que volem carregar
	| 
	| @desc: Funció que rep un id de categoria com a paràmetre i carrega tota
	|		la informació relacionada amb la categoria en un objecte Categoria
	|
	******************************************************************/
	public function loadCategory($categoryId)
	{
		// Busquem la categoria
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT * FROM forum_categories WHERE id = ?;");
		$stmt -> bind_param("i", $categoryId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$row = $result -> fetch_object();

			// Carreguem la categoria
			$this -> setCategoryId($row -> id);
			$this -> setTitle($row -> title);
		}
			
		$stmt -> close();
	}

	/******************************************************************
	| Name: loadCategories
	| 
	| @desc: Funció estàtica ja que les categories és el primer nivell del
	| 		forum. La funció retorna un array amb la informació de totes les
	|		categories.
	|
	******************************************************************/

	public static function loadCategories()
	{
		$arrayCategories 	 = array();
		
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT id FROM forum_categories");

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_object())
			{
				$arrayCategories[] = new ForumCategory($row -> id);
			}
		}

		$stmt -> close();

		return $arrayCategories;
	}

	/******************************************************************
	| Name: getCategoryTitle
	| 
	| @param: categoryId -> id de la categoria de la que volem saber el nom
	| @desc: Funció estàtica que retorna el títol d'una categoria.
	|
	******************************************************************/
	public static function getCategoryTitle($categoryId)
	{
		$link = connectarDataBase();
		$stmt = $link -> prepare("SELECT title FROM forum_categories WHERE id = ?;");
		$stmt -> bind_param("i", $categoryId);

		$stmt -> execute();
		$result = $stmt -> get_result();

		if($result -> num_rows === 1)
		{
			$category = $result -> fetch_object();
		}

		$stmt -> close();

		return $category -> title;
	}

	/******************************************************************
	| Name: saveThreat
	| 
	| @desc: Funció que guarda una nova categoria a la base de dades. Retorna l'id
	| 		en cas d'èxit, -1 si falla.
	|
	******************************************************************/
	public function saveCategory()
	{
		$link = connectarDataBase();

		// Preparem variables per a la query
		$titol = $this -> title;

		$stmt = $link -> prepare("INSERT INTO forum_categories (title) VALUES (?);");
		$stmt -> bind_param("s", $titol);

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