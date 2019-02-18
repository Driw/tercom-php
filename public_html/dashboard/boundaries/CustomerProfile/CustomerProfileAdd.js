
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
		message += ' <button class="btn btn-sm {0}" onclick="CustomerProfileAdd.onButtonLast()">{1} Ver Cliente</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		CustomerProfileAdd.lastAdded = customerProfile;
		CustomerProfileAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('customerProfile/view/{0}/{1}'.format(CustomerProfileAdd.lastAdded.id, CustomerProfileAdd.idCustomer));
	},
}
