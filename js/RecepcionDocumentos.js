$(document).ready (function (){
										$('#fecha_posible_sol').datepicker({dateFormat: 'yy/mm/dd'});
										$('#fecha_doc').datepicker({dateFormat: 'yy/mm/dd'});
										$('#fecha_recibido').datepicker({dateFormat: 'yy/mm/dd'});
										$(".error").hide();
										$("#altatipodoc").hide();
										$("#modificatipodoc").hide();
										$("#altaarea").hide();
										$("#modificaarea").hide();
										$("#altapuesto").hide();
										$("#modificpuesto").hide();
										$("#altaintruccion").hide();
										$("#modificinstruccion").hide();
										$("#solucion").hide();
										$("#turnar_a").hide();
										$("#solucion_area").hide();
										$('#denegar_respuesta').hide();
										$('#ccopia1').hide();
										$('#ccopia2').hide();
										$('#ccopia3').hide();
										$('#ccopia4').hide();
										$('#ccopia5').hide();
										$('#ccopia6').hide();
										$('#ccopia7').hide();
										$('#ccopia8').hide();
										$('#ccopia9').hide();
										$('#ccopia10').hide();
										$("#altadirectorio").hide();
										$("#modificdirectorio").hide();
										$("#turnar_a_dg").hide();
										$("#contrasena_error").hide();
										$("#contrasena_error1").hide();
										$("#solucion_area_indivi").hide();
										$('#infro_directo').hide();
										$("#vista_preliminar").hide();
										$("#info_turnado").hide();
										$("#turnar_a_directores").hide();
										$('#ccopia_direc1').hide();
										$('#ccopia_direc2').hide();
										$('#ccopia_direc3').hide();
										$('#ccopia_direc4').hide();
										$('#ccopia_direc5').hide();$
										$('#altarepresentante').hide();
										$('#modificarrepresentante').hide();
										$('#altausuarioarea').hide();
										$('#altausuariofinal').hide();
										$('#antecendentes_volante').hide();
										$('#modificarrepresentantesub').hide();
									$('#altarepresentantesub').hide();
									
})
function quietar_error(div){

 $("#"+div+"").hide();
}

