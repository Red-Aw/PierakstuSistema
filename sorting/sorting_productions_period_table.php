<?php
	include_once "../includes/manager.class.php";
	include_once "../includes/validate.class.php";

	$date_string = isset($_GET['p']) ? $_GET['p'] : date('Y-m');

	//Checks if year and month is correct
	if(!Validate::IsValidPeriod($date_string))
	{
		header("Location: 404");
		exit();
	}

	$invoices = Manager::GetSortingProductionsByInvoice($date_string);
	$employees = Manager::GetSortingEmployeesByDate($date_string);
	$total = Manager::GetAllSortingProductionSummByDate($date_string);
	$total_without_useless = Manager::GetAllUselessSortingProductionSummByDate($date_string);
?>

<div class="card-body">
	<h4 class="card-title text-center">Šķirotavas produkcijas</h4>
	<table class="table table-bordered">
		<thead class="thead-default table-active">
			<tr>
				<th>Pavadzīmes Nr.</th>
				<th>Datums</th>
				<th>Laiks</th>
				<th>
					Izmērs <abbr title="Biezums x Platums x Garums">(BxPxG)</abbr>
				</th>
				<th>
					<abbr title="Kopējais Skaits - Brāķi = Skaits">Skaits</abbr>
				</th>
				<th>Tilpums (m<sup>3</sup>)</th>
				<th>Garināts / Šķirots</th>
				<th>Skaits</th>
				<th>
					Izmērs <abbr title="Biezums x Platums x Garums">(BxPxG)</abbr>
				</th>
				<th>Tilpums (m<sup>3</sup>)</th>
				<th>m<sup>3</sup>/gab</th>
				<th>Darba veicēji</th>
				<th>Labot</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach($invoices as $invoice)
	{
		$productions = Manager::GetSortingProductions($invoice['invoice']);
		foreach($productions as $production)
		{
			$rows = $production['total_sorted'];
			if($rows == 0)
			{
				$rows = 1;	//If Reserved, set to 1
			}
?>
			<tr>
				<td rowspan="<?=$rows?>"><?=$invoice['invoice']?></td>
				<td rowspan="<?=$rows?>"><?=$production['date']?></td>
				<td rowspan="<?=$rows?>">
					<?=$production['time_from']?> - <?=$production['time_to']?>
				</td>
				<td rowspan="<?=$rows?>">
					<?=$production['thickness']?> x <?=$production['width']?> x <?=$production['length']?>
				</td>
				<td rowspan="<?=$rows?>">
				<?php
					if($production['reserved'] == 0)
					{
						echo $production['count']." - ";
						if(!isset($production['defect_count']))
						{
							echo "0";
						}
						else
						{
							echo $production['defect_count'];
						}

						$total_count = $production['count'] - $production['defect_count'];
						echo " = ".$total_count;
					}
					else
					{
						echo $production['count'];
					}
				?>			
				</td>
				<td rowspan="<?=$rows?>"><?=$production['capacity']?></td>
		<?php
			if($production['reserved'] == 0)
			{
				$sorted_productions = Manager::GetSortedProductionsByID($production['id']);
				$k = 0;
				foreach($sorted_productions as $sorted_production)
				{
					if($k == 0)
					{
		?>
							<td>
							<?php
								if($sorted_production['type'] == "W")
								{
									echo "Mērcēts";
								}
								else
								{
									echo $sorted_production['type'];
								}
							?>					
							</td>
							<td><?=$sorted_production['count']?></td>
							<td>
								<?=$sorted_production['thickness']?> x <?=$sorted_production['width']?> x <?=$sorted_production['length']?>
							</td>
							<td><?=$sorted_production['capacity']?></td>
							<td><?=$sorted_production['capacity_piece']?></td>
							<td>
							<?php
								if($sorted_production['type'] != "W")
								{
									echo '<ol class="list-space">';
									$workers = Manager::GetAllSortingProductionWorkers($sorted_production['id']);
									foreach($workers as $worker)
									{
										echo '<li>';
										echo $worker['name'];
										echo " ";
										echo $worker['last_name'];
										echo '</li>';
									}
									echo '</ol>';
								}
								else
								{
									echo "-";
								}
							?>
							</td>
							<td rowspan="<?=$rows?>">
								<a href="edit_production?id=<?=$production['id']?>" class="btn btn-info">
									Labot
								</a>
							</td>
						</tr>
				<?php
					} 
					else
					{
				?>
						<tr>
							<td>
							<?php
								if($sorted_production['type'] == "W")
								{
									echo "Mērcēts";
								}
								else
								{
									echo $sorted_production['type'];
								}
							?>					
							</td>
							<td><?=$sorted_production['count']?></td>
							<td>
								<?=$sorted_production['thickness']?> x <?=$sorted_production['width']?> x <?=$sorted_production['length']?>
							</td>
							<td><?=$sorted_production['capacity']?></td>
							<td><?=$sorted_production['capacity_piece']?></td>
							<td>
							<?php
								if($sorted_production['type'] != "W")
								{
									echo '<ol class="list-space">';
									$workers = Manager::GetAllSortingProductionWorkers($sorted_production['id']);
									foreach($workers as $worker)
									{
										echo '<li>';
										echo $worker['name'];
										echo " ";
										echo $worker['last_name'];
										echo '</li>';
									}
									echo '</ol>';
								}
								else
								{
									echo "-";
								}
							?>
							</td>
						</tr>
<?php
					}
					$k++;
				}
			}
			else
			{
?>
					<td>Rezervēts</td>
					<td colspan="5" class="table-active"></td>
					<td>
						<a href="edit_reserved_production?id=<?=$production['id']?>" class="btn btn-info">
							Labot
						</a>
					</td>
				</tr>
<?php
			}
		}
	?>
		<tr class="table-light">
			<td colspan="13"></td>
		</tr>
<?php
	}
