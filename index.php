<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Bilancio Economia Aziendale</title>
		<?php
			require(dirname(__FILE__) . "/utils/BilancioEA.php");
			$BilancioEA = new BilancioEA();
			if(!empty($_GET)){
				if(isset($_GET["exercise_id"])){
					$exerciseManager = $BilancioEA->getExerciseManager();
					$exerciseManager->setUserExercise($_GET["exercise_id"]);
					header("location: index.php#dashboard");
				}
			}
            require(dirname(__FILE__) . "/plugins/plugins.html");
        ?>
        
	</head>
	<body class="hold-transition skin-yellow ">
		<div class="wrapper">
			<header class="main-header">
				<a href="index.php" class="logo">
					<span class="logo-mini"><b>B</b>EA</span>
					<span class="logo-lg"><b>Bilancio</b>EA</span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li><a href="#add_Account" class="helpscout-support">Aggiungi Conti</a></li>
							<li><a href="#userExercises" class="helpscout-support">Esercizi</a></li>
							<li><a href="uploads/Gli%20indici%20di%20bilancio.pdf" class="helpscout-support">Teoria Indici</a></li>
							<?php if(isset($_SESSION["exercise"])){?>
								<li><a href="" id="btn-exit-exercise" class="helpscout-support">Esci dall'esercizio</a></li>
							<?php } ?>
						</ul>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar">
				<section class="sidebar">
					<div class="sidebar-form">
						<div class="input-group">
							<style>
								.select2-container--default .select2-selection--single {
									height: 35px !important;
								}
							</style>
							<select class="select form-control" style="height:100%;" name="account" id="select" size=30></select>
							<span class="input-group-btn">
								<button id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</div>
					<ul class="sidebar-menu">
						<li class="header">ELABORAZIONI</li>
						<li><a href="#incomeStatment"><i class="fa fa-circle-o"></i><span>Conto Economico</span></a></li>
						<li><a href="#balanceSheet"><i class="fa fa-circle-o"></i> <span>Stato Patrimoniale</span></a></li>
						<li class="header">RIELABORAZIONI</li>
						<li class="treeview">
							<a>
								<i class="fa fa-circle-o"></i> <span>Conto Economico (Rielab)</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li><a href="#reworkedIncomeStatment"><i class="fa fa-circle-o"></i> A Valore Aggiunto</a></li>
								<li><a href="#reworkedIncomeStatment2"><i class="fa fa-circle-o"></i> A Ricavi e Costi del Venduto</a></li>
							</ul>
						</li>
						<li class="treeview">
							<a>
								<i class="fa fa-circle-o"></i> <span>Stato Patrimoniale (Rielab)</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li><a href="#reworkedBalanceSheet"><i class="fa fa-circle-o"></i> Senza riparto Utile </a></li>
								<li><a href="#reworkedBalanceSheet2"><i class="fa fa-circle-o"></i> Con riparto Utile </a></li>
							</ul>
						</li>
						<li class="header">PROSPETTI</li>
						<li ><a href="#valueAdded"><i class="fa fa-circle-o"></i><span>Valore Aggiunto</span></a></li>
						<li ><a href="#costsOfSale"><i class="fa fa-circle-o"></i><span>Costo del venduto</span></a></li>
						<li ><a href="#profitSharing"><i class="fa fa-circle-o"></i><span>Riparto Utile</span></a></li>
						<li class="header">ANALISI</li>
                        <li ><a href="#profitabilityAnalysis"><i class="fa fa-circle-o"></i><span>Analisi della Reddività</span></a></li>
                        <li ><a href="#patrimonialAnalysis"><i class="fa fa-circle-o"></i><span>Analisi Patrimoniale</span></a></li>
                        <li ><a href="#financialAnalysis"><i class="fa fa-circle-o"></i><span>Analisi Finanziaria</span></a></li>
					</ul>
				</section>
			</aside>
			<div class="content-wrapper">
			</div>
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					Un progetto per la Prof.ssa <b>Antonella Mangano</b>
				</div>
				<strong>Copyright &copy; 2016-2017 <a href="http://www.itcbesta.gov.it/">ITCA F.Besta</a></strong>
		  </footer>
		</div>
		<!-- Modal conti sidebar -->
		<!-- Modal -->
		<div class="modal fade" id="sidebarAccountModal" tabindex="-1" role="dialog" aria-labelledby="sidebarAccountModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="sidebarAccountModalTitle"></h4>
					</div>
					<div class="modal-body">
						Conto n. <span id="sidebarAccountModalID"></span> <br>
						Eccedenza in <span id="sidebarAccountModalEccedence"></span> <br>
						Natura <span id="sidebarAccountModalNature"></span> <br>
						Questo conto rappresenta un <span id="sidebarAccountModalType"></span> <br>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">Ok</button>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script>

    $(document).ready(function(){
        initSelect();
    });

	$("#search-btn").click(function(){
		launchSidebarAccountModal($("#select").val());
	});		
	
	$("#btn-exit-exercise").click(function(){
		$.ajax({
				url: "utils/ajax/Exercises/manageExercise.php",
				context: document.body,
                data: {
                    action: "reset"
                },
                method: "GET"
			}).done(function(data) {
				 location.reload();
			});
	});

		//Amazing long name ;D
		function launchSidebarAccountModal(id) {
			$.ajax({
				url: "utils/ajax/Account/manageBalanceAccounts.php",
				context: document.body,
                data: {
                    action: "get",
                    account_id: id
                },
                method: "GET"
			}).done(function(data) {
				console.log(data);
				account = (JSON.parse(data).data);
				compileModal(account);
			});
		}

		function compileModal(account){
			var modal = $("#sidebarAccountModal");
			console.log(account);
			modal.find('#sidebarAccountModalTitle').text(account.name);
			modal.find('#sidebarAccountModalID').text(account.id);
			modal.find('#sidebarAccountModalEccedence').text(account.eccedence == "D" ? "Dare" : "Avere");
			modal.find('#sidebarAccountModalNature').text(account.nature == "P" ? "Patrimoniale" : "Economica");
			modal.find('#sidebarAccountModalType').text(account.type);
			modal.modal("show");
		}

	function initSelect(){
		$('.select').select2({
			placeholder: 'Seleziona un conto',
			allowClear: true,
			ajax: {
					url: function () {
					return "utils/ajax/Account/manageBalanceAccounts.php";
				},

				data: function (params){
					var query = {
						action : "search",
						account_name : params.term
					};
					return query;
				},
				cache: true,
				processResults: function (data) {
					obj = $.parseJSON(data);
					var categoriesArray = [];
					var accountsArray = [];
					var idex = 1;
					$.each(obj.data, function(key,value){
						accountsArray = [];

						$.each(value.accounts, function(key1,value1){
							accountsArray.push({
								id: value1.id,
								text: value1.name
							});
						});

						categoriesArray.push({
							id: idex++,
							text: value.name,
							children: accountsArray.slice(0)
						});

					});

					return {
						results: categoriesArray
					};
				}
			},
			language: {
				noResults: function(){
					return "Nessun contro trovato";
				},
				searching:function(){ 
					return"Sto cercando…"
				},
				errorLoading:function(){
					return"I risultati non possono essere caricati."
				}
			},
		});
	}

	</script>
</html>
