$(document).ready(
    function()
    {
        Controller.init();  
        
        $('#controller-add').click(function(){Controller.addManufacturer()});
    }
);
var Controller = Controller || 
{
    init: function()
    {
        this.form = $('#form-view');
    },
    addManufacturer: function()
	{
		var controller = this;
		var formData = this.form.serialize();

		$.ajax({
			'url': API_ENDPOINT+ 'manufacture/add',
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'beforeSend': function() {
				controller.form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível adicionar o fabricante');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
			},
			'complete': function() {
				controller.form.LoadingOverlay('hide');
			}
		});
    }
}