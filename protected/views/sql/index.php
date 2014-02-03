<script type="text/javascript">
	$(function()
	{
		// Get values from localstorage
		$('input[name=dblogin]').val(localStorage.getItem('dblogin'));
		$('input[name=dbpass]').val('*****');
		$('input[name=dbname]').val(localStorage.getItem('dbname'));

		// Submit form and save to localstorage
		$( "#connectionTest" ).click(function() {
			localStorage.setItem('dblogin', $('input[name=dblogin]').val());
			localStorage.setItem('dbpass', $('input[name=dbpass]').val());
			localStorage.setItem('dbname', $('input[name=dbname]').val());
			$('#connectionForm').submit();
		});
	});
</script>

<form id="connectionForm" method="POST">
	DB Login: <input type="text" name="dblogin"><br />
	DB Pass: <input type="text" name="dbpass"><br />
	DB Name: <input type="text" name="dbname"><br />
	<button id="connectionTest" class="btn" type="button">Test connection and save</button>
</form>