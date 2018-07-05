jQuery.widget("ui.menudesigner",{
	self: undefined,
	
	options:{
		designing_menu:'',
		available_menus:{},
		saved_menus:{}
	},

	_create: function(){
		var self=this;
		self.createUI();
		self.createAvailableMenus();
		self.createSavedMenus();
		console.log(self.options);
	},

	createUI: function(){
		var self = this;
		this.widgetui = $('<div class="row"></div>').appendTo(this.element);
		this.left_panel = $('<div class="col-md-6"></div>').appendTo(this.widgetui);
		this.save_button = $('<button>SAVE</button>').appendTo(this.widgetui);
		this.right_panel = $('<div class="col-md-6"></div>').appendTo(this.widgetui).html(self.options.designing_menu);
		this.right_panel_ui = $('<ul class="saved_menus"></ul>').appendTo(this.right_panel);
	},

	createAvailableMenus: function(){
		var self=this;
		
		$.each(self.options.available_menus, function(MainMenu, SubMenus) {
			var ul = $('<ul></ul>').appendTo(self.left_panel).html(MainMenu);
			$.each(SubMenus, function(index, menu) {
			 	$('<li class="available_menu"></li>').appendTo(ul).html(menu.name);
			});
		});

		$('.available_menu').draggable({
						inertia:true,
						appendTo:'body',
						connectToSortable:'.saved_menus',
						helper:'clone',
						start: function(event,ui){
						},
						stop: function(event,ui){
						},
						revert: 'invalid',
						tolerance: 'pointer'
					});
	},

	createSavedMenus: function(){
		var self=this;
		$.each(self.options.saved_menus, function(index, SubMenus) {
			$.each(SubMenus, function(index, menu) {
			 	$('<li class="saved_menu"></li>').appendTo(self.right_panel_ui).html(menu.name);
			});
		});

		$(self.right_panel_ui).sortable({
									appendTo:'body',
									connectWith:'.saved_menus',
									// handle: '.xepan-component-drag-handler',
									cursor: "move",
									revert: true,
									tolerance: "pointer"
								});

	}
});