function guardar_doc(){
	 var referencia=$('#referencia').val();
	 var fecha_posible_sol=$('#fecha_posible_sol').val();
	 var select_nombre_remi=$('#select_nombre_remi').val();
	 var select_nombre_desti=$('#select_nombre_desti').val();
	 var select_tipo_doc=$('#select_tipo_doc').val();
	 var select_tipo_proce=$('#select_tipo_proce').val();
	 var prioridad = $("input[name='prioridad']:checked").val();
	 var checkeado=$("#descrago").attr("checked");
		if(checkeado) {
				var checkbox=1;
			} else {
				var checkbox=0;
			}
	 var sintesis=$('#sintesis').val();
	 var accion=$("input[name='accion']:checked").val();
	 var select_intruciones=$('#select_intruciones').val();
	 var acciones_adicionales=$('#acciones_adicionales').val();
	 
	 
	 if (referencia==""){
	
		  $("#referencia_error").show();
		  $("#referencia").focus();
		  return false;
  
	 }
	 if (fecha_posible_sol==""){
		  $("#fecha_alta_error").show();
		  return false;
	 }
	  if (select_nombre_remi==0){
		  $("#remitente_error").show();
		  $("#select_nombre_remi").focus();
		  return false;
	 }
	  if (select_nombre_desti==0){
		  $("#destinatario_error").show();
		  $("#select_nombre_desti").focus();
		  return false;
	 }
	
}
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
function buscar_destinatario_proce_puest(id_empleado){
	var id_empleado=id_empleado;
	$.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "buscar_remitente.php", 
			    data:'id_empleado='+id_empleado,
			    success: function(requestData){	
					$('#consulta_destinatario_procedencia').html(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
}

function guardar_notificacion(){
	  var select_nombre_desti=$('#select_nombre_desti').val();
	  var mensaje=$('#mensaje').val();
	  var remi=$('#remitente').val();
	if (select_nombre_desti==0){
		  $("#destinatario_error").show();
		  $("#select_nombre_desti").focus();
		  return false;
	 }
	 
	  if (mensaje==""){
		  $("#mensaje_error").show();
		  $("#mensaje").focus();
		  return false;
	 }
	$.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "guardar_notificaciones.php", 
			    data:'select_nombre_desti='+select_nombre_desti+'&mensaje='+mensaje+'&remi='+remi,
			    success: function(requestData){	
					alert("La notificacion se ha enviado al destinatario satisfactoriamente!!");
					$('#select_nombre_desti').val(0);
					$('#mensaje').val("");
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
}
	function altatipodoc(){
		$('#altatipodoc').dialog('destroy');
		$("#altatipodoc").dialog({
													bgiframe: true,
													height: 150,
													width:735,
													modal: true,
													autoOpen: false
												});
		$("#altatipodoc").dialog("open");
				
	}
	
	function cat_alta_doc(){
		 var descrip_doc=$('#descrip_doc').val();
		  var sist_apli=$('#aplica').val();
		  if (descrip_doc==""){
			  $("#altatipodoc").dialog("open")
			$("#descripcion_doc_error").show();
		  $("#descrip_doc").focus();
			  return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_guardar.php", 
			    data:'descrip_doc='+descrip_doc+'&sist_apli='+sist_apli,
			    success: function(requestData){	
					$('#altatipodoc').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	
	function modiftipodoc(id,descrip){
		$('#modificatipodoc').dialog('destroy');
		$("#modificatipodoc").dialog({
													bgiframe: true,
													height: 150,
													width:735,
													modal: true,
													autoOpen: false
												});
		$("#modificatipodoc").dialog("open");
		$('#descrip_mod_doc').val(descrip);
		$('#id_doc').val(id);
	}
	function cat_modificar_doc(tipo){
		 var id_doc=$('#id_doc').val();
		 var descrip_mod_doc=$('#descrip_mod_doc').val();
		 var maplica=$('#maplica').val();
		 var tipo=tipo;
		  if (descrip_mod_doc==""){
			 	$("#modificatipodoc").dialog("open");
				$("#descripcion_doc_modif_error").show();
		  		$("#descrip_mod_doc").focus();
			  	return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_modificar_doc_guardar.php", 
			    data:'descrip_doc='+descrip_mod_doc+'&id_doc='+id_doc+'&tipo='+tipo+'&maplica='+maplica,
			    success: function(requestData){	
					$('#modificatipodoc').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
		function eliminartipodoc(id){
		
		var id_doc=id;
		var tipo='E';
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url:  "cat_modificar_doc_guardar.php", 
			    data:'id_doc='+id_doc+'&tipo='+tipo,
			    success: function(requestData){	
				   alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
////////////////////////////////////////////////////////////////////

	function altaarea(idarea){
		$('#altaarea').dialog('destroy');
		$("#altaarea").dialog({
													bgiframe: true,
													height: 150,
													width:735,
													modal: true,
													autoOpen: false
												});
		$("#altaarea").dialog("open");
		$("#id_area").val(idarea);		
	}
	function cat_alta_area(){
		 var descrip_area=escape($('#descrip_area').val());
		  var id_area=$('#id_area').val();
		  if (descrip_area==""){
			  	$("#altaarea").dialog("open")
				$("#descripcion_area_error").show();
		  		$("#descrip_area").focus();
			  return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_area_guardar.php", 
			    data:'descrip_area='+descrip_area+'&id_area='+id_area,
			    success: function(requestData){	
					$('#altatipodoc').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	
		function modifarea(id,descrip){
		$('#modificaarea').dialog('destroy');
		$("#modificaarea").dialog({
													bgiframe: true,
													height: 105,
													width:520,
													modal: true,
													autoOpen: false
												});
		$("#modificaarea").dialog("open");
		$('#descrip_mod_area').val(descrip);
		$('#id_area').val(id);
	}
	
	function cat_modificar_area(tipo){
		 var id_area=$('#id_area').val();
		 var descrip_mod_area=escape($('#descrip_mod_area').val());
		 var tipo=tipo;
		  if (descrip_mod_area==""){
			 	$("#modificaarea").dialog("open");
				$("#descripcion_area_modif_error").show();
		  		$("#descrip_mod_area").focus();
			  	return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_modificar_area_guardar.php", 
			    data:'descrip_mod_area='+descrip_mod_area+'&id_area='+id_area+'&tipo='+tipo,
			    success: function(requestData){	
					$('#modificaarea').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	function eliminararea(id){
		
		var id_area=id;
		var tipo='E';
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url:  "cat_modificar_area_guardar.php", 
			    data:'id_area='+id_area+'&tipo='+tipo,
			    success: function(requestData){	
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	/////////////////////////////////////////////////////////////////
	
	function altapuestos(){
		$('#altapuesto').dialog('destroy');
		$("#altapuesto").dialog({
													bgiframe: true,
													height: 105,
													width:520,
													modal: true,
													autoOpen: false
												});
		$("#altapuesto").dialog("open");
				
	}
	function cat_alta_puesto(){
		 var descrip_puesto=$('#descrip_puesto').val();
		  if (descrip_puesto==""){
			  	$("#altapuesto").dialog("open")
				$("#descripcion_puesto_error").show();
		  		$("#descrip_puesto").focus();
			  return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_puesto_guardar.php", 
			    data:'descrip_puesto='+descrip_puesto,
			    success: function(requestData){	
					$('#altapuesto').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	
	function modifpuesto(id,descrip){
		$('#modificpuesto').dialog('destroy');
		$("#modificpuesto").dialog({
													bgiframe: true,
													height: 105,
													width:520,
													modal: true,
													autoOpen: false
												});
		$("#modificpuesto").dialog("open");
		$('#descrip_mod_puesto').val(descrip);
		$('#id_puesto').val(id);
	}
	
	function cat_modificar_puesto(tipo){
		 var id_puesto=$('#id_puesto').val();
		 var descrip_mod_puesto=$('#descrip_mod_puesto').val();
		 var tipo=tipo;
		  if (descrip_mod_puesto==""){
			 	$("#modificpuesto").dialog("open");
				$("#descripcion_puesto_modif_error").show();
		  		$("#descrip_mod_puesto").focus();
			  	return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_modificar_puesto_guardar.php", 
			    data:'descrip_mod_puesto='+descrip_mod_puesto+'&id_puesto='+id_puesto+'&tipo='+tipo,
			    success: function(requestData){	
					$('#modificpuesto').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	function eliminarpuesto(id){
		
		var id_puesto=id;
		var tipo='E';
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_modificar_puesto_guardar.php", 
			    data:'id_puesto='+id_puesto+'&tipo='+tipo,
			    success: function(requestData){	
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
////////////////////////////////////////////////////////////
		
	function altadirectorios(){
		$('#altadirectorio').dialog('destroy');
		$("#altadirectorio").dialog({
													bgiframe: true,
													height:566,
													width:682,
													modal: true,
													autoOpen: false
												});
		$("#altadirectorio").dialog("open");
				
	}
	function cat_alta_directorio(){
		 var nombre=escape($('#nombre').val());
		 var ap_paterno=escape($('#ap_paterno').val());
		 var ap_materno=escape($('#ap_materno').val());
		 var titulo=escape($('#titulo').val());
		 var dependencia=$('#dependencia').val();
		 var puesto=escape($('#puesto').val());
		 var domicilio=escape($('#domicilio').val());
		 var telefono=$('#telefono').val();
		 var email=$('#email').val();
		 var contacto=escape($('#contacto').val());
		 var aplica=$('#aplica').val();
		  if (nombre==""){
			  	$("#altadirectorio").dialog("open")
				$("#nombre_error").show();
		  		$("#nombre").focus();
			  return false;
			}
	
			
		  if (ap_paterno==""){
			  	$("#altadirectorio").dialog("open")
				$("#ap_paterno_error").show();
		  		$("#ap_paterno").focus();
			  return false;
			}
			
		  if (ap_materno==""){
			 	 ap_materno=" ";
			  
			}
			
		  if (titulo==""){
			  	$("#altadirectorio").dialog("open")
				$("#titulo_error").show();
		  		$("#titulo").focus();
			  return false;
			}
			
		  if (puesto==""){
			  	$("#altadirectorio").dialog("open")
				$("#puesto_error").show();
		  		$("#puesto").focus();
			  return false;
			}
			
		  if (dependencia==""){
			  	$("#altadirectorio").dialog("open")
				$("#dependencia_error").show();
		  		
			  return false;
			}
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_directorio_guardar.php", 
			    data:'nombre='+nombre+'&ap_paterno='+ap_paterno+'&ap_materno='+ap_materno+'&titulo='+titulo+'&puesto='+puesto+'&dependencia='+dependencia+'&domicilio='+domicilio+'&telefono='+telefono+'&email='+email+'&contacto='+contacto+'&aplica='+aplica,
			    success: function(requestData){	
					$('#altadirectorio').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	
	function modifdirectorios(clave,titulo,nombre,ape_pat,ape_mat,desc_area,desc_puest,o_direcc, o_telefono, email, contacto,sistemaAplica){
		$('#modificdirectorio').dialog('destroy');
		$("#modificdirectorio").dialog({
													bgiframe: true,
													height:566,
													width:682,
													modal: true,
													autoOpen: false
												});
		$("#modificdirectorio").dialog("open");
		$('#mclave').val(clave);
		$('#mnombre').val(nombre);
		$('#map_paterno').val(ape_pat);
		$('#map_materno').val(ape_mat);
		$('#mtitulo').val(titulo);
		$('#mdependencia').val(desc_area);
		$('#mpuesto').val(desc_puest);
		$('#mdomicilio').val(o_direcc);
		$('#mtelefono').val(o_telefono);
		$('#memail').val(email);
		$('#mcontacto').val(contacto);
		$('#maplica').val(sistemaAplica)
	}
	
	function cat_modificar_directorio(){
		 var mclave=$('#mclave').val();
		 var mnombre=escape($('#mnombre').val());
		 var map_paterno=escape($('#map_paterno').val());
		 var map_materno=escape($('#map_materno').val());
		 var mtitulo=escape($('#mtitulo').val());
		 var mdependencia=$('#mdependencia').val();
		 var mpuesto=escape($('#mpuesto').val());
		 var mdomicilio=escape($('#mdomicilio').val());
		 var mtelefono=$('#mtelefono').val();
		 var memail=$('#memail').val();
		 var mcontacto=escape($('#mcontacto').val());
		  var maplica=$('#maplica').val();
		 
		  if (mnombre==""){
			  	$("#maltadirectorio").dialog("open")
				$("#mnombre_error").show();
		  		$("#mnombre").focus();
			  return false;
			}
	
			
		  if (map_paterno==""){
			  	$("#maltadirectorio").dialog("open")
				$("#map_paterno_error").show();
		  		$("#map_paterno").focus();
			  return false;
			}
			
		  if (map_materno==""){
			  	map_materno=" ";
			  return false;
			}
			
		  if (mtitulo==""){
			  	$("#maltadirectorio").dialog("open")
				$("#mtitulo_error").show();
		  		$("#mtitulo").focus();
			  return false;
			}
			
		  if (mpuesto==""){
			  	$("#maltadirectorio").dialog("open")
				$("#mpuesto_error").show();
		  		$("#mpuesto").focus();
			  return false;
			}
			
		  if (mdependencia==""){
			  	$("#maltadirectorio").dialog("open")
				$("#mdependencia_error").show();
		  		$("#mdependencia").focus();
			  return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_mod_directorio_guardar.php", 
			    data:'mclave='+mclave+'&mnombre='+mnombre+'&map_paterno='+map_paterno+'&map_materno='+map_materno+'&mtitulo='+mtitulo+'&mpuesto='+mpuesto+'&mdependencia='+mdependencia+'&mdomicilio='+mdomicilio+'&mtelefono='+mtelefono+'&memail='+memail+'&mcontacto='+mcontacto+'&maplica='+maplica,
			    success: function(requestData){	
					$('#altadirectorio').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	function eliminardirectorio(id){
		
		var id_directorio=id;
		var tipo='E';
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_mod_directorio_guardar.php", 
			    data:'id_directorio='+id_directorio+'&tipo='+tipo,
			    success: function(requestData){	
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}

////////////////////////////////////////////////////////////
function altainstruccion(){
		$('#altaintruccion').dialog('destroy');
		$("#altaintruccion").dialog({
													bgiframe: true,
													height: 150,
													width:735,
													modal: true,
													autoOpen: false
												});
		$("#altaintruccion").dialog("open");
				
	}
	function cat_alta_instruccion(){
		 var descrip_instruccion=escape($('#descrip_instruccion').val());
		 var id_area=$('#id_area').val();
		  if (descrip_instruccion==""){
			  	$("#altaintruccion").dialog("open")
				$("#descripcion_instruccion_error").show();
		  		$("#descrip_instruccion").focus();
			  return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_instrucciones_guardar.php", 
			    data:'descrip_instruccion='+descrip_instruccion+'&id_area='+id_area,
			    success: function(requestData){	
					$('#altaintruccion').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	
	function modifinstruccion(id,descrip){
		$('#modificinstruccion').dialog('destroy');
		$("#modificinstruccion").dialog({
													bgiframe: true,
													height: 105,
													width:520,
													modal: true,
													autoOpen: false
												});
		$("#modificinstruccion").dialog("open");
		$('#descrip_mod_instruccion').val(descrip);
		$('#id_instruccion').val(id);
	}
	
	function cat_modificar_instruccion(tipo){
		 var id_instruccion=$('#id_instruccion').val();
		 var descrip_mod_instruccion=escape($('#descrip_mod_instruccion').val());
		 var tipo=tipo;
		  if (descrip_mod_instruccion==""){
			 	$("#modificinstruccion").dialog("open");
				$("#descripcion_puesto_modif_error").show();
		  		$("#descrip_mod_instruccion").focus();
			  	return false;
			}
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_modificar_instruccion_guardar.php", 
			    data:'descrip_mod_instruccion='+descrip_mod_instruccion+'&id_instruccion='+id_instruccion+'&tipo='+tipo,
			    success: function(requestData){	
					$('#modificinstruccion').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	function eliminarinstruccion(id){
		
		var id_instruccion=id;
		var tipo='E';
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_modificar_instruccion_guardar.php", 
			    data:'id_instruccion='+id_instruccion+'&tipo='+tipo,
			    success: function(requestData){	
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
///////////////////////////////////////////////////////


function solucion_doc(id, nombre_doc){
		$('#solucion').dialog('destroy');
		$("#solucion").dialog({
													bgiframe: true,
													height: 243,
													width:612,
													modal: true,
													autoOpen: false
												});
		$("#solucion").dialog("open");
		$('#id_doc').val(id);
		$('#nombre_doc').val(nombre_doc);
	}
	
function turnar_doc(){
		
		var id_doc=$('#id_doc').val();
		var solucion=$('#texto_solucion').val();
		 if (solucion==""){
			  	$("#solucion").dialog("open")
				$("#texto_solucion_error").show();
		  		$("#texto_solucion").focus();
			  return false;
			}
	    $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "turnar_docs.php", 
			    data:'id_doc='+id_doc+'&solucion='+solucion,
			    success: function(requestData){
					alert('El documento se Soluciono correctamente!!');
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	function turnar_nuevo_doc(id){
		$('#turnar_a').dialog('destroy');
		$("#turnar_a").dialog({
													bgiframe: true,
													height: 255,
													width:650,
													modal: true,
													autoOpen: false
												});
		$("#turnar_a").dialog("open");
		$('#id_doc_turnar').val(id);
	}
	
	function turnar_nuevo_doc_directores(id){
		$('#turnar_a_directores').dialog('destroy');
		$("#turnar_a_directores").dialog({
													bgiframe: true,
													height: 205,
													width:650,
													modal: true,
													autoOpen: false
												});
		$("#turnar_a_directores").dialog("open");
		$('#id_doc_turnar').val(id);
	}
	
	function turnar_doc_area(area){
		
		var id_doc=$('#id_doc_turnar').val();
		var select_nombre_turnar=$('#select_nombre_turnar_dire').val();
		var select_intruciones_0=$('#select_intruciones_dire').val();
		var accion_adi=$('#accion_adi').val();
		var select_nombre_desti_1=$('#select_nombre_desti_1_dire').val();
		var select_intruciones_1=$('#select_intruciones_1_dire').val();
		var accion_adi1=$('#accion_adi1').val();
		var select_nombre_desti_2=$('#select_nombre_desti_2_dire').val();
		var select_intruciones_2=$('#select_intruciones_2_dire').val();
		var accion_adi2=$('#accion_adi2').val();
		var select_nombre_desti_3=$('#select_nombre_desti_3_dire').val();
		var select_intruciones_3=$('#select_intruciones_3_dire').val();
		var accion_adi3=$('#accion_adi3').val();
		var select_nombre_desti_4=$('#select_nombre_desti_4_dire').val();
		var select_intruciones_4=$('#select_intruciones_4_dire').val();
		var accion_adi4=$('#accion_adi4').val();
		var select_nombre_desti_5=$('#select_nombre_desti_5_dire').val();
		var select_intruciones_5=$('#select_intruciones_5_dire').val();
		var accion_adi5=$('#accion_adi5').val();
		var area=area;
		
	    $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "turnar_docs_area.php", 
			    data:'id_doc='+id_doc+'&select_nombre_turnar='+select_nombre_turnar+'&select_intruciones_0='+select_intruciones_0+'&area='+area+'&select_nombre_desti_1='+select_nombre_desti_1+'&select_intruciones_1='+select_intruciones_1+'&select_nombre_desti_2='+select_nombre_desti_2+'&select_intruciones_2='+select_intruciones_2+'&select_nombre_desti_3='+select_nombre_desti_3+'&select_intruciones_3='+select_intruciones_3+'&select_nombre_desti_4='+select_nombre_desti_4+'&select_intruciones_4='+select_intruciones_4+'&select_nombre_desti_5='+select_nombre_desti_5+'&select_intruciones_5='+select_intruciones_5+'&accion_adi='+accion_adi+'&accion_adi1='+accion_adi1+'&accion_adi2='+accion_adi2+'&accion_adi3='+accion_adi3+'&accion_adi4='+accion_adi4+'&accion_adi5='+accion_adi5,
			    success: function(requestData){
					alert('El documento se Turnado correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	
	function turnar_doc_area_2010(area, anio){
		
		var id_doc=$('#id_doc_turnar').val();
		var select_nombre_turnar=$('#select_nombre_turnar_dire').val();
		var select_intruciones_0=$('#select_intruciones_dire').val();
		var accion_adi=$('#accion_adi').val();
		var select_nombre_desti_1=$('#select_nombre_desti_1_dire').val();
		var select_intruciones_1=$('#select_intruciones_1_dire').val();
		var accion_adi1=$('#accion_adi1').val();
		var select_nombre_desti_2=$('#select_nombre_desti_2_dire').val();
		var select_intruciones_2=$('#select_intruciones_2_dire').val();
		var accion_adi2=$('#accion_adi2').val();
		var select_nombre_desti_3=$('#select_nombre_desti_3_dire').val();
		var select_intruciones_3=$('#select_intruciones_3_dire').val();
		var accion_adi3=$('#accion_adi3').val();
		var select_nombre_desti_4=$('#select_nombre_desti_4_dire').val();
		var select_intruciones_4=$('#select_intruciones_4_dire').val();
		var accion_adi4=$('#accion_adi4').val();
		var select_nombre_desti_5=$('#select_nombre_desti_5_dire').val();
		var select_intruciones_5=$('#select_intruciones_5_dire').val();
		var accion_adi5=$('#accion_adi5').val();
		var area=area;
		
	    $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "turnar_docs_area_2010.php?a="+anio, 
			    data:'id_doc='+id_doc+'&select_nombre_turnar='+select_nombre_turnar+'&select_intruciones_0='+select_intruciones_0+'&area='+area+'&select_nombre_desti_1='+select_nombre_desti_1+'&select_intruciones_1='+select_intruciones_1+'&select_nombre_desti_2='+select_nombre_desti_2+'&select_intruciones_2='+select_intruciones_2+'&select_nombre_desti_3='+select_nombre_desti_3+'&select_intruciones_3='+select_intruciones_3+'&select_nombre_desti_4='+select_nombre_desti_4+'&select_intruciones_4='+select_intruciones_4+'&select_nombre_desti_5='+select_nombre_desti_5+'&select_intruciones_5='+select_intruciones_5+'&accion_adi='+accion_adi+'&accion_adi1='+accion_adi1+'&accion_adi2='+accion_adi2+'&accion_adi3='+accion_adi3+'&accion_adi4='+accion_adi4+'&accion_adi5='+accion_adi5,
			    success: function(requestData){
					alert('El documento se Turnado correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	
	
	
	function solucion_doc_area(id,id_general,archivo, id_area){
		$('#solucion_area').dialog('destroy');
		$("#solucion_area").dialog({
													bgiframe: true,
													height: 243,
													width:612,
													modal: true,
													autoOpen: false
												});
		$("#solucion_area").dialog("open");
		$('#id_doc_area').val(id);
		$('#id_doc_general').val(id_general);
		$('#archivo').val(archivo);
		$('#id_area').val(id_area);
	}
	function no_es_de_mi_procedencia(){
		
		var id_doc_area=$('#id_doc_area').val();
		var id_doc_general=$('#id_doc_general').val();
		var id_area=$('#id_area').val();
		   $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "no_es_de_mi_procedencia.php", 
			    data:'id_doc_area='+id_doc_area+'&id_doc_general='+id_doc_general+'&id_area='+id_area,
			    success: function(requestData){
					alert('El soluciono correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	
	} 
	function no_es_de_mi_procedencia_2010(){
		
		var id_doc_area=$('#id_doc_area').val();
		var id_doc_general=$('#id_doc_general').val();
		var id_area=$('#id_area').val();
		   $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "no_es_de_mi_procedencia_2010.php", 
			    data:'id_doc_area='+id_doc_area+'&id_doc_general='+id_doc_general+'&id_area='+id_area,
			    success: function(requestData){
					alert('El soluciono correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	
	} 
	
		function no_es_de_mi_procedencia_dg(){
		
		var id_doc=$('#id_doc').val();
		$.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "no_es_de_mi_procedencia_dg.php", 
			    data:'id_doc='+id_doc,
			    success: function(requestData){
					alert('El soluciono correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	
	} 
	
	function no_es_de_mi_procedencia_dg_2010(anio){
		
		var id_doc=$('#id_doc').val();
		$.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "no_es_de_mi_procedencia_dg_2010.php?a="+anio, 
			    data:'id_doc='+id_doc,
			    success: function(requestData){
					alert('El soluciono correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	
	} 
	
	
	function solucionar_doc_area(area){
		
		var id_doc_area=$('#id_doc_area').val();
		var id_doc_general=$('#id_doc_general').val();
		var area=area;
		var solucion=$('#texto_solucion').val();
		if (solucion==""){
			  	$("#solucion_area").dialog("open")
				$("#texto_solucion_error").show();
		  		$("#texto_solucion").focus();
			  return false;
			}
		
	    $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "solucionar_docs_area.php", 
			    data:'id_doc_area='+id_doc_area+'&id_doc_general='+id_doc_general+'&area='+area+'&solucion='+solucion,
			    success: function(requestData){
					alert('El documento se Soluciono correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
//////////////////////////////////Denegar Respuesta////////////////////////////////////////////
function dialogo_denegar_respuesta(volante, id_area){
		$('#denegar_respuesta').dialog('destroy');
		$("#denegar_respuesta").dialog({
													bgiframe: true,
													height: 207,
													width:590,
													modal: true,
													autoOpen: false
												});
		$("#denegar_respuesta").dialog("open");
		$('#volante').val(volante);
		$('#area').val(id_area);
		
	}	
function denegar_respuesta_docu(){	
    var volante=$('#volante').val();
	var area=$('#area').val();
	var motivo=$('#motivo').val();
		if (motivo==""){
			  	$("#denegar_respuesta").dialog("open")
				$("#motivo_error").show();
		  		$("#motivo").focus();
			  return false;
			}
 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "denegar_respuesta.php", 
			    data:'volante='+volante+'&motivo='+motivo+'&area='+area,
			    success: function(requestData){
					alert('Se denego la Respuesta al Oficio!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	function denegar_respuesta_docu_2010(anio){	
    var volante=$('#volante').val();
	var area=$('#area').val();
	var motivo=$('#motivo').val();
		if (motivo==""){
			  	$("#denegar_respuesta").dialog("open")
				$("#motivo_error").show();
		  		$("#motivo").focus();
			  return false;
			}
 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "denegar_respuesta_2010.php?a="+anio, 
			    data:'volante='+volante+'&motivo='+motivo+'&area='+area,
			    success: function(requestData){
					alert('Se denego la Respuesta al Oficio!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	function denegar_respuesta_docu_area(){	
    var volante=$('#volante').val();
	var area=$('#area').val();
	var motivo=$('#motivo').val();
		if (motivo==""){
			  	$("#denegar_respuesta").dialog("open")
				$("#motivo_error").show();
		  		$("#motivo").focus();
			  return false;
			}
 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "denegar_respuesta_area.php", 
			    data:'volante='+volante+'&motivo='+motivo+'&area='+area,
			    success: function(requestData){
					alert('Se denego la Respuesta al Oficio!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	
	
	function abrircc(n){
		$('#ccopia'+n).show();
		
	}
	
	function cerrarcc(n){
		$('#ccopia'+n).hide();
		$('#select_nombre_desti_'+n).val(0);
		
	}
	function abrircc_dire(n){
		$('#ccopia_direc'+n).show();
		
	}
	
	function cerrarcc_dire(n){
		$('#ccopia_direc'+n).hide();
		$('#select_nombre_desti_'+n).val(0);
		
	}
////////////////////////////////////////////////
	function turnar_nuevo_doc_dg(id){
		$('#turnar_a_dg').dialog('destroy');
		$("#turnar_a_dg").dialog({
													bgiframe: true,
													height: 205,
													width:650,
													modal: true,
													autoOpen: false
												});
		$("#turnar_a_dg").dialog("open");
		$('#id_doc_turnar').val(id);
	}
	function turnar_doc_area_dg(){
		
		var id_doc=$('#id_doc_turnar').val();
		var select_nombre_turnar=$('#select_nombre_turnar_dg').val();
		var select_intruciones_0=$('#select_intruciones_0_dg').val();
		
		
	    $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "turnar_docs_area_dg.php", 
			    data:'id_doc='+id_doc+'&select_nombre_turnar='+select_nombre_turnar+'&select_intruciones_0='+select_intruciones_0,
			    success: function(requestData){
					alert('El documento se Turnado correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	function aumentar_dias(dias){
	var dias=dias;
	$.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "aumentar_fecha.php", 
			    data:'dias='+dias,
			    success: function(requestData){	
					$('#fecha_posible_sol').val(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	function CambiarInstruccion(){
		$('#fecha_posible_sol').val(" ");
	    $('#select_intruciones').val(2);
		$('#select_intruciones_1').val(2);
		$('#select_intruciones_2').val(2);
		$('#select_intruciones_3').val(2);
		$('#select_intruciones_4').val(2);
		$('#select_intruciones_5').val(2);
		$('#select_intruciones_6').val(2);
		$('#select_intruciones_7').val(2);
		$('#select_intruciones_8').val(2);
		$('#select_intruciones_9').val(2);
		$('#select_intruciones_10').val(2);
	}
	
	function cambiar_contrasena(){
	  var contrasena=$('#contrasena').val();
	  var contrasena1=$('#contrasena1').val();
	  var id_usuario=$('#id_usuario').val();
	  var mail=$('#correo').val();
		if (contrasena==""){
			  $("#contrasena_error").show();
			  $("#contrasena").focus();
			  return false;
		 }
		 
		  if (contrasena!=contrasena1){
			  $("#contrasena_error1").show();
			  $("#contrasena1").focus();
			  return false;
		 }
	$.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "emailcambiocontrasena.php", 
			    data:'contrasena='+contrasena+'&id_usuario='+id_usuario+'&mail='+mail,
			    success: function(requestData){	
					$('#resultado').html(requestData);
					$('#contrasena').val("");
					$('#contrasena1').val("");
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
}
/////////////////////////////////////////////////////

function turnar_nuevo_doc_area(id_dg,id_area, area_perte){
		$('#turnar_a').dialog('destroy');
		$("#turnar_a").dialog({
													bgiframe: true,
													height: 255,
													width:650,
													modal: true,
													autoOpen: false
												});
		$("#turnar_a").dialog("open");
		$('#id_doc_turnar_dg').val(id_dg);
		$('#id_doc_turnar_area').val(id_area);
		$('#area_perte').val(area_perte);
	}
function turnar_doc_area_nuevo(area){
		
		var id_doc_dg=$('#id_doc_turnar_dg').val();
		var id_doc_area=$('#id_doc_turnar_area').val();
		var select_nombre_turnar=$('#select_nombre_turnar').val();
		var select_intruciones_0=$('#select_intruciones_0').val();
		var select_nombre_turnar_1=$('#select_nombre_desti_1').val();
		var select_intruciones_1=$('#select_intruciones_1').val();
		var select_nombre_turnar_2=$('#select_nombre_desti_2').val();
		var select_intruciones_2=$('#select_intruciones_2').val();
		var select_nombre_turnar_3=$('#select_nombre_desti_3').val();
		var select_intruciones_3=$('#select_intruciones_3').val();
		var select_nombre_turnar_4=$('#select_nombre_desti_4').val();
		var select_intruciones_4=$('#select_intruciones_4').val();
		var select_nombre_turnar_5=$('#select_nombre_desti_5').val();
		var select_intruciones_5=$('#select_intruciones_5').val();
		var area=$('#area_perte').val();;
		   $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "turnar_docs_area_nuevo.php", 
			    data:'select_nombre_turnar='+select_nombre_turnar+'&select_intruciones_0='+select_intruciones_0+'&select_nombre_turnar_1='+select_nombre_turnar_1+'&select_intruciones_1='+select_intruciones_1+'&select_nombre_turnar_2='+select_nombre_turnar_2+'&select_intruciones_2='+select_intruciones_2+'&select_nombre_turnar_3='+select_nombre_turnar_3+'&select_intruciones_3='+select_intruciones_3+'&select_nombre_turnar_4='+select_nombre_turnar_4+'&select_intruciones_4='+select_intruciones_4+'&select_nombre_turnar_5='+select_nombre_turnar_5+'&select_intruciones_5='+select_intruciones_5+'&area='+area+'&id_doc_dg='+id_doc_dg+'&id_doc_area='+id_doc_area,
			    success: function(requestData){
					alert('El documento se Turnado correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}

function turnar_doc_area_nuevo_2010(area){
		
		var id_doc_dg=$('#id_doc_turnar_dg').val();
		var id_doc_area=$('#id_doc_turnar_area').val();
		var select_nombre_turnar=$('#select_nombre_turnar').val();
		var select_intruciones_0=$('#select_intruciones_0').val();
		var select_nombre_turnar_1=$('#select_nombre_desti_1').val();
		var select_intruciones_1=$('#select_intruciones_1').val();
		var select_nombre_turnar_2=$('#select_nombre_desti_2').val();
		var select_intruciones_2=$('#select_intruciones_2').val();
		var select_nombre_turnar_3=$('#select_nombre_desti_3').val();
		var select_intruciones_3=$('#select_intruciones_3').val();
		var select_nombre_turnar_4=$('#select_nombre_desti_4').val();
		var select_intruciones_4=$('#select_intruciones_4').val();
		var select_nombre_turnar_5=$('#select_nombre_desti_5').val();
		var select_intruciones_5=$('#select_intruciones_5').val();
		var area=$('#area_perte').val();;
		   $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "turnar_docs_area_nuevo_2010.php", 
			    data:'select_nombre_turnar='+select_nombre_turnar+'&select_intruciones_0='+select_intruciones_0+'&select_nombre_turnar_1='+select_nombre_turnar_1+'&select_intruciones_1='+select_intruciones_1+'&select_nombre_turnar_2='+select_nombre_turnar_2+'&select_intruciones_2='+select_intruciones_2+'&select_nombre_turnar_3='+select_nombre_turnar_3+'&select_intruciones_3='+select_intruciones_3+'&select_nombre_turnar_4='+select_nombre_turnar_4+'&select_intruciones_4='+select_intruciones_4+'&select_nombre_turnar_5='+select_nombre_turnar_5+'&select_intruciones_5='+select_intruciones_5+'&area='+area+'&id_doc_dg='+id_doc_dg+'&id_doc_area='+id_doc_area,
			    success: function(requestData){
					alert('El documento se Turnado correctamente!!');
					location.reload();
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}



	function solucion_doc_area_individual(id_doc_area, id_doc_dg, archivo, volante, id_area){
		$('#solucion_area_indivi').dialog('destroy');
		$("#solucion_area_indivi").dialog({
													bgiframe: true,
													height: 243,
													width:612,
													modal: true,
													autoOpen: false
												});
		$("#solucion_area_indivi").dialog("open");
		$('#id_doc_area_ind').val(id_doc_area);
		$('#id_doc_dg').val(id_doc_dg);
		$('#archivo_ind').val(archivo);
		$('#volante').val(volante);
		$('#id_area_ind').val(id_area);
	}
	function abrir_infor(){
		
		$('#infro_directo').dialog('destroy');
						$("#infro_directo").dialog({
													bgiframe: true,
													height: 301,
													width:315,
													modal: true,
													autoOpen: false
												});
		
		$('#infro_directo').dialog('open');
		}
	
	function ver_info_direc(clave){
	 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "informacion_directorio.php", 
			    data:'clave='+clave,
			    success: function(requestData){
					   $('#infro_directo').html(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	
	function vista_preliminar_f(){
	 var referencia=$('#referencia').val();
	 var fecha_posible_sol=$('#fecha_posible_sol').val();
	 var select_nombre_remi=$('#select_nombre_remi').val();
	 var select_nombre_desti=$('#select_nombre_desti').val();
	 var select_tipo_doc=$('#select_tipo_doc').val();
	 var select_tipo_proce=$('#select_tipo_proce').val();
	 var prioridad = $("input[name='prioridad']:checked").val();
	 var checkeado=$("#descrago").attr("checked");
		if(checkeado) {
				var checkbox=1;
			} else {
				var checkbox=0;
			}
	 var sintesis=$('#sintesis').val();
	 var accion=$("input[name='accion']:checked").val();
	 var select_intruciones=$('#select_intruciones').val();
	 var acciones_adicionales=$('#acciones_adicionales').val();
	 var fecha_doc=$('#fecha_doc').val();
	 var obervaciones=$('#obervaciones').val();
		$('#vista_preliminar').dialog('destroy');
		$("#vista_preliminar").dialog({
													bgiframe: true,
													height: 680,
													width:1000,
													modal: true,
													autoOpen: false
												});
		$("#vista_preliminar").dialog("open");
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "VistaPreliminar.php", 
			    data:'referencia='+referencia+'&fecha_posible_sol='+fecha_posible_sol+'&select_nombre_remi='+select_nombre_remi+'&select_nombre_desti='+select_nombre_desti+'&select_tipo_doc='+select_tipo_doc+'&select_tipo_proce='+select_tipo_proce+'&prioridad='+prioridad+'&sintesis='+sintesis+'&select_intruciones='+select_intruciones+'&acciones_adicionales='+acciones_adicionales+'&fecha_doc='+fecha_doc+'&obervaciones='+obervaciones,
			    success: function(requestData){
					$("#vista_preliminar").html(requestData);
					
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	
	function vista_preliminar_area(area){
	 var area=area;
	 var referencia=$('#referencia').val();
	 var fecha_posible_sol=$('#fecha_posible_sol').val();
	 var select_nombre_remi=$('#select_nombre_remi').val();
	 var select_nombre_desti=$('#select_nombre_desti').val();
	 var select_tipo_doc=$('#select_tipo_doc').val();
	 var select_tipo_proce=$('#select_tipo_proce').val();
	 var prioridad = $("input[name='prioridad']:checked").val();
	 var checkeado=$("#descrago").attr("checked");
		if(checkeado) {
				var checkbox=1;
			} else {
				var checkbox=0;
			}
	 var sintesis=$('#sintesis').val();
	 var accion=$("input[name='accion']:checked").val();
	 var select_intruciones=$('#select_intruciones').val();
	 var acciones_adicionales=$('#acciones_adicionales').val();
	 var fecha_doc=$('#fecha_doc').val();
	 var obervaciones=$('#obervaciones').val();
		$('#vista_preliminar').dialog('destroy');
		$("#vista_preliminar").dialog({
													bgiframe: true,
													height: 680,
													width:1000,
													modal: true,
													autoOpen: false
												});
		$("#vista_preliminar").dialog("open");
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "VistaPreliminarPorArea.php", 
			    data:'referencia='+referencia+'&fecha_posible_sol='+fecha_posible_sol+'&select_nombre_remi='+select_nombre_remi+'&select_nombre_desti='+select_nombre_desti+'&select_tipo_doc='+select_tipo_doc+'&select_tipo_proce='+select_tipo_proce+'&prioridad='+prioridad+'&sintesis='+sintesis+'&select_intruciones='+select_intruciones+'&acciones_adicionales='+acciones_adicionales+'&fecha_doc='+fecha_doc+'&obervaciones='+obervaciones+'&area='+area,
			    success: function(requestData){
					$("#vista_preliminar").html(requestData);
					
				
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
   function abrir_infor_turnado(){
		
		$('#info_turnado').dialog('destroy');
						$("#info_turnado").dialog({
													bgiframe: true,
													height: 300,
													width:707,
													modal: true,
													autoOpen: false
												});
		
		$('#info_turnado').dialog('open');
		}
	function ver_info_turnado(clave, area){
	 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "informacion_turnado.php", 
			    data:'clave='+clave+'&area='+area,
			    success: function(requestData){
					   $('#info_turnado').html(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	function ver_info_turnado_2010(clave, area, anio){
	 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "informacion_turnado_2010.php", 
			    data:'a='+anio+'&clave='+clave+'&area='+area,
			    success: function(requestData){
					   $('#info_turnado').html(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
function ver_info_turnado_individual(clave, area){
	 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "informacion_turnado_individual.php", 
			    data:'clave='+clave+'&area='+area,
			    success: function(requestData){
					   $('#info_turnado').html(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	function ver_info_turnado_individual_2010(clave, area){
	 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "informacion_turnado_individual_2010.php", 
			    data:'clave='+clave+'&area='+area,
			    success: function(requestData){
					   $('#info_turnado').html(requestData);
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
/////////////////////////Administradores//////////////////////////////////////
function altaRepresnetante(){
		$('#altarepresentante').dialog('destroy');
		$("#altarepresentante").dialog({
													bgiframe: true,
													height:215,
													width:682,
													modal: true,
													autoOpen: false
												});
		$("#altarepresentante").dialog("open");
				
	}
function cat_guardar_repsentante_area(){
		 var Area=$('#Area').val();
		var Represnetante=$('#Represnetante').val();
		var Asistente1=$('#Asistente1').val();
		var Asistente2=$('#Asistente2').val();
		var subfijo=$('#subfijo').val();
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_representante_area.php", 
			    data:'Area='+Area+'&Represnetante='+Represnetante+'&Asistente1='+Asistente1+'&Asistente2='+Asistente2+'&subfijo='+subfijo,
			    success: function(requestData){	
					$('#altarepresentante').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
function modifdirespresentantes(idArea,idRepresentante,idAsistente,idAsistente2,id,subfijo){
		$('#modificarrepresentante').dialog('destroy');
		$("#modificarrepresentante").dialog({
													bgiframe: true,
													height:215,
													width:682,
													modal: true,
													autoOpen: false
												});
		$("#modificarrepresentante").dialog("open");
		$('#mArea').val(idArea);
		$('#mRepresnetante').val(idRepresentante);
		$('#mAsistente1').val(idAsistente);
		$('#mAsistente2').val(idAsistente2);
		$('#msubfijo').val(subfijo);
		$('#mid').val(id);
		
	}
function cat_modificar_repsentante_area(){
		var mid=$('#mid').val();
		var mArea=$('#mArea').val();
		var mRepresnetante=$('#mRepresnetante').val();
		var mAsistente1=$('#mAsistente1').val();
		var mAsistente2=$('#mAsistente2').val();
		var msubfijo=$('#msubfijo').val();
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_modifi_representante_area.php", 
			    data:'mArea='+mArea+'&mRepresnetante='+mRepresnetante+'&mAsistente1='+mAsistente1+'&mAsistente2='+mAsistente2+'&msubfijo='+msubfijo+'&mid='+mid,
			    success: function(requestData){	
					$('#altarepresentante').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	function cat_aliminar_repsentante_area(id){
		
		var id=id;
	
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_eliminar_representante_area.php", 
			    data:'id='+id,
			    success: function(requestData){	
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}



function altaUsuarioArea(){
		$('#altausuarioarea').dialog('destroy');
		$("#altausuarioarea").dialog({
													bgiframe: true,
													height:215,
													width:682,
													modal: true,
													autoOpen: false
												});
		$("#altausuarioarea").dialog("open");
				
	}
	function cat_guardar_usuario_area(){
		 var Area=$('#Area').val();
		var usuario=$('#usuario').val();
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_usuarios_area.php", 
			    data:'Area='+Area+'&usuario='+usuario,
			    success: function(requestData){	
					$('#altausuarioarea').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	function cat_aliminar_usuario_area(idArea,IdUsuario ){
		
		var idArea=idArea;
	    var IdUsuario=IdUsuario
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_eliminar_usuarios_area.php", 
			    data:'idArea='+idArea+'&IdUsuario='+IdUsuario,
			    success: function(requestData){	
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	
	function altaUsuarioFinal(){
		$('#altausuariofinal').dialog('destroy');
		$("#altausuariofinal").dialog({
													bgiframe: true,
													height:215,
													width:682,
													modal: true,
													autoOpen: false
												});
		$("#altausuariofinal").dialog("open");
				
	}
	
	function cat_guardar_usuario_Final(){
		 var usuario_turnar=$('#usuario_turnar').val();
		var usuario_final=$('#usuario_final').val();
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_usuarios_finales.php", 
			    data:'usuario_turnar='+usuario_turnar+'&usuario_final='+usuario_final,
			    success: function(requestData){	
					$('#altausuariofinal').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	function cat_aliminar_usuario_final(idUsuarioTurnar,idUsuarioFinal ){
		
		var idUsuarioTurnar=idUsuarioTurnar;
	    var idUsuarioFinal=idUsuarioFinal
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_eliminar__usuarios_finales.php", 
			    data:'idUsuarioTurnar='+idUsuarioTurnar+'&idUsuarioFinal='+idUsuarioFinal,
			    success: function(requestData){	
					alert('Transaccion Exitosa!!')
					location.reload();
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
	
	function eliminar_doc_dg(volante,doc ){
		
		var volante=volante;
	    var doc=doc;
		
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "eliminar_doc_dg.php", 
			    data:'volante='+volante+'&doc='+doc,
			    success: function(requestData){	
					$('.archivo_'+doc).hide()
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
	}
  
  function ver_antecedentes(volante, clave, area){
	   var volante=volante;
		  var area=area;
		    var clave=clave;
		$('#antecendentes_volante').dialog('destroy');
		$("#antecendentes_volante").dialog({
													bgiframe: true,
													height:450,
													width: 450,
													modal: true,
													autoOpen: false
												});
		$("#antecendentes_volante").dialog("open");
		
		 $.ajax({	  
				dataType: "html",
				type: "POST",  
				url: "informacion_antecedentes.php", 
			    data:'volante='+volante+'&area='+area+'&clave='+clave,
			    success: function(requestData){	
					$('#antecendentes_volante').html(requestData);
					
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}/////////////////////////////////////////////////////////////////////
	
	function altaRepresnetanteSubarea(){
		$('#altarepresentantesub').dialog('destroy');
		$("#altarepresentantesub").dialog({
													bgiframe: true,
													height:215,
													width:682,
													modal: true,
													autoOpen: false
												});
		$("#altarepresentantesub").dialog("open");
				
	}
function cat_guardar_repsentante_subarea(){
		 var subArea=$('#inser_sub_area').val();
		var subRepresnetante=$('#inser_id_re_subarea').val();
	
		 $.ajax( 
			{	  
				dataType: "html",
				type: "POST",  
				url: "cat_alta_representante_subarea.php", 
			    data:'subArea='+subArea+'&subRepresnetante='+subRepresnetante,
			    success: function(requestData){	
					$('#altarepresentantesub').dialog('destroy');
					alert('Transaccion Exitosa!!')
					location.reload();
					
				},         
			    error: function(requestData, strError, strTipoError){   
					alert("Error " + strTipoError +': ' + strError); 
			    },
			    complete: function(requestData, exito){  
			    }  
			}
		  );
				
	}
	
	
	
	
	
  
