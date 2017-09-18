<?php

// Classe pensada per manegar les cintes LTO (robot)
class Cintes
{
	private $id;
	private $tipusCinta;
	private $status;

	public function __construct()
	{

	}

	public function getId()
	{
		return $this -> getId();
	}

	public function setId($id)
	{
		$this -> id = $id;
	}

	public function getTipusCinta()
	{
		return $this -> tipusCinta;
	}

	public function setTipusCinta($tipusCinta)
	{
		$this -> tipusCinta = $tipusCinta;
	}

	public function getStatus()
	{
		return $this -> status;
	}

	public function setStatus($status)
	{
		$this -> status = $status;
	}
}