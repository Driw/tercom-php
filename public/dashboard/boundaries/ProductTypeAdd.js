
$(document).ready(function()
{
        ProductTypeAdd.init();
});

var ProductTypeAdd = ProductTypeAdd ||
{
    init: function()
    {
        this.form = $('#form-product-type-add');
		this.initForm();
    },
	initForm: function()
	{
		ws.productType_settings(this.form, this.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProductTypeAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productType/avaiable/name',
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductTypeAdd.submit($(form));
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
		ws.productType_add(ProductTypeAdd.form, ProductTypeAdd.onSubmited);
    },
	onSubmited: function(manufacturer, message)
	{
		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		ProductTypeAdd.form.trigger('reset');

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
