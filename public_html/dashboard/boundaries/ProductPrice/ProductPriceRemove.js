
$(document).ready(function()
{
	ProductPriceRemove.init();
});

var ProductPriceRemove = ProductPriceRemove ||
{
	init: function()
	{
		ProductPriceRemove.form = $('#form-product-price');
		ProductPriceRemove.idProductPrice = $(ProductPriceRemove.form[0].idProductPrice).val();
		ProductPriceRemove.initForm();
		ProductPriceRemove.loadProductPrice();
	},
	initForm: function()
	{
		ProductPriceRemove.form.validate({
			submitHandler: function(form) {
				try {
					ProductPriceRemove.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	loadProductPrice: function()
	{
		ws.productPrice_get(ProductPriceRemove.idProductPrice, ProductPriceRemove.form, ProductPriceRemove.onLoadProductPrice);
	},
	submit: function(form)
	{
		ws.productPrice_remove(ProductPriceRemove.idProductPrice, form, ProductPriceRemove.onSubmited)
	},
	onSubmited: function(productPrice, message)
	{
		ProductPriceRemove.form.trigger('reset');
		ProductPriceRemove.form.hide('slow');
		Util.showSuccess(message);
	},
	onLoadProductPrice: function(productPrice)
	{
		ProductPriceRemove.productPrice = productPrice;
		var form = ProductPriceRemove.form[0];
		$(form.product_name).val(productPrice.product.name);
		$(form.product_description).html(productPrice.product.description);
		$(form.provider_name).val(productPrice.provider.fantasyName);
		if (productPrice.manufacturer !== null)
		$(form.manufacturer_name).val(productPrice.manufacturer.fantasyName);
		$(form.price_name).val(productPrice.name);
		$(form.amount).val(productPrice.amount);
		$(form.price).val(Util.formatMoney(productPrice.price));
	},
}
