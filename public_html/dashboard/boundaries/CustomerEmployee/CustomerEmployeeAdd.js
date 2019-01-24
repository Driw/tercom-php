
$(document).ready(function()
{
	CustomerEmployeeAdd.init();
});

var CustomerEmployeeAdd = CustomerEmployeeAdd ||
{
	init: function()
	{
		CustomerEmployeeAdd.form = $('#form-customer-employee-add');
		CustomerEmployeeAdd.selectCustomer = $(CustomerEmployeeAdd.form[0].idCustomer);
		CustomerEmployeeAdd.selectProfiles = $(CustomerEmployeeAdd.form[0].idCustomerProfile);
		CustomerEmployeeAdd.initFormSettings();

		CustomerEmployeeAdd.selectCustomer.change(CustomerEmployeeAdd.loadCustomerProfiles);

		if ($('#idCustomer').val() !== '')
			CustomerEmployeeAdd.selectCustomer.val($('#idCustomer').val());
		else
			CustomerEmployeeAdd.loadCustomers();
	},
	getCustomerId: function()
	{
		var val = CustomerEmployeeAdd.selectCustomer.val();
		return val === '' ? 0 : val;
	},
	initFormSettings: function()
	{
		ws.customerEmployee_settings(CustomerEmployeeAdd.form, CustomerEmployeeAdd.onSettings);
	},
	loadCustomers: function()
	{
		ws.customer_getAll(CustomerEmployeeAdd.selectCustomer, CustomerEmployeeAdd.onLoadCustomers);
	},
	loadCustomerProfiles: function()
	{
		var idCustomer = CustomerEmployeeAdd.getCustomerId();

		if (idCustomer === 0)
			CustomerEmployeeAdd.loadCustomers();
		else
			ws.customerProfile_getByCustomer(idCustomer, CustomerEmployeeAdd.selectProfiles, CustomerEmployeeAdd.onLoadCustomerProfiles);
	},
	onLoadCustomers: function(customers)
	{
		CustomerEmployeeAdd.selectCustomer.empty();
		CustomerEmployeeAdd.selectCustomer.append(Util.createElementOption('Selecione um Cliente', ''));
		CustomerEmployeeAdd.customers = customers.elements;
		CustomerEmployeeAdd.customers.forEach((customer, index) => {
			var option = Util.createElementOption(customer.fantasyName, customer.id);
			CustomerEmployeeAdd.selectCustomer.append(option);
		});
	},
	onLoadCustomerProfiles: function(customerProfiles)
	{
		CustomerEmployeeAdd.selectProfiles.empty();
		CustomerEmployeeAdd.selectProfiles.append(Util.createElementOption('Selecione um Perfil', ''));
		CustomerEmployeeAdd.customerProfiles = customerProfiles.elements;
		CustomerEmployeeAdd.customerProfiles.forEach((customerProfile, index) => {
			var option = Util.createElementOption(customerProfile.name, customerProfile.id);
			CustomerEmployeeAdd.selectProfiles.append(option);
		});
	},
	onSettings: function(settings)
	{
		CustomerEmployeeAdd.form.validate({
			'rules': {
				'idCustomerProfile': {
					'required': true,
				},
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'email': {
					'required': true,
					'maxlength': settings.maxEmailLen,
					'remoteapi': {
						'webservice': 'customerEmployee/avaiable/email',
					},
				},
				'password': {
					'required': true,
					'rangelength': [ settings.minPasswordLen, settings.maxPasswordLen ],
				},
				'repassword': {
					'required': true,
					'equalTo': '#password',
				},
			},
			'messages': {
				'repassword': {
					'equalTo': 'As senhas não conferem',
				},
			},
			submitHandler: function(form) {
				try {
					CustomerEmployeeAdd.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.customerEmployee_add(CustomerEmployeeAdd.form, CustomerEmployeeAdd.onSubmited);
	},
	onSubmited: function(customerEmployee, message)
	{
		CustomerEmployeeAdd.selectProfiles.empty();
		CustomerEmployeeAdd.form.trigger('reset');

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
