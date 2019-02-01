
$(document).ready(function()
{
	ServiceSearch.init();
});

var ServiceSearch = ServiceSearch ||
{
	init: function()
	{
		this.services = [];
		this.form = $('#form-search');
		this.table = $('#table-services');
		this.tbody = this.table.find('tbody');
		this.datatables = newDataTables(this.table);
		this.initFormtSettings();
	},
	initFormtSettings: function()
	{
		this.form.validate({
			'rules': {
				'filter': {
					'required': true,
				},
				'value': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				ServiceSearch.onSearch($(form));
				return false;
			},
		});
	},
	onSearch: function()
	{
		var form = ServiceSearch.form[0];
		var value = $(form.value).val();
		var filter = $(form.filter).val();

		ws.service_search(filter, value, ServiceSearch.form, ServiceSearch.searchServices);
	},
	searchServices: function(services)
	{
		ServiceSearch.datatables.rows().remove();
		ServiceSearch.services = services.elements;
		ServiceSearch.services.forEach(function(service, index)
		{
			var viewButton = '<button type="button" class="btn btn-info" data-index="' +index+ '" onclick="ServiceSearch.onButtonSee(this)">Ver</button>';
			var pricesButton = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ServiceSearch.onButtonPrices(this)">Preços</button>';
			var row = ServiceSearch.datatables.row.add([
				service.id,
				service.name,
				service.description,
				service.tags,
				'<div class="btn-group">' + viewButton + pricesButton + '</div>',
			]).draw();
		});
	},
	onButtonSee: function(button)
	{
		var index = button.dataset.index;
		var service = ServiceSearch.services[index];

		if (service !== undefined)
			Util.redirect('service/view/' + service.id, true);
	},
	onButtonPrices: function(button)
	{
		var index = button.dataset.index;
		var service = ServiceSearch.services[index];

		if (service !== undefined)
			Util.redirect('servicePrices/view/' + service.id, true);
	}
}
