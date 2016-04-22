$.each({
	initToolbarBootstrapBindings : function () {
		var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 
					'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
					'Times New Roman', 'Verdana'],
			fontTarget = $('[title=Font]').siblings('.dropdown-menu');
		
		$.each(fonts, function (idx, fontName) {
			fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
		});
		$('a[title]').tooltip({container:'body'});
		$('.dropdown-menu input').click(function() {return false;})
			.change(function () {$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
			.keydown('esc', function () {this.value='';$(this).change();});

		$('[data-role=magic-overlay]').each(function () { 
			var overlay = $(this), target = $(overlay.data('target')); 
			overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
		});
		if ("onwebkitspeechchange"	in document.createElement("input")) {
			var editorOffset = $(this.jquery).offset();
			$('#voiceBtn').css('position','absolute').offset({top: editorOffset.top, left: editorOffset.left+$('#editor').innerWidth()-35});
		} else {
			$('#voiceBtn').hide();
		}
	},
	showErrorAlert : function (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	},
	richtext: function(obj,options){
		tinymce.baseURL = "../vendor/tinymce/tinymce";

        tinymce.editors=[];
        tinymce.activeEditors=[];

        $(document).on('focusin', function(event) {
            if ($(event.target).closest(".mce-window").length) {
                event.stopImmediatePropagation();
            }
        });

        com_options = $.extend({
            selector: '#'+$(obj).attr('id'),
            init_instance_callback : function(editor) {
                // console.log("Editor: " + editor.id + " is now initialized.");
            },
            file_browser_callback: function elFinderBrowser(field_name, url, type, win) {
                $('<div/>').dialogelfinder({
                    url: 'index.php?page=xepan_base_elconnector',
                    lang: 'en',
                    width: 840,
                    destroyOnClose: true,
                    getFileCallback: function(files, fm) {
                        $('#' + field_name).val(files.url);
                    },
                    commandsOptions: {
                        getfile: {
                            oncomplete: 'close',
                            folders: true
                        }
                    }
                }).dialogelfinder('instance');
            },
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor colorpicker"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
            // toolbar2: "print preview media | forecolor backcolor emoticons",
            image_advtab: true,
            save_enablewhendirty: false,
            // content_css: 'templates/css/epan.css',
            browser_spellcheck : true,
            setup: function(ed) {
                ed.on("change", function(ed) {
                    tinyMCE.triggerSave();
                });
                ed.on('init',function(ed){
                    $(obj)
                        .prev('.mce-container')
                        .find('.mce-edit-area')
                        .droppable({
                            drop: function(event, ui) {
                                tinyMCE.activeEditor.execCommand('mceInsertContent', false,ui.helper.html());
                            }
                        });
                });
            }
        },options);
            

        tinymce.init(com_options);
	}
},$.univ._import);