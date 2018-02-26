<?php

	include "header.php";
	include "includes/manager.class.php";

	$employees = Manager::Employees();

?>

<div class="container">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "message.php"; ?>
			</div>
			<div class="card">

				<div class="card-body">
					<h4 class="card-title text-center">Zāģētavas darbinieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr class="d-flex">
								<th class="col-md-1">Nr. p. k</th>
								<th class="col-md-3">Vārds</th>
								<th class="col-md-3">Uzvārds</th>
								<th class="col-md-1">Maiņa</th>
								<th class="col-md-2">Amats</th>
								<th class="col-md-2">...</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($employees as $employee)
						{
							if($employee['place'] == "Zagetava")
							{
					?>
								<tr class="d-flex">
									<th class="col-md-1"><?=$i++?></th>
									<td class="col-md-3"><?=$employee['name']?></td>
									<td class="col-md-3"><?=$employee['last_name']?></td>
									<td class="col-md-1">1</td>
									<td class="col-md-2">
									<?php
										$positions = Manager::EmployeePositions($employee['ID']);
										foreach($positions as $position)
										{
											echo $position['name'].'<br>';
										}
									?>
									</td>
									<td class="col-md-2"></td>
								</tr>
					<?php
							}
						}

						foreach ($employees as $employee)
						{
							if($employee['place'] == "Zagetava")
							{
					?>
								<tr class="d-flex table-success">
									<th class="col-md-1"><?=$i++?></th>
									<td class="col-md-3"><?=$employee['name']?></td>
									<td class="col-md-3"><?=$employee['last_name']?></td>
									<td class="col-md-1">2</td>
									<td class="col-md-2">
									<?php
										$positions = Manager::EmployeePositions($employee['ID']);
										foreach($positions as $position)
										{
											echo $position['name'].'<br>';
										}
									?>
									</td>
									<td class="col-md-2"></td>
								</tr>
					<?php
							}
						}
					?>
						</tbody>
					</table>
				</div>

				<div class="card-body">
					<h4 class="card-title text-center">Šķirošanas darbinieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr class="d-flex">
								<th class="col-md-1">Nr. p. k</th>
								<th class="col-md-3">Vārds</th>
								<th class="col-md-3">Uzvārds</th>
								<th class="col-md-3">Amats</th>
								<th class="col-md-2">...</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($employees as $employee)
						{
							if($employee['place'] == "Skirotava")
							{
					?>
								<tr class="d-flex">
									<th class="col-md-1"><?=$i++?></th>
									<td class="col-md-3"><?=$employee['name']?></td>
									<td class="col-md-3"><?=$employee['last_name']?></td>
									<td class="col-md-3">
									<?php
										$positions = Manager::EmployeePositions($employee['ID']);
										foreach($positions as $position)
										{
											echo $position['name'].'<br>';
										}
									?>
									</td>
									<td class="col-md-2"></td>
								</tr>
					<?php
							}
						}
					?>
						</tbody>
					</table>
				</div>

				<div class="card-body">
					<h4 class="card-title text-center">Biroja darbinieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr class="d-flex">
								<th class="col-md-1">Nr. p. k</th>
								<th class="col-md-3">Vārds</th>
								<th class="col-md-3">Uzvārds</th>
								<th class="col-md-3">Amats</th>
								<th class="col-md-2">...</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($employees as $employee)
						{
							if($employee['place'] == "Birojs")
							{
					?>
								<tr class="d-flex">
									<th class="col-md-1"><?=$i++?></th>
									<td class="col-md-3"><?=$employee['name']?></td>
									<td class="col-md-3"><?=$employee['last_name']?></td>
									<td class="col-md-3">
									<?php
										$positions = Manager::EmployeePositions($employee['ID']);
										foreach($positions as $position)
										{
											echo $position['name'].'<br>';
										}
									?>
									</td>
									<td class="col-md-2"></td>
								</tr>
					<?php
							}
						}
					?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<?php

	include "footer.php";

?>