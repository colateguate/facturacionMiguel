<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FAQ
 *
 * @author metro
 */
class FAQ {

    private $id;
    private $question;
    private $answer;

    public function __construct ($faqId = NULL, $question = NULL, $answer = NULL) {
        if (isset ($faqId) AND $faqId != '') {
            $this -> id = $faqId;
            $this -> load ();
        } else {
            $this -> question = $question;
            $this -> answer = $answer;
            $faqId = $this -> saveCategory ();
        }
    }

// ------------------ GETTERS i SETTERS ----------------------
    function getId ()
    {
        return $this -> id;
    }

    function getQuestion ()
    {
        return $this -> question;
    }

    function getAnswer ()
    {
        return $this -> answer;
    }

    function setId ($id)
    {
        $this -> id = $id;
    }

    function setQuestion ($question)
    {
        $this -> question = $question;
    }

    function setAnswer ($answer)
    {
        $this -> answer = $answer;
    }

    // ---------------FI  GETTERS i SETTERS -----------------

    /*     * ****************************************************************
      | Name: load
      |
      |
      | @desc: Funció que carrega tota la informació relacionada amb
      | la entrada de FAQ
      |
     * **************************************************************** */

    public function load ()
    {
        $conn = connectarDataBase ();

        //Preparem variables per la query
        $id = $this -> id;

        $stmt = $conn -> prepare("SELECT * FROM faqs WHERE id = ?");
        $stmt -> bind_param("i", $id);

        $stmt -> execute();
        $result = $stmt -> get_result();

        if($result -> num_rows === 1)
        {
            $row = $result -> fetch_object();

            $this -> setQuestion($row -> question);
            $this -> setAnswer($row -> answer);
        }

        $stmt -> close();
    }

    /*     * ****************************************************************
      | Name: loadAll
      |
      | @desc: Funció estàtica que carrega totes les pregunes
      |
     * *************************************************************** */

    public static function loadAll () {
        $arrayReturn = array();
        $conn = connectarDataBase ();
        $stmt = $conn -> prepare("SELECT id FROM faqs;");

        $stmt -> execute();
        $result = $stmt -> get_result();
        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_object())
            {
                $arrayReturn[] = new FAQ ($row -> id);
            }
        }

        $stmt -> close ();

        return $arrayReturn;
    }

}
