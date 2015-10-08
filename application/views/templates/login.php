<?php $this->load->view('default_header'); ?>

<div class="panel-heading">
	<h3 class="panel-title"><b>FamilyMart <i>(Nielsen)</i></b></h3>
</div>
<div class="panel-body">
<?php if (isset($process_error)) { ?>
	<div class="alert alert-danger">
		<p><?php echo $process_error ?></p>
	</div>
<?php } ?>
		
<form role="form" method="POST">
	<fieldset>
		<?php if($this->session->flashdata('message') != ""){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert" style="font-size: 13px">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <strong>Warning!</strong> <?php echo $this->session->flashdata("message"); ?>
		</div>
		<?php } ?>
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