/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
config.toolbarGroups = [
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
	{ name: 'forms', groups: [ 'forms' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
	{ name: 'links', groups: [ 'links' ] },
	{ name: 'insert', groups: [ 'insert' ] },
	'/',
	{ name: 'styles', groups: [ 'styles' ] },
	{ name: 'colors', groups: [ 'colors' ] },
	{ name: 'tools', groups: [ 'tools' ] },
	{ name: 'others', groups: [ 'others' ] },
	{ name: 'about', groups: [ 'about' ] }
];

config.removeButtons = 'About,Source,Save,NewPage,Preview,Print,Templates,PasteText,PasteFromWord,Undo,Redo,Replace,Find,SelectAll,Scayt,HiddenField,Form,TextField,Textarea,Select,Button,ImageButton,CreateDiv,Flash,Smiley,Iframe,ShowBlocks,Subscript,Superscript,Styles,Format,Font,FontSize,BGColor,TextColor,Maximize,Cut,Copy,Paste,Radio,Checkbox';
};
