
const API_SUCCESS = 1;

$(document).ready(function()
{
	initSpoiler();
	initDateTimePicker();
	initJQueryValidators();
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

function initDateTimePicker()
{
	var autoClose = 1;
	var weekStart = 0;
	var todayBtn = 1;
	var todayHighlight = 1;
	var forceParse = 0;
	var fontAwesome = 1;

	$('#date-timer-picker').datetimepicker({
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
	$('#date-timer-picker').mask("00-00-0000 00:00:00", {placeholder: "0000-00-00 00:00:00"});

	$('#date-picker').datetimepicker({
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
	$('#date-picker').mask("00-00-0000", {placeholder: "0000-00-00"});

	$('#time-picker').datetimepicker({
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
	$('#time-picker').mask("00:00:00", {placeholder: "00:00:00"});
}

function initJQueryValidators()
{
	$.validator.addMethod('remoteapi', function(value, element, params)
	{
		return $.validator.methods.remote.call(this, value, element, {
			url: params+ '/' +value,
			type: 'post',
			data: {},
			dataType: 'json',
			dataFilter: function(response)
			{
				var jsonResponse = JSON.parse(response);

				if (jsonResponse.status == 0)
				{
					$.validator.messages.remote = jsonResponse.message;
					return false;
				}

				if (jsonResponse.result.message != 'undefined' && jsonResponse.result.message != null)
					$.validator.messages.remote = jsonResponse.result.message;

				if (jsonResponse.result.ok != 'undefined')
					return jsonResponse.result.ok == 1;

				return jsonResponse.status == 1;
			}
		});
	}, function() {
		return $.validator.messages.remote;
	});
}

function callApiFailure(data)
{

}
