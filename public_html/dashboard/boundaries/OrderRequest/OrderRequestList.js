
$(document).ready(function()
{
	OrderRequestList.init();
});

var OrderRequestList = OrderRequestList ||
{
	init: function()
	{
		OrderRequestList.table = $('#table-order-requests');
		OrderRequestList.tbody = OrderRequestList.table.children('tbody');
		OrderRequestList.datatables = newDataTables(OrderRequestList.table);
		OrderRequestList.loadOrderRequests();
	},
	loadOrderRequests: function()
	{
		ws.orderRequest_getAll(OrderRequestList.tbody, OrderRequestList.onLoadOrderRequests);
	},
	onLoadOrderRequests: function(orderRequests)
	{
		OrderRequestList.orderRequests = orderRequests.elements;
		OrderRequestList.orderRequests.forEach((orderRequest, index) =>
		{
			var orderRequestRowData = OrderRequestList.newOrderRequestRowData(index, orderRequest);
			OrderRequestList.datatables.row.add(orderRequestRowData).draw();
		});
	},
	newOrderRequestRowData: function(index, orderRequest)
	{
		var disabledEdit = orderRequest.status === ORS_CANCEL_BY_CUSTOMER || orderRequest.status === ORS_CANCEL_BY_TERCOM ? 'disabled="disabled"' : '';
		var disabledCancel = orderRequest.status === ORS_CANCEL_BY_CUSTOMER || orderRequest.status === ORS_CANCEL_BY_TERCOM || orderRequest.status === ORS_DONE ? 'disabled="disabled"' : '';
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="OrderRequestList.{1}({2}, this)" title="{3}"{5}>{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Dados da Solicitação', ICON_VIEW, disabledEdit);
		var btnCancel = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonCancel', index, 'Cancelar Solicitação', ICON_CANCEL, disabledCancel);

		return [
			orderRequest.id,
			orderRequest.customerEmployee.name,
			orderRequest.tercomEmployee !== null ? orderRequest.tercomEmployee.name : '-',
			Util.formatMoney(orderRequest.budget),
			orderRequest.statusDescription,
			orderRequest.register.date,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnCancel),
		];
	},
	onButtonView: function(index)
	{
		var orderRequest = OrderRequestList.orderRequests[index];
		Util.redirect('orderRequest/view/{0}'.format(orderRequest.id));
	},
	onButtonCancel: function(index, button)
	{
		var orderRequest = OrderRequestList.orderRequests[index];
		var label = 'Confirmar - Cancelamento do Pedido #{0}'.format(orderRequest.id);
		var message =	'Uma vez que o seu pedido tenha sido cancelado terá a cotação interrompida e cancelada enquanto o pedido não poderá mais ser descancelado. '+
						'Tem certeza que deseja cancelar o pedido?';
		OrderRequestList.orderRequestCancel = orderRequest;
		OrderRequestList.orderRequestCancelTr = $(button).parents('tr');
		OrderRequestList.orderRequestCancelIndex = index;
		Util.showConfirm(label, message, OrderRequestList.cancelOrderRequest);
	},
	cancelOrderRequest: function(confirmed, button)
	{
		if (!confirmed)
			return true;

		ws.orderRequest_cancelByCustomer(OrderRequestList.orderRequestCancel.id, Util.getModalConfirm(), OrderRequestList.onOrderRequestCanceled);
		return false;
	},
	onOrderRequestCanceled: function(orderRequest, message)
	{
		var index = OrderRequestList.orderRequestCancelIndex;
		var tr = OrderRequestList.orderRequestCancelTr;
		OrderRequestList.orderRequests[index] = orderRequest;

		var orderRequestRowData = OrderRequestList.newOrderRequestRowData(index, orderRequest);
		OrderRequestList.datatables.row(tr).data(orderRequestRowData).draw();

		Util.closeConfirmModal();
		Util.showSuccess(message);
	},
}
