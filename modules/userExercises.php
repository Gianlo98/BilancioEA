<style>
	#section_name{
		display: inline-block;
		line-height: 150%;
	}
	
	.auth_btn{
		width: 30%;
		isplay: inline-block;
		float: right;
	}
    
    @media screen and (max-width: 1440px) {
        .manager_btn{
		  width: 25% !important;
        }
    }
	
	.manager_btn{
		width: 10%;
		display: inline-block;
		float: right;
	}
	
	#create_exercise_btn{
		margin-right: 10px;
	}


</style>

<?php

    require_once(dirname(__FILE__) . "/../utils/BilancioEA.php");
    $BilancioEA = new BilancioEA();

    $exerciseManager = $BilancioEA->getExerciseManager();
	
?>
<section class="content-header">
    <h1 id="section_name"> Esercizi </h1>
	<?php 
	//if($exerciseManager->getUserExerciseID() != false) echo " attualmente caricato:" . $exerciseManager->getUserExerciseID();
	if($exerciseManager->isAuth()){
		echo '<button type="button" id="exit_manage_exercises_btn" class="btn btn-danger manager_btn">Esci modalità gestione</button>';
		echo '<button type="button" id="create_exercise_btn" class="btn btn-primary manager_btn">Nuovo Esercizio</button>';
	}else{ 
		echo '<button type="button" id="manage_exercises_btn" class="btn btn-primary auth_btn ">Entra modalità gestione</button>';
	}?>
</section>

<div class="modal fade" id="modal_create_exercises" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Creazione nuovo esercizio (Saranno inclusi tutti i conti attualmente usati)</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label for="input_exercise_id" class="col-lg-2 control-label">Numero</label>
							<div class="col-lg-10">
								<input type="number" class="form-control" id="input_exercise_id" placeholder="ID Esercizio">
							</div>
						</div>
						<div class="form-group">
							<label for="input_exercise_pages" class="col-lg-2 control-label">Pagina</label>
							<div class="col-lg-10">
								<input type="number" class="form-control" id="input_exercise_pages" placeholder="Pagina">
							</div>
						</div>						
						<div class="form-group">
							<label for="input_exercise_difficulty" class="col-lg-2 control-label">Difficoltà</label>
							<div class="col-lg-10">
								<input type="number" class="form-control" id="input_exercise_difficulty" placeholder="Difficoltà">
							</div>
						</div>						
						<div class="form-group">
							<label for="input_exercise_notes" class="col-lg-2 control-label">Note</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="3" id="input_exercise_notes" placeholder="Esercizio sul conto economico con..."></textarea>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
				<button type="button" id="btn-create-Exercise" class="btn btn-primary">Crea</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_propriety_exercises" exercise_id="" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Proprietà dell'esercizio</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<fieldset>
                        <div class="form-group">
							<label for="input_subscribed_propriety" class="col-lg-8 control-label">Quota credito soci già richiamata</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_subscribed_propriety" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label for="input_resources_propriety" class="col-lg-8 control-label">Immobilizzazioni finanziarie esigilibili entro l'esercizio successivo</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_resources_propriety" placeholder="">
							</div>
						</div>						
						<div class="form-group">
							<label for="input_credit_propriety" class="col-lg-8 control-label">Crediti esigilibili oltre l'esercizio successivo</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_credit_propriety" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-8 control-label">
                                <label for="input_debts_propriety">Debiti esigilibi oltre l'esercizio successivo</label>
                                <span class="help-block">Ad eccezione dei prestiti obbligazionari e dei mutui passivi che sono già inclusi</span>
                            </div>
                            <div class="col-lg-4">
								<input type="number" class="form-control" id="input_debts_propriety" placeholder="">
							</div>
						</div>
                        <div class="form-group">
							<label for="input_severance_propriety" class="col-lg-8 control-label">Quota TRF in liquidazione</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_severance_propriety" placeholder="">
							</div>
						</div>	
						<div class="form-group">
							<label for="input_shareholders_propriety" class="col-lg-8 control-label">Numero di Azionisti</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_shareholders_propriety" placeholder="">
							</div>
						</div>							
                        <div class="form-group">
							<label for="input_statutory_reserve_propriety" class="col-lg-8 control-label">Riserva Statutaria</label>
							<div class="col-lg-4">
                                <div class="input-group">
								    <input type="number" class="form-control" id="input_statutory_reserve_propriety" placeholder="">
                                    <span class="input-group-addon">%</span>
                                </div>
							</div>
						</div>							
                        <div class="form-group">
							<label for="input_straordinary_reserve_propriety" class="col-lg-8 control-label">Importo riserva Straoridinaria</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_straordinary_reserve_propriety" placeholder="">
							</div>
						</div>                        
						<div class="form-group">
							<label for="input_industrials_costs_propriety" class="col-lg-8 control-label">Costi Industriali</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_industrials_costs_propriety" placeholder="">
							</div>
						</div>                       
						<div class="form-group">
							<label for="input_administratives_costs_propriety" class="col-lg-8 control-label">Costi Amministrativi</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_administratives_costs_propriety" placeholder="">
							</div>
						</div>                        
						<div class="form-group">
							<label for="input_commercials_costs_propriety" class="col-lg-8 control-label">Costi Commerciali</label>
							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_commercials_costs_propriety" placeholder="">
							</div>
						</div>						
						<div class="form-group">
						
							<div class="col-lg-8 control-label">
                                <label for="input_accessory_managment_propriety" class="control-label">Risultato Gestione Accessoria</label>
                                <span class="help-block">Indicare col segno - se negativa</span>
                            </div>
							

							<div class="col-lg-4">
								<input type="number" class="form-control" id="input_accessory_managment_propriety" placeholder="">
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
				<button type="button" id="btn-save-proprieties-exercise" class="btn btn-primary">Salva</button>
			</div>
		</div>
	</div>
