<?php
	include_once "../includes/manager.class.php";

	$positions = Manager::Positions();
?>

	<select class="custom-select mb-2" name="positions[]">
		<option selected value="" style="font-weight:bold;">Izvēlieties amatu</option>

<?php
	foreach($positions as $position)
	{
		echo '<option value="'.$position['id'].'"  >'.$position['name'].'</option>';
	}
?>

	</select>