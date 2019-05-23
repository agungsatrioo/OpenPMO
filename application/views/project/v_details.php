<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo (@$isSubtasks==false?$nama_proyek:$judul) ?><small><?php if(@$isSubtasks==true) echo "Rincian Subprogres"; else echo "Rincian Progres"?></small></h1>
        
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <?php if(@$isAllTask==false && @$isMyTask==false && @$isMySubtask!="yes") echo '<li class="active"><a href="'.base_url('project').'">Project Lists</a></li>'?>
            
            <?php if(@$isSubtasks==true) echo "<li><a href='".base_url("project/details/").@$id_project."'>".@$nama_proyek."</a></li>"; else echo "<li class='active'>".@$nama_proyek."</li>" ?>
            
            <?php if(@$isSubtasks==true) echo '<li class="active">'. @$judul .'</li>'?>
		</ol>
	</section>
	
	<section class="content">
		<div class="row">
			<div class="col-lg-12">
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
					<?php if(@$showbar) { ?>
					  <div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo @$done ?>%">
						  Done
						</div>
						<div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo @$in_progress ?>%">
						  In progress
						</div>
					   <div class="progress-bar progress-bar-info" role="progressbar" style="width:<?php echo @$review ?>%">
						  Review
						</div>
						<div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo @$canceled ?>%">
						  Canceled
						</div>
					<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width:<?php echo @$in_problem ?>%">
						  In problem
						</div>
					<div class="progress-bar progress-bar-blank" role="progressbar" style="width:<?php echo @$nihil ?>%">
						  (nihil)
						</div>
					  </div>
					<?php } ?>
						<table id="data-table" class="table table-bordered table-responsive table-striped table-hover">
                            
							<?php if (@$isAllTask==false) { ?>
							<a href="<?php echo site_url("project/".(@$isSubtasks==true?"subtask_form/".@$id_progress:"details_form/".@$id_project)) ?>" class="btn btn-primary dt-buttons">
								<i class="fa fa-plus"></i> Tambah Pekerjaan
							</a>
							<?php } ?>
							<thead>
								<tr>
									<th>No</th>
									<?php if(@$isAllTask==true) echo "<th>Project</th>";?>
									<th><?php if(@$isSubtasks==true) echo "Subtask name"; else echo "Tasks"?></th>
									<?php if(@$isMyTask==false&&@$isMySubtask!="yes"||@$isAllSubtask=="yes") echo "<th>Person in Command</th>";?>
									<?php if(@$isMyTask==false&&@$isAllTask==false||@$isMySubtask=="yes") echo "<th>Status</th>";?>
                                    <?php if(@$noAct==false) echo "<th>Action</th>";?>
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