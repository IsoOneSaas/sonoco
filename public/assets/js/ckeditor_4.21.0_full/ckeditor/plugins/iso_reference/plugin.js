CKEDITOR.plugins.add( 'iso_reference', {
    icons: 'reference',
    init: function( editor ) {
        editor.addCommand( 'insertReference', {
            exec: function( editor ) {
                setReferencesGrid();
            }
        });

        editor.ui.addButton( 'Reference', {
            label: 'Insertar Referencia',
            command: 'insertReference',
            toolbar: 'insert,100'
        });        
    }
});
