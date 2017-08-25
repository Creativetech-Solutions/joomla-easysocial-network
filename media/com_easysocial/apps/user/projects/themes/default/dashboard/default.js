(function($){

	EasySocial.require()
	.library( 'dialog' , 'expanding' )
	.done(function($)
	{
		EasySocial.Controller(
			'Dashboard.User.Apps.Projects',
			{
				defaultOptions:
				{
					"{create}"	: "[data-app-projects-create]",
					"{items}"	: "[data-apps-projects-item]",
					"{list}"	: "[data-apps-projects]",
					"{empty}"	: "[data-apps-projects-empty]"
				}
			},
			function(self)
			{

				return {

					init : function()
					{
						self.implementItemController();
					},

					implementItemController: function()
					{
						self.items().implement( EasySocial.Controller.Dashboard.User.Apps.Projects.Item ,
							{
								"{parent}"	: self
							});
					},

					checkEmpty: function()
					{
						if( self.items().length <= 0 )
						{
							self.empty().show();
						}
						else
						{
							self.empty().hide();
						}
					},

					"{create} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'apps/user/projects/controllers/projects/form' , {} ),
							bindings	:
							{
								init : function()
								{
									// Implement expanding text area.
									this.content().expandingTextarea();
								},
								"{createButton} click" : function()
								{
									EasySocial.ajax( 'apps/user/projects/controllers/projects/store' ,
									{
										title 	: this.projectTitle().val(),
										content : this.content().val(),
										appId 	: self.element.data( 'app-id' ),
										stream	: this.stream().is( ':checked' ) ? '1' : '0'
									})
									.done(function( item )
									{
										// Append item to the list.
										$.buildHTML( item ).prependTo( self.list() );

										// Implement item controller before appending item
										self.implementItemController();

										// Remove empty if required
										self.checkEmpty();

										// Close the dialog when we are done.
										EasySocial.dialog().close();
									});
								}
							}
						});
					}
				}
			});

		EasySocial.Controller(
			'Dashboard.User.Apps.Projects.Item',
			{
				defaultOptions:
				{
					"{edit}"	: "[data-apps-projects-edit]",
					"{delete}"	: "[data-apps-projects-delete]"
				}
			},
			function(self)
			{

				return {

					init : function()
					{
						self.options.id 	= self.element.data( 'id' );
					},

					"{edit} click"	: function( el , event )
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'apps/user/projects/controllers/projects/form' , { id : self.options.id } ),
							bindings	:
							{
								init : function()
								{
									// Implement expanding text area.
									this.content().expandingTextarea();
								},
								"{createButton} click" : function()
								{
									EasySocial.ajax( 'apps/user/projects/controllers/projects/store' ,
									{
										id		: self.options.id,
										title 	: this.projectTitle().val(),
										content : this.content().val(),
										appId 	: self.element.data( 'app-id' ),
										stream	: this.stream().is( ':checked' ) ? '1' : '0'
									})
									.done(function( item )
									{
										// Append item to the list.
										self.element.replaceWith( item );

										$( item ).implement( EasySocial.Controller.Dashboard.User.Apps.Projects.Item , { "{parent}" : self });

										// Remove empty if required
										self.parent.checkEmpty();

										EasySocial.dialog().close();
									});
								}
							}
						});
					},

					"{delete} click" : function( el , event )
					{
						EasySocial.dialog(
						{
							content		: EasySocial.ajax( 'apps/user/projects/controllers/projects/confirmDelete' ),
							bindings	:
							{
								"{deleteButton} click" : function()
								{
									EasySocial.ajax( 'apps/user/projects/controllers/projects/delete' ,
									{
										"id"	: self.options.id
									})
									.done(function()
									{
										self.element.remove();

										// Check if there's any more entries.
										self.parent.checkEmpty();

										// Close the dialog
										EasySocial.dialog().close();
									});
								}
							}
						})
					}
				}
			});


		// Implement the controller.
		$( '[data-dashboard-app-projects]' ).implement( EasySocial.Controller.Dashboard.User.Apps.Projects );

	});
})();

