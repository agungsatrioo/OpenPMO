<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PMO</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/datepicker/datepicker3.css">
 
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/jQueryUI/jquery-ui.css">
  <link rel="stylesheet" href="<?php echo base_url("media")?>/custom.css">
    
     <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/data-tables/datatables.css">
     <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/tooltipster/css/tooltipster.bundle.css">
     <link rel="stylesheet" href="<?php echo base_url("media")?>/plugins/dropzone.js/dropzone.css">
    
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-black-light sidebar-mini sidebar-collapse">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url('home')?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>P</b>MO</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Project</b>Monitoring</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications: style can be found in dropdown.less -->
		  <?php if ($this->session->userdata('level')==1) { ?>
		   <li class="notifications-menu alert-warning">
			<a >
              <i class="fa fa-warning"></i>
			  <span class="hidden-xs">
				<b >WARNING</b>: You are in admin mode.
			  </span>
			  <span class="hidden-lg hidden-md hidden-sm">
				 Admin mode
			  </span>
            </a>
          </li>
		  <?php } ?>
		 
          <!-- User Account: style can be found in dropdown.less -->
          <li class="user">
            <a>
              <span><?php echo $this->session->userdata('profiles')['fullname'] ?></span>
            </a>
          </li>
		  <li>
            <a href="<?php echo site_url('login/logout') ?>" class="tipster" title="Logout">
              <span><i class="fa fa-sign-out"></i></span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </header>