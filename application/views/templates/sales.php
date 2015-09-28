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
				<strong>Sales Filter Date  </strong><hr>
				<div class="row">
					<div class="col-sm-3 control-label"><label>Date from: </label></div>
					<div class="col-sm-4">
							 <div class="form-group input-group">
                                <input type="text" id="dpd1" name="datefrom" class="form-control input-sm">
                               <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-3 control-label"><label>Date to: </label></div>
					<div class="col-sm-4">
							 <div class="form-group input-group">
                                <input type="text" id="dpd2" name="dateto" class="form-control input-sm">
                               <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-2 col-md-1"><input type="submit" class="btn btn-success btn-sm" value="Export To CSV"></div>
				</div>	
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('main/footer'); ?>