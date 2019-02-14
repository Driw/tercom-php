
$(document).ready(function()
{
	ProductSubgroupAdd.init();
});

var ProductSubgroupAdd = ProductSubgroupAdd ||
{
	init: function()
	{
		ProductSubgroupAdd.form = $('#form-product-subgroup-add');
		ProductSubgroupAdd.selectGroup = $(ProductSubgroupAdd.form[0].idProductGroup);
		ProductSubgroupAdd.initForm();
		ProductSubgroupAdd.loadProductFamilies();
	},
	initForm: function()
	{
		ws.productSubgroup_settings(ProductSubgroupAdd.form, ProductSubgroupAdd.onFormSettings);
	},
	loadProductFamilies: function()
	{
		ws.productGroup_getAll(ProductSubgroupAdd.selectGroup, ProductSubgroupAdd.onProductFamiliesLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductSubgroupAdd.form.validate({
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
					ProductSubgroupAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	onProductFamiliesLoaded: function(productFamilies)
	{
		ProductSubgroupAdd.selectGroup.selectpicker();

		var option = Util.createElementOption('Selecione um Grupo', '');
		ProductSubgroupAdd.selectGroup.append(option);

		ProductSubgroupAdd.productFamilies = productFamilies.elements;
		ProductSubgroupAdd.productFamilies.forEach(productGroup =>
		{
			var option = Util.createElementOption(productGroup.name, productGroup.id, ProductSubgroupAdd.selectGroup.val() == productGroup.id);
			ProductSubgroupAdd.selectGroup.append(option);
		});
		ProductSubgroupAdd.selectGroup.selectpicker('refresh');
	},
	submit: function(form)
	{
		ws.productSubgroup_add(form, ProductSubgroupAdd.onSubmited);
	},
	onSubmited: function(productCategory, message)
	{
		message += ' <button class="btn btn-sm {0}" onclick="ProductSubgroupAdd.onButtonLast()">{1} Ver Setores</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ProductSubgroupAdd.lastAdded = productCategory;
		ProductSubgroupAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('productSector/list/{0}'.format(ProductSubgroupAdd.lastAdded.id));
	},
}
