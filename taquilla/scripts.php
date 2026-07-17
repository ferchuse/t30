
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="../../vendor/jquery/alertify.min.js"></script>


<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

<!-- Core plugin JavaScript  <script src="../../vendor/datatables/jquery.dataTables.js"></script>
<script src="../../vendor/datatables/dataTables.bootstrap4.js"></script>-->

<script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>


<!-- Custom scripts for all pages-->
<script src="../../js/sb-admin.min.js"></script>
<script src="../../js/date.js"></script>
<script src="../../js/permisos.js"></script>
<script src="../../js/menu.js?v=123"></script>

<script src="../../vendor/jquery/tableExport.js"></script>
<script src="../../vendor/jquery/jquery.base64.js"></script>

<script src="../../lib/spatial_navigation.js"></script>
<script > var selected </script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script src="../../lib/jquery.autocomplete.min.js"></script>
<!--
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.0.5/css/tableexport.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.0.5/js/tableexport.min.js"></script>
-->

<script >
	window.addEventListener('load', e => {
		// new PWAConfApp();
		registerSW(); 
	});
	
	
	async function registerSW() { 
		console.log("registerSW()")
		if ('serviceWorker' in navigator) { 
			try {
				await navigator.serviceWorker.register('../../sw.js'); 
				} catch (e) {
				alert('ServiceWorker registration failed. Sorry about that.'); 
			}
			} else {
			document.querySelector('.alert').removeAttribute('hidden'); 
		}
	}
	
</script>
