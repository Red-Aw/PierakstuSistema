<?php
	
	session_start();

/****************** Includes ******************/
	include_once "../includes/beam_size.class.php";
	include_once "../includes/validate.class.php";
/****************** Includes ******************/

	if(!isset($_POST['size']))
	{
		header("Location: 404");
		exit();
	}

	//Sets variable
	$size = htmlspecialchars($_POST['size']);

	//Error handlers
	//Checks if input field - size is empty
	if(empty($size))
	{
		$_SESSION['new_beam'] = "Lūdzu, aizpildiet Izmērs lauku!";
		$_SESSION['beam'] = $_POST;
		header("Location: add_beam_size");
		exit();
	}

	//If user typed number with comma, it changes it to dot
	$size = str_replace(',', '.', $size);

	//Check if number matches complexity
	if(!Validate::IsValidFloatNumber($size))
	{
		$_SESSION['new_beam'] = "Izmērs drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['beam'] = $_POST;
		header("Location: add_beam_size");
		exit();
	}
	if($size <= 0)
	{
		$_SESSION['new_beam'] = "Izmērs drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['beam'] = $_POST;
		header("Location: add_beam_size");
		exit();
	}

	//Check if number already exists in database
	if(BeamSize::ExistsSize($size))
	{
		$_SESSION['warning'] = "Izmērs jau eksistē, jums nav nepieciešams to ievadīt vēlreiz!";
		$_SESSION['beam'] = $_POST;
		header("Location: add_beam_size");
		exit();
	}

	//Object
	$beam = new BeamSize();
	$beam->size = $size;
	$beam->Save();

	$_SESSION['success'] = "Kubatūras izmērs pievienots!";
	header("Location: add_beam_size");
	exit();

?>