<?php $this->load->view('main/header'); ?>
<div class="col-md-7 col-md-offset-2">
	<div class="panel panel-green">
	<div class="panel-heading">Information</div>
		<div class="panel-body">
			<form method="post">
				<?php if($this->session->flashdata('message') != ''){ ?>
					<div class="alert alert-success alert-dismissible" role="alert" id="alertclose">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong><i class="fa fa-info"></i> <?php echo $this->session->flashdata("message"); ?></strong>
				</div>
				<?php } ?>
				<strong>Store </strong><hr>

				<div class="row">
					<div class="col-md-offset-4 col-md-1"><input type="submit" class="btn btn-success btn-sm" value="Export To CSV"></div>
				</div>
				<div class="row" style="margin-top: 5px">
					<div class="col-md-offset-4 col-md-1"><img src="<?php echo base_url(); ?>assets/dist/spinnr/spin.gif" style="display:none"  id="img_spin"></div>
				</div>		
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('main/footer'); ?>