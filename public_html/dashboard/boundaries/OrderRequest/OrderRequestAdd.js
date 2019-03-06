
$(document).ready(function()
{
	OrderRequestAdd.init();
});

var OrderRequestAdd = OrderRequestAdd ||
{
	init: function()
	{
		OrderRequestAdd.form = $('#form-order-request-add');
		OrderRequestAdd.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.orderRequest_settings(OrderRequestAdd.form, OrderRequestAdd.onSettings);
	},
	onSettings: function(settings)
	{
		OrderRequestAdd.form.validate({
			'rules': {
				'expiration': {
					'required': true,
				},
				'budget': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					OrderRequestAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.orderRequest_add(OrderRequestAdd.form, OrderRequestAdd.onSubmited);
	},
	onSubmited: function(orderRequest, message)
	{
		message += ' <button class="btn btn-sm {0}" onclick="OrderRequestAdd.onButtonLast()">{1} Ver Solicitação de Cotação</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		OrderRequestAdd.lastAdded = orderRequest;
		OrderRequestAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('orderRequest/view/{0}'.format(OrderRequestAdd.lastAdded.id));
	},
}
