<?php
	if (@$form_edit) {
		$title = "Edit Data Task Proyek";
		
		// URL Jika Edit Data
        if($subtask==true) {
            $title = "Sunting Data Subtask Proyek";
            $url   = "project/edit_subtask/".@$id_subprogress;
        } else {
            $url   = "project/edit_task/" . $result->id_progress;
            
        }

	} else {
		$title = "Tambah Data Task Proyek";
		
		//URL Jika Tambah Data 
        if($subtask==true) {
            $title = "Tambah Data Subtask Proyek";
            $url   = "project/do_tambah_subtask/".@$id_project;
        } else {
            $url   = "project/do_tambah_task/".@$id_project;
        }
		
	}

    if($subtask==true) {
        
        $name_progress = "id_subprogress";
        $value_progress = @$id_subprogress;            
        $name_project = "id_progress";
        $value_project = @$id_progress;
    } else {
        $name_progress = "id_progress";
        $value_progress = @$result->id_progress;            
        $name_project = "id_project";
        $value_project = @$id_project;
    }
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title?><small><?php echo @$judul ?></small></h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url() ?>"><i class="fa fa-dashboard"></i> Data</a></li>
			<li><a href="<?php echo base_url('project/details/').(@$subtask==true?@$result->id_progress:@$result->id_project) ?>"><?php echo @$judul ?></a></li>
			<li class="active"><?php echo $title?></li>
		</ol>
	</section>
    
    
    	<section class="content">
		<div class="row">
			<div class="col-lg-12 box-list">
				<div class="box box-primary">
					<div class="box-header">
					
		              </div>
                    <?= form_open(site_url($url)) ?>
					<input type="hidden" name="<?php echo $name_progress ?>" value="<?php echo $value_progress ?>">
                        <input type="hidden" name="<?php echo $name_project ?>" value="<?php echo $value_project  ?>">
                        
                    
					<div class="box-body">
						<div class="form-group">
							<label for="nama_proyek">Task Name</label>
							<input type="text" name="bagian" class="form-control" id="bagian" placeholder="Nama Task" value="<?php echo @$result->bagian?>" required/>
						</div>
						
                        <?php if($subtask) { ?>
						<div class="form-group">
                            <label for="tgl_mulai">Status</label>
                            <select class="form-control" name="status" id="status" required>
                                <?php 
                                    $pic_lists = $this->db->get('t_project_status')->result();
                                    foreach($pic_lists as $i) {
                                        echo "<option value='".$i->id_pstatus."' ".($i->id_pstatus==@$result->status?"selected":"").">".$i->status_name."</option>";
                                    }
                                ?>
                            </select>
						</div>
                        <?php }?>
                        
						<div class="form-group datepicker-group">
                            <label id="label-datepicker">??</label>
                            <input type="text" name="deadline" class="form-control" id="datepicker" placeholder="" value=""/>
						</div>
						<?php if(@$level==1) { ?>
                         <div class="form-group">
                            <label for="pic">Person in Command</label>
                             <select class="form-control" name="pic">
                                <?php 
                                    $pic_lists = $this->db->get('t_user')->result();
                                    foreach($pic_lists as $i) {
                                        echo "<option value='".$i->id_user."' ".($i->id_user==@$result->pic?"selected":"").">".$i->fullname."</option>";
                                    }
                                ?>
                            </select>
						</div>
						<?php } else { ?>
							<input type="hidden" name="pic" value="<?php echo $this->session->userdata('profiles')['id_user'] ?>">
						<?php } ?>
						<div class="form-group form-problem">
							<label for="problem_details">Problem</label>
							<input type="text" name="problem_details" class="form-control" id="bagian" placeholder="What's your problem?" value="<?php echo @$result->problem?>"/>
						</div>
                        <div class="form-group">
							<label for="nama_proyek">Attachment</label>
                            <div class="col-lg-12">
								<div class="dropzone"></div>
							</div>
							<input type="hidden" name="attachment" class="form-control" id="attachment" placeholder="Nama Task" value="<?php echo @$result->attachment ?>"/>
						</div>
					</div>
                    
                   
					
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				<?= form_close() ?>
                </div>
            </div>
            </div>
	</section>
</div>
