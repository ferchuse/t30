function dame_permiso(){
	console.log("dame_permiso()")
	var permiso = $("#permiso").val();
	console.log("Permiso",permiso);
	
	
	if(permiso == "Lectura"){
		// $(".btn-success, .btn-outline-success, .btn-warning, .btn-outline-warning , .btn-outline-danger, .btn-danger, .eliminar, .editar").hide();
	}
	if(permiso == "Supervisor"){
		$(".supervisor").prop("readonly", false);
	}
	if(permiso == "Escritura"){
		$(".btn-outline-danger, .btn-danger, .eliminar").hide();
	}
	
	
}
dame_permiso();

$(document).ready(function (){
	$(".modal").on("shown.bs.modal", function(ev){
		console.log("Focus En Modal");
		$(this).find("input:visible").first().focus();
	});
	
});

