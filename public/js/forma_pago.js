$('button').on('click', function(){
	var idcoment = $(this).attr('id');
	$('#'+idcoment).hide();
	var idcoment = idcoment.split('-');
	var estado = $(this).val()
	$.post('actualizafp.php',{idComentario: idcoment[1], intEstado: estado});
});