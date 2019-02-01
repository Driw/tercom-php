
$(document).ready(function()
{
		ServicePriceView.init();
});

var ServicePriceView = ServicePriceView ||
{
	init: function()
	{
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
					'required': true,
					'maxlength': settings.maxAdditionalDescriptionLen,
				},
				'price': {
					'required': true,
					'minlength': settings.minPriceLen,
				},
			},
			submitHandler: function(form) {
				try {
					ServicePriceView.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	onServicePriceLoaded: function(servicePrice)
	{
		var form = ServicePriceView.form[0];
		$(form.name).val(servicePrice.name);
		$(form.price).val(servicePrice.price);
		$(form.additionalDescription).val(servicePrice.additionalDescription);
		$(form.serviceName).val(servicePrice.service.name);
		$(form.serviceDescription).val(servicePrice.service.description);
	},
	submit: function(form)
	{
		ws.servicePrice_set(ServicePriceView.id, ServicePriceView.form, ServicePriceView.onSubmited);
	},
	onSubmited: function(servicePrice, message)
	{
		ServicePriceView.onServicePriceLoaded(servicePrice);

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}

















//
