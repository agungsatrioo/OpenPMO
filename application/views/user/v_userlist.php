<div class="content-wrapper">
	<section class="content-header">
		<h1>User Lists</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">User Lists</li>
		</ol>
	</section>
    
	<section class="content">
		<div class="row">
			<div class="col-lg-12 box-list">
				<?php if (!empty($success)) { ?>
                         <div class="callout callout-info">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4>Success!</h4>
							<p>Your request has been runned with this message: <?php echo $success ?></p>
						</div>
					<?php } ?>
                    <?php if (!empty($error)) { ?>
                        <div class="callout callout-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4>Error has been occured!</h4>
						
						<p>Your request has been aborted with this message: <?php echo $error ?></p>
						</div>
				<?php } ?>
				<div class="box box-primary">
					<div class="box-body">
						<table id="data-table" class="table table-bordered table-striped table-hover" style="width: 100%;">
                            <?php if ($this->session->userdata('level') == 1) { ?>
							<a href="<?php echo site_url("user/user_form") ?>" class="btn btn-primary dt-buttons">
								<i class="fa fa-plus"></i> Add User
							</a>
							<?php } ?>
							<thead class="text-align: center">
								<tr>
                                    <th rowspan="3">No</th>
                                    <th rowspan="3">Username<br></th>
                                    <th rowspan="3">Status</th>
                                    <th colspan="9">Privileges</th>
                                    <th rowspan="3">Action</th>
                                  </tr>
                                  <tr>
                                    <th colspan="3">Project</th>
                                    <th colspan="3">Task<br></th>
                                    <th colspan="3">Subtask</th>
                                  </tr>
                                  <tr>
                                    <th>Create</th>
                                    <th>Edit</th>
                                    <th>Delete</th>                                         
                                    <th>Create</th>
                                    <th>Edit</th>
                                    <th>Delete</th>                                         
                                    <th>Create</th>
                                    <th>Edit</th>
                                    <th>Delete</th>              
                                  </tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>