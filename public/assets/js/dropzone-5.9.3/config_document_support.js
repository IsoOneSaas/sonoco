Dropzone.options.uploadForm = {
    autoProcessQueue: false,
	maxFilesize: 16, // MB
	maxFiles: 1,
	acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.xlsm,.ppt,.pps,.pptx,.ppsx,.pub,.one,.dwg,.cad",
    addRemoveLinks: false,
    url: $('#uploadForm').attr('action'),
    init: function () {

        var myDropzone = this;		
		this.removeAllFiles();

        // Update selector to match your button
        $("#btn-support-ok").on('click', function (e) {
            e.preventDefault();
            myDropzone.processQueue();
        });

        $("#btn-support-clear").on('click', function (e) {
            myDropzone.removeAllFiles();
        });		

		this.on("maxfilesexceeded", function(file) {
            this.removeAllFiles();
            this.addFile(file);
      	});		

        this.on('sending', function(file, xhr, formData) {
            // Append all form inputs to the formData Dropzone will POST
            var data = $('#uploadForm').serializeArray();
            $.each(data, function(key, el) {
                formData.append(el.name, el.value);
            });
        });
    },
	success: function(file, response) {
		console.log('*** File uploaded succesfully');
		//file.previewElement.id = response.success;
		//console.log(file); 
		console.log(response);
		if( response.status == 'success' ) {
			// Message
			setSuccessNotification('success', '', response.message);			
			// Limpiar
			// $("input[name='date']").val('');
			// $("#btn-support-clear").trigger("click"); No necesita porque recarga página
			// Reacondicionar			
			setSupportData();			
			// recargar página
			location.reload();
		} else {
			setSuccessNotification('error', 'Oops!', response.message);
		}
	},
	error: function(file, response) {
	   if($.type(response) === "string")
			var message = response; //dropzone sends it's own error messages in string
		else
			var message = response.message;
		console.error('*** File uploading error: '+ message);
		file.previewElement.classList.add("dz-error");
		_ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
		_results = [];
		for (_i = 0, _len = _ref.length; _i < _len; _i++) {
			node = _ref[_i];
			_results.push(node.textContent = message);
		}
		return _results;
	},
	dictDefaultMessage: "Suelte aquí el archivo de soporte del documento",
	dictFallbackMessage: "Su navegador no soporta el arrastre de archivos",
	dictFallbackText: "Utilice el formulario alternativo a continuación para cargar sus archivos como en los viejos tiempos",
	dictFileTooBig: "Archivo es demasiado pesado ({{filesize}}MiB). Máximo peso de archivo: {{maxFilesize}}MiB.",
	dictInvalidFileType: "No es posible cargar archivos de este tipo",
	dictResponseError: "El servidor responde con código de estado: {{statusCode}}",
	dictCancelUpload: "Carga cancelada",
	dictCancelUploadConfirmation: "Está seguro que quiere cancelar la carga?",
	dictRemoveFile: "Remover archivo",
	dictRemoveFileConfirmation: null,
	dictMaxFilesExceeded: "No es posible cargar más archivos",		
}