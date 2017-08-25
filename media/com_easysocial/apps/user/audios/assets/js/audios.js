(function ($) {

    EasySocial.require()
            .library('dialog', 'expanding')
            .done(function ($)
            {
                EasySocial.Controller(
                        'Canvas.User.Apps.Audios',
                        {
                            defaultOptions:
                                    {
                                        "{create}": "[data-app-audios-create]",
                                        "{items}": "[data-apps-audios-item]",
                                        "{list}": "[data-apps-audios]",
                                        "{empty}": "[data-apps-audios-empty]"
                                    }
                        },
                        function (self)
                        {

                            return {

                                init: function ()
                                {
                                    self.implementItemController();
                                },

                                implementItemController: function ()
                                {
                                    self.items().implement(EasySocial.Controller.Canvas.User.Apps.Audios.Item,
                                            {
                                                "{parent}": self
                                            });
                                },

                                checkEmpty: function ()
                                {
                                    if (self.items().length <= 0)
                                    {
                                        self.empty().show();
                                    } else
                                    {
                                        self.empty().hide();
                                    }
                                },

                                "{create} click": function ()
                                {
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/audios/controllers/audios/form', {}),
                                                bindings:
                                                        {
                                                            init: function ()
                                                            {
                                                                // Implement expanding text area.
                                                                this.content().expandingTextarea();
                                                            },
                                                            "{createButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/audios/controllers/audios/store',
                                                                        {
                                                                            title: this.audioTitle().val(),
                                                                            content: this.content().val(),
                                                                            appId: self.element.data('app-id'),
                                                                            stream: this.stream().is(':checked') ? '1' : '0'
                                                                        })
                                                                        .done(function (item)
                                                                        {
                                                                            // Append item to the list.
                                                                            $.buildHTML(item).prependTo(self.list());

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
                        'Canvas.User.Apps.Audios.Item',
                        {
                            defaultOptions:
                                    {
                                        "{edit}": "[data-apps-audios-edit]",
                                        "{delete}": "[data-apps-audios-delete]",
                                        "{feature}": "[data-apps-audios-feature]"
                                    }
                        },
                        function (self)
                        {

                            return {

                                init: function ()
                                {
                                    self.options.id = self.element.data('id');
                                },

                                "{edit} click": function (el, event)
                                {
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/audios/controllers/audios/form', {id: self.options.id}),
                                                bindings:
                                                        {
                                                            init: function ()
                                                            {
                                                                // Implement expanding text area.
                                                                this.content().expandingTextarea();
                                                            },
                                                            "{createButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/audios/controllers/audios/store',
                                                                        {
                                                                            id: self.options.id,
                                                                            title: this.audioTitle().val(),
                                                                            content: this.content().val(),
                                                                            appId: self.element.data('app-id'),
                                                                            stream: this.stream().is(':checked') ? '1' : '0'
                                                                        })
                                                                        .done(function (item)
                                                                        {
                                                                            // Append item to the list.
                                                                            self.element.replaceWith(item);

                                                                            $(item).implement(EasySocial.Controller.Canvas.User.Apps.Audios.Item, {"{parent}": self});

                                                                            // Remove empty if required
                                                                            self.parent.checkEmpty();

                                                                            EasySocial.dialog().close();
                                                                        });
                                                            }
                                                        }
                                            });
                                },

                                "{delete} click": function (el, event)
                                {
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/audios/controllers/audios/confirmDelete'),
                                                bindings:
                                                        {
                                                            "{deleteButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/audios/controllers/audios/delete',
                                                                        {
                                                                            "id": self.options.id
                                                                        })
                                                                        .done(function ()
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
                                },
                                "{feature} click": function (el, event)
                                {
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/audios/controllers/audios/confirmFeature',
                                                        {
                                                            id: self.options.id

                                                        }),
                                                bindings:
                                                        {
                                                            "{featureButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/audios/controllers/audios/feature',
                                                                        {
                                                                            "id": self.options.id
                                                                        })
                                                                        .done(function ()
                                                                        {
                                                                            // Close the dialog
                                                                            EasySocial.dialog().close();
                                                                            window.location.reload();
                                                                        });
                                                            }
                                                        }
                                            })
                                }
                            }
                        });


                // Implement the controller.
                $('[data-canvas-app-audios]').implement(EasySocial.Controller.Canvas.User.Apps.Audios);

            });
})();

