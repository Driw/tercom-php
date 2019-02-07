
$(document).ready(function()
{
	ProductGroupView.init();
});

var ProductGroupView = ProductGroupView ||
{
	init: function()
	{
		ProductGroupView.form = $('#form-product-group-view');
		ProductGroupView.idProductGroup = $('#idProductGroup').val();
		ProductGroupView.initForm();
		ProductGroupView.loadGroup();
	},
	initForm: function()
	{
		ws.productGroup_settings(ProductGroupView.form, ProductGroupView.onFormSettings);
	},
	loadGroup: function()
	{
		ws.productGroup_get(ProductGroupView.idProductGroup, ProductGroupView.form, ProductGroupView.onGroupLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductGroupView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minCategoryNameLen, settings.maxCategoryNameLen ],
					'remoteapi': {
						'webservice': 'productGroup/avaiable/name/{value}/{idProductGroup}',
						'parameters': {
							'idProductGroup': ProductGroupView.idProductGroup,
						},
					},
				},
				'idProductGroup': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductGroupView.submit($(form));
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
		ws.productGroup_set(ProductGroupView.idProductGroup, form, ProductGroupView.onSubmited);
	},
	onGroupLoaded: function(productGroup)
	{
		var form = ProductGroupView.form[0];
		$(form.name).val(productGroup.name);
	},
	onSubmited: function(productCategory, message)
	{
		ProductGroupView.onGroupLoaded(productCategory);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