?>
			<tr class="table-info">
				<td colspan="4" class="text-right"><strong> Kopā: </strong></td>
				<td>
					<?php
						echo $total['count'];
						echo " - ";
						if(!isset($total['defect_count']))
						{
							echo "0";
						}
						else
						{
							echo $total['defect_count'];
						}

						$total_count = $total['count'] - $total['defect_count'];
						echo " = ".$total_count;
					?>	
				</td>
				<td><?=$total['capacity']?></td>
				<td></td>
				<td><?=$total['sorted_count']?></td>
				<td></td>
				<td><?=$total['sorted_capacity']?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>

<div class="card-body">
	<table class="table table-bordered table-hover">
		<thead class="thead-default table-active">
			<tr>
				<th colspan="3">Izejviela</th>
				<th colspan="3">Ražojums</th>
			</tr>
			<tr>
				<th>Noliktava</th>
				<th>Skaits (gab)</th>
				<th>Tilpums (m<sup>3</sup>)</th>
				<th>Noliktava</th>
				<th>Skaits (gab)</th>
				<th>Tilpums (m<sup>3</sup>)</th>
			</tr>
		</thead>
		<tbody>
			<tr class="table-info">
				<td></td>
				<td><?=$total['count']?></td>
				<td><?=$total['capacity']?></td>
				<td></td>
				<td><?=$total['sorted_count']?></td>
				<td><?=$total['sorted_capacity']?></td>
			</tr>
			<tr>
				<td><b>K20</b></td>
				<td><?=$total_without_useless['reserved_count']?></td>
				<td><?=$total_without_useless['reserved_capacity']?></td>
				<td><b>K21</b></td>
				<td><?=$total_without_useless['soaked_count']?></td>
				<td><?=$total_without_useless['soaked_capacity']?></td>
			</tr>
			<tr>
				<td><b>K13</b></td>
				<td>
				<?php 
					echo $total['count'] - $total_without_useless['reserved_count'];
				?>
				</td>
				<td>
				<?php 
					echo $total['capacity'] - $total_without_useless['reserved_capacity'];
				?>
				</td>
				<td><b>K14 / K20</b></td>
				<td>
				<?php 
					echo $total['sorted_count'] - $total_without_useless['soaked_count'];
				?>
				</td>
				<td>
				<?php
					echo $total['sorted_capacity'] - $total_without_useless['soaked_capacity'];
				?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-right"><b> Saražots: </b></td>
				<td>
				<?php 
					echo $total['capacity'] - $total_without_useless['reserved_capacity'] - $total_without_useless['soaked_capacity'];
				?>
				</td>
				<td colspan="2" class="text-right"><b> Šķelda: </b></td>
				<td>
				<?php
					echo $total['capacity'] - $total['sorted_capacity'];
				?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="card-body">
	<h4 class="card-title text-center">Šķirotavas Darbinieki</h4>
	<table class="table table-bordered table-hover">
		<thead class="thead-default table-active">
			<tr>
				<th rowspan="2">Nr.p.k</th>
				<th rowspan="2">V. Uzvārds</th>
				<th rowspan="2">Amats</th>
				<th colspan="4">Darba aprēķins</th>
				<th colspan="2">Stundas</th>
				<th rowspan="2">Atskaite</th>
			</tr>
			<tr>
				<th>Līdz 0,0089 m3</th>
				<th>No 0,009 līdz 0,0160 m3/gab</th>
				<th>No 0,0161 m3/gab</th>
				<th>Kopā</th>
				<th>Stundas</th>
				<th>Dienas</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$i = 1;
		foreach($employees as $employee)
		{
	?>
			<tr>
				<th><?=$i++?></th>
				<td><?=$employee['name']?> <?=$employee['last_name']?></td>
				<td>
				<?php
					$positions = Manager::EmployeePositions($employee['id']);
					foreach($positions as $position)
					{
						echo $position['name'].'<br>';
					}
				?>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<a href="report?id=<?=$employee['id']?>&period=<?=$date_string?>" class="btn btn-success">
						Skatīt
					</a>
				</td>
			</tr>
	<?php
		}
	?>
		</tbody>
	</table>
</div>