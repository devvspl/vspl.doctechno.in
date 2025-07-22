<?php
$scan_id = $this->uri->segment(3);
$group_id = $this->uri->segment(4);

?>

<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Upload - Supporting File</h3>
					</div>
					<form id="form1" action="<?= base_url(); ?>Scan/upload_support" id="scan_support" name="scan_support" method="post" accept-charset="utf-8" enctype="multipart/form-data">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>
							<div class="form-group">
								<input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id; ?>">
								<input type="hidden" name="group_id" id="group_id" value="<?= $group_id; ?>">
								<input class="filestyle form-control" type='file' name='support_file' id="support_file" accept="image/*,application/pdf">
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" id="upload_support" class="btn btn-warning pull-right">Upload</button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"><?= $this->customlib->getDocumentName($scan_id); ?>
						</h3>
						<div class="box-tools pull-right">
							<a href="<?= base_url(); ?>scan_rejected_list" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
						</div>
					</div>
					<div class="bx-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<th>S.No</th>
									<th>File</th>
									<th>File Type</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php
									$main_file = $this->db->query("SELECT * FROM y{$this->year_id}_scan_file WHERE scan_id = $scan_id")->row();
									?>
									<tr>
										<td>1</td>
										<td><a href="javascript:void(0);" target="popup" onclick="window.open('<?= $main_file->file_path; ?>','popup','width=600,height=600');"><?= $main_file->file_name; ?></a></td>
										<td><?= ($main_file->is_main_file == 'Y') ? 'Main File' : 'Support File'; ?></td>
										<td>
											<input type="file" name="rep_file" id="rep_file" class="d-inline"> <button type="button" class="btn btn-sm btn-primary d-inline" id="replace">Replace</button>
										</td>
									</tr>

									<?php
									$supporting_files = $this->db->query("SELECT * FROM support_file WHERE scan_id = $scan_id")->result();
									$i = 2;
									foreach ($supporting_files as $supporting_file) {
									?>
										<tr>
											<td><?= $i; ?></td>
											<td><a href="javascript:void(0);" target="popup" onclick="window.open('<?= $supporting_file->file_path; ?>','popup','width=600,height=600');"><?= $supporting_file->file_name; ?></a></td>
											<td><?= ($supporting_file->is_main_file == 'Y') ? 'Main File' : 'Support File'; ?></td>
											<td><a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="delete_file(<?= $supporting_file->support_id ?>)"><i class="fa fa-trash"></a></td>
										</tr>
									<?php
										$i++;
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-footer">
						<button type="submit" id="final_submit" class="btn btn-success pull-right">Final Submit</button>
						<button type="submit" id="delete_all" class="btn btn-danger ">Delete All</button>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', '#delete_all', function() {
			var scan_id = $('#scan_id').val();
			var group_id = $("#group_id").val();
			var url = '<?= base_url() ?>Scan/delete_all';
			if (confirm('Are you sure to delete all ?')) {
				$.ajax({
					url: url,
					type: 'POST',
					data: {
						'scan_id': scan_id
					},
					dataType: 'json',
					success: function(data) {
						if (data.status == 200) {
							if (group_id != '') {
								window.location.href = '<?= base_url() ?>super_scan/' + group_id;
							} else {
								window.location.href = '<?= base_url() ?>Scan';
							}
						}
					}
				});
			}
		});

		$(document).on('click', '#final_submit', function() {
			var scan_id = $('#scan_id').val();
			var group_id = $("#group_id").val();
			var url = '<?= base_url() ?>Scan/final_submit_after_edit';
			if (confirm('Are you sure to final submit ?')) {
				$.ajax({
					url: url,
					type: 'POST',
					data: {
						'scan_id': scan_id
					},
					dataType: 'json',
					success: function(data) {
						if (data.status == 200) {
							if (group_id != '') {
								window.location.href = '<?= base_url() ?>super_scan/' + group_id;
							} else {
								window.location.href = '<?= base_url() ?>Scan';
							}
						}
					}
				});
			}
		});
	});

	function delete_file(id) {
		var url = '<?= base_url() ?>Scan/delete_file';
		var group_id = $("#group_id").val();
		if (confirm('Are you sure to delete ?')) {
			$.ajax({
				url: url,
				type: 'POST',
				data: {
					'id': id
				},
				dataType: 'json',
				success: function(data) {
					if (data.status == 200) {
						if (group_id != '') {
							window.location.href = '<?= base_url() ?>Scan/upload_supporting/<?= $scan_id; ?>/' + group_id;
						} else {
							window.location.href = '<?= base_url() ?>Scan/upload_supporting/<?= $scan_id; ?>';
						}

					}
				}
			});
		}

	}


	//onclick image upload 
	$(document).on('click', '#replace', function() {
		var scan_id = $('#scan_id').val();
		var group_id = $("#group_id").val();
		var url = '<?= base_url() ?>Scan/replace_file';
		var input = document.getElementById('rep_file');
		var file = input.files[0];
		formData = new FormData();
		formData.append('image', file);
		formData.append('scan_id', scan_id);

		$.ajax({
			url: url,
			type: 'POST',
			data: formData,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function(data) {
				if (data.status == 200) {
					if (group_id != '') {
						window.location.href = '<?= base_url() ?>Scan/edit_scan/<?= $scan_id; ?>/' + group_id;
					} else {
						window.location.href = '<?= base_url() ?>Scan/edit_scan/<?= $scan_id; ?>';
					}

				} else {
					alert(data.error);
				}
			}
		});

	});
</script>
