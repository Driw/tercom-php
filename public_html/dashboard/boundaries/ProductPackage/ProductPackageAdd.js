
$(document).ready(function()
{
	ProductPackageAdd.init();
});

var ProductPackageAdd = ProductPackageAdd ||
{
	init: function()
	{
		this.form = $('#form-product-package-add');
		this.initForm();
	},
	initForm: function()
	{
		ws.productPackage_settings(this.form, this.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProductPackageAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productPackage/avaiable/name',
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductPackageAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	submit: function()
	{
		ws.productPackage_add(ProductPackageAdd.form, ProductPackageAdd.onSubmited);
	},
	onSubmited: function(productPackage, message)
	{
		message += ' <button class="btn btn-sm {0}" onclick="ProductPackageAdd.onButtonLast()">{1} Ver Embalgem de Produto</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ProductPackageAdd.lastAdded = productPackage;
		ProductPackageAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('productPackage/view/{0}'.format(ProductPackageAdd.lastAdded.id));
	}
}
