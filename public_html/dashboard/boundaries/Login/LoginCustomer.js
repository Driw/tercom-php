
$(document).ready(function()
{
	LoginCustomer.init();
});

var LoginCustomer = LoginCustomer ||
{
	init: function()
	{
		LoginCustomer.form = $('#login-form');
		LoginCustomer.form.validate({
			'rules': {
				'email': {
					'required': true,
				},
				'password': {
					'required': true,
				},
			},
		});
	},
};
