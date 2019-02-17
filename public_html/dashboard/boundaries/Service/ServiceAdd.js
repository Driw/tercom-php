
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
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.service_add(form, ServiceAdd.onSubmited);
	},
	onSubmited: function(service, message)
	{
		$(ServiceAdd.form[0].tags).tagsinput('removeAll');

		message += ' <button class="btn btn-sm {0}" onclick="ServiceAdd.onButtonLast()">{1} Ver Serviço</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ServiceAdd.lastAdded = service;
		ServiceAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('service/view/{0}'.format(ServiceAdd.lastAdded.id));
	}
}

















//
