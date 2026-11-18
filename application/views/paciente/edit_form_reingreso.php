<?php $this->load->view('header_static'); ?>

	<div class="p-3">
		<nav class="d-none d-md-block hidden-print" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="<?php echo html_escape(site_url('paciente/edit/'.$row->id)); ?>" class="link-muted">&lt; Volver</a>
				</li>
			</ol>
		</nav>

		<div class="card mb-3" style="max-width: 1200px">
			<div class="card-body">
				<div class="hidden-print">
					<?php $this->load->view('alert'); ?>
				</div>

				<h1 class="h2 mb-5">Constancia de <strong>Solicitud de Reingreso</strong></h1>
			
				<div class="row">
					<div class="col-sm-12">
						<?php echo form_open('', ['id'=>'formIngreso']); ?>
						<div class="form-row mb-3">
								<div class="form-group col-sm-4">
									<label>ART</label>
									<select name="art_id" class="custom-select select2" data-select="<?php echo html_escape($form->art_id); ?>">
										<option value="">Buscar por empresa o cuit</option>
										<?php foreach($this->art_list as $art): ?>
											<option value="<?php echo html_escape($art->id); ?>"><?php echo html_escape($art->nombre); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>NRO SINIESTRO</label>
									<input type="text" class="form-control form-control-line" name="nro_siniestro" value="<?php echo html_escape($form->nro_siniestro); ?>">
								</div>
							</div>
							<div class="form-row mb-3">
								<div class="form-group col-sm-4">
									<label>OBRA SOCIAL</label>
									<select name="obra_social" class="custom-select select2" data-select="<?php echo html_escape($row->obra_social); ?>">
										<option value="">Selecciona una opción</option>
										<?php foreach($this->obra_social_list as $obra_social): ?>
											<option value="<?php echo html_escape($obra_social->id); ?>"><?php echo html_escape($obra_social->nombre); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>NRO AFILIADO</label>
									<input type="text" class="form-control form-control-line" name="nro_afiliado" value="<?php echo html_escape($row->nro_afiliado); ?>">
								</div>
							</div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DATOS DEL TRABAJADOR</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-4">
									<label>Apellido</label>
									<input type="text" class="form-control form-control-line" name="last_name" value="<?php echo html_escape($row->last_name); ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Nombre</label>
									<input type="text" class="form-control form-control-line" name="first_name" value="<?php echo html_escape($row->first_name); ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>CUIL/DNI</label>
									<input type="text" class="form-control form-control-line" name="dni" value="<?php echo html_escape($row->dni); ?>">
								</div>
								<div class="col-sm-2">
									<label class="d-inline mr-3">Sexo:</label>
									<?php $options = ['M', 'F']; ?>
									<?php foreach($options as $k=>$option): ?>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="sexo" id="sexo<?php echo html_escape($k); ?>" value="<?php echo html_escape($option); ?>" <?php if($row->sexo==$option) echo 'checked'; ?>>
											<label class="form-check-label" for="sexo<?php echo html_escape($k); ?>"><?php echo html_escape($option); ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="col-sm-3">
									<label>Fecha de Nacimiento</label>
									<input type="date" class="form-control form-control-line" name="fecha_nac" value="<?php echo html_escape($row->fecha_nac); ?>">
								</div>
								<div class="form-group col-sm-7">
									<label>Calle</label>
									<input type="text" class="form-control form-control-line" name="calle" value="<?php echo html_escape($form->calle); ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Nro</label>
									<input type="text" class="form-control form-control-line" name="calle_nro" value="<?php echo html_escape($form->calle_nro); ?>">
								</div>
							</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-1">
									<label>Piso</label>
									<input type="text" class="form-control form-control-line" name="piso" value="<?php echo html_escape($form->piso); ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Depto</label>
									<input type="text" class="form-control form-control-line" name="depto" value="<?php echo html_escape($form->depto); ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Localidad</label>
									<input type="text" class="form-control form-control-line" name="localidad" value="<?php echo html_escape($form->localidad); ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Provincia</label>
									<select class="form-control form-control-line" name="provincia" data-select="<?php echo html_escape($form->provincia); ?>">
										<option value="14">Mendoza</option>
										<option disabled>----</option>
										<?php foreach($this->provincias_list as $provincia): ?>
											<option value="<?php echo html_escape($provincia->id); ?>"><?php echo html_escape($provincia->nombre); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-2">
									<label>CP</label>
									<input type="text" class="form-control form-control-line" name="cp" value="<?php echo html_escape($form->cp); ?>">
								</div>
							</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-3">
									<label>Tel. Fijo</label>
									<input type="text" class="form-control form-control-line" name="tel_fijo" value="<?php echo html_escape($form->tel_fijo); ?>">
								</div>
								<div class="form-group col-sm-3">
									<label>Tel. Móvil</label>
									<input type="text" class="form-control form-control-line" name="tel_movil" value="<?php echo html_escape($form->tel_movil); ?>">
								</div>
                                <div class="form-group col-sm-6">
                                    <label>E-mail</label>
                                    <input type="email" class="form-control form-control-line" name="email" value="<?php echo html_escape($row->email); ?>">
                                </div>
                            </div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DATOS DEL EMPLEADOR</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-8">
									<label>Nombre de la Empresa</label>
									<input type="text" class="form-control form-control-line" name="empleador_nombre" value="<?php echo html_escape($form->empleador_nombre); ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>CUIT</label>
									<input type="text" class="form-control form-control-line" name="empleador_cuit" value="<?php echo html_escape($form->empleador_cuit); ?>">
								</div>
							</div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DATOS DEL PRESTADOR</div>

							<div class="form-row mb-2 mb-lg-3">
                                <div class="form-group col-md-8">
                                    <label>Nombre del Establecimiento Asistencial</label>
                                    <input type="text" class="form-control form-control-line" name="prestador_nombre" value="<?php echo html_escape($form->prestador_nombre); ?>">
                                </div>
								<div class="form-group col-sm-4">
									<label>CUIT</label>
									<input type="text" class="form-control form-control-line" name="prestador_cuit" value="<?php echo html_escape($form->prestador_cuit); ?>">
								</div>
                            </div>

                            <div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-5">
									<label>Calle</label>
									<input type="text" class="form-control form-control-line" name="prestador_calle" value="<?php echo html_escape($form->prestador_calle); ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Nro</label>
									<input type="text" class="form-control form-control-line" name="prestador_calle_nro" value="<?php echo html_escape($form->prestador_calle_nro); ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Piso</label>
									<input type="text" class="form-control form-control-line" name="prestador_piso" value="<?php echo html_escape($form->prestador_piso); ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Depto</label>
									<input type="text" class="form-control form-control-line" name="prestador_depto" value="<?php echo html_escape($form->prestador_depto); ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Localidad</label>
									<input type="text" class="form-control form-control-line" name="prestador_localidad" value="<?php echo html_escape($form->prestador_localidad); ?>">
								</div>
							</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-4">
									<label>Provincia</label>
									<select class="form-control form-control-line" name="prestador_provincia">
										<option value="14">Mendoza</option>
										<option disabled>----</option>
										<?php foreach($this->provincias_list as $provincia): ?>
											<option value="<?php echo html_escape($provincia->id); ?>"><?php echo html_escape($provincia->nombre); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-2">
									<label>CP</label>
									<input type="text" class="form-control form-control-line" name="prestador_cp" value="<?php echo html_escape($form->prestador_cp); ?>">
								</div>
								<div class="form-group col-sm-3">
									<label>Tel</label>
									<input type="text" class="form-control form-control-line" name="prestador_tel" value="<?php echo html_escape($form->prestador_tel); ?>">
								</div>
								<div class="form-group col-sm-3">
									<label>Fax</label>
									<input type="text" class="form-control form-control-line" name="prestador_fax" value="<?php echo html_escape($form->prestador_fax); ?>">
								</div>
							</div>

							<div class="form-group mb-5">
								<label>E-mail</label>
								<input type="email" class="form-control form-control-line" name="prestador_email" value="<?php echo html_escape($form->prestador_email); ?>">
							</div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DESCRIPCIÓN DEL MOTIVO DE CONSULTA</div>

							<div class="mb-5">
								<input type="hidden" name="motivo_consulta" value="Sin especificar">
								<?php $options = ['Accidente de Trabajo', 'Accidente in itinere', 'Enfermedad Profesional', 'Intercurrencia', 'Sin especificar']; ?>
								<?php foreach($options as $k=>$option): ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="motivo_consulta" id="motivo_consulta<?php echo html_escape($k); ?>" value="<?php echo html_escape($option); ?>" <?php if($form->motivo_consulta==$option) echo 'checked'; ?>>
										<label class="form-check-label" for="motivo_consulta<?php echo html_escape($k); ?>"><?php echo html_escape($option); ?></label>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-5">
									<label>Fecha de ocurrencia de la contingencia original</label>
									<input type="date" class="form-control form-control-line" name="fecha_ocurrencia" value="<?php echo html_escape($form->fecha_ocurrencia ? date('Y-m-d', strtotime($form->fecha_ocurrencia)) : ''); ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Hora</label>
									<input type="text" class="form-control form-control-line" name="hora_ocurrencia" value="<?php echo html_escape($form->fecha_ocurrencia ? date('H:i', strtotime($form->fecha_ocurrencia)) : ''); ?>">
								</div>
							</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-5">
									<label>Fecha de Alta Médica</label>
									<input type="date" class="form-control form-control-line" name="fecha_alta_medica" value="<?php echo html_escape($form->fecha_alta_medica ? date('Y-m-d', strtotime($form->fecha_alta_medica)) : ''); ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Hora</label>
									<input type="text" class="form-control form-control-line" name="hora_alta_medica" value="<?php echo html_escape($form->fecha_alta_medica ? date('H:i', strtotime($form->fecha_alta_medica)) : ''); ?>">
								</div>
							</div>

							<div class="form-row mb-2 mb-lg-3">
								<div class="form-group col-sm-5">
								<label>Fecha de Solicitud de Reingreso</label>
									<input type="date" class="form-control form-control-line" name="fecha_solicitud_reingreso" value="<?php echo html_escape($form->fecha_solicitud_reingreso ? date('Y-m-d', strtotime($form->fecha_solicitud_reingreso)) : ''); ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Hora</label>
									<input type="text" class="form-control form-control-line" name="hora_solicitud_reingreso" value="<?php echo html_escape($form->fecha_solicitud_reingreso ? date('H:i', strtotime($form->fecha_solicitud_reingreso)) : ''); ?>">
								</div>
							</div>

							<div class="form-group">
								<label>Descripción del motivo de consulta</label>
								<textarea class="form-control form-control-line" name="descripcion_motivo_consulta" rows="2"><?php echo html_escape($form->descripcion_motivo_consulta); ?></textarea>
							</div>

							<div class="form-group">
								<label>Diagnóstico</label>
								<textarea class="form-control form-control-line" name="diagnostico" rows="2"><?php echo html_escape($form->diagnostico); ?></textarea>
							</div>

							<div class="form-group mb-4 mb-lg-5">
								<label>Indicaciones / Tratamiento</label>
								<textarea class="form-control form-control-line" name="indicaciones" rows="2"><?php echo html_escape($form->indicaciones); ?></textarea>
							</div>

							<div class="row mb-4">
								<div class="col-sm-6">
                                    <div class="form-group">
									    <label class="d-inline mr-3">Aceptación de Reingreso al tratamiento</label>
										<input type="hidden" name="aceptacion_reingreso" value="Si">
                                        <?php $options = ['Si', 'No']; ?>
                                        <?php foreach($options as $k=>$option): ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="aceptacion_reingreso" id="aceptacion_reingreso<?php echo html_escape($k); ?>" value="<?php echo html_escape($option); ?>" <?php if($form->aceptacion_reingreso==$option) echo 'checked'; ?>>
                                                <label class="form-check-label" for="aceptacion_reingreso<?php echo html_escape($k); ?>"><?php echo html_escape($option); ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Fundamento de la denegación de reingreso</label>
                                        <input type="text" class="form-control form-control-line" name="aceptacion_reingreso_otro" value="<?php echo html_escape($form->aceptacion_reingreso_otro); ?>">
                                    </div>
                                </div>
							</div>

                            <div class="p-2 mb-2 border">"Sr.Trabajador: en caso de discrepancia con esta decisión Ud. puede concurrir a la Comisión Médica más cercana a su domicilio o correspondiente a su jurisdicción. Los datos de contacto de las Comisiones Médicas se encuentran al dorso."</div>

							<div style="height: 110px"></div><!-- espaciador -->
							
							<div class="form-row text-center">
								<div class="form-group col-sm-4">
									<input type="text" class="form-control form-control-line text-center" value="Mendoza, <?php echo date('d/m/Y', strtotime($form->created_at)); ?>">
									<label>Lugar y Fecha de la Asistencia Médica</label>
								</div>
								<div class="form-group col-sm-4">
									<input type="text" class="form-control form-control-line bg-white" disabled>
									<label>Firma y Aclaración del Trabajador</label>
								</div>
								<div class="form-group col-sm-4">
									<input type="text" class="form-control form-control-line bg-white" disabled>
									<label>Firma y Sello Médico c/ N° de Matricula</label>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="row hidden-print">
			<div class="col-sm-12">
				<button type="submit" form="formIngreso" class="btn btn-primary btn-lg">Guardar cambios</button>
				<button type="button" class="btn btn-info btn-lg" onclick="window.print()">Imprimir Página</button>
			</div>
		</div>
	</div>

	<?php $this->load->view('footer_static'); ?>