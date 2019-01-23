
$(document).ready(function()
{
		ProductTypeView.init();
});

var ProductTypeView = ProductTypeView ||
{
	init: function()
	{
		this.form = $('#form-product-type-view');
		this.idProductType = $(this.form[0].idProductType).val();
		this.initForm();
		this.loadProductType();
	},
	initForm: function()
	{
		ws.productType_settings(this.form, this.onFormSettings);
	},
	loadProductType: function()
	{
		ws.productType_get(this.idProductType, this.form, this.onProductTypeLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductTypeView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productType/avaiable/name/{value}/{idProductType}',
						'parameters': {
							'idProductType': ProductTypeView.idProductType,
						}
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductTypeView.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	onProductTypeLoaded: function(productType)
	{
		console.log('oi');
		var form = ProductTypeView.form[0];
		$(form.name).val(productType.name);
	},
	submit: function(form)
	{
		ws.productType_set(ProductTypeView.idProductType, form, ProductTypeView.onSubmited);
	},
	onSubmited: function(productType, message)
	{
		ProductTypeView.onProductTypeLoaded(productType);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
