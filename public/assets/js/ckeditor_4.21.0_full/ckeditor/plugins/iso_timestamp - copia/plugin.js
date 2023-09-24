CKEDITOR.plugins.add( 'iso_template', {
    icons: 'template',
    init: function( editor ) {
        editor.addCommand( 'insertTemplate', {
            exec: function( editor ) {
                setTemplatesGrid();
            }
        });

        editor.ui.addButton( 'Plantilla', {
            label: 'Insertar plantilla',
            command: 'insertTemplate',
            toolbar: 'insert,100'
        });        
    }
});
