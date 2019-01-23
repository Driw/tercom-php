
$(document).ready(function()
{
	CustomerProfileView.init();
});

var CustomerProfileView = CustomerProfileView ||
{
	init: function()
	{
		CustomerProfileView.form = $('#form-customer-profile-view');
		CustomerProfileView.id = $(CustomerProfileView.form[0].idCustomerProfile).val();
		CustomerProfileView.idCustomer = $(CustomerProfileView.form[0].idCustomer).val();
		CustomerProfileView.initFormSettings();
		CustomerProfileView.loadCustomerProfile();
	},
	initFormSettings: function()
	{
		ws.customerProfile_settings(CustomerProfileView.form, CustomerProfileView.onSettings);
	},
	loadCustomerProfile: function()
	{
		ws.customerProfile_get(CustomerProfileView.id, CustomerProfileView.form, CustomerProfileView.onCustomerProfileLoaded);
	},
	onSettings: function(settings)
	{
		CustomerProfileView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'assignmentLevel': {
					'required': true,
					'range': [ settings.minAssignmentLevel, settings.maxAssignmentLevel ],
				},
			},
			submitHandler: function(form) {
				try {
					CustomerProfileView.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	onCustomerProfileLoaded: function(customerProfile)
	{
		var form = CustomerProfileView.form[0];
		$(form.name).val(customerProfile.name);
		$(form.assignmentLevel).val(customerProfile.assignmentLevel);
	},
	submit: function(form)
	{
		ws.customerProfile_set(CustomerProfileView.id, CustomerProfileView.form, CustomerProfileView.onSubmited);
	},
	onSubmited: function(customerProfile, message)
	{
		CustomerProfileView.onCustomerProfileLoaded(customerProfile);

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
