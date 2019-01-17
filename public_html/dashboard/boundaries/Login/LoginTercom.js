
$(document).ready(function()
{
	LoginTercom.init();
});

var LoginTercom = LoginTercom ||
{
	init: function()
	{
		LoginTercom.form = $('#login-form');
		LoginTercom.form.validate({
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
