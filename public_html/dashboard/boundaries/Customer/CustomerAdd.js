
$(document).ready(function()
{
	CustomerAdd.init();
});

var CustomerAdd = CustomerAdd ||
{
	init: function()
	{
		CustomerAdd.form = $('#form-customer-add');
		CustomerAdd.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.customer_settings(CustomerAdd.form, CustomerAdd.onSettings);
	},
	onSettings: function(settings)
	{
		CustomerAdd.form.validate({
			'rules': {
				'stateRegistry': {
					'required': true,
					'maxlength': settings.maxStateRegistryLen,
				},
				'cnpj': {
					'required': true,
					'remoteapi': {
						'webservice': 'customer/avaiable/cnpj',
						'replacePattern': [ /\D/g, '' ],
					},
				},
				'companyName': {
					'required': true,
					'rangelength': [ settings.minCompanyNameLen, settings.maxCompanyNameLen ],
					'remoteapi': {
						'webservice': 'customer/avaiable/companyName',
					},
				},
				'fantasyName': {
					'required': true,
					'rangelength': [ settings.minFantasyNameLen, settings.maxFantasyNameLen ],
				},
				'email': {
					'required': true,
					'maxlength': settings.maxEmailLen,
				},
			},
			submitHandler: function(form) {
				try {
					CustomerAdd.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.customer_add(CustomerAdd.form, CustomerAdd.onSubmited);
	},
	onSubmited: function(customer, message)
	{
		CustomerAdd.form.trigger('reset');

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
