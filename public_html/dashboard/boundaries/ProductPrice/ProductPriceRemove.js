
$(document).ready(function()
{
	ProductPriceRemove.init();
});

var ProductPriceRemove = ProductPriceRemove ||
{
	init: function()
	{
		this.form = $('#form-product-price');
		this.idProductPrice = $(this.form[0].idProductPrice).val();
		this.initForm();
		this.loadProductPrice();
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
		ws.productPrice_get(this.idProductPrice, this.form, this.onLoadProductPrice);
	},
	submit: function(form)
	{
		ws.productPrice_remove(ProductPriceRemove.idProductPrice, form, ProductPriceRemove.onSubmited)
	},
	onSubmited: function(productPrice, message)
	{
		ProductPriceRemove.form.hide('slow');

		$('#row-message').show('slow');
		$('#row-message-span').html(message);
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
