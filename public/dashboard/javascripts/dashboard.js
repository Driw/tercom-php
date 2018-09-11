
const DEV = window.location.host === 'tercom.localhost';
const BASE_URL = window.location.protocol+ '//' +window.location.host+ '/';
const API_STATUS_SUCCESS = 1;

$(document).ready(function()
{
	initSpoiler();
	initMasks();
	initDateTimePicker();
	initJQueryValidators();
	initJQueryLoadingOverlay();
});

function initSpoiler()
{
	$('.spoiler-trigger').click(function()
	{
		var targetID = this.dataset.spoilerTarget;
		var target = document.getElementById(targetID);
		target.collapse('toggle');
	});
}

function initMasks()
{
	$('.mask-cep').mask('00000-000', {reverse: false});
	$('.mask-cpf').mask('000.000.000-00', {reverse: false});
	$('.mask-cnpj').mask('00.000.000/0000-00', {reverse: false});
	$('.mask-money').mask('#.##0,00', { reverse: true });
	$('.mask-percent').mask('00,00%', { reverse: true });
	$('.mask-integer').mask('#0', { reverse: true });
	$('.mask-simple-date').mask('0000-00-00', { reverse: false });
	$('.mask-date-time').mask('0000-00-00 00:00:00', { reverse: false });
	$('.mask-full-time').mask('00:00:00', { reverse: false });
	$('.mask-hour-minute').mask('00:00', { reverse: false });
	$('.mask-credit-card').mask('0000 0000 0000 ####', {reverse: false});

	var SPMaskBehavior = function (val) { return val.replace(/\D/g, '').length === 9 ? '00000-0000' : '0000-00009'; };
	var spOptions = { onKeyPress: function(val, e, field, options) { field.mask(SPMaskBehavior.apply({}, arguments), options); } };
	$('.mask-phone').mask(SPMaskBehavior, spOptions);
}

function initDateTimePicker()
{
	var autoClose = 1;
	var weekStart = 0;
	var todayBtn = 1;
	var todayHighlight = 1;
	var forceParse = 0;
	var fontAwesome = 1;

	$('.birthday-picker').datetimepicker({
		format: 'yyyy-mm-dd',
		language:  'pt-BR',
	    startView: 'decade',
		minView: 'month',
		maxView: 'decade',
		autoclose: autoClose,
	    weekStart: weekStart,
		forceParse: forceParse,
	    todayBtn:  todayBtn,
		todayHighlight: todayHighlight,
		fontAwesome: fontAwesome,
	});
	$('.birthday-picker').mask("0000-00-00", {placeholder: "0000-00-00"});

	$('.date-timer-picker').datetimepicker({
		format: 'yyyy-mm-dd HH:ii:00',
		language:  'pt-BR',
	    startView: 'month',
		minView: 'hour',
		maxView: 'year',
		autoclose: autoClose,
	    weekStart: weekStart,
		forceParse: forceParse,
	    todayBtn:  todayBtn,
		todayHighlight: todayHighlight,
		fontAwesome: fontAwesome,
	});
	$('.date-timer-picker').mask("00-00-0000 00:00:00", {placeholder: "0000-00-00 00:00:00"});

	$('.date-picker').datetimepicker({
		format: 'yyyy-mm-dd',
		language:  'pt-BR',
	    startView: 'month',
		minView: 'month',
		maxView: 'year',
		autoclose: autoClose,
	    weekStart: weekStart,
		forceParse: forceParse,
	    todayBtn:  todayBtn,
		todayHighlight: todayHighlight,
		fontAwesome: fontAwesome,
	});
	$('.date-picker').mask("00-00-0000", {placeholder: "0000-00-00"});

	$('.time-picker').datetimepicker({
		format: 'HH:ii:00',
		language:  'pt-BR',
	    startView: 'day',
		minView: 'hour',
		maxView: 'day',
		autoclose: autoClose,
	    weekStart: weekStart,
		forceParse: forceParse,
	    todayBtn:  0,
		todayHighlight: 0,
		fontAwesome: fontAwesome,
	});
	$('.time-picker').mask("00:00:00", {placeholder: "00:00:00"});
}

