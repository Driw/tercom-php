
$(document).ready(function()
{
	ProductSubgroupView.init();
});

var ProductSubgroupView = ProductSubgroupView ||
{
	init: function()
	{
		ProductSubgroupView.form = $('#form-product-group-view');
		ProductSubgroupView.idProductSubgroup = $('#idProductSubgroup').val();
		ProductSubgroupView.initForm();
		ProductSubgroupView.loadSubgroup();
	},
	initForm: function()
	{
		ws.productSubgroup_settings(ProductSubgroupView.form, ProductSubgroupView.onFormSettings);
	},
	loadSubgroup: function()
	{
		ws.productSubgroup_get(ProductSubgroupView.idProductSubgroup, ProductSubgroupView.form, ProductSubgroupView.onSubgroupLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductSubgroupView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minCategoryNameLen, settings.maxCategoryNameLen ],
					'remoteapi': {
						'webservice': 'productSubgroup/avaiable/name/{value}/{idProductSubgroup}',
						'parameters': {
							'idProductSubgroup': ProductSubgroupView.idProductSubgroup,
						},
					},
				},
				'idProductSubgroup': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductSubgroupView.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	submit: function(form)
	{
		ws.productSubgroup_set(ProductSubgroupView.idProductSubgroup, form, ProductSubgroupView.onSubmited);
	},
	onSubgroupLoaded: function(productSubgroup)
	{
		var form = ProductSubgroupView.form[0];
		$(form.name).val(productSubgroup.name);
	},
	onSubmited: function(productCategory, message)
	{
		ProductSubgroupView.onSubgroupLoaded(productCategory);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
