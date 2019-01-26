
$(document).ready(function()
{
	TercomEmployeeView.init();
});

var TercomEmployeeView = TercomEmployeeView ||
{
	init: function()
	{
		TercomEmployeeView.form = $('#form-tercom-employee-view');
		TercomEmployeeView.selectProfiles = $(this.form[0].idTercomProfile);
		TercomEmployeeView.id = $(this.form[0].idTercomEmployee).val();
		TercomEmployeeView.initFormSettings();
		TercomEmployeeView.loadProfiles();
		TercomEmployeeView.loadTercomEmployee();
	},
	initFormSettings: function()
	{
		ws.tercomEmployee_settings(TercomEmployeeView.form, TercomEmployeeView.onSettings);
	},
	loadProfiles: function()
	{
		ws.tercomProfile_getAll(TercomEmployeeView.form, TercomEmployeeView.onLoadProfiles);
	},
	loadTercomEmployee: function()
	{
		ws.tercomEmployee_get(TercomEmployeeView.id, TercomEmployeeView.form, this.onLoadTercomEmployee);
	},
	onSettings: function(settings)
	{
		TercomEmployeeView.form.validate({
			'rules': {
				'cpf': {
					'required': true,
					'remoteapi': {
						'webservice': 'tercomEmployee/avaiable/cpf/{value}/{idTercomEmployee}',
						'replacePattern': [ /\D/g, '' ],
						'parameters': {
							'idTercomEmployee': function() { return TercomEmployeeView.id; },
						},
					},
				},
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'password': {
					'required': false,
					'rangelength': [ settings.minPasswordLen, settings.maxPasswordLen ],
				},
				'repassword': {
					'required': false,
					'equalTo': '#password',
				},
				'email': {
					'required': true,
					'maxlength': settings.maxEmailLen,
					'remoteapi': {
						'webservice': 'tercomEmployee/avaiable/email/{value}/{idTercomEmployee}',
						'parameters': {
							'idTercomEmployee': function() { return TercomEmployeeView.id; },
						},
					},
				},
			},
			'messages': {
				'repassword': {
					'equalTo': 'As senhas não conferem',
				},
			},
			submitHandler: function(form) {
				try {
					TercomEmployeeView.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	onLoadTercomEmployee: function(tercomEmployee)
	{
		var form = TercomEmployeeView.form[0];
		$(form.cpf).val(tercomEmployee.cpf).trigger('input');
		$(form.name).val(tercomEmployee.name);
		$(form.email).val(tercomEmployee.email);
		TercomEmployeeView.tercomEmployee = tercomEmployee;
		TercomEmployeeView.selectProfiles.val(tercomEmployee.tercomProfile.id);
	},
	onLoadProfiles: function(tercomProfiles)
	{
		TercomEmployeeView.selectProfiles.empty();
		TercomEmployeeView.selectProfiles.append(Util.createElementOption('Selecione um Perfil', ''));
		TercomEmployeeView.tercomProfiles = tercomProfiles.elements;
		TercomEmployeeView.tercomProfiles.forEach((tercomProfile, index) => {
			var option = Util.createElementOption(tercomProfile.name, tercomProfile.id);
			TercomEmployeeView.selectProfiles.append(option);
		});

		if (TercomEmployeeView.tercomEmployee !== undefined)
			TercomEmployeeView.selectProfiles.val(TercomEmployeeView.tercomEmployee.tercomProfile.id);
	},
	submit: function(form)
	{
		ws.tercomEmployee_set(TercomEmployeeView.id, TercomEmployeeView.form, TercomEmployeeView.onSubmited);
	},
	onSubmited: function(tercomEmployee, message)
	{
		TercomEmployeeView.onLoadTercomEmployee(tercomEmployee);

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
