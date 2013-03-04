function buscar_remitente_proce_puest(id_empleado){
	var id_empleado=id_empleado;
	$.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "buscar_remitente_exter.php", 
			    data:'id_empleado='+id_empleado,
			    success: function(requestData){	
					$('#consulta_remitente_procedencia').html(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
}