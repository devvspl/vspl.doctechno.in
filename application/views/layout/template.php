<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SnapDoc</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta http-equiv="Cache-control" content="no-cache">
	<meta name="theme-color" content="#424242"/>
	<link rel="icon" type="image/x-icon" href="<?= base_url(); ?>assets/images/favicon.png">

	<link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css">

	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/style-main.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/themes/gray/skins/skin-light.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/themes/gray/ss-main-light.css">

	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/ionicons.min.css">

	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/custom_style.css">


	<!--print table-->
	<link href="<?= base_url(); ?>assets/dist/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/dist/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/dist/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<!--print table mobile support-->
	<link href="<?= base_url(); ?>assets/dist/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/dist/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/dist/css/bootstrap-select.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/dropify.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.datetimepicker.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.verySimpleImageViewer.css">

	<script src="<?= base_url(); ?>assets/custom/jquery.min.js"></script>
	<script src="<?= base_url(); ?>assets/js/parsley.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/js/moment.min.js"></script>

	<script src="<?= base_url(); ?>assets/dist/js/jquery-ui.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.datetimepicker.full.js"></script>
	<script src="<?= base_url(); ?>assets/js/jquery.verySimpleImageViewer.js"></script>
	<script src="<?= base_url(); ?>assets/js/custom.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<style>
		.parsley-errors-list {
			margin-left: 0px;
			padding-left: 0px;
		}

		.parsley-errors-list li {
			list-style: none;
			color: red;
			font-size: 13px;
		}
	</style>
</head>


<?php $this->load->view('layout/header.php'); ?>
<?php $this->load->view($main); ?>
<?php $this->load->view('layout/footer.php'); ?>
