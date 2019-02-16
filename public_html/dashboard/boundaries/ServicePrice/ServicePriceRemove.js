
$(document).ready(function()
{
		ServicePriceRemove.init();
});

var ServicePriceRemove = ServicePriceRemove ||
{
	init: function()
	{
		ServicePriceRemove.form = $('#form-service-price-remove');
		ServicePriceRemove.id = $('#idServicePrice').val();
		ServicePriceRemove.initFormSettings();
		ServicePriceRemove.loadServicePrice();
	},
	initFormSettings: function()
	{
		ServicePriceRemove.form.validate({
			submitHandler: function(form) {
				try {
					ServicePriceRemove.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	loadServicePrice: function()
	{
		ws.servicePrice_get(ServicePriceRemove.id, ServicePriceRemove.form, ServicePriceRemove.onServicePriceLoaded);
	},
	onServicePriceLoaded: function(servicePrice)
	{
		var form = ServicePriceRemove.form[0];
		$(form.name).val(servicePrice.name);
		$(form.price).val(servicePrice.price);
		$(form.serviceName).val(servicePrice.service.name);
	},
	submit: function(form)
	{
		ws.servicePrice_remove(ServicePriceRemove.id, ServicePriceRemove.form, ServicePriceRemove.onSubmited);
	},
	onSubmited: function(servicePrice, message)
	{
		ServicePriceRemove.form.trigger('reset');
		ServicePriceRemove.form.hide('slow');
		Util.showSuccess(message);
	}
}

















//
