Dropzone.options.uploadForm = {
    autoProcessQueue: false,
	maxFilesize: 8, // MB
	maxFiles: 1,
	acceptedFiles: ".jpeg,.jpg,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pps,.pptx,.ppsx,.pub,.one,.dwg,.cad",
    addRemoveLinks: true,
    url: $('#uploadForm').attr('action'),
    init: function () {

        var myDropzone = this;		
		this.removeAllFiles();

        // Update selector to match your button
        $("#btn-suggestion-ok").on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (myDropzone.files.length) {
                console.log('With File');
                myDropzone.processQueue(); // upload files and submit the form
            } else {
                console.log('Without File');
                $('#uploadForm').submit(); // submit the form
            }
        });

        $("#btn-suggestion-clear").on('click', function (e) {
			$('#uploadForm')[0].reset();
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
		console.log('*** File uploaded succesfully*');
        //console.dir(response);
		if( response.status == 'success' ) {
			// Message
			setSuccessNotification('success', '', response.message);
            // Cerrar Modal
            $("#btn-suggestion-ko")[0].click();
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