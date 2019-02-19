
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
					Util.showError(e.stack);
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
		var idCustomer = CustomerEmployeeAdd.selectCustomer.val();
		message += ' <button class="btn btn-sm {0}" onclick="CustomerEmployeeAdd.onButtonLast({2})">{1} Ver Cliente</button>'.format(BTN_CLASS_VIEW, ICON_VIEW, idCustomer);
		CustomerEmployeeAdd.lastAdded = customerEmployee;
		CustomerEmployeeAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function(idCustomer)
	{
		Util.redirect('customerEmployee/view/{0}/{1}'.format(CustomerEmployeeAdd.lastAdded.id, idCustomer));
	},
}
