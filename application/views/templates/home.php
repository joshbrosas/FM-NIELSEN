<?php $this->load->view('main/header'); ?>
<div class="panel panel-green">
	<div class="panel-heading">
		Information
	</div>
	<div class="panel-body">
	<strong>Welcome to <b style="color: #5cb85c">FamilyMart</b> . To begin, please select the job you wanted to perform on the left side menu.
	</div>
	</div>

	<?php
$ddate = "1/5/2015";
$date = new DateTime($ddate);
$week = $date->format("W");
$year = $date->format('Y');
echo "Weeknumber: $year$week"; ?>
	</div>
<?php $this->load->view('main/footer'); ?>