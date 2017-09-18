/**
@desc: Inserta un nou Hdd de backup mitjançant una crida ajax
@script: hddsBackups.php
*/
function insertNewHddBackup()
{
	// Valors del formulari	
	var newHddMetropolitanaId = $("#newHddMetropolitanaId").val();
	var newHddNom 			  = $("#newHddNom").val();
	var newHddEstat 		  = $("#newHddEstat").val();

	$.ajax({
		      method: "POST",
		      url: "/ajax/ajaxHdd.php",
		      data: { "action": "insertNewHddBackup", "newHddMetropolitanaId": newHddMetropolitanaId, "newHddNom": newHddNom, "newHddEstat": newHddEstat},
		      dataType: "json"
		    }).done(function(data) {

		      if(data == "1")
		      {
		      	// Si hi ha exit amaguem el botó de insertar projecte i carreguem altres de finalitzar i insertar nou projecte
		      	alert("Disc dur de backup afegit correctament");
		      	window.location.href='/manage/hdds/hddsBackups.php';
		      }
		      else
		      {
		      	alert("No s'ha pogut guardar el Disc dur");
		      }
		    });
}

/**
@desc: Carrega el formulari per insertar un projecte en un disc dur de backup
@script: hddsBackups.php
*/
function loadInsertHddForm()
{
	$("#insertHddForm").show();
}

/**
@desc: Amaga el formulari per insertar un projecte en un disc dur de backup
@script: hddsBackups.php
*/
function hideInsertHddForm()
{
	$("#insertHddForm").hide();
}

/**
@desc: Envia un ajax amb la info del projecte del formulari per insertar-la a la bbdd.
@script: hddsBackups.php
*/
function insertProject()
{
	// Valors del formulari	
	var idHdd 				 = $("#idHdd").val();
	var idProjecte 			 = $("#idProjecte").val();
	var nomProjecte 		 = $("#nomProjecte").val();
	var clientProjecte 		 = $("#clientProjecte").val();
	var operadorProjecte 	 = $("#operadorProjecte").val();
	var sistemaProjecte 	 = $("#sistemaProjecte").val();
	var observacionsProjecte = $("#observacionsProjecte").val(); 

	$.ajax({
		      method: "POST",
		      url: "/ajax/ajaxHdd.php",
		      data: { "action": "insertProject", "idHdd": idHdd, "idProjecte": idProjecte, "nomProjecte": nomProjecte, "clientProjecte": clientProjecte, "operadorProjecte": operadorProjecte, "sistemaProjecte": sistemaProjecte, "observacionsProjecte": observacionsProjecte},
		      dataType: "json"
		    }).done(function(data) {

		      if(data == "1")
		      {
		      	// Si hi ha exit amaguem el botó de insertar projecte i carreguem altres de finalitzar i insertar nou projecte
		      	alert("Projecte afegit correctament");
		      	$("#groupLoadInsertForm").hide();
		        $("#addProjectOrFinishHdd").show();
		      }
		      else
		      {
		      	alert("No s'ha pogut guardar el projecte al HDD");
		      }
		    });
}

/**
@desc: Blanqueja el formulari per insertar un nou projecte
@script: hddsBackups.php
*/

function insertNewProject()
{
	$("#nomProjecte").val('');
	$("#clientProjecte").val('');
	$("#operadorProjecte").val('');
	$("#sistemaProjecte").val('');
	$("#observacionsProjecte").val('');

	$("#groupLoadInsertForm").show();
	$("#addProjectOrFinishHdd").hide();
}

/**
@desc: Dóna com a finalitzat (omplert) un HDD de backup
@script: hddsBackups.php
*/
function finishForm()
{
	var hddId = $("#idHdd").val();

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "finishHdd", "idHdd": hddId},
	      dataType: "json"
	    }).done(function(data) {

	      if(data == "1")
	      {
	      	alert("Disc dur marcat com a plè");
	      	window.location.href='/manage/hdds/hddsBackups.php';
	      }
	      else
	      {
	      	alert("Error al intentar marcar el HDD com a plè");
	      }
	    });
}

