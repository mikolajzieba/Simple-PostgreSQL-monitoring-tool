<script type="text/javascript">
	// Starting params
	var refreshInterval = 5000;
	var refreshTimerInterval = 500;
	var lastRefresh = null; // Date refresh

	function filterTable() {
		$('.filterInput').each(function() {
			// Value is not null, then filter
			if($(this).val() != '') {
				var filterValue = $(this).val();

				$(".col" + $(this).attr('name')).each(function() {
					if($(this).text().match('.*' + filterValue + '.*') == null)
						$(this).parent().remove();
				});
			}
		});
	}

    /**
     * Refresh content function
	 */
	function refreshContent() {
		$.ajax({
			url: '<?php echo Yii::app()->createUrl('stats/tablestats'); ?>',
		}).done(function (data) {
				$('#ajaxContent').html(data);
				filterTable();
				lastRefresh = new Date();
				refreshTimer();
//				$('#tableStats').dataTable();
			});
	}
    /**
     * Refresh timer function
	 */
	function refreshTimer() {
		var last = (new Date() - lastRefresh)/1000;
		$('#lastRefresh').text(Math.round(last));
	}

	// refresh now
	refreshContent();

	$(function() {
		// Init filters values from storage
		$('.filterInput').each(function() {
			// Value is not null, then filter
			if(localStorage.getItem('filter' + $(this).attr('name'))) {
				$(this).val(localStorage.getItem('filter' + $(this).attr('name')));
			}
		});

		// If input filter change, save to localstorage
		$('.filterInput').change(function() {
			localStorage.setItem('filter' + $(this).attr('name'), $(this).val());
		});

		// Refresh content on interval
		var refreshContentInterval = setInterval(refreshContent, refreshInterval);
		setInterval(refreshTimer, refreshTimerInterval);
		$('#favicon').attr('href', 'images/favicon_db_start.png');

		// Pause refresh
		$('#optionPause').click(function () {
			// Active / inactive
			if($(this).hasClass('active')) {
				refreshContentInterval = setInterval(refreshContent, refreshInterval);
				$('#favicon').attr('href', '<?php echo Yii::app()->baseUrl; ?>/images/favicon_db_start.png');
			}
			else {
				clearInterval(refreshContentInterval);
				$('#favicon').attr('href', '<?php echo Yii::app()->baseUrl; ?>/images/favicon_db_stop.png');
			}
		});
	});
</script>


<!-- Panel -->
<div class="">
	<button id="optionPause" class="btn btn-primary" data-toggle="button">Pause</button><br /><br />
	<label>Username filter</label>
	<input class="filterInput" type="text" placeholder="Type username..." name="username"><br />
</div>
<br />
Ostatnia aktualizacja: <span id="lastRefresh">0</span>s
<br />
<!-- Content -->
<span id="ajaxContent">
</span>

<div class="modal hide" id="modalWindow">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Zapytanie SQL</h3>
	</div>
	<div class="modal-body">
		<p id="modalContent">…</p>
	</div>
</div>