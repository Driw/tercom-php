
$(document).ready(function()
{
        ProductUnitView.init();
});

var ProductUnitView = ProductUnitView ||
{
    init: function()
    {
		this.form = $('#form-product-unit-view');
		this.idProductUnit = $(this.form[0].idProductUnit).val();
		this.initForm();
		this.loadProductUnit();
    },
	initForm: function()
	{
		ws.productUnit_settings(this.form, this.onFormSettings);
	},
	loadProductUnit: function()
	{
		ws.productUnit_get(this.idProductUnit, this.form, this.onProductUnitLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductUnitView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/name/{value}/{idProductUnit}',
						'parameters': {
							'idProductUnit': ProductUnitView.idProductUnit,
						},
					},
				},
				'shortName': {
					'required': true,
					'rangelength': [ settings.minShortNameLen, settings.maxShortNameLen ],
					'remoteapi': {
						'webservice': 'productUnit/avaiable/shortName/{value}/{idProductUnit}',
						'parameters': {
							'idProductUnit': ProductUnitView.idProductUnit,
						},
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductUnitView.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	onProductUnitLoaded: function(productUnit)
	{
		var form = ProductUnitView.form[0];
		$(form.name).val(productUnit.name);
		$(form.shortName).val(productUnit.shortName);
	},
    submit: function(form)
	{
		ws.productUnit_set(ProductUnitView.idProductUnit, form, ProductUnitView.onSubmited);
    },
	onSubmited: function(productUnit, message)
	{
		ProductUnitView.onProductUnitLoaded(productUnit);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
