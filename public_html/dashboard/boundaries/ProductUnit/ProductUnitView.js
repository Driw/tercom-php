
$(document).ready(function()
{
		ProductUnitView.init();
});

var ProductUnitView = ProductUnitView ||
{
	init: function()
	{
		ProductUnitView.loaded = false;
		ProductUnitView.form = $('#form-product-unit-view');
		ProductUnitView.idProductUnit = $(ProductUnitView.form[0].idProductUnit).val();
		ProductUnitView.initForm();
		ProductUnitView.loadProductUnit();
	},
	initForm: function()
	{
		ws.productUnit_settings(ProductUnitView.form, ProductUnitView.onFormSettings);
	},
	loadProductUnit: function()
	{
		ws.productUnit_get(ProductUnitView.idProductUnit, ProductUnitView.form, ProductUnitView.onProductUnitLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductUnitView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/name/{value}/{idProductUnit}',
						'parameters': {
							'idProductUnit': ProductUnitView.idProductUnit,
						},
					},
				},
				'shortName': {
					'required': true,
					'rangelength': [ settings.minShortNameLen, settings.maxShortNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/shortName/{value}/{idProductUnit}',
						'parameters': {
							'idProductUnit': ProductUnitView.idProductUnit,
						},
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductUnitView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	onProductUnitLoaded: function(productUnit, message)
	{
		var form = ProductUnitView.form[0];
		$(form.name).val(productUnit.name);
		$(form.shortName).val(productUnit.shortName);

		if (ProductUnitView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ProductUnitView.loaded = true;
	},
	submit: function(form)
	{
		ws.productUnit_set(ProductUnitView.idProductUnit, form, ProductUnitView.onSubmited);
	},
	onSubmited: function(productUnit, message)
	{
		ProductUnitView.onProductUnitLoaded(productUnit);
	},
}
