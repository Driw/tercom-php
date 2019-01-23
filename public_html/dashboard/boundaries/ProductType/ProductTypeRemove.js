
$(document).ready(function()
{
		ProductTypeRemove.init();
});

var ProductTypeRemove = ProductTypeRemove ||
{
	init: function()
	{
		this.form = $('#form-product-type-remove');
		this.idProductType = $(this.form[0].idProductType).val();
		this.initForm();
		this.loadProductType();
	},
	initForm: function()
	{
		ProductTypeRemove.form.validate({
			submitHandler: function(form) {
				try {
					ProductTypeRemove.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	loadProductType: function()
	{
		ws.productType_get(this.idProductType, this.form, this.onProductTypeLoaded);
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
		ProductTypeRemove.onProductTypeLoaded(productType);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
