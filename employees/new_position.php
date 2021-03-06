<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/position.class.php";
	include_once "../includes/validate.class.php";
/****************** Includes ******************/

	if(!isset($_POST['name']))
	{
		header("Location: 404");
		exit();
	}

	//Sets variable
	$position = htmlspecialchars($_POST['name']);

	//Error handlers
	//Check if position is set
	if(empty($position))
	{
		$_SESSION['new_position'] = "Lūdzu, aizpildiet Amats lauku!";
		$_SESSION['position'] = $_POST;
		header("Location: add_position");
		exit();
	}

	//Check position length
	if(!Validate::IsValidPositionLength($position))
	{
		$_SESSION['new_position'] = "Amats jābūt garumā no 3 simboliem līdz 255 simboliem!";
		$_SESSION['position'] = $_POST;
		header("Location: add_position");
		exit();
	}

	//Check if position match complexity
	if(!Validate::IsValidText($position))
	{
		$_SESSION['new_position'] = "Amats drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
		$_SESSION['position'] = $_POST;
		header("Location: add_position");
		exit();
	}

	//Check if possition already exists
	if(Position::ExistsName($position))
	{
		$_SESSION['warning'] = "Amats jau eksistē, jums nav nepieciešams to ievadīt vēlreiz!";
		$_SESSION['position'] = $_POST;
		header("Location: add_position");
		exit();
	}

	//Object
	$pos = new Position();
	$pos->name = $position;
	$pos->Save();

	$_SESSION['success'] = "Amats pievienots veiksmīgi!";
	header("Location: add_position");
	exit();

?>