
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
					'remoteapi': {
						'webservice': 'productGroup/avaiable/name',
					},
				},
				'idProductGroup': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductGroupAdd.submit($(form));
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
		ws.productGroup_add(ProductGroupAdd.selectFamily.val(), form, ProductGroupAdd.onSubmited);
	},
	onSubmited: function(productCategory, message)
	{
		ProductGroupAdd.form.trigger('reset');

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
