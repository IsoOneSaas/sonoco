Dropzone.options.dropzone =
         {
	        maxFiles: 1, 
            maxFilesize: 8,	// MB
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.xlsm,.ppt,.pps,.pptx,.ppsx,.pub,.one,.dwg,.cad",
            addRemoveLinks: false,
            timeout: 50000,
            success: function(file, response) 
            {
                console.log('*** File uploaded succesfully');
				file.previewElement.id = response.success;
				console.log(file); 
                console.log(response.success); 
				// set new images names in dropzone’s preview box.
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
				file.previewElement.querySelector("img").alt = response.success;
				olddatadzname.innerHTML = response.success;
            },
            error: function(file, response)
            {
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
            }
            
};