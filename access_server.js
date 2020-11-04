
function accessServer(controller){
	$.ajax( {
      url:controller.url,
        processData: false,
        contentType: false,
        cache: false,
        enctype: 'multipart/form-data',
        data: (controller.data)?controller.data:"",
      method:controller.method,
      success:function(response) {
          controller.callBackFunction(response);
      },
      error: function(error) {
          if(controller.error) {
              
              controller.error(error);
          }
      }
    });
}