/**
@desc: Cridada en event onchange. Carrega la direcció de la opció selccionada en el formulari original
@script: hddsBackups.php
@param: selectVAlue: Valor del valor seleccionat al formulari
*/
function carregaAdrecaClient(selectValue)
{
	var clientId = selectValue.value;

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadAdrecaClient", "clientId": clientId},
	      
	    }).done(function(data) {

	      if(data)
	      {
	      	$("#adrecaClient").val(data);
	      }
	      else
	      {
	      	alert("Error al carregar l'adreça del client");
	      }
	    });
}

/**
@desc: Cridada en event onchange. Carrega la direcció de la opció selccionada en el modal de editar
@script: hddsBackups.php
@param: selectVAlue: Valor del valor seleccionat al formulari
*/
function modalCarregaAdrecaClient(selectValue)
{
	var clientId = selectValue.value;

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadAdrecaClient", "clientId": clientId},
	      
	    }).done(function(data) {

	      if(data)
	      {
	      	$("#modalAdrecaClientHdd").val(data);
	      }
	      else
	      {
	      	alert("Error al carregar l'adreça del client");
	      }
	    });
}

/**
@desc: Cridada en event onchange. Carrega el client de la opció selccionada en el form
@script: hddsClients.php
@param: selectVAlue: Valor del valor seleccionat al formulari
*/
function carregaClients(selectValue)
{
	var clientId = selectValue.value;

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadClients", "clientId": clientId},
	      
	    }).done(function(data) {
	      if(data)
	      {
	      	$("#selectClientUserClient").val(data);
	      }
	      else
	      {
	      	alert("Error al carregar el tipus d'usuari seleccionat");
	      }
	    });
}

/**
@desc: Cridada en event onchange. Carrega el client de la opció selccionada en el modal de editar
@script: hddsClients.php
@param: selectVAlue: Valor del valor seleccionat al formulari
*/
function modalCarregaClients(selectValue)
{
	var clientId = selectValue.value;

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadClients", "clientId": clientId},
	      
	    }).done(function(data) {
	      if(data)
	      {
	      	$("#modalClientUserClient").val(data);
	      }
	      else
	      {
	      	alert("Error al carregar el tipus d'usuari seleccionat");
	      }
	    });
}

/**
@desc: Cridada en event onchange. Carrega els tipus d'usuari al formulari. Transforma ids en noms legibles
@script: hddsClients.php
@param: selectVAlue: Valor del valor seleccionat al formulari
*/
function carregaTipusUsuari(selectValue)
{
	var tipusUsuariId = selectValue.value;

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadTipusUsuari", "tipusUsuariId": tipusUsuariId},
	      
	    }).done(function(data) {
	    	console.log(data);
	      if(data)
	      {
	      	$("#selectTipusUsuari").val(data);
	      }
	      else
	      {
	      	alert("Error al carregar el tipus d'usuari seleccionat");
	      }
	    });
}

/**
@desc: Cridada en event onchange. Carrega els idiomes al formulari. Transforma ids en noms legibles
@script: hddsClients.php
@param: selectVAlue: Valor del valor seleccionat al formulari
*/
function carregaIdiomes(selectValue)
{
	var idiomaId = selectValue.value;

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadIdioma", "idiomaId": idiomaId},
	      
	    }).done(function(data) {

	      if(data)
	      {
	      	$("#selectIdiomes").val(data);
	      }
	      else
	      {
	      	alert("Error al carregar el idioma seleccionat");
	      }
	    });
}

