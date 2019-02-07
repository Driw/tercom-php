
$(document).ready(function()
{
	ProductFamilyView.init();
});

var ProductFamilyView = ProductFamilyView ||
{
	init: function()
	{
		ProductFamilyView.form = $('#form-product-family-view');
		ProductFamilyView.idProductFamily = $('#idProductFamily').val();
		ProductFamilyView.initForm();
		ProductFamilyView.loadFamily();
	},
	initForm: function()
	{
		ws.productFamily_settings(ProductFamilyView.form, ProductFamilyView.onFormSettings);
	},
	loadFamily: function()
	{
		ws.productFamily_get(ProductFamilyView.idProductFamily, ProductFamilyView.form, ProductFamilyView.onFamilyLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductFamilyView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minCategoryNameLen, settings.maxCategoryNameLen ],
					'remoteapi': {
						'webservice': 'productFamily/avaiable/name/{value}/{idProductFamily}',
						'parameters': {
							'idProductFamily': ProductFamilyView.idProductFamily,
						},
					},
				},
				'idProductFamily': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductFamilyView.submit($(form));
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
		ws.productFamily_set(ProductFamilyView.idProductFamily, form, ProductFamilyView.onSubmited);
	},
	onFamilyLoaded: function(productFamily)
	{
		var form = ProductFamilyView.form[0];
		$(form.name).val(productFamily.name);
	},
	onSubmited: function(productCategory, message)
	{
		ProductFamilyView.onFamilyLoaded(productCategory);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
