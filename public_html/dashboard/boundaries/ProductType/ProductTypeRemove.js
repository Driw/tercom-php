
$(document).ready(function()
{
	ProductTypeRemove.init();
});

var ProductTypeRemove = ProductTypeRemove ||
{
	init: function()
	{
		ProductTypeRemove.form = $('#form-product-type-remove');
		ProductTypeRemove.idProductType = $(ProductTypeRemove.form[0].idProductType).val();
		ProductTypeRemove.initForm();
		ProductTypeRemove.loadProductType();
	},
	initForm: function()
	{
		ProductTypeRemove.form.validate({
			submitHandler: function(form) {
				try {
					ProductTypeRemove.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	loadProductType: function()
	{
		ws.productType_get(ProductTypeRemove.idProductType, ProductTypeRemove.form, ProductTypeRemove.onProductTypeLoaded);
	},
	onProductTypeLoaded: function(productType)
	{
		var form = ProductTypeRemove.form[0];
		$(form.name).val(productType.name);
	},
	submit: function(form)
	{
		ws.productType_remove(ProductTypeRemove.idProductType, form, ProductTypeRemove.onSubmited);
	},
	onSubmited: function(productType, message)
	{
		ProductTypeRemove.form.trigger('reset');
		ProductTypeRemove.form.fadeOut('fast');
		Util.showSuccess(message);
	},
}
