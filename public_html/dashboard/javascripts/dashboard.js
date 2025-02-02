
const DEV = window.location.host === 'tercom.localhost';
const BASE_URL = window.location.protocol+ '//' +window.location.host+ '/';
const API_STATUS_SUCCESS = 1;
const NO_VALUE = '-';
const DEFAULT_FADE = 'fast';
const DEFAULT_TIMEOUT = 5000;

const PRODUCT_FAMILY_TYPE = 1;
const PRODUCT_GROUP_TYPE = 2;
const PRODUCT_SUBGROUP_TYPE = 3;
const PRODUCT_SECTOR_TYPE = 4;

const ORS_NONE = 0;
const ORS_CANCEL_BY_CUSTOMER = 1;
const ORS_CANCEL_BY_TERCOM = 2;
const ORS_QUEUED = 3;
const ORS_QUOTING = 4;
const ORS_QUOTED = 5;
const ORS_DONE = 6;

const ICON_VIEW = '<i class="fas fa-pencil-alt"></i>';
const ICON_EDIT = '<i class="fas fa-pencil-alt"></i>';
const ICON_ENABLE = '<i class="fas fa-eye"></i>';
const ICON_DISABLE = '<i class="fas fa-eye-slash"></i>';
const ICON_REMOVE = '<i class="fas fa-trash"></i>';
const ICON_PRODUCT = '<i class="fas fa-boxes"></i>';
const ICON_PRODUCT_FAMILY = '<i class="fas fa-chess-king"></i>';
const ICON_PRODUCT_GROUP = '<i class="fas fa-chess-queen"></i>';
const ICON_PRODUCT_SUBGROUP = '<i class="fas fa-chess-knight"></i>';
const ICON_PRODUCT_SECTOR = '<i class="fas fa-chess-rook"></i>';
const ICON_SERVICE = '<i class="fas fa-file-contract"></i>';
const ICON_LINk = '<i class="fas fa-external-link-alt"></i>';
const ICON_PRICES = '<i class="fas fa-dollar-sign"></i>';
const ICON_ADDRESSES = '<i class="fas fa-map-marker-alt"></i>';
const ICON_PROFILES = '<i class="fas fa-users"></i>';
const ICON_PERMISSIONS = '<i class="fas fa-user-lock"></i>';
const ICON_EMPLOYEES = '<i class="fas fa-users-cog"></i>';
const ICON_PHONE = '<i class="fas fa-phone"></i>';
const ICON_OPEN = '<i class="fas fa-sign-in-alt"></i>';
const ICON_CANCEL = '<i class="fas fa-ban"></i>';

const SHOW_CLASS_FORMAT = 'alert alert-{0} col-12 text-center';

const BTN_CLASS_VIEW = 'btn-primary';
const BTN_CLASS_OPEN = 'btn-info';
const BTN_CLASS_REMOVE = 'btn-danger';
const BTN_CLASS_ENABLE = 'btn-success';
const BTN_CLASS_DISABLE = 'btn-secondary';

