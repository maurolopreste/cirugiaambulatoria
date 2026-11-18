<?php $this->load->view('email/layout_top'); ?>

<h2>Se ha registrado una nueva cita en la agenda online.</h2>

<p>Fecha: <?php echo html_escape($agenda->fecha); ?><br>
Comentario: <?php echo html_escape($agenda->comentarios); ?></p>

<p>A continuación los datos del paciente:</p>

<p>Nombre: <?php echo html_escape($paciente->nombre_completo); ?><br>
Teléfono: <?php echo html_escape($paciente->telefono); ?><br>
Email: <?php echo html_escape($paciente->email); ?></p>

<?php $this->load->view('email/layout_bottom'); ?>