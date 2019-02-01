
$(document).ready(function()
{
		ServiceView.init();
});

var ServiceView = ServiceView ||
{
	init: function()
	{
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
					console.log(e);
				}
				return false;
			}
		});
	},
	onServiceLoaded: function(service)
	{
		var form = ServiceView.form[0];
		$(form.id).val(service.id);
		$(form.name).val(service.name);
		$(form.description).val(service.description);
		service.tags.elements.forEach(element => {
			$(form.tags).tagsinput('add', element);
		});
	},
	submit: function(form)
	{
		ws.service_set(ServiceView.id, ServiceView.form, ServiceView.onSubmited);
	},
	onSubmited: function(service, message)
	{
		ServiceView.onServiceLoaded(service);

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
