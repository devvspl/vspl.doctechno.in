<footer class="main-footer">
    &copy; <?= date('Y'); ?> VNR Group</footer>
<div class="control-sidebar-bg"></div>
</div>

<link href="<?= base_url(); ?>assets/toast-alert/toastr.css" rel="stylesheet" />
<script src="<?= base_url(); ?>assets/toast-alert/toastr.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/select2/select2.min.css">
<script src="<?= base_url(); ?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?= base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?= base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<script src="<?= base_url(); ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>


<!--language js-->
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/js/bootstrap-select.min.js"></script>





<script src="<?= base_url(); ?>assets/plugins/fastclick/fastclick.min.js"></script>
<script src="<?= base_url(); ?>assets/dist/js/app.min.js"></script>
<!--nprogress-->
<script src="<?= base_url(); ?>assets/dist/js/nprogress.js"></script>
<!--file dropify-->
<script src="<?= base_url(); ?>assets/dist/js/dropify.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dist/datatables/js/ss.custom.js"></script>
<script>
	$(document).ready(function (){
		$('#punch_form').parsley({
			excluded: "input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden"
		});

		$('#punch_form').on('submit', function(e) {
			$(this).parsley().validate();

		});
	});
</script>
</body>

</html>
