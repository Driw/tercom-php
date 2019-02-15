
$(document).ready(function()
{
		ProductUnitAdd.init();
});

var ProductUnitAdd = ProductUnitAdd ||
{
	init: function()
	{
		ProductUnitAdd.form = $('#form-product-unit-add');
		ProductUnitAdd.initForm();
	},
	initForm: function()
	{
		ws.productUnit_settings(ProductUnitAdd.form, ProductUnitAdd.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProductUnitAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/name',
					},
				},
				'shortName': {
					'required': true,
					'rangelength': [ settings.minShortNameLen, settings.maxShortNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/shortName',
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductUnitAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	submit: function()
	{
		ws.productUnit_add(ProductUnitAdd.form, ProductUnitAdd.onSubmited);
	},
	onSubmited: function(productUnit, message)
	{
		message += ' <button class="btn btn-sm {0}" onclick="ProductUnitAdd.onButtonLast()">{1} Ver Unidade de Produto</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ProductUnitAdd.lastAdded = productUnit;
		ProductUnitAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('productUnit/view/{0}'.format(ProductUnitAdd.lastAdded.id));
	},
}
