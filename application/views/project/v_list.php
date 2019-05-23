<div class="content-wrapper">
	<section class="content-header">
		<h1>Project Lists</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Project Lists</li>
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
						<table id="data-table" class="table table-bordered table-striped table-hover">
                            <?php if (@$can_add==true) { ?>
							<a href="<?php echo site_url("project/project_form") ?>" class="btn btn-primary dt-buttons">
								<i class="fa fa-plus"></i> Add Project
							</a>
							<?php } ?>
							<thead>
								<tr>
									<th>No</th>
									<th>Project Name</th>
									<th>Start Date</th>
									<th>Deadline</th>
									<th>Person in Command</th>
                                    <th>Progress</th>
									<th>Action</th>
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
