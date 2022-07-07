<?php
$days = array('1' => 'Lu', '2' => 'Ma', '3' => 'Mi', '4' => 'Ju', '5' => 'Vi', '6' => 'Sa');
$hours = array('09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00');

if($rows):
	foreach($rows as $row):
	?>
	<div class="d-flex justify-content-start border form-check-days mb-2 py-2 px-3">
		<form method="post" action="<?php echo site_url('/profesional/add_dia_habilitado/'.$profesional_id); ?>" class="formDiaHorario">
			<input type="hidden" name="dia_id" value="<?php echo $row->id; ?>">
			<?php foreach($days as $k=>$v): ?>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" name="days[]" id="inlineCheckbox<?php echo $k; ?>" value="<?php echo $k; ?>" <?php if(strpos($row->daysOfWeekDisabled, strval($k))===false) echo 'checked'; ?>>
				<label class="form-check-label" for="inlineCheckbox<?php echo $k; ?>"><?php echo $v; ?></label>
			</div>
			<?php endforeach; ?>
			<div class="form-inline pt-2">
				De
				<select name="hora_desde" class="form-control form-control-sm mx-2">
					<?php foreach($hours as $h): ?>
					<?php $selected = $row->hora_desde==$h ? 'selected' : ''; ?>
					<option value="<?php echo $h; ?>" <?php echo $selected; ?>><?php echo $h; ?></option>
					<?php endforeach; ?>
				</select>
				a
				<select name="hora_hasta" class="form-control form-control-sm mx-2">
					<?php foreach($hours as $h): ?>
					<?php $selected = $row->hora_hasta==$h ? 'selected' : ''; ?>
					<option value="<?php echo $h; ?>" <?php echo $selected; ?>><?php echo $h; ?></option>
					<?php endforeach; ?>
				</select>
				<button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
			</div>
		</form>
		<!-- <a class="link-dark d-inline-block ml-auto" href="<?php echo site_url('/profesional/delete_dia_habilitado/'.$row->id); ?>" onclick="return confirm('Atención: Se eliminá este horario', this)">
			<i class="gd-trash icon-text"></i>
		</a> -->
	</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="d-flex justify-content-start border form-check-days mb-2 py-2 px-3">
		<form method="post" action="<?php echo site_url('/profesional/add_dia_habilitado/'.$profesional_id); ?>" class="formDiaHorario">
			<?php foreach($days as $k=>$v): ?>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" name="days[]" id="inlineCheckbox<?php echo $k; ?>" value="<?php echo $k; ?>" <?php if($k<6) echo 'checked'; ?>>
				<label class="form-check-label" for="inlineCheckbox<?php echo $k; ?>"><?php echo $v; ?></label>
			</div>
			<?php endforeach; ?>
			<div class="form-inline pt-2">
				De
				<select name="hora_desde" class="form-control form-control-sm mx-2">
					<?php foreach($hours as $h): ?>
					<option value="<?php echo $h; ?>"><?php echo $h; ?></option>
					<?php endforeach; ?>
				</select>
				a
				<select name="hora_hasta" class="form-control form-control-sm mx-2">
					<?php foreach($hours as $h): ?>
					<?php $selected = $h=='20:00' ? 'selected' : ''; ?>
					<option value="<?php echo $h; ?>" <?php echo $selected; ?>><?php echo $h; ?></option>
					<?php endforeach; ?>
				</select>
				<button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
			</div>
		</form>
		<!-- <a class="link-dark d-inline-block ml-auto" href="<?php echo site_url('/profesional/delete_dia_habilitado/'.$row->id); ?>" onclick="return confirm('Atención: Se eliminá este horario', this)">
			<i class="gd-trash icon-text"></i>
		</a> -->
	</div>
<?php endif; ?>