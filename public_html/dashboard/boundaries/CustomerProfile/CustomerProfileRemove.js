
$(document).ready(function()
{
	CustomerProfileView.init();
});

var CustomerProfileView = CustomerProfileView ||
{
	init: function()
	{
		CustomerProfileView.form = $('#form-customer-profile-remove');
		CustomerProfileView.id = $(CustomerProfileView.form[0].idCustomerProfile).val();
		CustomerProfileView.idCustomer = $(CustomerProfileView.form[0].idCustomer).val();
		CustomerProfileView.initFormSettings();
		CustomerProfileView.loadCustomerProfile();
	},
	initFormSettings: function()
	{
		CustomerProfileView.form.validate({
			submitHandler: function(form) {
				try {
					CustomerProfileView.submit($(form));
				} catch (e) {
					Util.showError(e);
				}
				return false;
			}
		});
	},
	loadCustomerProfile: function()
	{
		ws.customerProfile_get(CustomerProfileView.id, CustomerProfileView.form, CustomerProfileView.onCustomerProfileLoaded);
	},
	onCustomerProfileLoaded: function(customerProfile)
	{
		var form = CustomerProfileView.form[0];
		$(form.name).val(customerProfile.name);
	},
	submit: function(form)
	{
		ws.customerProfile_remove(CustomerProfileView.id, form, CustomerProfileView.onSubmited);
	},
	onSubmited: function(customerProfile, message)
	{
		CustomerProfileView.form.trigger('reset');
		CustomerProfileView.form.hide('slow');
		Util.showSuccess(message);
	}
}