$(document).ready(function()
{
	initSpoiler();
	initMasks();
	initSelectPicker();
	initDateTimePicker();
	initJQueryValidators();
	initJQueryLoadingOverlay();
	initModalConfirm();
	Dashboard.init();
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
	// FIXME: arrumar getFloat() para que aceite $('.mask-money').mask('#.##0,00', { reverse: true });
	$('.mask-money').mask('99990.00', { reverse: true });
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

function initSelectPicker()
{
	$('.select-picker').selectpicker({
		'liveSearch': true,
		'liveSearchNormalize': true,
		'size': 6,
	});
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
	jQuery.validator.addMethod("length", function (value, element, param)
	{
		return this.optional(element) || value.length == param;
	}, $.validator.format("Por favor inserir {0} caracteres."));

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
			'data': Dashboard.getAuthenticateData(),
			'dataType': API_DATA_TYPE_JSON,
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

function initModalConfirm()
{
	$('#modal-confirm-btn-cancel').click(function()
	{
		if (Util.modalConfirmListener !== undefined && Util.modalConfirmListener !== null)
			if (Util.modalConfirmListener(false, this))
				$('#modal-confirm').modal('hide');
	});
	$('#modal-confirm-btn-proceed').click(function()
	{
		if (Util.modalConfirmListener !== undefined && Util.modalConfirmListener !== null)
			if (Util.modalConfirmListener(true, this))
				$('#modal-confirm').modal('hide');
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
	Util.onApiError();
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

/* OUTROS */

String.prototype.format = function()
{
	var args = arguments;
	return this.replace(/{(\d+)}/g, function(match, number)
	{
		return typeof args[number] != 'undefined' ? args[number] : match;
	});
};

/* MÉTODOS UTILITÁRIOS */

var Util = Util ||
{
	onApiError: function()
	{
		Util.closeConfirmModal();
	},
	getModalConfirm: function()
	{
		return $('#modal-confirm');
	},
	showConfirm: function(label, message, listener)
	{
		$('#modal-confirm-message').html(message);
		$('#modal-confirm-label').html(label);
		$('#modal-confirm').modal('show');
		Util.modalConfirmListener = listener;
	},
	closeConfirmModal: function()
	{
		$('#modal-confirm').modal('hide');
	},
	showMessage: function(type, message, timeout, fade)
	{
		if (timeout === undefined) timeout = DEFAULT_TIMEOUT;
		if (fade === undefined) fade = DEFAULT_FADE;

		var rowMessage = $('#row-message');
		var rowMessageP = $('#row-message p');

		rowMessage.fadeIn(fade);
		rowMessageP.removeClass();
		rowMessageP.addClass(SHOW_CLASS_FORMAT.format(type));
		$('#row-message-span').html(message);

		if (rowMessage.data('timeoutid') !== undefined)
			rowMessage.clearTimeout(rowMessage.data('timeoutid'));

		var tid = setTimeout(function()
		{
			rowMessage.fadeOut(fade);
			rowMessage.removeData('timeout-id');
		}, timeout);

		rowMessage.data('timeout-id', tid);
	},
	showSuccess: function(message, timeout, fade)
	{
		Util.showMessage('success', message, timeout, fade);
	},
	showInfo: function(message, timeout, fade)
	{
		Util.showMessage('info', message, timeout, fade);
	},
	showWarning: function(message, timeout, fade)
	{
		Util.showMessage('warning', message, timeout, fade);
	},
	showError: function(message, timeout, fade)
	{
		Util.showMessage('danger', message, timeout, fade);
	},
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
	hiddenTimeout: function(target, duration)
	{
		target.show(DEFAULT_FADE);

		setTimeout(function() { target.hide(DEFAULT_FADE) }, duration === undefined ? DEFAULT_TIMEOUT : duration);
	},
	showChecked: function(bool)
	{
		return bool === true ? '<i class="fas fa-check-square"></i>' : '<i class="fas fa-square"></i>';
	},
	createElementOption: function(html, value, selected)
	{
		var element = document.createElement('option');
		var option = $(element);
		option.val(value);
		option.html(html);
		option.prop('selected', selected);

		return option;
	},
	formatNull: function(value)
	{
		return value === null || value === undefined ? NO_VALUE : value;
	},
	formatDateTime: function(datetime)
	{
		datetime = Util.formatNull(datetime);

		return datetime.length === 1 ? datetime : datetime.split('#')[0];
	},
	formatOnlyNumbers: function(value)
	{
		return value.replace(PATTERN_ONLY_NUMBERS, '');
	},
	formatCnpj: function(cnpj)
	{
		cnpj = Util.formatNull(cnpj);

		return cnpj.length !== 14 ? cnpj : cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
	},
	formatCep: function(cep)
	{
		cep = Util.formatNull(cep);

		return cep.length !== 8 ? cep : cep.replace(/^(\d{5})(\d{3})/, "$1-$2");
	},
	formatMoney: function(money)
	{
		money = Util.formatNull(money);

		return money === NO_VALUE ? money : money.toFixed(2);
	},
}

var Dashboard = Dashboard ||
{
	init: function()
	{
		Dashboard.initNavbarPermissions();
	},
	initNavbarPermissions: function()
	{
		if (typeof(IS_LOGIN_TERCOM) === 'undefined')
			return;

		Dashboard.navbarPermission = $('#navbar-permission');
		Dashboard.navbarPermission.fadeOut('fast');

		if (IS_LOGIN_TERCOM)
			ws.managePermissions_tercom_getAll(Dashboard.navbarPermission, Dashboard.onParsePermissions);
		else
			ws.managePermissions_customer_getAll(Dashboard.navbarPermission, Dashboard.onParsePermissions);
	},
	onParsePermissions: function(permissions)
	{
		Dashboard.rawPermissions = permissions.elements;
		Dashboard.permissions = {};

		for (var i = 0; i < permissions.elements.length; i++)
		{
			var permission = permissions.elements[i];
			var packet = permission.packet;
			var action = permission.action;

			if (!Dashboard.hasPermissionPacket(packet))
				Dashboard.permissions[packet] = {};

			Dashboard.permissions[packet][action] = true;
		}

		$('.nav-item-packet-js').each(function(index, element)
		{
			if (element.dataset.permissionPacket !== '' && !Dashboard.hasPermissionPacket(element.dataset.permissionPacket))
				$(element).remove();
		});

		$('.nav-item-action-js').each(function(index, element)
		{
			if (element.dataset.permissionPacket !== '' &&
				element.dataset.permissionAction !== '' &&
				!Dashboard.hasPermissionAction(element.dataset.permissionPacket, element.dataset.permissionAction))
				$(element).remove();
		});

		Dashboard.navbarPermission.fadeIn('fast');
	},
	hasPermissionPacket: function(packet)
	{
		return typeof(Dashboard.permissions[packet]) !== 'undefined';
	},
	hasPermissionAction: function(packet, action)
	{
		return Dashboard.hasPermissionPacket(packet) && typeof(Dashboard.permissions[packet][action]) !== 'undefined';
	},
	getAuthenticateData: function()
	{
		var object = {};

		if (typeof(LOGIN_ID) !== 'undefined' &&
			typeof(LOGIN_TOKEN) !== 'undefined' &&
			typeof(LOGIN_EMPLOYEE_ID) !== 'undefined' &&
			typeof(IS_LOGIN_TERCOM) !== 'undefined')
		{
			object.login_id = LOGIN_ID;
			object.login_token = LOGIN_TOKEN;

			if (IS_LOGIN_TERCOM)
				object.login_tercomEmployee = LOGIN_EMPLOYEE_ID;
			else
				object.login_customerEmployee = LOGIN_EMPLOYEE_ID;
		}

		return object;
	}
}
