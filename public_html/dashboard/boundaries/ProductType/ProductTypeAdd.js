
$(document).ready(function()
{
	ProductTypeAdd.init();
});

var ProductTypeAdd = ProductTypeAdd ||
{
	init: function()
	{
		ProductTypeAdd.form = $('#form-product-type-add');
		ProductTypeAdd.initForm();
	},
	initForm: function()
	{
		ws.productType_settings(ProductTypeAdd.form, ProductTypeAdd.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProductTypeAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productType/avaiable/name',
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductTypeAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	submit: function()
	{
		ws.productType_add(ProductTypeAdd.form, ProductTypeAdd.onSubmited);
	},
	onSubmited: function(productType, message)
	{
		message += ' <button class="btn btn-sm {0}" onclick="ProductTypeAdd.onButtonLast()">{1} Ver Tipo de Produto</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ProductTypeAdd.lastAdded = productType;
		ProductTypeAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('productType/view/{0}'.format(ProductTypeAdd.lastAdded.id));
	},
}
