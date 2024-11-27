Dropzone.options.uploadForm = {
    autoProcessQueue: false,
	maxFilesize: 8, // MB
	maxFiles: 1,
	acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsm,.xlsx,.ppt,.pps,.pptx,.ppsx,.pub,.one,.dwg,.cad",
    addRemoveLinks: false,
    url: $('#upload-form').attr('action'),
    init: function () {

        var myDropzone = this;		
		this.removeAllFiles();

		// for Dropzone to process the queue (instead of default form behavior):
		$("#btn-attachments-ok").on('click', function (e) {
			// Make sure that the form isn't actually being sent.
			e.preventDefault();
			e.stopPropagation();
			var nameValue = document.getElementById("file-name").value;
			document.getElementById('modal-attachments-message').innerHTML="";
			if (nameValue === '') document.getElementById('modal-attachments-message').innerHTML="<font color=red size=3 face='Verdana'> &#8673; Nombre para identificar el anexo faltante!</font>";			
			else myDropzone.processQueue();
		});		

        // Update selector to match your button
        // $("#btn-attachments-ok").on('click', function (e) {
        //     e.preventDefault();
        //     myDropzone.processQueue();
        // });

        $("#btn-attachments-clear").on('click', function (e) {
			document.getElementById('modal-attachments-message').innerHTML="";
            myDropzone.removeAllFiles();
        });		

		this.on("maxfilesexceeded", function(file) {
            this.removeAllFiles();
            this.addFile(file);
      	});		

        this.on('sending', function(file, xhr, formData) {
            // Append all form inputs to the formData Dropzone will POST
            var data = $('#upload-form').serializeArray();
            $.each(data, function(key, el) {
                formData.append(el.name, el.value);
            });
        });
    },
	success: function(file, response) {
		console.log('*** File uploading response');
		console.dir(response);
        // Cerrar modal
        $("#btn-attachments-ko").trigger("click");
		if( response.success ) {
            var message = '';
            var output = '';
            var size = 0;			
			// Limpiar
			$("input[name='filename']").val('');
			$("#btn-attachments-clear").trigger("click");
            // Render
            if( response.name == '' ) {
                message = 'Archivo anexo no pudo ser cargado al servidor';                
                output = '<div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert"><i data-lucide="alert" class="w-4 h-4"></i><p>'+response.message+'</p></div>';
            } else {
                size = Math.round(parseInt(response.size)/1000);
                message = 'Archivo anexo cargado exitósamente';
                output += '<div class="input-group mt-5">'; 
                output += '<div class="input-group-text flex"><i data-lucide="file" class="w-4 h-4 mr-1"></i> Archivo</div>';
                output += '<input type="text" name="attachname[]" value="'+response.filename+'" class="form-control w-full input-status" readonly>';
                output += '<input type="text" name="attachsize[]" value="'+size+'" class="form-control w-full input-status" readonly>';
                output += '<input type="text" name="attachmime[]" value="'+response.mime+'" class="form-control w-full input-status" readonly>';
                output += '<input type="hidden" name="attachfile[]" value="'+response.name+'" class="form-control w-full">';
                output += '</div>';
            }
			// Message
			setSuccessNotification('success', '', message);
            // Output
            $("#div-attachments").append(output);
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
	dictDefaultMessage: "Suelte aquí el archivo para cargar",
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