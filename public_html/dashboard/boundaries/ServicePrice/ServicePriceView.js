
$(document).ready(function()
{
	ServicePriceView.init();
});

var ServicePriceView = ServicePriceView ||
{
	init: function()
	{
		ServicePriceView.loaded = false;
		ServicePriceView.form = $('#form-service-price-view');
		ServicePriceView.id = $('#idServicePrice').val();
		ServicePriceView.initFormSettings();
		ServicePriceView.loadServicePrice();
	},
	initFormSettings: function()
	{
		ws.servicePrice_settings(ServicePriceView.form, ServicePriceView.onSettings);
	},
	loadServicePrice: function()
	{
		ws.servicePrice_get(ServicePriceView.id, ServicePriceView.form, ServicePriceView.onServicePriceLoaded);
	},
	onSettings: function(settings)
	{
		ServicePriceView.form.validate({
			'rules': {
				'idProvider': {
					'required': true,
				},
				'name': {
					'required': true,
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
					ServicePriceView.confirm();
				} catch (e) {
					Util.showError(e);
				}
				return false;
			}
		});
	},
	onServicePriceLoaded: function(servicePrice, message)
	{
		var form = ServicePriceView.form[0];
		$(form.name).val(servicePrice.name);
		$(form.price).val(Util.formatMoney(servicePrice.price));
		$(form.additionalDescription).val(servicePrice.additionalDescription);
		$(form.serviceName).val(servicePrice.service.name);
		$(form.serviceDescription).val(servicePrice.service.description);

		if (ServicePriceView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ServicePriceView.loaded = true;
	},
	confirm: function()
	{
		var message = 'Uma vez que os dados do preço de serviço sejam atualizados não poderão ser revertidos. Deseja continuar?';
		Util.showConfirm('Atualizar dados do preço de serviço', message, ServicePriceView.submit);
	},
	submit: function(confirm)
	{
		if (confirm)
			ws.servicePrice_set(ServicePriceView.id, ServicePriceView.form, ServicePriceView.onSubmited);
		return true;
	},
	onSubmited: function(servicePrice, message)
	{
		ServicePriceView.onServicePriceLoaded(servicePrice,message);
	}
}

















//
