<?php $this->load->view('default_header'); ?>

<div class="panel-heading">
	<h3 class="panel-title"><b>Family Mart</b></h3>
</div>
<div class="panel-body">
<?php if (isset($process_error)) { ?>
	<div class="alert alert-danger">
		<p><?php echo $process_error ?></p>
	</div>
<?php } ?>
		
<form role="form" method="POST">
	<fieldset>
		<div class="form-group">
			<input class="form-control" placeholder="username" name="username" value="<?php echo set_value('username'); ?>" autofocus>
		</div>
		<div class="form-group">
			<input class="form-control" placeholder="Password" name="password" type="password">
		</div>
		<button type="submit" name="action" value="process" class="btn btn-success">Login </button>
	</fieldset>
</form>
</div>
<?php $this->load->view('default_footer'); ?>