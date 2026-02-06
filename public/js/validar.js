// JavaScript Document
function validacion(){
	var nombre = document.getElementById("nombre").value;
	expr = /^([a-zA-Z·ÈÌÛ˙Ò¸‡Ë¡…Õ”⁄—])/;
	if( nombre == null || nombre.length == 0 || /^\s+$/.test(nombre) || !expr.test(nombre) ) {
		document.getElementById("nrequired").style.display ="";
		/*alert ("Por favor indique su nombre completo");*/
		document.getElementById("nombre").focus();
		return false;
	}
	var email = document.getElementById("email").value;
	expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(email == "" || !expr.test(email)){
		document.getElementById("erequired").style.display ="";
		document.getElementById("email").focus();
		return false;
	}
	var mensaje = document.getElementById("mensaje").value;
	if(mensaje == "" || mensaje.length == 0 || /^\s+$/.test(mensaje)){
		document.getElementById("mrequired").style.display ="";
		document.getElementById("mensaje").focus();
		return false;
	}
}
	