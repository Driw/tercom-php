
$(document).ready(function()
{
		ProductPackageView.init();
});

var ProductPackageView = ProductPackageView ||
{
	init: function()
	{
		this.form = $('#form-product-package-view');
		this.idProductPackage = $(this.form[0].idProductPackage).val();
		this.initForm();
		this.loadProductPackage();
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
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	onProductPackageLoaded: function(productPackage)
	{
		console.log('oi');
		var form = ProductPackageView.form[0];
		$(form.name).val(productPackage.name);
	},
	submit: function(form)
	{
		ws.productPackage_set(ProductPackageView.idProductPackage, form, ProductPackageView.onSubmited);
	},
	onSubmited: function(productPackage, message)
	{
		ProductPackageView.onProductPackageLoaded(productPackage);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
