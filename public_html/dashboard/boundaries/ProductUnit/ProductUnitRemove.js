
$(document).ready(function()
{
        ProductUnitRemove.init();
});

var ProductUnitRemove = ProductUnitRemove ||
{
    init: function()
    {
		this.form = $('#form-product-unit-remove');
		this.idProductUnit = $(this.form[0].idProductUnit).val();
		this.initForm();
		this.loadProductUnit();
    },
	initForm: function()
	{
		ProductUnitRemove.form.validate({
			submitHandler: function(form) {
				try {
					ProductUnitRemove.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	loadProductUnit: function()
	{
		ws.productUnit_get(this.idProductUnit, this.form, this.onProductUnitLoaded);
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
		ProductUnitRemove.onProductUnitLoaded(productUnit);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
