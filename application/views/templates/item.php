<?php $this->load->view('main/header'); ?>
<div class="col-md-7 col-md-offset-2">
	<div class="panel panel-green">
	<div class="panel-heading">Information</div>
		<div class="panel-body">
			<form method="post" id="formitem">
				<?php if($this->session->flashdata('message') != ''){ ?>
					<div class="alert alert-success alert-dismissible" role="alert" id="alertclose">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong><i class="fa fa-info"></i> <?php echo $this->session->flashdata("message"); ?></strong>
				</div>
				<?php } ?>
				<strong>Item </strong><hr>
				
				<div class="row">
					<div class="col-sm-3 control-label"><label>Select Date: </label></div>
					<div class="col-sm-4">
							 <div class="form-group input-group">
                                <input type="text" id="dpd1" name="selectdate" class="form-control input-sm">
                               <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-offset-3 col-md-1"><input type="submit" class="btn btn-success btn-sm" id="btnexport" value="Export To CSV"></div>
				</div>
				<div class="row" style="margin-top: 5px">
					<div class="col-md-offset-4 col-md-1"><img src="<?php echo base_url(); ?>assets/dist/spinnr/spin.gif" style="display:none"  id="img_spin"></div>
				</div>		
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('main/footer'); ?>