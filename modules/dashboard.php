<?php
	require(dirname(__FILE__) . "/../utils/BilancioEA.php");
	$BilancioEA = new BilancioEA();
?>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
</head>

<script> var exercise_id = null</script>

<style>
#exerciseQR{
	display: inline-block;
}
.row_left{
	display: inline-block;
    left: -12vw;
    top: 80px;
    height: 300px;
}
.row_right{
	display: inline-block;
    right: -15vw;
    top: 85px;
    height: 300px;
	transform: scaleX(-1) !important;
	margin-top: 5px;
}
.description{
	font-size: 20px;
}
.qrcodeContainer{
	position: relative;
	width: 100%;
	text-align: center;
}
.presentation_container{
	text-align: center;
}
.presentation_title{
	font-size: 3vw;
}
.main-footer {
    z-index: 2;
    position: relative;
}
.content-wrapper{
	position: relative;
}
.ipad-photo{
	position: absolute;
    left: 0;
    right: 0;
    margin: auto;
    bottom: 0;
    z-index: 0;
}
#exercise-btn{
	display: block;
    width: 23vw;
    margin: auto;
    margin-top: 5vh;
    font-size: 30px;
	}
/*iPhone*/	
@media only screen 
and (min-device-width : 375px) 
and (max-device-width : 667px) 
and (orientation : portrait) {
	.ipad-photo{
		display: none;
	}
	#exercise-btn{
	display: block;
    width: 100%;
    margin: auto;
    margin-top: 5vh;
    font-size: 30px;
	}
	.row_right{
		display: none !important;
	}	
	.row_left{
		display: none !important;
	}	
	
		#exerciseQR{
		width:100%;
	}
		
}
/*iPad*/
@media only screen 
and (min-device-width : 768px) 
and (max-device-width : 1024px)  {
		
	.ipad-photo{
		display: none !important;
	}	
	.row_right{
		display: none !important;
	}	
	.row_left{
		display: none !important;
	}	
		
	#exercise-btn{
		display: block;
		width: 50vw;
		margin: auto;
		margin-top: 5vh;
		font-size: 30px;
		}
	
	
}

</style>

<section class="content">
	<?php if(isset($_SESSION['exercise'])){ ?>
	<div class="box">
		<div class="box-header with-border">
			<span class="box-title">
				<h1>Esercizio caricato <?php echo $_SESSION['exercise']; ?></h1>
			</span>
		</div>
		<div class="box-body">
			<div class="row animated bounceIn">
				<div class="qrcodeContainer col-md-3">
				<img class="row_left" src="http://www.onlygfx.com/wp-content/uploads/2016/07/hand-drawn-arrow-20.png">
				  <?php
					echo "<script> exercise_id = \"" . $_SESSION['exercise'] . "\";</script>";
					echo '<img class="" src="public/plugins/phpqrcode/temp/yesQR_b6167f7117a4b36e1ae81bdc07d4249d.png" id="exerciseQR">';
				  ?>
					<script>
						
						if(exercise_id !== null){
							//http://data.baeproject.eu/BilancioEA/utils/QRcode/exerciseQR_00.png
							$("#exerciseQR").attr("src","utils/QRcode/exerciseQR_" + String(exercise_id).replace(".","") + ".png")
						}
					</script>
				
				<img class="row_right" src="http://www.onlygfx.com/wp-content/uploads/2016/07/hand-drawn-arrow-20.png">
				</div>
			</div>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<span class="description">
					Puoi caricare l'esercizio sul tuo dispositivo scannerizzando il QRcode oppure collegandoti all'indidirizzo: <span style="color:#f39c12; word-wrap: break-word;"><?php echo $_SERVER['HTTP_REFERER'] . "?exercise_id=" . $_SESSION['exercise']; ?></span>
					
					</span>
				</div>
			</div>
		</div>
	</div>
	<?php }else{ ?>
		<div class="presentation_container">
			<span class="presentation_title animated bounceIn"> Bilancio<span style="color: #f39c12">EA</span>, un progetto per la scuola 2.0 </span>
			<a href="#userExercises"><btn id="exercise-btn" class="btn btn-success animated bounceInLeft"> Seleziona un esercizio </btn></a>
			<img class="ipad-photo  animated bounceInUp" src="/BilancioEA/uploads/EA.png">
			
			
		<div>
			<img 
		</div>
	<?php }?>
</section>