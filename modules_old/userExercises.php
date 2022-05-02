<section class="content-header">
    <h1>
        Esercizi
    </h1>
</section>

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
    
    $.ajax("utils/rest/get-exercises.php").done(function(data){
        $.each(JSON.parse(data),function(id,exercise){
            //console.log(exercise);
            dataSet.push([id, exercise.exercises_pages, exercise.exercises_difficulty, exercise.exercises_notes.charAt(0).toUpperCase() + exercise.exercises_notes.slice(1), getButtonHtml(id)]);
        });
        $('#exercisesTable').DataTable( {
            data: dataSet,
            columns: [
                { title: "ID" , "width": "10%", "className": "dt-center"},
                { title: "Pagina","width": "10%" },
                { title: "Difficoltà","width": "10%"},
                { title: "Descrizione","width": "55%","orderable": false},
                { title: "", "width": "15%" ,"orderable": false}
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
        
                    
        $(".buttonExercise").click(function(){
            window.location="utils/setupExercise.php?exercise_id=" + $(this).attr("exercise_id");
        });
        
    });
    
});

function getButtonHtml(id){
   return '<button exercise_id="'+id+'" type="button" class="btn btn-primary buttonExercise" style="width:100%">Seleziona</button>'; 
}

</script>