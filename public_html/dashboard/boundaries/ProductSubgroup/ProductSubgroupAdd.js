
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
					'remoteapi': {
						'webservice': 'productSubgroup/avaiable/name',
					},
				},
				'idProductGroup': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductSubgroupAdd.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
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
		ProductSubgroupAdd.form.trigger('reset');

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
