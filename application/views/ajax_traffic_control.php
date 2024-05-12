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