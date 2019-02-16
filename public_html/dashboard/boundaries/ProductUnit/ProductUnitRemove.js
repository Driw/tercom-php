
$(document).ready(function()
{
		ProductUnitRemove.init();
});

var ProductUnitRemove = ProductUnitRemove ||
{
	init: function()
	{
		ProductUnitRemove.form = $('#form-product-unit-remove');
		ProductUnitRemove.idProductUnit = $(ProductUnitRemove.form[0].idProductUnit).val();
		ProductUnitRemove.initForm();
		ProductUnitRemove.loadProductUnit();
	},
	initForm: function()
	{
		ProductUnitRemove.form.validate({
			submitHandler: function(form) {
				try {
					ProductUnitRemove.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	loadProductUnit: function()
	{
		ws.productUnit_get(ProductUnitRemove.idProductUnit, ProductUnitRemove.form, ProductUnitRemove.onProductUnitLoaded);
	},
	onProductUnitLoaded: function(productUnit)
	{
		var form = ProductUnitRemove.form[0];
		$(form.name).val(productUnit.name);
	},
	submit: function(form)
	{
		ws.productUnit_remove(ProductUnitRemove.idProductUnit, form, ProductUnitRemove.onSubmited);
	},
	onSubmited: function(productUnit, message)
	{
		ProductUnitRemove.form.trigger('reset');
		ProductUnitRemove.form.hide('slow');
		Util.showSuccess(message);
	},
}
