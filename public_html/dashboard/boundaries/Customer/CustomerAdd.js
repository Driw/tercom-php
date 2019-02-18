
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
					Util.show(e.stack);
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
		message += ' <button class="btn btn-sm {0}" onclick="CustomerAdd.onButtonLast()">{1} Ver Cliente</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		CustomerAdd.lastAdded = customer;
		CustomerAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('customer/view/{0}'.format(CustomerAdd.lastAdded.id));
	},
}
