
$(document).ready(function()
{
		ServiceView.init();
});

var ServiceView = ServiceView ||
{
	init: function()
	{
		ServiceView.loaded = false;
		ServiceView.form = $('#form-service-view');
		ServiceView.id = $(ServiceView.form[0].idService).val();
		ServiceView.initFormSettings();
		ServiceView.loadService();
	},
	initFormSettings: function()
	{
		ws.service_settings(ServiceView.form, ServiceView.onSettings);
	},
	loadService: function()
	{
		ws.service_get(ServiceView.id, ServiceView.form, ServiceView.onServiceLoaded);
	},
	onSettings: function(settings)
	{
		ServiceView.form.validate({
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
					ServiceView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	onServiceLoaded: function(service, message)
	{
		var form = ServiceView.form[0];
		$(form.id).val(service.id);
		$(form.name).val(service.name);
		$(form.description).val(service.description);
		service.tags.elements.forEach(element => {
			$(form.tags).tagsinput('add', element);
		});

		if (ServiceView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ServiceView.loaded = true;
	},
	submit: function(form)
	{
		ws.service_set(ServiceView.id, ServiceView.form, ServiceView.onSubmited);
	},
	onSubmited: function(service, message)
	{
		ServiceView.onServiceLoaded(service);
	}
}