/**
@desc: Cridada en event onchange. Carrega els idiomes al modal de editar. Transforma ids en noms legibles
@script: hddsClients.php
@param: selectVAlue: Valor del valor seleccionat al formulari
*/
function modalCarregaIdiomes(selectValue)
{
	var idiomaId = selectValue.value;

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadIdioma", "idiomaId": idiomaId},
	      
	    }).done(function(data) {

	      if(data)
	      {
	      	$("#modalClientUserIdioma").val(data);
	      }
	      else
	      {
	      	alert("Error al carregar el idioma seleccionat");
	      }
	    });
}

/**
@desc: Agafa les dades del formulari de insertar un nou HDD. Envia un ajax que s'encarrega d'insertar a bbdd
@script: hddsClients.php
*/
function insertClientHdd()
{
	var client 		 = $("#selectClient").val();
	var adreca 		 = $("#adrecaClient").val();
	var projecte 	 = $("#projecte").val();
	var descripcio 	 = $("#descripcio").val();
	var localitzacio = $("#localitzacio").val();

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "insertClientHdd", "client": client, "adreca": adreca, "projecte": projecte, "descripcio": descripcio, "localitzacio": localitzacio},
	      dataType: "json"
	    }).done(function(data) {

	      if(data == "1")
	      {
	      	alert("Disc inseretat correctament");
	      	window.location.href='/manage/hdds/hddsClients.php';
	      }
	      else
	      {
	      	alert("Error al carregar l'adreça del client");
	      }
	    });
}

function insertProjectIntoHddBackup()
{
	var nomProjecte 	= $("#nomProjecte").val();
	var operador 		= $("#selectOperador").val();
	var sistema 		= $("#selectSistema").val();
	var operadorSuport 	= $("#selectSuport").val();
	var client 			= $("#clientProjecte").val();
	var comentaris 		= $("#comentarisProjecte").val();
	var hdd 			= $("#hddIdProjecte").val();
	var rutaMaster 		= $("#rutaMaster").val();

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "insertProjectIntoHddBackup", "nomProjecte": nomProjecte, "operador": operador, "sistema": sistema, "operadorSuport": operadorSuport, "client": client, "comentaris": comentaris, "hdd": hdd, "rutaMaster": rutaMaster},
	      dataType: "json"
	    }).done(function(data) {

	      if(data == "1")
	      {
	      	alert("Projecte inseretat correctament");
	      	window.location.href='/manage/hdds/hddsBackups.php';
	      }
	      else
	      {
	      	alert("Error al insertar un nou projecte");
	      }
	    });
}

/**
@desc: Carrega un modal amb les dades del hdd seleccionat. Envia un ajax on carrega les dades de l'id passat
@script: hddsClients.php
@param: hddId -> id del disc dur que volem carregar al modal
*/
function loadHddModal(hddId)
{
	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadHddModal", "hddId": hddId},
	      dataType: "json"
	    }).done(function(data) {
	      if(data)
	      {
	      	$("#modalUpdateHdd").modal({width:250,height:450});
			$("#modalHddId").val(data.id);
			$("#modalSelectClient").val(data.client);
			$("#modalDescripcio").val(data.descripcio);
			$("#modalAdrecaClientHdd").val(data.adreca);
			$("#modalProjecteDepartament").val(data.projecteDepartament);
			$("#modalLocalitzacio").val(data.localitzacio);
			$("#modalDataInsercio").val(data.dataInsercio);
			$("#modalEstat").val(data.estat);
			$("#modalDataRetorn").val(data.dataRetorn);
	      }
	      else
	      {
	      	alert("Error al carregar la informació del disc seleccionat");
	      }
	    });
}

