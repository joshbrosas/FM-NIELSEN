<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<title>FamilyMart - <?php echo $pagetitle ?></title>	
	<!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url(); ?>assets/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- Timeline CSS -->
    <link href="<?php echo base_url(); ?>assets/dist/css/timeline.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/dist/css/datepicker.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets/dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url(); ?>assets/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<!-- DataTables CSS -->
    <link href="<?php echo base_url(); ?>assets/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="<?php echo base_url(); ?>assets/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url();?>index.php/main/index"><strong style="color: #5cb85c">FamilyMart <i>(Neilsen)</i> </strong></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">                    
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw" style="color: #5cb85c"></i> <strong style="color: #5cb85c"><?php echo $this->session->userdata('fm_username'); ?></strong>  <i class="fa fa-caret-down" style="color: #5cb85c"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
						<!--
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>-->
                        <li><a href="<?php echo base_url(); ?>index.php/main/index/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
							<li><a href="<?php echo base_url(); ?>index.php/main/index"><i class="fa fa-home fa-fw"></i>  Home</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/main/index/sales"><i class="fa fa-home fa-table"></i>  Nielsen <span class="fa arrow"></span></a>
                             <ul class="nav nav-second-level">
                                <li><a href="<?php echo base_url(); ?>index.php/main/index/sales">Sales</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/main/index/item">Item</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/main/index/store">Store</a></li>
                            </ul>       
                            </li>
							
							<li>
                
							</li>						
							<li>
                            <!--<a href="<?php //echo base_url(); ?>index.php/main/about"><i class="fa fa-info fa-fw"></i> About</a>-->
							</li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $pagetitle ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>