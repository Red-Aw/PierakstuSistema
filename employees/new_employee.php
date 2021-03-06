<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/position.class.php";
	include_once "../includes/validate.class.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/employee_position.class.php";
/****************** Includes ******************/

	$inputs = ['name', 'last_name', 'place', 'positions', 'person_no'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets Variables
	$name = htmlspecialchars($_POST['name']);
	$last_name = htmlspecialchars($_POST['last_name']);
	$place = htmlspecialchars($_POST['place']);
	$positions = $_POST['positions'];
	$person_no = htmlspecialchars($_POST['person_no']);
	
	//Error handlers
	//Check if fields are empty
	if(empty($name) || empty($last_name) || empty($place) || empty($person_no))
	{
		$_SESSION['error'] = "Lūdzu, aizpildiet visus obligātos laukus!";
		$_SESSION['employee'] = $_POST;
		header("Location: add_employee");
		exit();
	}

	//Checks working place
	if($place == "1")
	{
		$place = "Birojs";
		$shift = NULL;
		$capacity_rate = NULL;
		$hour_rate = NULL;
	}
	elseif($place == "2")
	{
		//Checks sawmill inputs
		$inputs = ['shift', 'capacity_rate', 'hour_rate'];

		foreach($inputs as $input)
		{
			if(!isset($_POST[$input]))
			{
				header("Location: /");
				exit();
			}
		}

		$shift = htmlspecialchars($_POST['shift']);
		$capacity_rate = htmlspecialchars($_POST['capacity_rate']);
		$hour_rate = htmlspecialchars($_POST['hour_rate']);

		if(empty($shift) || empty($capacity_rate) || empty($hour_rate))
		{
			$_SESSION['error'] = "Lūdzu, aizpildiet visus obligātos laukus!";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}

		if($shift != "1" && $shift != "2")
		{
			$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}

		//If user typed number with comma, it changes it to dot
		$capacity_rate = str_replace(',', '.', $capacity_rate);
		$hour_rate = str_replace(',', '.', $hour_rate);

		if(!Validate::IsValidFloatNumberWithTwoDigitsAfterDot($capacity_rate))
		{
			$_SESSION['place'] = "Kubikmetra likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}
		if(!Validate::IsValidFloatNumberWithTwoDigitsAfterDot($hour_rate))
		{
			$_SESSION['place'] = "Stundas likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}
		if($capacity_rate <= 0)
		{
			$_SESSION['place'] = "Kubikmetra likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}
		if($hour_rate <= 0)
		{
			$_SESSION['place'] = "Stundas likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}

		$place = "Zagetava";
	}
	elseif($place == "3")
	{
		$place = "Skirotava";
		$shift = NULL;
		$capacity_rate = NULL;
		$hour_rate = NULL;
	}
	else
	{
		$_SESSION['place'] = "Lūdzu, izvēlieties darba vietu!";
		$_SESSION['employee'] = $_POST;
		header("Location: add_employee");
		exit();
	}

	//Check if employee all positions are set
	foreach($positions as $position)
	{
		if(empty($position))
		{
			$_SESSION['position'] = "Lūdzu, izvēlieties darbinieka amatu/s!";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}
		else if(!Position::Exists($position)) //Checks if position with this id exists
		{
			$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
			$_SESSION['employee'] = $_POST;
			header("Location: add_employee");
			exit();
		}
	}

	//Checks names length
	if(!Validate::IsValidNameLength($name))
	{
		$_SESSION['name'] = "Vārds jābūt garumā no 3 simboliem līdz 50 simboliem!";
		$_SESSION['employee'] = $_POST;
		header("Location: add_employee");
		exit();
	}

	//Check if name is correct
	if(!Validate::IsValidName($name))
	{
		$_SESSION['name'] = "Vārds drīkst saturēt tikai latīņu burtus!";
		$_SESSION['employee'] = $_POST;
		header("Location: add_employee");
		exit();
	}

	//Checks last name length
	if(!Validate::IsValidNameLength($last_name))
	{
		$_SESSION['last_name'] = "Uzvārds jābūt garumā no 3 simboliem līdz 50 simboliem!";
		$_SESSION['employee'] = $_POST;
		header("Location: add_employee");
		exit();
	}

	//Check if last name is correct
	if(!Validate::IsValidName($last_name))
	{
		$_SESSION['last_name'] = "Uzvārds drīkst saturēt tikai latīņu burtus!";
		$_SESSION['employee'] = $_POST;
		header("Location: add_employee");
		exit();
	}

	//Check if person id is correct
	if(!Validate::IsValidPersonNumber($person_no))
	{
		$_SESSION['person_no'] = "Personas kods drīkst sastāvēt no 11 cipariem un defises!";
		$_SESSION['employee'] = $_POST;
		header("Location: add_employee");
		exit();
	}

	//Check if person id already exists
	if(Employee::ExistsPersonNo($person_no))
	{
		$_SESSION['person_no'] = "Darbinieks ar šādu personas kodu jau eksistē!";
		$_SESSION['position'] = $_POST;
		header("Location: add_position");
		exit();
	}

	//Object
	$employee = new Employee();
	$employee->name = $name;
	$employee->last_name = $last_name;
	$employee->place = $place;
	$employee->person_id = $person_no;
	$employee->shift = $shift;
	$employee->capacity_rate = $capacity_rate;
	$employee->hour_rate = $hour_rate;
	$employee->Save();

	//Sub-entity 
	$employee_position = new EmployeePosition();
	foreach($positions as $position)
	{
		$employee_position->employee_id = $employee->id;
		$employee_position->position_id = $position;
		$employee_position->Save();
	}

	$_SESSION['success'] = "Darbinieks pievienots!";
	header("Location: add_employee");
	exit();

?>