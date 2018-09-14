
$(document).ready(function()
{
        ProductUnitAdd.init();
});

var ProductUnitAdd = ProductUnitAdd ||
{
    init: function()
    {
        this.form = $('#form-product-unit-add');
		this.initForm();
    },
	initForm: function()
	{
		ws.productUnit_settings(this.form, this.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProductUnitAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/name',
					},
				},
				'shortName': {
					'required': true,
					'rangelength': [ settings.minShortNameLen, settings.maxShortNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/shortName',
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductUnitAdd.submit($(form));
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
		ws.productUnit_add(ProductUnitAdd.form, ProductUnitAdd.onSubmited);
    },
	onSubmited: function(productUnit, message)
	{
		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		ProductUnitAdd.form.trigger('reset');

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
