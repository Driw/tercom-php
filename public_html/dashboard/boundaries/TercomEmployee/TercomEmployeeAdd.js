
$(document).ready(function()
{
		TercomEmployeeAdd.init();
});

var TercomEmployeeAdd = TercomEmployeeAdd ||
{
	init: function()
	{
		TercomEmployeeAdd.form = $('#form-tercom-employee-add');
		TercomEmployeeAdd.selectProfiles = $(TercomEmployeeAdd.form[0].idTercomProfile);
		TercomEmployeeAdd.initFormSettings();
		TercomEmployeeAdd.loadProfiles();
	},
	initFormSettings: function()
	{
		ws.tercomEmployee_settings(TercomEmployeeAdd.form, TercomEmployeeAdd.onSettings);
	},
	loadProfiles: function()
	{
		ws.tercomProfile_getAll(TercomEmployeeAdd.form, TercomEmployeeAdd.onLoadProfiles);
	},
	onSettings: function(settings)
	{
		TercomEmployeeAdd.form.validate({
			'rules': {
				'cpf': {
					'required': true,
					'remoteapi': {
						'webservice': 'tercomEmployee/avaiable/cpf',
						'replacePattern': [ /\D/g, '' ],
					},
				},
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'password': {
					'required': true,
					'rangelength': [ settings.minPasswordLen, settings.maxPasswordLen ],
				},
				'repassword': {
					'required': true,
					'equalTo': 'password',
				},
				'email': {
					'required': true,
					'maxlength': settings.maxEmailLen,
					'remoteapi': {
						'webservice': 'tercomEmployee/avaiable/email'
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
					TercomEmployeeAdd.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			},
		});
	},
	onLoadProfiles: function(tercomProfiles)
	{
		TercomEmployeeAdd.selectProfiles.empty();
		TercomEmployeeAdd.selectProfiles.append(Util.createElementOption('Selecione um Perfil', ''));
		TercomEmployeeAdd.tercomProfiles = tercomProfiles.elements;
		TercomEmployeeAdd.tercomProfiles.forEach((tercomProfile, index) => {
			var option = Util.createElementOption(tercomProfile.name, tercomProfile.id);
			TercomEmployeeAdd.selectProfiles.append(option);
		});
	},
	submit: function(form)
	{
		ws.tercomEmployee_add(form, TercomEmployeeAdd.onSubmited);
	},
	onSubmited: function(tercomEmployee, message)
	{
		TercomEmployeeAdd.form.trigger('reset');

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
