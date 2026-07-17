$(document).ready(function(){
	console.log("ready fform  contraseña")
	$("#form_contrasena").submit(cambiarContrasena);
	
});




function cambiarContrasena(event){ 
	console.log("cambiarContrasena()")
	console.log($("#new_password").val())
	console.log($("#old_password").val())
	event.preventDefault();
	
	
	if($("#new_password").val() != $("#confirm_password").val()){
		
		alertify.error("Las contraseñas no coinciden");
		
		return false;
	}
	
	
	
	
	$.ajax({
		"url": "../usuarios/consultas/cambiar_contrasena.php",
		"method": "POST",
		"data": $("#form_contrasena").serialize()
		
		}).done(function (respuesta, status){
		
		$("#modal_contrasena").modal("hide");
		alertify.success("Contraseña Modificada");
	})
}				