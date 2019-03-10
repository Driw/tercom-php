
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
				'idServiceCustomer': {
					'required': true,
					'remoteapi': {
						'webservice': 'service/avaiable/idServiceCustomer/{value}/{idService}',
						'parameters': {
							'idService': function() { return ServiceView.id; },
						},
					},
				},
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
					ServiceView.confirm();
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
		$(form.idServiceCustomer).val(service.idServiceCustomer);
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
	confirm: function()
	{
		var message = 'Uma vez que os dados do serviço sejam atualizados não poderão ser revertidos. Deseja continuar?';
		Util.showConfirm('Atualizar dados do serviço', message, ServiceView.submit);
	},
	submit: function(confirm)
	{
		if (confirm)
			ws.service_set(ServiceView.id, ServiceView.form, ServiceView.onSubmited);
		return true;
	},
	onSubmited: function(service, message)
	{
		ServiceView.onServiceLoaded(service);
	},
	onButtonPrices: function()
	{
		Util.redirect('service/viewPrices/{0}'.format(ServiceView.id));
	},
}
