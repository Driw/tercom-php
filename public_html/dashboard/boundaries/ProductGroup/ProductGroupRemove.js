
$(document).ready(function()
{
	ProductGroupView.init();
});

var ProductGroupView = ProductGroupView ||
{
	init: function()
	{
		ProductGroupView.form = $('#form-product-Group-remove');
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
			submitHandler: function(form) {
				try {
					ProductGroupView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	submit: function(form)
	{
		ws.productGroup_remove(ProductGroupView.idProductGroup, form, ProductGroupView.onSubmited);
	},
	onGroupLoaded: function(productGroup)
	{
		var form = ProductGroupView.form[0];
		$(form.name).val(productGroup.name);
	},
	onSubmited: function(productCategory, message)
	{
		ProductGroupView.form.trigger('reset');
		ProductGroupView.form.fadeOut('fast');
		Util.showSuccess(message);
	},
}
