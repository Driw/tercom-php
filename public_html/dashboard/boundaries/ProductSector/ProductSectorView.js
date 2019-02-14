
$(document).ready(function()
{
	ProductSectorView.init();
});

var ProductSectorView = ProductSectorView ||
{
	init: function()
	{
		ProductSectorView.loaded = false;
		ProductSectorView.form = $('#form-product-group-view');
		ProductSectorView.idProductSector = $('#idProductSector').val();
		ProductSectorView.initForm();
		ProductSectorView.loadSector();
	},
	initForm: function()
	{
		ws.productSector_settings(ProductSectorView.form, ProductSectorView.onFormSettings);
	},
	loadSector: function()
	{
		ws.productSector_get(ProductSectorView.idProductSector, ProductSectorView.form, ProductSectorView.onSectorLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductSectorView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minCategoryNameLen, settings.maxCategoryNameLen ],
					'remoteapi': {
						'webservice': 'productSector/avaiable/name/{value}/{idProductSector}',
						'parameters': {
							'idProductSector': ProductSectorView.idProductSector,
						},
					},
				},
				'idProductSector': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductSectorView.submit($(form));
				} catch (e) {
					Util.showErro(e.stack);
				}
				return false;
			},
		});
	},
	submit: function(form)
	{
		ws.productSector_set(ProductSectorView.idProductSector, form, ProductSectorView.onSubmited);
	},
	onSectorLoaded: function(productSector, message)
	{
		var form = ProductSectorView.form[0];
		$(form.name).val(productSector.name);

		if (ProductSectorView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ProductSectorView.loaded = true;
	},
	onSubmited: function(productCategory, message)
	{
		ProductSectorView.onSectorLoaded(productCategory,message);
	},
}
