
$(document).ready(function()
{
	ProductTypeView.init();
});

var ProductTypeView = ProductTypeView ||
{
	init: function()
	{
		ProductTypeView.loaded = false;
		ProductTypeView.form = $('#form-product-type-view');
		ProductTypeView.idProductType = $(ProductTypeView.form[0].idProductType).val();
		ProductTypeView.initForm();
		ProductTypeView.loadProductType();
	},
	initForm: function()
	{
		ws.productType_settings(ProductTypeView.form, ProductTypeView.onFormSettings);
	},
	loadProductType: function()
	{
		ws.productType_get(ProductTypeView.idProductType, ProductTypeView.form, ProductTypeView.onProductTypeLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductTypeView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productType/avaiable/name/{value}/{idProductType}',
						'parameters': {
							'idProductType': ProductTypeView.idProductType,
						}
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductTypeView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	onProductTypeLoaded: function(productType, message)
	{
		var form = ProductTypeView.form[0];
		$(form.name).val(productType.name);

		if (ProductTypeView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ProductTypeView.loaded = true;
	},
	submit: function(form)
	{
		ws.productType_set(ProductTypeView.idProductType, form, ProductTypeView.onSubmited);
	},
	onSubmited: function(productType, message)
	{
		ProductTypeView.onProductTypeLoaded(productType);
	},
}
