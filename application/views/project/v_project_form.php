<?php
	if (@$form_edit) {
		$title = "Edit Data Proyek";
		$url   = "project/edit_project/" . $result->id_project;
	} else {
		$title = "Tambah Data Project";
		$url   = "project/do_tambah_proyek/";
	}
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title?></h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url() ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><?php echo $title?></li>
		</ol>
	</section>
	
	<section class="content">
		<div class="row">
			<div class="box box-primary">
                <?= form_open(site_url($url)) ?>
					<div class="box-body">
                        <input type="hidden" name="id_project" value="<?php echo @$result->id_project?>">
                        <input type="hidden" name="start_date" class="form-control datepicker" placeholder="Tanggal Proyek Dimulai" value="<?php echo (!empty(@$result->start_date)?@$result->start_date:date("Y-m-d")) ?>"/>
                         <input type="hidden" name="deadline" class="form-control datepicker" placeholder="Tanggal Proyek Selesai" value="<?php echo (!empty(@$result->deadline)?@$result->deadline:date("Y-m-d"))?>"/>
                        
						<div class="form-group">
							<label for="nama_proyek">Nama Proyek</label>
							<input type="text" name="nama_proyek" class="form-control" id="nama_proyek" placeholder="Nama Proyek" value="<?php echo @$result->nama_proyek?>" required/>
						</div>
                        
                        <div class="form-group">
                            <label for="deadline">Rentang Waktu</label>
                            <input type="text" id="daterange" class="form-control .range-project" placeholder="Tanggal Proyek Selesai" value="<?php echo @$result->start_date ."-". @$result->deadline ?>" required/>
						</div>
						
						<div class="form-group">
							<label for="nama_proyek">Attachment</label>
							<div class="col-lg-12">
								<div class="dropzone"></div>
							</div>
							<input type="hidden" name="attachment" class="form-control" id="attachment" placeholder="Nama Proyek" value="<?php echo @$result->attachment?>"/>
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
						
					</div>
					
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				<?= form_close() ?>
			</div>
		</div>
	</section>
</div>
