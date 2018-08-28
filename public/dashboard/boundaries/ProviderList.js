
$(document).ready(function()
{
	Controller.init();
	Controller.loadProviders();
});

var Controller = Controller ||
{
	init: function()
	{
		this.providers = [];
		this.table = $('#providers-table');
		this.tbody = this.table.find('tbody');
		this.dataTable = newDataTables(this.table);
	},
	loadProviders: function()
	{
		var controller = this;

		$.ajax({
			'type': 'post',
			'url': API_ENDPOINT+ 'provider/list/all',
			'cache': 'false',
			'contentType': 'application/json; charset=utf8',
			'beforeSend': function() {
				controller.tbody.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível obter a lista de fornecedores');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;
				var result = response.result;
				var elements = result.providers.attributes.elements;

				elements.forEach(function(provider, index) {
					var provider = parseAdvancedObject(provider);
					var id = provider.id;
					controller.providers[provider.id] = provider;

					var siteButton = '<button type="button" class="btn btn-info" data-id="' +id+ '" onclick="Controller.onSite(this)">Site</button>';
					var openButton = '<button type="button" class="btn btn-primary" data-id="' +id+ '" onclick="Controller.onSee(this)">Ver</button>';
					var row = controller.dataTable.row.add([
						provider.id,
						provider.cnpj,
						provider.companyName,
						provider.fantasyName,
						provider.spokesman,
						'<div class="btn-group">' +siteButton+openButton+ '</div>',
					]).draw();
				});
			},
			'complete': function() {
				controller.tbody.LoadingOverlay('hide');
			}
		});
	},
	onSite: function(btn)
	{
		var provider = this.providers[btn.dataset.id];
		window.open('http://' +provider.site, '_blank');
	},
	onSee: function(btn)
	{
		var provider = this.providers[btn.dataset.id];
		window.open('provider/view/' +provider.id, '_blank');
	},
}
