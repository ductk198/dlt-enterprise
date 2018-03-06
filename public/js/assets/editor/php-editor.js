function PHPEditor(element, language='en', url='') {
	var lang = {
			'execute': 'Execute',
			'execute_code': 'Execute code',
			'switch views': 'Switch views',
			'download script': 'Download script',
			'define GET/POST variables': 'Define GET/POST-Variablen variables',
			'hide_get': 'Hide GET parameters',
			'hide_post': 'Hide POST parameters',
			'value': 'Value...',
			'help': 'Help',
			'last_execution': 'Last execution:',
			'error': "Sadly the PHP script couldn't be executed. One reason could be an too old browser version."
	}
	this.lang = lang;
	
	
	var id = jQuery(element).data('ace-editor-id');
	var allowExecution = false;
	var mode = "ace/mode/php";
	var scriptName = "page.php";
	var hideVars = false;	
	var defaultGet = "";
	var defaultPost = "";
	
	if (jQuery(element).attr('data-ace-editor-allow-execution')) {
		allowExecution = jQuery(element).data('ace-editor-allow-execution') === true;
	}

	if (jQuery(element).attr('data-ace-editor-mode')) {
		mode = jQuery(element).attr('data-ace-editor-mode');
	}
	console.log(mode);
	if (jQuery(element).attr('data-ace-editor-hide-vars')) {
		hideVars = jQuery(element).data('ace-editor-hide-vars');
	}
	
	if (jQuery(element).attr('data-ace-editor-script-name')) {
		scriptName = jQuery(element).data('ace-editor-script-name');
	}
	
	if (jQuery(element).attr('data-ace-editor-default-get')) {
		defaultGet = jQuery(element).data('ace-editor-default-get');
	}
	
	if (jQuery(element).attr('data-ace-editor-default-post')) {
		defaultPost = jQuery(element).data('ace-editor-default-post');
	}
	
	this.editor = addEditor(id, allowExecution, scriptName, hideVars, defaultGet, defaultPost, url);
	this.url = url
	PHPEditor.editors[id] = this
	
	function addEditor(id, allowExecution, scriptName, hideVars, defaultGet, defaultPost) {
		function escapeHtml(string) {
			  var entityMap = {
					    "&": "&amp;",
				    "<": "&lt;",
				    ">": "&gt;",
				    '"': '&quot;',
				    "'": '&#39;',
				    "/": '&#x2F;'
				  };
	
			  return String(string).replace(/[&<>"'\/]/g, function (s) {
				  return entityMap[s];
			  });
		}
	
		
		allowExecution = typeof allowExecution !== 'undefined' ? allowExecution : false;
		scriptName = typeof scriptName !== 'undefined' ? scriptName : 'page.php';
		hideVars = typeof hideVars !== 'undefined' ? hideVars : false;
		defaultGet = typeof defaultGet !== 'undefined' ? defaultGet : '';
		defaultPost = typeof defaultPost !== 'undefined' ? defaultPost : '';
		
		defaultPostParameter = '';
		
		if(defaultPost.trim() != '') {
			parsedPost = JSON.parse('{'+defaultPost.trim()+'}');
			for (var key in parsedPost) {
				defaultPostParameter += '<input type="text" value="'+escapeHtml(key)+'" onkeyup="addPostValue(\''+id+'\')" /> <input type="text" value="'+escapeHtml(parsedPost[key])+'" onkeyup="addPostValue(\''+id+'\')"/><br />';
			}
		}
	
		var editor = ace.edit("code_editor_"+id);
	
	    editor.setTheme("ace/theme/crimson_editor");
	    editor.session.setMode(mode);
		editor.setOption("highlightActiveLine", false);
	    editor.setOptions({
	        maxLines: Infinity,
	        highlightActiveLine: false,
	        showPrintMargin: false,
			fontSize: "12pt"
	    });
	
	    editor.commands.bindKey("F9", function(cm) {
			PHPEditor.submitCode(id);
		});
	
	    
	    jQuery('#code_'+id).on('keydown', function(e){
	    	if(e.keyCode == 120) { //Fp
	    		 submitCode(id);
	    	     return false;
	    	}
	    });
	
	    editor.session.setOption("useWorker", false);
	
	    if(!allowExecution) {
	    	editor.setReadOnly(true);
	    }
	    return editor;
	}
}

PHPEditor.editors = {};
PHPEditor.get = function(id) {
	return PHPEditor.editors[id];
}	  