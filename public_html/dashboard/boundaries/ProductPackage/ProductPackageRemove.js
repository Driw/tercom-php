
$(document).ready(function()
{
        ProductPackageRemove.init();
});

var ProductPackageRemove = ProductPackageRemove ||
{
    init: function()
    {
		this.form = $('#form-product-package-remove');
		this.idProductPackage = $(this.form[0].idProductPackage).val();
		this.initForm();
		this.loadProductPackage();
    },
	initForm: function()
	{
		ProductPackageRemove.form.validate({
			submitHandler: function(form) {
				try {
					ProductPackageRemove.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	loadProductPackage: function()
	{
		ws.productPackage_get(this.idProductPackage, this.form, this.onProductPackageLoaded);
	},
	onProductPackageLoaded: function(productPackage)
	{
		var form = ProductPackageRemove.form[0];
		$(form.name).val(productPackage.name);
	},
    submit: function(form)
	{
		ws.productPackage_remove(ProductPackageRemove.idProductPackage, form, ProductPackageRemove.onSubmited);
    },
	onSubmited: function(productPackage, message)
	{
		ProductPackageRemove.onProductPackageLoaded(productPackage);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
