<?php
function warning($message){
	?>
	<div onclick="this.style.display = 'none'" class="warning" >
		<p>
			<?php echo $message ?>
		</p>
	</div>	
<?php
}
?>
