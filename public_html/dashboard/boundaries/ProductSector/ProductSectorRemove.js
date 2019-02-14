
$(document).ready(function()
{
	ProductSectorView.init();
});

var ProductSectorView = ProductSectorView ||
{
	init: function()
	{
		ProductSectorView.form = $('#form-product-sector-remove');
		ProductSectorView.idProductSector = $('#idProductSector').val();
		ProductSectorView.initForm();
		ProductSectorView.loadSector();
	},
	initForm: function()
	{
		ws.productSector_settings(ProductSectorView.form, ProductSectorView.onFormSettings);
	},
	loadSector: function()
	{
		ws.productSector_get(ProductSectorView.idProductSector, ProductSectorView.form, ProductSectorView.onSectorLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductSectorView.form.validate({
			submitHandler: function(form) {
				try {
					ProductSectorView.submit($(form));
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
		ws.productSector_remove(ProductSectorView.idProductSector, form, ProductSectorView.onSubmited);
	},
	onSectorLoaded: function(productSector)
	{
		var form = ProductSectorView.form[0];
		$(form.name).val(productSector.name);
	},
	onSubmited: function(productCategory, message)
	{
		ProductSectorView.form.trigger('reset');
		ProductSectorView.form.fadeOut('fast');
		Util.showSuccess(message);
	},
}