function initJQueryValidators()
{
	$.validator.addMethod('remoteapi', function(value, element, params)
	{
		var settings = {};

		if (typeof params === 'object')
			settings = params;

		else if (Array.isArray(params))
		{
			settings.webservice = params[0];
		}

		if (settings.webservice === undefined)
		{
			$.validator.messages.remote = 'remoteapi sem webservice definido';
			return false;
		}

		if (settings.replacePattern !== undefined)
			value = value.replace(settings.replacePattern[0], settings.replacePattern[1]);

		var url = API_ENDPOINT+settings.webservice+ '/' +value;

		if (settings.parameters !== undefined)
		{
			var webservice = settings.webservice.replace('{value}', value);

			for (var key in settings.parameters)
			{
				var parameter = settings.parameters[key];
				parameter = typeof(parameter) === 'function' ? parameter() : parameter;
				webservice = webservice.replace('{' +key+ '}', parameter);
			}

			url = API_ENDPOINT+webservice;
		}

		return $.validator.methods.remote.call(this, value, element, {
			'url': url,
			'type': 'POST',
			'cache': false,
			'dataType': 'json',
			'processData': false,
			dataFilter: function(response)
			{
				var jsonResponse = JSON.parse(response);

				if (jsonResponse.status !== API_STATUS_SUCCESS)
				{
					$.validator.messages.remote = jsonResponse.message;
					return false;
				}

				$.validator.messages.remote = jsonResponse.message;

				if (jsonResponse.result.ok !== undefined)
					return jsonResponse.result.ok;

				return jsonResponse.status === API_STATUS_SUCCESS;
			}
		});
	}, function() {
		return $.validator.messages.remote;
	});
}

function initJQueryLoadingOverlay()
{
	$.LoadingOverlaySetup({
		background	: 'rgb(0, 0, 0, 0.0)',
	});
}

function newDataTables(table)
{
	return table.DataTable({
		'autoWidth': false,
		'language': {
			'sProcessing': 'A processar...',
			'sLengthMenu': 'Mostrar _MENU_ registos',
			'sZeroRecords': 'Não foram encontrados resultados',
			'sInfo': 'Mostrando de _START_ até _END_ de _TOTAL_ registos',
			'sInfoEmpty': 'Mostrando de 0 até 0 de 0 registos',
			'sInfoFiltered': '(filtrado de _MAX_ registos no total)',
			'sInfoPostFix': '',
			'sSearch': 'Procurar:',
			'sUrl': '',
			'oPaginate': {
				'sFirst': 'Primeiro',
				'sPrevious': 'Anterior',
				'sNext': 'Seguinte',
				'sLast': 'Último'
			}
		}
	});
}

function showModalApiError(message, title)
{
	$('#api-error-modal-label').html(title === undefined ? 'Erro de API' : title);
	$('#api-error-modal-message').html(message);
	$('#api-error-modal').modal('show');
}

function apiNeedShowErrorModal(response, title)
{
	if (response.status === API_STATUS_SUCCESS)
		return false;

	showModalApiError(response.message, title);
	return true;
}

function parseAdvancedObject(advancedObject)
{
	var object = {};

	if (advancedObject.attributes !== undefined)
		object = advancedObject.attributes;
	else
		object = advancedObject;

	if (object.elements === undefined)
		for (var attribute in object)
		{
			if (object[attribute] !== null && object[attribute].attributes !== undefined)
				object[attribute] = parseAdvancedObject(object[attribute]);
		}

	else
	{
		for (var i = 0; i < object.elements.length; i++)
			object.elements[i] = parseAdvancedObject(object.elements[i]);
	}

	return object;
}

/* MÉTODOS UTILITÁRIOS */

var Util = Util ||
{
	redirect: function(path, newTab)
	{
		if (!path.startsWith('/'))
			path = 'dashboard/' +path;

		var url = BASE_URL + path;

		if (newTab !== true)
			window.location = url;
		else
			window.open(url, '_blank');
	},
	showChecked: function(bool)
	{
		return bool === true ? '<i class="fas fa-check-square"></i>' : '<i class="fas fa-uncheck-square"></i>';
	},
	createElementOption: function(html, value)
	{
		var element = document.createElement('option');
		var option = $(element);
		option.val(value);
		option.html(html);

		return option;
	},
}
