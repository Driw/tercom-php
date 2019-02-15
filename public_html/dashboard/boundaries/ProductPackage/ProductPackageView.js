
$(document).ready(function()
{
		ProductPackageView.init();
});

var ProductPackageView = ProductPackageView ||
{
	init: function()
	{
		ProductPackageView.loaded = false;
		ProductPackageView.form = $('#form-product-package-view');
		ProductPackageView.idProductPackage = $(ProductPackageView.form[0].idProductPackage).val();
		ProductPackageView.initForm();
		ProductPackageView.loadProductPackage();
	},
	initForm: function()
	{
		ws.productPackage_settings(this.form, this.onFormSettings);
	},
	loadProductPackage: function()
	{
		ws.productPackage_get(this.idProductPackage, this.form, this.onProductPackageLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductPackageView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productPackage/avaiable/name/{value}/{idProductPackage}',
						'parameters': {
							'idProductPackage': ProductPackageView.idProductPackage,
						}
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductPackageView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	onProductPackageLoaded: function(productPackage, message)
	{
		var form = ProductPackageView.form[0];
		$(form.name).val(productPackage.name);

		if (ProductPackageView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ProductPackageView.loaded = true;
	},
	submit: function(form)
	{
		ws.productPackage_set(ProductPackageView.idProductPackage, form, ProductPackageView.onSubmited);
	},
	onSubmited: function(productPackage, message)
	{
		ProductPackageView.onProductPackageLoaded(productPackage, message);
	},
}
