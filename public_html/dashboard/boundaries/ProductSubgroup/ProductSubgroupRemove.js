
$(document).ready(function()
{
	ProductSubgroupView.init();
});

var ProductSubgroupView = ProductSubgroupView ||
{
	init: function()
	{
		ProductSubgroupView.form = $('#form-product-Subgroup-remove');
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
			submitHandler: function(form) {
				try {
					ProductSubgroupView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	submit: function(form)
	{
		ws.productSubgroup_remove(ProductSubgroupView.idProductSubgroup, form, ProductSubgroupView.onSubmited);
	},
	onSubgroupLoaded: function(productSubgroup)
	{
		var form = ProductSubgroupView.form[0];
		$(form.name).val(productSubgroup.name);
	},
	onSubmited: function(productCategory, message)
	{
		ProductSubgroupView.form.trigger('reset');
		ProductSubgroupView.form.fadeOut('fast');
		Util.showSuccess(message);
	},
}
