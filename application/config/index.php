<?php $this->load->view('header'); ?>
	
	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="/config">Configuración</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Editar configuración</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Editar configuración</div>
				</div>
				
				<?php echo form_open_multipart(''); ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Título del sitio</label>
								<input type="text" class="form-control" name="site_title" value="<?php echo $row->site_title; ?>" required>
							</div>
							<div class="form-group">
								<label>Color primario</label>
								<input type="text" class="form-control" name="primary_color" value="<?php echo $row->primary_color; ?>" required>
							</div>
							<div class="form-group">
								<label>Email administración</label>
								<input type="email" class="form-control" name="admin_email" value="<?php echo $row->admin_email; ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<label>Logo del sitio web</label>

							<div class="custom-file">
								<?php if($row->logo): ?>
									<button class="delete" style="display: block;">&times;</button>
									<img src="<?php echo base_url('uploads/'.$row->logo); ?>" class="preview-image">
								<?php else: ?>
									<button class="delete">&times;</button>
									<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" class="preview-image">
								<?php endif; ?>
								<input type="file" name="userfile" class="custom-file-input" onchange="readURL(this)" id="customFile1">
								<label class="custom-file-label" for="customFile1"></label>
							</div>
						</div>
					</div>
					<hr>
					<button type="submit" class="btn btn-primary mt-4">Guardar</button>
				</form>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>