// JavaScript Document
function validacion(){
	//var elemento = document.clientes.elements[2].value;
	var elemento = document.clientes;
	var texto = /^([a-zA-ZáéíóúñüàèÁÉÍÓÚÑ])/;
	var numero = /^([0-9])/;
	var mail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var fecha = new Date();
	var ano = fecha.getFullYear();
	var mes = fecha.getMonth()+1
	for(var i=0; i<elemento.length; i++){
		if(elemento[i].type == "text" && !texto.test(elemento[i].value) && elemento[i].name != "strDNI[]"){
		alert('Debe rellenar el campo \"'+elemento[i].placeholder+'\" correctamente');
		elemento[i].focus();
		return false;
		}
		if(elemento[i].name == "strDNI[]" && elemento[i].value == ""){
		alert('Debe rellenar el campo \"'+elemento[i].placeholder+'\" correctamente');
		elemento[i].focus();
		return false;
		}
		if(elemento[i].type == "number" && (elemento[i].value == "" || !numero.test(elemento[i].value))){
		alert('Debe rellenar el campo \"'+elemento[i].placeholder+'\" correctamente');						
		elemento[i].focus();
		return false;
		}
		if(elemento[i].type == "email" && (elemento[i].value == "" || !mail.test(elemento[i].value))){
		alert('Debe rellenar el campo \"'+elemento[i].placeholder+'\" correctamente');
		elemento[i].focus();
		return false;
		}
		if(elemento[i].name == "intDia[]" && (elemento[i].value < 1 || elemento[i].value > 31 )){
		alert('Debe rellenar el campo \"'+elemento[i].placeholder+'\" con un valor entre 1 y 31');
		elemento[i].focus();
		return false;
		}
		if(elemento[i].name == "mensaje" && (elemento[i].value == "" || /^\s+$/.test(elemento[i].value) || elemento[i].value.length == 0)){
		alert('Debe rellenar el campo \"'+elemento[i].placeholder+'\" correctamente');
		elemento[i].focus();
		return false;
		}
		if(elemento[i].name == "strMes[]" && elemento[i].value == "" ){
		alert('Debe seleccionar un Mes');
		elemento[i].focus();
		return false;
		}
		if(elemento[i].name == "intAnios[]" && (elemento[i].value < ano-90 || elemento[i].value > ano )){
		var anomin = ano-90;
		alert('Debe rellenar el campo \"'+elemento[i].placeholder+'\" con un valor entre '+anomin+' y '+ano);
		elemento[i].focus();
		return false;
		}
		if(elemento[i].name == "chx_termsAndConditions" && !elemento[i].checked){
		alert('Para continuar debes ACEPTAR los terminos y condiciones del servicio');
		elemento[i].focus();  
		return false;
		}
	}
}        