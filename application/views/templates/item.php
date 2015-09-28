<?php $this->load->view('main/header'); ?>
<div class="col-md-7 col-md-offset-2">
	<div class="panel panel-success">
	<div class="panel-heading">Information</div>
		<div class="panel-body">
			<form method="post">
				<strong>Item Filter Date  </strong><hr>
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