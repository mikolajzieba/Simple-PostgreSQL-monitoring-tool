<script type="text/javascript">
	$(function() {
		$('.sqlQuery').click(function() {
//			console.log($(this).attr('data-sqlquery'));
			$('#modalContent').html($(this).attr('data-sqlquery'));
			$('#modalWindow').modal('show');
		});

		// Locks button
		$('.btn.locks').click(function() {
			// Get all locks for given record and split it into array
			var locks = $(this).attr('data-locks').replace('{', '').replace('}', '').split(',');
			$('#tableStats > tbody  > tr').each(function() {
				if($.inArray($(this).attr('data-procpid'), locks) != -1)
					$(this).addClass('error');
				else
					$(this).removeClass('error');
			});
		});

		// Kill button
		$('.btn-kill').click(function() {
			// If db settings dont exists
			if(!localStorage.getItem('dblogin') || !localStorage.getItem('dbpass') || !localStorage.getItem('dbname')) {
				alert('Brak ustawień bazy danych');
				return false;
			}

			$.ajax({
				url: '<?php echo Yii::app()->createUrl('stats/kill'); ?>',
				type: "POST",
				data: {dbname: localStorage.getItem('dbname'), dblogin: localStorage.getItem('dblogin'), dbpass: localStorage.getItem('dbpass'), pid: $(this).attr('data-pid')}
			})
				.done(function (data) {
					console.log(data);
					refreshContent();
				})
				.fail(function(jqXHR, textStatus) {
					alert('Killing failed: ' + jqXHR.statusText);
				});
		});

		// Cancel button
		$('.btn-cancel').click(function() {
			// If db settings dont exists
			if(!localStorage.getItem('dblogin') || !localStorage.getItem('dbpass') || !localStorage.getItem('dbname')) {
				alert('Brak ustawień bazy danych');
				return false;
			}

			$.ajax({
				url: '<?php echo Yii::app()->createUrl('stats/cancel'); ?>',
				type: "POST",
				data: {dbname: localStorage.getItem('dbname'), dblogin: localStorage.getItem('dblogin'), dbpass: localStorage.getItem('dbpass'), pid: $(this).attr('data-pid')}
			})
				.done(function (data) {
					console.log(data);
					refreshContent();
				})
				.fail(function(jqXHR, textStatus) {
					alert('Cancelling failed: ' + jqXHR.statusText);
				});
		});
	});
</script>

<table class="table table-bordered" id="tableStats">
	<thead>
	<tr>
		<td>Query</td>
		<td>Elapsed</td>
		<td>datname</td>
		<td>user_name</td>
		<td>application</td>
		<td>client_addr</td>
		<td>backend_start</td>
		<td>query_start</td>
		<td>wait</td>
		<td>Options</td>
	</tr>
	</thead>
	<tbody>
	<?php foreach($dataProvider as $arr): ?>
		<tr data-procpid="<?php echo $arr['procpid']; ?>">
			<td class="sqlQuery" data-sqlquery="<?php echo nl2br(CHtml::encode($arr['current_query'])); ?>"><?php echo $arr['query']; ?></td>
			<td><?php echo $arr['elapsed']; ?></td>
			<td><?php echo $arr['datname']; ?></td>
			<td class="colusername"><?php echo $arr['usename']; ?></td>
			<td><?php echo $arr['application_name']; ?></td>
			<td><?php echo $arr['client_addr']; ?></td>
			<td><?php echo $arr['backend_start']; ?></td>
			<td><?php echo $arr['query_start']; ?></td>
			<td><?php echo $arr['waiting']; ?></td>
			<td>
				<?php if(count(explode(',', $arr['locks'])) >= 1 && $arr['locks'] != '{}'): ?>
					<button class="btn locks" data-locks="<?php echo $arr['locks']; ?>">Locks(<?php echo count(explode(',', $arr['locks'])) ?>)</button>
				<?php endif; ?>
				<button class="btn btn-danger btn-kill" data-pid="<?php echo $arr['procpid']; ?>">kill</button>
				<button class="btn btn-danger btn-cancel" data-pid="<?php echo $arr['procpid']; ?>">cancel</button>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
