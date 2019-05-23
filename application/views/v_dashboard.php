<!--
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ol>
    </section>
      <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">	  
		  <div class="box box-primary">
		  <div class="box-body box-profile">
              <?php if(!empty($this->session->userdata('dashboard')['img'])) {?> <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url('media/uploads/').$this->session->userdata('dashboard')['img'] ?>" alt="User profile picture"><?php } ?>

              <h3 class="profile-username text-center"><?php echo $this->session->userdata('dashboard')['fullname'] ?></h3>

              <p class="text-muted text-center"><?php echo $desc ?></p>

            </div>
            </div>

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <p><?php echo $this->session->userdata('dashboard')['about_me'];?>
			  
			  <?php
					
			  ?>
			  
			  </p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		
		<div class="col-md-9">
		
		<div class="row">
			<div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-aqua">
				<div class="inner">
				  <h3><?php echo $this->db->count_all('t_project') ?></h3>

				  <p>All Project(s)</p>
				</div>
				<div class="icon">
				  <i class="fa fa-window-maximize"></i>
				</div>
				
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-green">
				<div class="inner">
				  <h3><?php echo $this->db->count_all('t_project_progress') ?></sup></h3>

				  <p>All Task(s)</p>
				</div>
				<div class="icon">
				  <i class="fa fa-tasks"></i>
				</div>
				
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-yellow">
				<div class="inner">
				  <h3><?php echo $myprojs ?></h3>

				  <p>My Task(s)</p>
				</div>
				<div class="icon">
				  <i class="fa fa-tasks"></i>
				</div>
				
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-red">
				<div class="inner">
				  <h3><?php echo $unfinprojs ?></h3>

				  <p>My Unfinished Task(s)</p>
				</div>
				<div class="icon">
				  <i class="ion ion-pie-graph"></i>
				</div>
				
			  </div>
			</div>
			<!-- ./col -->
		  </div>
	  
			<div class="col-md-12">
			  <div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">Daily Progress Report</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				  <div class="row">
					<div class="col-md-12">

					  <div class="chart">
						<!-- Sales Chart Canvas -->
						<div id="chart-make" style="height: 250px;"></div>
					  </div>
					  <!-- /.chart-responsive -->
					</div>
					<!-- /.col -->
					<!-- /.col -->
				  </div>
				  <!-- /.row -->
				</div>
				<!-- ./box-body -->
				<!-- /.box-footer -->
			  </div>
			  <!-- /.box -->
			</div>
		</div>
      <!-- /.row -->
  </div>



  <!-- /.content-wrapper -->