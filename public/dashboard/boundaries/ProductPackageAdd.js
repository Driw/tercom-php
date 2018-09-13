
$(document).ready(function()
{
    ProductPackageAdd.init();
});

var ProductPackageAdd = ProductPackageAdd ||
{
    init: function()
    {
        this.form = $('#form-product-package-add');
		this.initForm();
    },
	initForm: function()
	{
		ws.productPackage_settings(this.form, this.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProductPackageAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productPackage/avaiable/name',
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductPackageAdd.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
    submit: function()
	{
		ws.productPackage_add(ProductPackageAdd.form, ProductPackageAdd.onSubmited);
    },
	onSubmited: function(manufacturer, message)
	{
		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		ProductPackageAdd.form.trigger('reset');

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
