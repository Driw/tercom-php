
$(document).ready(function()
{
		ServiceAdd.init();
});

var ServiceAdd = ServiceAdd ||
{
	init: function()
	{
		ServiceAdd.form = $('#form-service-add');
		ServiceAdd.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.service_settings(ServiceAdd.form, ServiceAdd.onSettings);
	},
	onSettings: function(settings)
	{
		ServiceAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'description': {
					'required': true,
					'maxlength': settings.maxDescriptionLen,
				},
			},
			submitHandler: function(form) {
				try {
					ServiceAdd.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.service_add(ServiceAdd.form, ServiceAdd.onSubmited);
	},
	onSubmited: function(service, message)
	{
		ServiceAdd.form.trigger('reset');
		$(ServiceAdd.form[0].tags).tagsinput('removeAll');

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}

















//
