
$(document).ready(function()
{
		CustomerProfileAdd.init();
});

var CustomerProfileAdd = CustomerProfileAdd ||
{
	init: function()
	{
		CustomerProfileAdd.form = $('#form-customer-profile-add');
		CustomerProfileAdd.idCustomer = $(CustomerProfileAdd.form[0].idCustomer).val();
		CustomerProfileAdd.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.customerProfile_settings(CustomerProfileAdd.form, CustomerProfileAdd.onSettings);
	},
	onSettings: function(settings)
	{
		CustomerProfileAdd.form.validate({
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
					CustomerProfileAdd.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.customerProfile_add(CustomerProfileAdd.form, CustomerProfileAdd.onSubmited);
	},
	onSubmited: function(customerProfile, message)
	{
		CustomerProfileAdd.form.trigger('reset');

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