/**
@desc: Carrega un modal amb les dades del userClient seleccionat. Envia un ajax on carrega les dades de l'id passat
@script: hddsClients.php
@param: userClientId -> id del userClient que volem carregar al modal
*/
function loadUserClientModal(userClientId)
{
	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadUserClientModal", "userClientId": userClientId},
	      dataType: "json"
	    }).done(function(data) {
	      if(data)
	      {	
			$("#modalUsuariClientId").val(data.id);
			$("#modalNomUserClient").val(data.nom);
			$("#modalCognom1UserClient").val(data.cognom1);
			$("#modalCognom2UserClient").val(data.cognom2);
			$("#modalEmailUserClient").val(data.email);
			$("#modalTelefonUserClient").val(data.telefon);
			$("#modalClientUserClient").val(data.client);
			$("#modalClientUserTipusUsuari").val(data.tipusUsuari);
			$("#modalClientUserIdioma").val(data.idioma);
			$("#modalUserClientNotificacio").val(data.notificacio);
			$("#modalUpdateUserClient").modal({width:250,height:450});
	      }
	      else
	      {
	      	alert("Error al carregar la informació de l'usuari seleccionat");
	      }
	    });
}

/**
@desc: Carrega un modal amb les dades del client seleccionat. Envia un ajax on carrega les dades de l'id passat
@script: hddsClients.php
@param: clientId -> id del client que volem carregar al modal
*/
function loadClientModal(clientId)
{
	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadClientModal", "clientId": clientId},
	      dataType: "json"
	    }).done(function(data) {
	      if(data)
	      {	
	      	console.log(data);
			$("#modalClientId").val(data.id);
			$("#modalNomClient").val(data.nom);
			$("#modalAdrecaClient").val(data.adreca);
			
			$("#modalUpdateClient").modal({width:250,height:450});
	      }
	      else
	      {
	      	alert("Error al carregar la informació del client seleccionat");
	      }
	    });
}

/**
@desc: Inserta un nou client a la bbdd. Envia un ajax on guarda les dades de un nou client en bbdd
@script: hddsClients.php
*/
function insertNewClient()
{
	var nom    = $("#newClientNom").val();
	var adreca = $("#newClientAdreca").val();

	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "insertNewClient", "nom": nom, "adreca": adreca},
	      dataType: "json"
	    }).done(function(data) {

	      if(data == "1")
	      {
	      	alert("Client inseretat correctament");
	      	window.location.href='/manage/hdds/hddsClients.php';
	      }
	      else
	      {
	      	alert("Error al insertar un nou client");
	      }
	    });
}

/**
@desc: Obre un modal per editar la informació dun disc dur de backup
@script: hddsBackups.php
*/
function loadHddBackupModal(hddId)
{
	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadHddBackupModal", "hddId": hddId},
	      dataType: "json"
	    }).done(function(data) {
	      if(data)
	      {
			$("#modalHddBackupId").val(data.id);
			$("#modalHddBackupMetropolitanaId").val(data.metropolitanaId);
			$("#modalNomHddBackup").val(data.nom);
			$("#modalEstatHddBackup").val(data.estat);
			
			$("#modalHddBackup").modal({width:250,height:450});
	      }
	      else
	      {
	      	alert("Error al carregar la informació del disc seleccionat");
	      }
	    });
}

/**
@desc: Obre un modal per editar la informació dun disc dur de backup
@script: hddsBackups.php
*/
function loadProjectHddBackupModal(projectId)
{
	$.ajax({
	      method: "POST",
	      url: "/ajax/ajaxHdd.php",
	      data: { "action": "loadProjectHddBackupModal", "projectId": projectId},
	      dataType: "json"
	    }).done(function(data) {
	      if(data)
	      {
			$("#modalProjecteId").val(data.id);
			$("#modalProjecteHddId").val(data.hddId);
			$("#modalProjectOperador").val(data.operador);
			$("#modalProjectSistema").val(data.sistema);
			$("#modalProjectNomProjecte").val(data.projecte);
			$("#modalProjectDataProject").val(data.data);
			$("#modalProjectComentaris").val(data.comentaris);
			$("modalProjectClientProjecte").val(data.clientProjecte);
			$("#modalProjectRutaAMaster").val(data.rutaMaster);
			
			$("#loadProjectHddBackupModal").modal({width:250,height:450});
	      }
	      else
	      {
	      	alert("Error al carregar la informació del projecte seleccionat");
	      }
	    });
}


