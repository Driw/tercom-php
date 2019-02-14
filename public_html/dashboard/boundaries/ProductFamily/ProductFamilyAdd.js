
$(document).ready(function()
{
	ProductFamilyAdd.init();
});

var ProductFamilyAdd = ProductFamilyAdd ||
{
	init: function()
	{
		ProductFamilyAdd.form = $('#form-product-family-add');
		ProductFamilyAdd.initForm();
	},
	initForm: function()
	{
		ws.productFamily_settings(ProductFamilyAdd.form, ProductFamilyAdd.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProductFamilyAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minCategoryNameLen, settings.maxCategoryNameLen ],
					'remoteapi': {
						'webservice': 'productFamily/avaiable/name',
					},
				},
				'idProductFamily': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductFamilyAdd.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	submit: function(form)
	{
		ws.productFamily_add(form, ProductFamilyAdd.onSubmited);
	},
	onSubmited: function(productCategory, message)
	{
		message += ' <button class="btn btn-{0} btn-sm" onclick="ProductFamilyAdd.onButtonAdded()">{1} Ver Grupos</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ProductFamilyAdd.form.trigger('reset');
		ProductFamilyAdd.lastAdded = productCategory;
		Util.showSuccess(message);
	},
	onButtonAdded: function()
	{
		Util.redirect('productGroup/list/{0}'.format(ProductFamilyAdd.lastAdded.id));
	},
}
