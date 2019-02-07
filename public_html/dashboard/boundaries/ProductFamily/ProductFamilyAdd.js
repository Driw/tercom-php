
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
		ProductFamilyAdd.form.trigger('reset');

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