</div>

<section class="content">
    <div class="box">
        <div class="box-body">
                <table id="exercisesTable" class="table table-bordered table-striped dataTable" role="grid">
        </div>
    </div>
</section>
<script>
    
$(function() {
    
    var dataSet = [];    
    
    $.ajax("utils/ajax/Exercises/manageExercise.php?action=get_all").done(function(data){
        $.each(JSON.parse(data).data,function(key,exercise){
            //console.log(exercise);
            dataSet.push([
				exercise.exercises_id, 
				exercise.exercises_pages,
				exercise.exercises_difficulty, 
				exercise.exercises_notes.charAt(0).toUpperCase() + exercise.exercises_notes.slice(1), 
				getSelectExerciseBtn(exercise.exercises_id)
				<?php 
					if($exerciseManager->isAuth()){ 
						echo ',getEditExerciseBtn(exercise.exercises_id),getDeleteExerciseBtn(exercise.exercises_id)';
					}
				?>
			]);
        });
        $('#exercisesTable').DataTable( {
            data: dataSet,
            columns: [
                { title: "ID" , "width": "10%", "className": "dt-center"},
                { title: "Pagina","width": "10%" },
                { title: "Difficoltà","width": "10%"},
                { title: "Descrizione","width": "50%","orderable": false},
                { title: "", "width": "15%" ,"orderable": false}
				<?php if($exerciseManager->isAuth()){ 
					echo',{ title: "", "width": "30%" ,"orderable": false}';
					echo',{ title: "", "width": "30%" ,"orderable": false}';
				
				
				}?>
            ],
            "language": {
                "lengthMenu": "Mostro _MENU_ esercizi per pagina",
                "search": "Cerca:",
                "zeroRecords": "Nessun esercizio trovato :C ",
                "info": "Mostro pagina _PAGE_ di _PAGES_",
                "infoEmpty": "Nessun esercizio trovato",
                "infoFiltered": "(Cercati su _MAX_ esercizi)",
                "paginate": {
                    "first":      "Primo",
                    "last":       "Ultimo",
                    "next":       "Avanti",
                    "previous":   "Indietro"
                }
            }
        });
        
                    
        $(".btn_select_exercise").click(function(){
            window.location="index.php?exercise_id=" + $(this).attr("exercise_id");
        });
		
		$(".btn_delete_exercise").click(function(){
			ex_id =  $(this).attr("exercise_id");
			swal({
				title:"Sicuro di volerlo fare ?",
				text:"Sei sicuro di voler eliminare l'esercizio " + $(this).attr("exercise_id"),
				showCancelButton: true,
				confirmButtonText: "Elimina",
				cancelButtonText: "Annulla"
			}).then(function(){
				$.ajax({
					url: "utils/ajax/Exercises/manageExercise.php?action=delete",
					data: { exercise_id: ex_id },
					method: "GET"
				}).done(function(data){
					console.log(data);
					location.reload();
				});
			});
		});
		
		$(".btn_propriety_exercise").click(function(){
			var ex_id = $(this).attr('exercise_id');
			$.ajax({
				url: "utils/ajax/Exercises/manageExercise.php",
				data: {action: "get_properties", exercise_id: ex_id},
				method: "GET"
			}).done(function(data){
				//input_debts_propriety
				//input_credit_propriety
				//input_shareholders_propriety
				var modal=$("#modal_propriety_exercises");
				modal.attr("exercise_id",ex_id);
				modal.find("#input_debts_propriety").val(null);
				modal.find("#input_shareholders_propriety").val(null);
				modal.find("#input_credit_propriety").val(null);
				modal.find("#input_resources_propriety").val(null);
                modal.find("#input_subscribed_propriety").val(null);
                modal.find("#input_severance_propriety").val(null);
                modal.find("#input_statutory_reserve_propriety").val(null);
                modal.find("#input_straordinary_reserve_propriety").val(null);
                modal.find("#input_industrials_costs_propriety").val(null);
                modal.find("#input_administratives_costs_propriety").val(null);
                modal.find("#input_commercials_costs_propriety").val(null);
                modal.find("#input_accessory_managment_propriety").val(null);
				response = JSON.parse(data);
				propriety = response.data;
				$.each(propriety,function(key,value){
					ex_propriety = value.exercise_propriety;
					propriety_value = value.exercise_propriety_value;
					switch(ex_propriety){
						case "DEBTS_AFTER_YEAR":
							modal.find("#input_debts_propriety").val(propriety_value);
							break;						
						case "CREDIT_AFTER_YEAR":
							modal.find("#input_credit_propriety").val(propriety_value);
							break;							
						case "LTRESOURCES_IN_YEAR":
							modal.find("#input_resources_propriety").val(propriety_value);
							break;						
						case "SHAREHOLDERS":
							modal.find("#input_shareholders_propriety").val(propriety_value);
							break;
                        case "SEVERANCE_PAY_LIQUIDATED":
                            modal.find("#input_subscribed_propriety").val(propriety_value);
                            break;
                        case "SUBSCRIBED_CAPITAL":
                            modal.find("#input_severance_propriety").val(propriety_value);
                            break;                        
                        case "STATUTORY_RESERVE":
                            modal.find("#input_statutory_reserve_propriety").val(propriety_value);
                            break;                       
                        case "STRAORDINARY_RESERVE":
                            modal.find("#input_straordinary_reserve_propriety").val(propriety_value);
                            break;                        
						case "COMMERCIALS_COSTS":
                            modal.find("#input_commercials_costs_propriety").val(propriety_value);
                            break;                        
						case "ADMINISTRATIVES_COSTS":
                            modal.find("#input_administratives_costs_propriety").val(propriety_value);
                            break;                       
						case "INDUSTRIALS_COSTS":
                            modal.find("#input_industrials_costs_propriety").val(propriety_value);
                            break;						
						case "ACCESSORY_MANAGMENT":
                            modal.find("#input_accessory_managment_propriety").val(propriety_value);
                            break;
					}
				});
				modal.modal('show');
			});
		});
	});
});
$("#manage_exercises_btn").click(function(){
	swal({
		title: "Controllo Accesso",
		text: "Inserisci il codice per la modifica degli esercizi:",
		input: 'password',
		showCancelButton: true,
		animation: "slide-from-top",
		inputPlaceholder: "#MATURITA' 2017",
		confirmButtonText: 'Controlla!',
		cancelButtonText: 'Annulla',
		preConfirm: function (inputValue) {
			return new Promise(function (resolve, reject) {
				if (inputValue === "") {
					swal.showValidationError("Almeno scrivi qualcosa!");
					swal.enableButtons()
				}else{
					$.ajax({
						url:"utils/ajax/Exercises/manageExercise.php?action=auth_key",
						method : "POST",
						data: {manager_key : inputValue}
					}).done(function(data){
						if(JSON.parse(data).data === true){
							swal({
								title: "Autenticato!", 
								type: "success",
								timer: 2000
							}).then(function(){
									location.reload()
								},function (dismiss) {
									if (dismiss === 'timer') {
									  location.reload()
									}
								});
						}else {
							swal.showValidationError("Codice errato!");
							swal.enableButtons()
						}
					});
				}
			})
		}
	})
});

