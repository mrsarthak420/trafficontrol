<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Trafic Control</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<style type="text/css">

		.trafic_slug{
			background-color: #f4f4f4;
			text-align: center;
		}
		.trafic_slug > label{
			border-radius: 50px;
			padding: 10px;
			display: block;
		}
	</style>
</head>
<body>
	<h1>Traffic Control</h1>
	<div class="container">
		<div class="error_message" style="display: none;"><div class="alert alert-danger">Please enter proper data</div></div>
		<div class="success_message" style="display: none;"><div class="alert alert-success">Successfully updated.</div></div>
		<form method="post" action="" id="trafficForm">
			<div class="row trafic_slug_row">
				<?php if(!empty($traffic_control)){
					foreach ($traffic_control as $key => $value) {
				 ?>
					<div class="col col-md-5 ">
						<div class="m-4 trafic_slug" data-id="<?php echo $value->id ?>">
							<label class="bg-danger p-3"><?php echo $value->name; ?></label>
							<input type="number" name="sequence<?php echo $value->id ?>" value="<?php echo $value->sequence; ?>" class="form-control" required min="1" />
						</div>	
					</div>
				<?php } } ?>
			</div>

			<div class="row">
				<div class="text-center col col-md-6 bg-success p-2">
					<label class=" text-white">For Green light Interval</label>
					<input type="number" class="form-control" value="2" id="green_interval" min="1" name="green_interval" required>
				</div>
				<div class="text-center col col-md-6 bg-warning p-2">
					<label class="">For Yellow light Interval</label>
					<input type="number" class="form-control" value="2" id="yellow_interval" min="1" name="yellow_interval" required>
				</div>
			</div>
			<br>
			<div class="text-center">
				<button type="submit" class="btn start btn-success start-btn">Start</button>
				<button type="button" class="btn stop btn-secondary stop-btn">Stop</button>
			</div>
		</form>
	</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script type="text/javascript">
	$('#trafficForm').on('submit',function(e){
		e.preventDefault();
		//get traffic name with sequence
		var trafic_arr = [];

		$('.trafic_slug').each(function(){
			$(this).children('label').removeClass('bg-warning').removeClass('bg-success').addClass('bg-danger');
			let value = {
				'id' : $(this).data('id'),
				'sequence' : $('input[name=sequence'+$(this).data('id')+']').val(),
			};
			trafic_arr.push(value);
		});

		jQuery.ajax({
	        type : "POST",
	        dataType : "json",
	        url : "<?php echo base_url(); ?>"+'TrafficControl/start',
	        data : {
	        	"trafic_arr":trafic_arr,
	        },
	        beforeSend: function(){
	        },
	        success: function(response) {
	        	if(response.action=='success'){
	        		$('.trafic_slug_row').html('');
	        		$('.trafic_slug_row').html(response.traffic_control);
	        		$('.success_message').show();
	        		setTimeout(function() {$('.success_message').hide();}, 3000);
	        		$('.error_message').hide();

	        		//execute interval
	        		intervalRun(trafic_arr); 
	        	}else{
	        		$('.success_message').hide();
	        		$('.error_message').show();
	        		setTimeout(function() {$('.error_message').hide();}, 3000);
	        	}
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
	            alert(errorThrown);
	        }
	    });
	});
	function intervalRun(trafic_arr){
		var green_interval = parseInt($('#green_interval').val())*1000;
		var yellow_interval = parseInt($('#yellow_interval').val())*1000;
		//sort with sequence
		if(trafic_arr.length!=0){
			trafic_arr.sort(function(a, b){
	            return a.sequence.localeCompare(b.sequence);
	        });
	        var inc = 1; var yellowInt;
	        var firstElement = $('.trafic_slug[data-id='+trafic_arr[0].id+'] label');
	        //default green for first traffic signal
	        firstElement.addClass('bg-warning').removeClass('bg-danger').removeClass('bg-success');
    		yellowInt = setTimeout(function() {
				firstElement.addClass('bg-success').removeClass('bg-warning').removeClass('bg-danger');
    		}, yellow_interval);

	        var greenInt = setInterval(function(){
		        trafic_arr.forEach(function(value,index){
		        	var element = $('.trafic_slug[data-id='+value.id+'] label');
		        	if(index==inc){
		        		//add green
			    		element.addClass('bg-warning').removeClass('bg-danger').removeClass('bg-success');
			    		yellowInt = setTimeout(function() {
	        				element.addClass('bg-success').removeClass('bg-warning').removeClass('bg-danger');
		        		}, yellow_interval);
		        	}else{
		        		if(index!=0){
			        		//remove green for other signals
			        		element.addClass('bg-danger').removeClass('bg-success').removeClass('bg-warning');
		        		}else{
		        			yellowInt = setTimeout(function() {
		        				element.addClass('bg-danger').removeClass('bg-warning').removeClass('bg-success');
			        		}, yellow_interval);
		        		}
		        	}
		        });
		        inc++;
        	},green_interval);
	        $('.start-btn').on('click',function(){
	        	clearTimeout(greenInt);
	        });
	        //for stop traffic lights
			$('.stop-btn').on('click',function(){
				clearTimeout(greenInt);
				clearTimeout(yellowInt);
				$('.trafic_slug').each(function(){
					$(this).children('label').removeClass('bg-warning').removeClass('bg-success').addClass('bg-danger');
				});
				return false;
			});
		}
	}
</script>
</body>
</html>
