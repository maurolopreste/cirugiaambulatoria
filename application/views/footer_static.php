
	<script src="<?php echo base_url('/assets/js/graindashboard.js'); ?>"></script>
	<script src="<?php echo base_url('/assets/js/graindashboard.vendor.js'); ?>"></script>
	<script src="<?php echo base_url('/assets/js/select2.full.min.js'); ?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
	<script type="text/javascript">var base_url = '<?php echo site_url(); ?>';</script>
	<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

	<?php if(isset($this->include_js) && is_file(FCPATH.'assets/js/'.$this->include_js)): ?>
	<script src="<?php echo html_escape(base_url('assets/js/'.$this->include_js)); ?>"></script>
	<?php endif; ?>

</body>
</html>