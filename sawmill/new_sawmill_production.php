<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/sawmill_production.class.php";
	include_once "../includes/sawmill_maintenance.class.php";
	include_once "../includes/employees_sawmill_productions.class.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";
	include_once "../includes/beam_size.class.php";
/****************** Includes ******************/

	$inputs = ['date', 'time_from', 'time_to', 'invoice', 'beam_count', 'sizes', 'lumber_count', 'lumber_capacity', 'note', 'maintenance_times', 'maintenance_notes', 'shifts'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$date = htmlspecialchars($_POST['date']);
	$time_from = htmlspecialchars($_POST['time_from']);
	$time_to = htmlspecialchars($_POST['time_to']);
	$invoice = htmlspecialchars($_POST['invoice']);

	if(empty($_POST['note']))
	{
		$note = NULL;
	}
	else
	{
		$note = htmlspecialchars($_POST['note']);
	}

	$beam_count = htmlspecialchars($_POST['beam_count']);
	$beam_size = htmlspecialchars($_POST['sizes']);

	$lumber_count = htmlspecialchars($_POST['lumber_count']);
	$lumber_capacity = htmlspecialchars($_POST['lumber_capacity']);

	$maintenance_times = $_POST['maintenance_times'];
	$maintenance_notes = $_POST['maintenance_notes'];

	$shift = htmlspecialchars($_POST['shifts']);

	//Error handlers
	//Check if fields are empty
	if(empty($date) || empty($time_from) || empty($time_to) || empty($invoice) || empty($beam_count) || empty($lumber_count) || empty($lumber_capacity) || empty($shift))
	{
		$_SESSION['error'] = "Lūdzu, aizpildiet visus obligātos laukus!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Checks if shift number exists in database
	if(!Manager::ExistsShift($shift))
	{
		$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Checks if employees input fields are set
	$inputs = ['id', 'working'];
	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$ids = $_POST['id'];
	$working_hours = $_POST['working'];

	//Checks if date is correct, like yyyy/mm/dd or yyyy-mm-dd
	if(!Validate::IsValidDate($date))
	{
		$_SESSION['date'] = "Lūdzu, ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Check if production times are correct
	if(!Validate::IsValidTime($time_from))
	{
		$_SESSION['time'] = "Lūdzu, ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}
	if(!Validate::IsValidTime($time_to))
	{
		$_SESSION['time'] = "Lūdzu, ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Check if invoice is number
	if(!Validate::IsValidIntegerNumber($invoice))
	{
		$_SESSION['invoice'] = "Ievadītais pavadzīmes numurs ir neatbilstošs! Tas var sastāvēt tikai no cipariem!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Checks if entered invoice number already exists
	if(SawmillProduction::ExistsInvoice($invoice))	
	{
		$_SESSION['invoice'] = "Pavadzīme ar šādu numuru jau eksistē!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Check if beam_count is number
	if(!Validate::IsValidIntegerNumber($beam_count))
	{
		$_SESSION['beam_count'] = "Apaļkoku skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Check if beam_size is sellected
	if(empty($beam_size))
	{
		$_SESSION['beam_size'] = "Lūdzu, izvēlieties kubatūras izmēru";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}
	else if(!BeamSize::ExistsId($beam_size)) //Checks if position with this id exists
	{
		$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Check if lumber_count is number
	if(!Validate::IsValidIntegerNumber($lumber_count))
	{
		$_SESSION['lumber_count'] = "Zāģmatariālu skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//If user typed number with comma, it changes it to dot
	$lumber_capacity = str_replace(',', '.', $lumber_capacity); 

	//Check if lumber_capacity is float number with comma or dot
	if(!Validate::IsValidFloatNumber($lumber_capacity))
	{
		$_SESSION['lumber_capacity'] = "Zāģmatariālu tilpums drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}
	if($lumber_capacity <= 0)
	{
		$_SESSION['lumber_capacity'] = "Zāģmatariālu tilpums drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['sawmill_prod'] = $_POST;
		header("Location: add_sawmill_production");
		exit();
	}

	//Checks if note is filled, then matches its content with regular expression
	if(!empty($note))
	{
		if(!Validate::IsValidTextLength($note))
		{
			$_SESSION['note'] = "Citas piezīmes jābūt garumā no 3 simboliem līdz 255 simboliem!";
			$_SESSION['sawmill_prod'] = $_POST;
			header("Location: add_sawmill_production");
			exit();
		}

		if(!Validate::IsValidText($note))
		{
			$_SESSION['note'] = "Citas piezīmes drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
			$_SESSION['sawmill_prod'] = $_POST;
			header("Location: add_sawmill_production");
			exit();
		}
	}

	//Check maintenances
	for($i=0; $i < count($maintenance_times); $i++)
	{
		if(!empty($maintenance_times[$i]))
		{
			if(!Validate::IsValidIntegerNumber($maintenance_times[$i]))
			{
				$_SESSION['maintenance'] = "Remonta laiks drīkst sastāvēt tikai no cipariem!";
				$_SESSION['sawmill_prod'] = $_POST;
				header("Location: add_sawmill_production");
				exit();
			}

			if(!empty($maintenance_notes[$i]))
			{
				if(!Validate::IsValidTextLength($maintenance_notes[$i]))
				{
					$_SESSION['maintenance'] = "Piezīme jābūt garumā no 3 simboliem līdz 255 simboliem!";
					$_SESSION['sawmill_prod'] = $_POST;
					header("Location: add_sawmill_production");
					exit();
				}

				if(!Validate::IsValidText($maintenance_notes[$i]))
				{
					$_SESSION['maintenance'] = "Piezīme drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
					$_SESSION['sawmill_prod'] = $_POST;
					header("Location: add_sawmill_production");
					exit();
				}
			}
		}
		else if(empty($maintenance_times[$i]) && !empty($maintenance_notes[$i])) //Minutes not enetered
		{
			$_SESSION['maintenance'] = "Lūdzu, ievadiet remonta laiku vai remonta laiku un piezīmi!";
			$_SESSION['sawmill_prod'] = $_POST;
			header("Location: add_sawmill_production");
			exit();
		}
	}

	//Checks employees working fields
	for($i = 0; $i < count($ids); $i++) 
	{
		if(!Validate::IsValidDropdownWorkingHours($working_hours[$i]))
		{
			$_SESSION['shift'] = "Lūdzu, aizpildiet darbinieku tabulu!";
			$_SESSION['sawmill_prod'] = $_POST;
			header("Location: add_sawmill_production");
			exit();
		}
	}

	$beamSize = BeamSize::Get($beam_size);
	(float)$beam_capacity = (int)$beam_count * (float)$beamSize->size; //Calculates beam_capacity

	(float)$percentage = ((float)$lumber_capacity / (float)$beam_capacity) * 100; //Calculates percentage

	$percentage = round($percentage, 2);

	//Get timestamp
	$timestamp = new DateTime('now', new DateTimezone('Europe/Riga'));
	$timestamp = $timestamp->format('Y-m-d H:i:s');

	//Objects
	//Saves sawmill production
	$sawmillProduction = new SawmillProduction();
	$sawmillProduction->date = $date;
	$sawmillProduction->datetime = $timestamp;
	$sawmillProduction->time_from = $time_from;
	$sawmillProduction->time_to = $time_to;
	$sawmillProduction->invoice = $invoice;
	$sawmillProduction->beam_count = $beam_count;
	$sawmillProduction->beam_capacity = $beam_capacity;
	$sawmillProduction->lumber_count = $lumber_count;
	$sawmillProduction->lumber_capacity = $lumber_capacity;
	$sawmillProduction->percentage = $percentage;
	$sawmillProduction->note = $note;
	$sawmillProduction->beam_size_id = $beam_size;
	$sawmillProduction->Save();

	//Saves sawmillproduction maintenance times and notes
	for($i = 0; $i < count($maintenance_times); $i++)
	{
		if(!empty($maintenance_times[$i]))
		{
			if(empty($maintenance_notes[$i]))
			{
				$maintenance_notes[$i] = NULL;
			}

			$sawmillMaintenance = new SawmillMaintenance();
			$sawmillMaintenance->time = $maintenance_times[$i];
			$sawmillMaintenance->note = $maintenance_notes[$i];
			$sawmillMaintenance->sawmill_production_id = $sawmillProduction->id;
			$sawmillMaintenance->Save();
		}
	}

	//Saves data to tables: employees_sawmill_productions, working_times, times
	$employees_sawmill_procutions = new EmployeeSawmillProductions();
	$working_times = new WorkingTimes();
	$times = new Times();

	for($i = 0; $i < count($ids); $i++)
	{
		$employees_sawmill_procutions->employee_id = $ids[$i];
		$employees_sawmill_procutions->sawmill_id = $sawmillProduction->id;
		$employees_sawmill_procutions->Save();

		if($working_hours[$i] > 0 && $working_hours[$i] < 9)
		{
			$working_times->date = $date;
			$working_times->datetime = $timestamp;
			$working_times->invoice = $invoice;
			$working_times->working_hours = $working_hours[$i];
			$working_times->employee_id = $ids[$i];
			$working_times->Save();
		}
		else if($working_hours[$i] >= 9 && $working_hours[$i] < 12)
		{	
			if($working_hours[$i] == 9)
			{
				$times->vacation = "A";
				$times->sick_leave = NULL;
				$times->nonattendance = NULL;
			}
			else if($working_hours[$i] == 10)
			{
				$times->vacation = NULL;
				$times->sick_leave = "S";
				$times->nonattendance = NULL;
			}
			else if($working_hours[$i] == 11)
			{
				$times->vacation = NULL;
				$times->sick_leave = NULL;
				$times->nonattendance = "N";
			}

			$times->date = $date;
			$times->datetime = $timestamp;
			$times->invoice = $invoice;
			$times->pregnancy = NULL;
			$times->employee_id = $ids[$i];
			$times->Save();
		}
	}

	$_SESSION['success'] = "Zāģētavas produkcija pievienota veiksmīgi!";
	header("Location: show_sawmill_production");
	exit();
		
?>