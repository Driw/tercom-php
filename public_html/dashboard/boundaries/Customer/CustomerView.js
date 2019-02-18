
$(document).ready(function()
{
		CustomerView.init();
});

var CustomerView = CustomerView ||
{
	init: function()
	{
		CustomerView.loaded = false;
		CustomerView.form = $('#form-customer-view');
		CustomerView.id = $(CustomerView.form[0].idCustomer).val();
		CustomerView.initFormSettings();
		CustomerView.loadCustomer();
	},
	initFormSettings: function()
	{
		ws.customer_settings(CustomerView.form, CustomerView.onSettings);
	},
	loadCustomer: function()
	{
		ws.customer_get(CustomerView.id, CustomerView.form, CustomerView.onCustomerLoaded);
	},
	onSettings: function(settings)
	{
		CustomerView.form.validate({
			'rules': {
				'stateRegistry': {
					'required': true,
					'maxlength': settings.maxStateRegistryLen,
				},
				'cnpj': {
					'required': true,
					'remoteapi': {
						'webservice': 'customer/avaiable/cnpj/{value}/{idCustomer}',
						'replacePattern': [ /\D/g, '' ],
						'parameters' : {
							'idCustomer': function() { return CustomerView.id; },
						},
					},
				},
				'companyName': {
					'required': true,
					'rangelength': [ settings.minCompanyNameLen, settings.maxCompanyNameLen ],
					'remoteapi': {
						'webservice': 'customer/avaiable/companyName/{value}/{idCustomer}',
						'parameters' : {
							'idCustomer': function() { return CustomerView.id; },
						},
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
					CustomerView.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	onCustomerLoaded: function(customer, message)
	{
		var form = CustomerView.form[0];
		$(form.id).val(customer.id);
		$(form.stateRegistry).val(customer.stateRegistry);
		$(form.cnpj).val(customer.cnpj).trigger('input');
		$(form.companyName).val(customer.companyName);
		$(form.fantasyName).val(customer.fantasyName);
		$(form.email).val(customer.email);

		if (CustomerView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		CustomerView.loaded = true;
	},
	submit: function(form)
	{
		ws.customer_set(CustomerView.id, CustomerView.form, CustomerView.onSubmited);
	},
	onSubmited: function(customer, message)
	{
		CustomerView.onCustomerLoaded(customer, message);
	}
}