$("#exit_manage_exercises_btn").click(function(){
	swal({
	  title: 'Uscire dalla modalità gestione ?',
	  text: "Non potrai più cancellare/creare nuovi esercizi!",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonText: 'Esci!',
	  cancelButtonText: 'Annulla'
	}).then(function () {
		$.ajax({
			url:"utils/ajax/Exercises/manageExercise.php?action=deauth_key"
		}).done(function(data){
			console.log(data);
			if(JSON.parse(data).status === "Done"){
				swal({
					title: 'Successo!',
					text: 'Sei uscito dalla modalià di gestione degli esercizi.',
					type: 'success',
					timer: 2000
				}).then(function(){
					location.reload()
				},function (dismiss) {
					if (dismiss === 'timer') {
					  location.reload()
					}
				});
			}else{
				swal(
					'Errore!',
					'C\è stato un errore imprevisto',
					'danger'
				)
			}
		});
	})

});

//$("#ex11").slider({step: 5, min: 0, max: 100});

$("#btn-create-Exercise").click(function(){
	$.ajax({
		url: "utils/ajax/Exercises/manageExercise.php?action=create",
		data: {
			exercise_id: $("#input_exercise_id").val(),
			exercise_difficulty: $("#input_exercise_difficulty").val(),
			exercise_pages: $("#input_exercise_pages").val(),
			exercise_notes: $("#input_exercise_notes").val(),
			},
		method: "POST"
	}).done(function(data){
		console.log(data);
		location.reload();
	});
});

