
$(document).ready(function()
{
	Controller.init();
	Controller.loadProvider();

	$('#controller-save').click(function() { Controller.saveProvider(); });
	$('#controller-reload').click(function() { Controller.loadProvider(); });
});

var Controller = Controller ||
{
	init: function()
	{
		this.form = $('#form-view');
		this.idProvider = this.form[0].id.value;
		this.provider = {};
	},
	setProvider: function(provider)
	{
		$('.mask-cnpj').val(provider.cnpj).trigger('input');
		this.idProvider = provider.id;
		this.form[0].companyName.value = provider.companyName;
		this.form[0].fantasyName.value = provider.fantasyName;
		this.form[0].spokesman.value = provider.spokesman;
		this.form[0].site.value = provider.site;
	},
	loadProvider: function()
	{
		var controller = this;

		$.ajax({
			'url': API_ENDPOINT+ 'provider/get/' +controller.idProvider,
			'type': 'POST',
			'dataType': 'json',
			'contentType': 'application/json; charset=utf8',
			'beforeSend': function() {
				controller.form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível obter os dados do fornecedor (id: ' +controller.idProvider+ ')');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
				var provider = controller.provider = parseAdvancedObject(response.result);
				controller.setProvider(provider);
			},
			'complete': function() {
				controller.form.LoadingOverlay('hide');
			}
		});
	},
	saveProvider: function()
	{
		var controller = this;
		var formData = this.form.serialize();

		$.ajax({
			'url': API_ENDPOINT+ 'provider/set/' +controller.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'beforeSend': function() {
				controller.form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível atualizar os dados do fornecedor (id: ' +controller.idProvider+ ')');
			},
			'success': function(response) {
				console.log(response);
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
				var provider = controller.provider = parseAdvancedObject(response.result);
				controller.setProvider(provider);
			},
			'complete': function() {
				controller.form.LoadingOverlay('hide');
			}
		});
	}
}
