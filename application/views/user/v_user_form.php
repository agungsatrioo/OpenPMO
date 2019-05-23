<?php
	if (@$form_edit) {
		$title = "Edit Data User";
		$url   = "user/edit_user/" . $result->id_user;
	} else {
		$title = "Tambah Data User";
		$url   = "user/do_add_user/";
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
                        <input type="hidden" name="id_user" value="<?php echo @$result->id_user?>">
                        
						<div class="form-group">
							<label for="nama_proyek">Nama Lengkap</label>
							<input type="text" name="fullname" class="form-control" placeholder="Isi nama lengkap" value="<?php echo @$result->fullname?>" required/>
						</div>
						
						<div class="form-group">
							<label for="nama_proyek">Nama Pengguna</label>
							<input type="text" name="username" class="form-control" placeholder="Isi nama pengguna" value="<?php echo @$result->username?>" required/>
						</div>
						
						<div class="form-group">
							<label for="nama_proyek">Kata Sandi</label>
							<input type="password" name="password" class="form-control" placeholder="Isi sandi Anda" value="<?php echo @$result->password?>" required/>
						</div>
						
						<div class="form-group">
							<label for="nama_proyek">Gambar</label>
							<div class="col-lg-12">
							
								<div class="col-lg-3">
									<img id="user-pic-big" src="<?php echo base_url('media/uploads/').@$result->img ?>">
								</div>
								
								<div class="col-lg-9">
									<div class="dropzone"></div>
								</div>
							</div>
							<input type="hidden" name="img" id="imge" class="form-control" value="<?php echo @$result->img?>" required/>
						</div>
                        
						<?php if(@$result->level!=1) { ?>
                         <div class="form-group">
                            <label for="pic">User Level</label>
                             <select class="form-control" name="level">
                                <?php 
                                    $pic_lists = $this->db->get('t_user_level')->result();
                                    foreach($pic_lists as $i) {
										if($i->id_level!=1) {
                                        echo "<option value='".$i->id_level."' ".($i->id_level==@$result->level?"selected":"").">".$i->desc."</option>";
										}
                                    }
                                ?>
                            </select>
						</div>
						<?php  } ?>
						
						<div class="form-group">
							<label for="nama_proyek">About User</label>
							<input type="text" name="about_me" class="form-control" placeholder="Isi nama lengkap Anda" value="<?php echo @$result->about_me?>" required/>
						</div>
					
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				<?= form_close() ?>
			</div>
		</div>
	</section>
</div>
