$(document).ready(function(){
	$("#form_login").submit(function(event){
		event.preventDefault();
		let boton = $(this).find(':submit');
		boton.prop("disabled", true);
		boton.text('Cargando...');
		$.ajax({
			url: "login.php", 
			method: "POST",
			dataType: 'JSON', 
			data: $("#form_login").serialize()
			}).done(function(respuesta){
			if(respuesta.login == "valid"){
				alertify.success("Acceso Correcto");
				
				if($("#retorno").val() == ''){
					location.href="../taquilla/boletos.php";
					
				}
				else{
					location.href = $("#retorno").val();
				}
				
				}else{ 
				alertify.error(respuesta.mensaje);
				// $('#form_login')[0].reset();
			}
			}).always(function(){
			boton.text('Iniciar Sesión');
			boton.prop("disabled", false);
		});
	});
});


$("#btn_mostrar").click(mostrarContrasena)

function mostrarContrasena(){
	var tipo = document.getElementById("password");
	if(tipo.type == "password"){
		tipo.type = "text";
		
		
		}else{
		tipo.type = "password";
	}
	$("#btn_mostrar").find("i").toggleClass("fa-eye fa-eye-slash ");
}