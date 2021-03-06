<?php

	include_once "header.php";

?>
	<!-- Login -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<!-- Shows error message -->
				<?php include "message.php"; ?>	

				<div class="card">
					<div class="card-body">
						<?php
							if(!isset($_SESSION['id']))
							{
						?>
								<h4 class="card-title text-center">Autentifikācija</h4>
								<form action="login" method="POST">
									<div class="form-group row">
										<label class="col-md-2 offset-md-1 col-form-label">Lietotājvārds</label>
										<div class="col-md-5">
											<input class="form-control" type="text" name="usr" placeholder="Lietotājvārds" value="<?php echo isset($_SESSION['username_login']) ? $_SESSION['username_login'] : ''; unset($_SESSION['username_login']); ?>">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 offset-md-1 col-form-label">Parole</label>
										<div class="col-md-5">
											<input class="form-control" type="password" name="pwd" placeholder="********">
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-3 offset-md-3">
											<button class="btn btn-success" type="submit" name="submit">Pieslēgties</button>
										</div>
									</div>
								</form>

						<?php
							}
							else
							{
								if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p") && ($_SESSION['role'] != "l")) || ($_SESSION['active'] != 1))
								{
									echo "<p class='text-danger'>";
									echo "Jūsu konts ir bloķēts!";
									echo "</p>";
								}
								else
								{
									echo "<p class='text-dark'>";
									echo "Jūs esat pieteicies!";
									echo "</p>";
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	include_once "footer.php";
?>