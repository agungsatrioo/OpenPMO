  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li <?php echo current_url()==base_url("home")?"class='hover'":"" ?>>
            <a href="<?php echo base_url("home") ?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>
        <li <?php echo current_url()==base_url("project")?"class='hover'":"" ?>>
            <a href="<?php echo base_url("project") ?>">
                <i class="fa fa-window-restore"></i> <span>Project List</span>
            </a>
        </li>        
		<li <?php echo current_url()==base_url("project/my_tasks")?"class='hover'":"" ?>>
            <a href="<?php echo base_url("project/my_tasks") ?>">
                <i class="fa fa-tasks"></i> <span>My Tasks</span>
            </a>
        </li>
          <li <?php echo current_url()==base_url("project/my_subtasks")?"class='hover'":"" ?>>
            <a href="<?php echo base_url("project/my_subtasks") ?>">
                <i class="fa fa-tasks"></i> <span>My Subtasks</span>
            </a>
        </li>
      </ul>
	  <?php if($this->session->userdata('level')==1) { ?>
        <ul class="sidebar-menu">
            <li class="header">ADMINISTRATOR MENU</li>
            <li <?php echo current_url()==base_url("user")?"class='hover'":"" ?>>
                <a href="<?php echo base_url("user") ?>">
                    <i class="fa fa-group"></i> <span>User List</span>
                </a>
            </li>            
			<li <?php echo current_url()==base_url("project/all_tasks")?"class='hover'":"" ?>>
                <a href="<?php echo base_url("project/all_tasks") ?>">
                    <i class="fa fa-database"></i> <span>All Tasks</span>
                </a>
            </li>
                    </li>
          <li <?php echo current_url()==base_url("project/all_subtasks")?"class='hover'":"" ?>>
            <a href="<?php echo base_url("project/all_subtasks") ?>">
                <i class="fa fa-tasks"></i> <span>All Subtasks</span>
            </a>
        </li>
        </ul>
	  <?php } ?>
    </section>
    <!-- /.sidebar -->
  </aside>