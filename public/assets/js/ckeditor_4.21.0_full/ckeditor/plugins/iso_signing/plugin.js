CKEDITOR.plugins.add( 'iso_signing', {
    icons: 'signing',
    init: function( editor ) {
        editor.addCommand( 'insertSigning', {
            exec: function( editor ) {
                renderSigning();
            }
        });

        editor.ui.addButton( 'Signing', {
            label: 'Insertar Firma',
            command: 'insertSigning',
            toolbar: 'insert,100'
        });        
    }
});
