
$(document).ready(function()
{
	Controller.init();
	Controller.form.submit(function() {
		try { return Controller.searchProvider(); } catch (e) { alert(e.message); }
		return false;
	});
});

var Controller = Controller ||
{
	init: function()
	{
		this.providers = [];
		this.form = $('#form-search');
		this.table = $('#providers-table');
		this.tbody = this.table.find('tbody');
		this.dataTable = newDataTables(this.table);
	},
	searchProvider: function()
	{
		var controller = this;
		var filter = this.form[0].filter.value;
		var value = this.form[0].value.value;

		$.ajax({
			'type': 'post',
			'url': API_ENDPOINT+ 'provider/search/' +filter+ '/' +value,
			'cache': 'false',
			'contentType': 'application/json; charset=utf8',
			'beforeSend': function() {
				controller.tbody.LoadingOverlay('show');
				var rows = controller.dataTable.rows().remove();
			},
			'error': function() {
				showModalApiError('não foi possível realizar a busca por fornecedores');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;
				var providers = parseAdvancedObject(response.result);
				var elements = providers.elements;

				elements.forEach(function(provider, index) {
					var provider = parseAdvancedObject(provider);
					var id = provider.id;
					controller.providers[provider.id] = provider;

					var siteButton = '<button type="button" class="btn btn-info btn-site" data-id="' +id+ '">Site</button>';
					var openButton = '<button type="button" class="btn btn-primary btn-see" data-id="' +id+ '">Ver</button>';
					var row = controller.dataTable.row.add([
						provider.id,
						provider.cnpj,
						provider.companyName,
						provider.fantasyName,
						provider.spokesman,
						'<div class="btn-group">' +siteButton+openButton+ '</div>',
					]).draw();
				});

				$('.btn-site').click(function() {
					var provider = controller.providers[this.dataset.id];
					window.open('http://' +provider.site, '_blank');
				});

				$('.btn-see').click(function() {
					var provider = controller.providers[this.dataset.id];
					window.open('provider/view/' +provider.id, '_blank');
				});
			},
			'complete': function() {
				controller.tbody.LoadingOverlay('hide');
			}
		});

		return false;
	},
}
