
$(document).ready(function()
{
	ProductFamilyView.init();
});

var ProductFamilyView = ProductFamilyView ||
{
	init: function()
	{
		ProductFamilyView.form = $('#form-product-family-remove');
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
		ws.productFamily_remove(ProductFamilyView.idProductFamily, form, ProductFamilyView.onSubmited);
	},
	onFamilyLoaded: function(productFamily)
	{
		var form = ProductFamilyView.form[0];
		$(form.name).val(productFamily.name);
	},
	onSubmited: function(productCategory, message)
	{
		ProductFamilyView.form.trigger('reset');
		ProductFamilyView.form.fadeOut('fast');

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
