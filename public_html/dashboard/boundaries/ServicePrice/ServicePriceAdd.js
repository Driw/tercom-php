
$(document).ready(function()
{
		ServicePriceAdd.init();
});

var ServicePriceAdd = ServicePriceAdd ||
{
	init: function()
	{
		ServicePriceAdd.form = $('#form-service-price-add');
		ServicePriceAdd.idService = $(ServicePriceAdd.form[0].idService).val();
		ServicePriceAdd.selectProvider = $(ServicePriceAdd.form[0].idProvider);
		ServicePriceAdd.initFormSettings();
		ServicePriceAdd.loadService();
		ServicePriceAdd.loadProviders();
	},
	initFormSettings: function()
	{
		ws.servicePrice_settings(ServicePriceAdd.form, ServicePriceAdd.onSettings);
	},
	loadService: function()
	{
		ws.service_get(ServicePriceAdd.idService, ServicePriceAdd.form, ServicePriceAdd.onServiceLoaded);
	},
	loadProviders: function()
	{
		ws.provider_getAll(ServicePriceAdd.selectProvider, ServicePriceAdd.onLoadProviders);
	},
	onSettings: function(settings)
	{
		ServicePriceAdd.form.validate({
			'rules': {
				'idProvider': {
					'required': true,
				},
				'name': {
					'required': false,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'additionalDescription': {
					'required': false,
					'maxlength': settings.maxAdditionalDescriptionLen,
				},
				'price': {
					'required': true,
					'minlength': settings.minPriceLen,
				},
			},
			submitHandler: function(form) {
				try {
					ServicePriceAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.servicePrice_add(form, ServicePriceAdd.onSubmited);
	},
	onServiceLoaded: function(service)
	{
		var form = ServicePriceAdd.form[0];
		$(form.serviceName).val(service.name);
		$(form.serviceDescription).html(service.description);
		$(form.name).val(service.name);
	},
	onLoadProviders: function(providers)
	{
		ServicePriceAdd.selectProvider.empty();
		ServicePriceAdd.selectProvider.append(Util.createElementOption('selecione um fornecedor', ''));

		ServicePriceAdd.providers = providers.elements;
		ServicePriceAdd.providers.forEach(function(provider)
		{
			var option = Util.createElementOption(provider.fantasyName, provider.id);
			ServicePriceAdd.selectProvider.append(option);
		});
		ServicePriceAdd.selectProvider.selectpicker('refresh');
	},
	onSubmited: function(servicePrice, message)
	{
		message += ' <button class="btn btn-sm {0}" onclick="ServicePriceAdd.onButtonLast()">{1} Ver Preço de Serviço</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ServicePriceAdd.lastAdded = servicePrice;
		ServicePriceAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('service/viewPrice/{0}'.format(ServicePriceAdd.lastAdded.id));
	},
}

















//
