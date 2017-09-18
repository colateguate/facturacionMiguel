/*
Name: 			Tables / Advanced - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	1.5.4
*/

(function($) {

	'use strict';

	var datatableInit = function() {

		$('#datatable-default').dataTable();
		$("#datatableTaulaClients").dataTable();
		$("#datatableClientsUsuaris").dataTable();

	};

	$(function() {
		datatableInit();
	});

}).apply(this, [jQuery]);