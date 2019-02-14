
$(document).ready(function()
{
	ProductGroupAdd.init();
});

var ProductGroupAdd = ProductGroupAdd ||
{
	init: function()
	{
		ProductGroupAdd.form = $('#form-product-Group-add');
		ProductGroupAdd.selectFamily = $(ProductGroupAdd.form[0].idProductFamily);
		ProductGroupAdd.initForm();
		ProductGroupAdd.loadProductFamilies();
	},
	initForm: function()
	{
		ws.productGroup_settings(ProductGroupAdd.form, ProductGroupAdd.onFormSettings);
	},
	loadProductFamilies: function()
	{
		ws.productFamily_getAll(ProductGroupAdd.selectFamily, ProductGroupAdd.onProductFamiliesLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductGroupAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minCategoryNameLen, settings.maxCategoryNameLen ],
				},
				'idProductGroup': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductGroupAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	onProductFamiliesLoaded: function(productFamilies)
	{
		ProductGroupAdd.selectFamily.selectpicker();
		ProductGroupAdd.productFamilies = productFamilies.elements;
		ProductGroupAdd.productFamilies.forEach(productFamily =>
		{
			var option = Util.createElementOption(productFamily.name, productFamily.id, ProductGroupAdd.selectFamily.val() == productFamily.id);
			ProductGroupAdd.selectFamily.append(option);
		});
		ProductGroupAdd.selectFamily.selectpicker('refresh');
	},
	submit: function(form)
	{
		ws.productGroup_add(form, ProductGroupAdd.onSubmited);
	},
	onSubmited: function(productCategory, message)
	{
		message += ' <button class="btn btn-sm btn-info" onclick="ProductGroupAdd.onButtonLast()">{0} Ver Subgrupos</button>'.format(ICON_PRODUCT_SUBGROUP);
		ProductGroupAdd.lastAdded = productCategory;
		ProductGroupAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('productSubgroup/list/{0}'.format(ProductGroupAdd.lastAdded.id));
	},
}
