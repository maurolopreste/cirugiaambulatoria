
	<?php echo form_open('', ['id'=>'editEvolutivoForm']); ?>
		<header class="modal-header">
			<h4 class="modal-title">Editar evolutivo</h4>
		</header>

		<div class="modal-body pt-3">
			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Profesional</label>
					<select name="profesional_id" class="custom-select select2" required>
						<option value="">Seleccione una opción</option>
						<?php foreach($this->profesionales_list as $profesional): ?>
							<?php $selected = $row->profesional_id==$profesional->id ? 'selected' : ''; ?>
							<option value="<?php echo html_escape($profesional->id); ?>" <?php echo html_escape($selected); ?>><?php echo html_escape($profesional->first_name); ?> <?php echo html_escape($profesional->last_name); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label>Fecha</label>
				<input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d', strtotime($row->fecha)); ?>" required>
			</div>
			<div class="form-group">
				<label>Informe</label>
				<textarea rows="5" class="form-control" name="informe" required><?php echo html_escape($row->informe); ?></textarea>
			</div>
			<div class="form-group mb-4">
				<label for="ControlFile1">Documentación Adjunta (opcional)</label>
				<input type="file" name="userfile" class="form-control-file" id="ControlFile1">
				<?php if(!empty($row->file)): ?>
				<div class="alert alert-warning mt-2 p-2">Archivo subido: <a href="<?php echo html_escape(site_url('archivo/ver/'.rawurlencode($row->file))); ?>" class="alert-link" target="_blank"><?php echo html_escape($row->file); ?></a></div>
				<?php endif; ?>
			</div>
			<div class="form-group">
				<label>Próxima visita</label>
				<input type="date" class="form-control" name="proxima_visita" value="<?php echo html_escape($row->proxima_visita); ?>">
			</div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label>¿Paciente fue dado de alta?</label>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="alta" id="inlineRadio1" value="1" <?php if($row->alta) echo 'checked'; ?>>
						<label class="form-check-label" for="inlineRadio1">Si</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="alta" id="inlineRadio2" value="0" <?php if(!$row->alta) echo 'checked'; ?>>
						<label class="form-check-label" for="inlineRadio2">No</label>
					</div>
				</div>
				<div class="form-group col-md-6">
					<label>Orden facturada</label>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="facturado" id="facturado1" value="1" <?php if($row->facturado) echo 'checked'; ?>>
						<label class="form-check-label" for="facturado1">Si</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="facturado" id="facturado2" value="0" <?php if(!$row->facturado) echo 'checked'; ?>>
						<label class="form-check-label" for="facturado2">No</label>
					</div>
				</div>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
			<button type="submit" class="btn btn-primary btn-submit">Guardar cambios</button>
		</footer>
	</form>