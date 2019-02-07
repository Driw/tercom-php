
$(document).ready(function()
{
	ProductSectorAdd.init();
});

var ProductSectorAdd = ProductSectorAdd ||
{
	init: function()
	{
		ProductSectorAdd.form = $('#form-product-Sector-add');
		ProductSectorAdd.selectSubgroup = $(ProductSectorAdd.form[0].idProductSubGroup);
		ProductSectorAdd.initForm();
		ProductSectorAdd.loadProductSubgroups();
	},
	initForm: function()
	{
		ws.productSector_settings(ProductSectorAdd.form, ProductSectorAdd.onFormSettings);
	},
	loadProductSubgroups: function()
	{
		ws.productSubgroup_getAll(ProductSectorAdd.selectSubgroup, ProductSectorAdd.onProductSubgroupsLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductSectorAdd.form.validate({
			'rules': {
				'idProductSector': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductSectorAdd.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	onProductSubgroupsLoaded: function(productSubgroups)
	{
		ProductSectorAdd.selectSubgroup.selectpicker();
		ProductSectorAdd.productSubgroups = productSubgroups.elements;
		ProductSectorAdd.productSubgroups.forEach(productSubgroup =>
		{
			var option = Util.createElementOption(productSubgroup.name, productSubgroup.id, ProductSectorAdd.selectSubgroup.val() == productSubgroup.id);
			ProductSectorAdd.selectSubgroup.append(option);
		});
		ProductSectorAdd.selectSubgroup.selectpicker('refresh');
	},
	submit: function(form)
	{
		ws.productSector_add(form, ProductSectorAdd.onSubmited);
	},
	onSubmited: function(productCategory, message)
	{
		ProductSectorAdd.form.trigger('reset');

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