$("#create_exercise_btn").click(function(){
	$("#modal_create_exercises").modal("show");
});

$("#btn-save-proprieties-exercise").click(function(){
	//TODO EVITARE SALVATAGGIO OGNI VOLTA !!!!
	var modal=$("#modal_propriety_exercises")
	debts = modal.find("#input_debts_propriety").val();
	credit = modal.find("#input_credit_propriety").val();
	resources = modal.find("#input_resources_propriety").val();
	shareholders = modal.find("#input_shareholders_propriety").val();
    subscribed_cp = modal.find("#input_subscribed_propriety").val();
    severance_pay_liq = modal.find("#input_severance_propriety").val();
    statutory_reserve= modal.find("#input_statutory_reserve_propriety").val();
    straordinary_reserve= modal.find("#input_straordinary_reserve_propriety").val();
    commercials_costs= modal.find("#input_commercials_costs_propriety").val();
    administratives_costs= modal.find("#input_administratives_costs_propriety").val();
    industrials_costs= modal.find("#input_industrials_costs_propriety").val();
    accessory_managment= modal.find("#input_accessory_managment_propriety").val();
	ex_id = modal.attr("exercise_id")
	proprieties = [
        {name: "DEBTS_AFTER_YEAR", value : debts},
        {name: "CREDIT_AFTER_YEAR", value: credit},
        {name: "SHAREHOLDERS", value: shareholders},
        {name: "SUBSCRIBED_CAPITAL", value: subscribed_cp},
        {name: "SEVERANCE_PAY_LIQUIDATED", value: severance_pay_liq},
        {name: "LTRESOURCES_IN_YEAR", value: resources},
        {name: "STATUTORY_RESERVE", value: statutory_reserve},
        {name: "STRAORDINARY_RESERVE", value: straordinary_reserve},
        {name: "INDUSTRIALS_COSTS", value: industrials_costs},
        {name: "ADMINISTRATIVES_COSTS", value: administratives_costs},
        {name: "COMMERCIALS_COSTS", value: commercials_costs},
        {name: "ACCESSORY_MANAGMENT", value: accessory_managment}
    ];
	/*$.each(proprieties, function(key,propriety){
		$.ajax({
			url: "utils/ajax/Exercises/manageExercise.php",
			data: {action: "set_propriety", exercise_id: ex_id, exercise_propriety: propriety.name , exercise_propriety_value: propriety.value},
			method: "POST"
		}).done(function(data){
			/*console.log(data)
			modal.modal('hide');
		});
	});*/
	
	$.ajax({
			url: "utils/ajax/Exercises/manageExercise.php",
			data: {action: "set_propriety", exercise_id: ex_id, exercise_proprieties: proprieties},
			method: "POST"
		}).done(function(data){
			modal.modal('hide');
		});
	
});

function getSelectExerciseBtn(id){
   return '<button exercise_id="'+id+'" type="button" class="btn btn-primary btn_select_exercise" style="width:100%">Seleziona</button>'; 
}
function getEditExerciseBtn(id){
   return '<button exercise_id="'+id+'" type="button" class="btn btn-warning btn_propriety_exercise" style="width:100%">Proprietà</button>'; 
}
function getDeleteExerciseBtn(id){
   return '<button exercise_id="'+id+'" type="button" class="btn btn-danger btn_delete_exercise" style="width:100%">Elimina</button>'; 
}

</script>