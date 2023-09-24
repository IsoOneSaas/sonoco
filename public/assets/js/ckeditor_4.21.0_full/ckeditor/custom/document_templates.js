/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'es';
	config.uiColor = '#F1F5F9';
	config.toolbarGroups = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },

        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'insert', groups: [ 'insert' ] },
         
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'styles', groups: [ 'styles' ] },
	];

	config.removeButtons = 'Source,Save,NewPage,ExportPdf,Templates,Scayt,Form,Blockquote,CreateDiv,Language,Link,Unlink,Anchor,Flash,Iframe,PageBreak,ShowBlocks,About,Maximize';	
    config.removePlugins = 'exportpdf';
};
