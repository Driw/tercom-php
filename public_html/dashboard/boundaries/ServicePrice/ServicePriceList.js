
$(document).ready(function()
{
	ServicePriceList.init();
});

var ServicePriceList = ServicePriceList ||
{
	init: function()
	{
		ServicePriceList.table = $('#table-service-prices');
		ServicePriceList.idService = $('#idService').val();
		ServicePriceList.tbody = ServicePriceList.table.find('tbody');
		ServicePriceList.datatables = newDataTables(ServicePriceList.table);
		ServicePriceList.loadServicePrices();
	},
	loadServicePrices: function()
	{
		ws.servicePrice_getAll(ServicePriceList.idService, ServicePriceList.tbody, ServicePriceList.onServicePriceLoaded);
	},
	onServicePriceLoaded: function(servicePrices)
	{
		ServicePriceList.servicePrices = servicePrices.elements;
		ServicePriceList.servicePrices.forEach((servicePrice, index) =>
		{
			var servicePriceRowData = ServicePriceList.newServicePriceRowData(index, servicePrice);
			ServicePriceList.datatables.row.add(servicePriceRowData).draw();
		});
	},
	newServicePriceRowData: function(index, servicePrice)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ServicePriceList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Preço de Serviço', ICON_VIEW);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Preço de Serviço', ICON_REMOVE);

		return [
			servicePrice.id,
			servicePrice.name,
			servicePrice.provider.fantasyName,
			servicePrice.service.description,
			Util.formatMoney(servicePrice.price),
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var servicePrice = ServicePriceList.servicePrices[index];
		Util.redirect('service/viewPrice/{0}'.format(servicePrice.id));
	},
	onButtonRemove: function(index)
	{
		var servicePrice = ServicePriceList.servicePrices[index];
		Util.redirect('service/removePrice/{0}'.format(servicePrice.id));
	},
	onButtonAdd: function()
	{
		Util.redirect('service/addPrice/{0}'.format(ServicePriceList.idService), true);
	},
}
