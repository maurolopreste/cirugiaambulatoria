<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Agenda</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Todas los eventos</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Agenda</div>
					<?php if($this->is_admin): ?>
					<select class="form-control" style="display:inline-block;max-width:260px" onchange="document.location.href='?p_id='+this.value">
						<option value="">Filtrar por profesional</option>
						<?php foreach($this->profesionales_list as $profesional): ?>
							<?php if(!$profesional->active) continue; ?>
							<?php $selected = $this->input->get('p_id')==$profesional->id ? 'selected' : ''; ?>
							<option value="<?php echo html_escape($profesional->id); ?>" <?php echo html_escape($selected); ?>><?php echo html_escape($profesional->first_name); ?> <?php echo html_escape($profesional->last_name); ?></option>
						<?php endforeach; ?>
					</select>
					<a href="<?php echo site_url('/agenda/add'); ?>" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Agendar turno</a>
					<?php endif; ?>
				</div>

				<?php echo $calendar; ?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var year = '<?php echo html_escape($year); ?>';
		var month = '<?php echo html_escape($month); ?>';
	</script>

<?php $this->load->view('footer'); ?>