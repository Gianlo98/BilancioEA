<html>
	<?php
		session_start();
		if(!isset($_SESSION['accounts'])){
			header('Location: index.php');
			exit;
		}
	?>
	<style>
	</style>
	<head>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<script src="js/jquery-2.2.4.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<link  rel="stylesheet"  href="css/select2.min.css"/>
		<script src="js/select2.min.js"></script>
	</head>
	
	<body>
        <?php require("core/navbar.php");?>
		<div class="container">
								<div class="panel panel-primary" style="">
						<div class="panel-body">
							<table class="table table-striped table-hover ">
								<thead>
									<tr>
									<th>ID</th>
									<th>Nome Conto</th>
									<th>Importo Conto</th>
									</tr>
								</thead>
								<tbody>
									<?php 
                                        $accounts = $_SESSION['db_accounts'];
										foreach($_SESSION['accounts'] as $key => $import){
											echo "<tr>";
											echo "<td>" . $accounts[$key]->accounts_id . '</td>';
											echo "<td><div class=\"account_name test\" data-toggle=\"modal\" data-target=\"#exampleModal\" data-whatever=\"".$accounts[$key]->accounts_name."\">" . $accounts[$key]->accounts_name . '</div></td>';
											echo "<td>" . number_format ($import,0,",",".") . '</td>';
											echo "<td style=\"width:50px\"><a href=\"".$_SERVER['PHP_SELF']."?remove_id=".$accounts[$key]->accounts_id."\" style=\"color:grey\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a></br>";
											echo "</tr>";
										}
										if(empty($_SESSION['accounts'])){
											echo "<tr><td>Nessun conto inserito</td></tr>";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
		</div>
	</body>
</html>