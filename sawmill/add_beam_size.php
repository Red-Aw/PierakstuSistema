<?php
	include_once "../header.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Adding new beam size possible if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p")) || ($_SESSION['active'] != 1))	//Check if user have permission
	{
		header("Location: 404");
		exit();
	}

	if(isset($_SESSION['beam']))
	{
		extract($_SESSION['beam']);
	}
?>

	<!-- Add new beam size -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">

				<div id="message">
					<?php include "../message.php"; ?>
				</div>
		
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu kubatūras izmēru apaļkoka tilpuma aprēķiniem</h4>
						<form id="beam_form" action="new_beam_size" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Izmērs
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" step="0.001" name="size" aria-describedby="sizeArea" placeholder="0,000" value="<?php echo isset($_SESSION['beam']) ? $size : ''; ?>">
									<small id="sizeArea" class="form-text text-muted">
										* Satur tikai ciparus. (Maksimums 3 cipari aiz komata) *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['new_beam']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['new_beam']?>
										</div>
									<?php
											unset($_SESSION['new_beam']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Pievienot</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/beam_form.js"></script> 

<?php
	unset($_SESSION['beam']);
	include_once "../footer.php";
?>