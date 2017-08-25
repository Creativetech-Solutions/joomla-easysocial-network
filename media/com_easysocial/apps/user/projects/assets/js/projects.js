(function ($) {

    EasySocial.require()
            .library('dialog', 'expanding')
            .done(function ($)
            {
                EasySocial.Controller(
                        'Canvas.User.Apps.Projects',
                        {
                            defaultOptions:
                                    {
                                        "{create}": "[data-app-projects-create]",
                                        "{items}": "[data-apps-projects-item]",
                                        "{list}": "[data-apps-projects]",
                                        "{empty}": "[data-apps-projects-empty]"
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
                                    self.items().implement(EasySocial.Controller.Canvas.User.Apps.Projects.Item,
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
                                                content: EasySocial.ajax('apps/user/projects/controllers/projects/form', {}),
                                                bindings:
                                                        {
                                                            init: function ()
                                                            {
                                                                // Implement expanding text area.
                                                                this.content().expandingTextarea();
                                                            },
                                                            "{createButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/projects/controllers/projects/store',
                                                                        {
                                                                            title: this.projectTitle().val(),
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
                        'Canvas.User.Apps.Projects.Item',
                        {
                            defaultOptions:
                                    {
                                        "{edit}": "[data-apps-projects-edit]",
                                        "{delete}": "[data-apps-projects-delete]",
                                        "{feature}": "[data-apps-projects-feature]",
                                        "{jobApply}": "[data-apps-projects-job-apply]",
                                        "{featureButton}": "[data-project-feature]",
                                        "{unfeatureButton}": "[data-project-unfeature]"
                                    }
                        },
                        function (self)
                        {

                            return {

                                init: function ()
                                {
                                    self.options.id = self.element.data('id');
                                },
                                
                                "{unfeatureButton} click": function(unfeatureButton, event) {
                                        EasySocial.dialog({
                                                content: EasySocial.ajax("apps/user/projects/controllers/projects/confirmFeature", {
                                                        "id": self.options.id
                                                        
                                                }),
                                                bindings:
                                                        {
                                                            "{featuredButton} click": function ()
                                                            {
                                                                EasySocial.dialog({
                                                                    content: '<div class="fd-loading"><span>Loading</span></div>'
                                                                });

                                                                EasySocial.ajax('apps/user/projects/controllers/projects/feature', {
                                                                    id: self.options.id,
                                                                    "feature": false
                                                                }).done(function(content) {
                                                                     EasySocial.dialog().close();
                                                                     window.location.reload();
                                                                }).fail(function(error) {
                                                                    EasySocial.dialog({
                                                                        content: error.message
                                                                    });
                                                                });
                                                            }
                                                        }
                                        })
                                },

                                "{featureButton} click": function(featureButton, event) {

                                        EasySocial.dialog({
                                                content: EasySocial.ajax("apps/user/projects/controllers/projects/confirmFeature", {
                                                        "id": self.options.id
                                                        
                                                }),
                                                bindings:
                                                        {
                                                            "{featuredButton} click": function ()
                                                            {
                                                                EasySocial.dialog({
                                                                    content: '<div class="fd-loading"><span>Loading</span></div>'
                                                                });

                                                                EasySocial.ajax('apps/user/projects/controllers/projects/feature', {
                                                                    id: self.options.id,
                                                                    "feature": true
                                                                }).done(function(content) {
                                                                     EasySocial.dialog().close();
                                                                     window.location.reload();
                                                                }).fail(function(error) {
                                                                    EasySocial.dialog({
                                                                        content: error.message
                                                                    });
                                                                });
                                                            }
                                                        }
                                        });
                                },

                                "{edit} click": function (el, event)
                                {
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/projects/controllers/projects/form', {id: self.options.id}),
                                                bindings:
                                                        {
                                                            init: function ()
                                                            {
                                                                // Implement expanding text area.
                                                                this.content().expandingTextarea();
                                                            },
                                                            "{createButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/projects/controllers/projects/store',
                                                                        {
                                                                            id: self.options.id,
                                                                            title: this.projectTitle().val(),
                                                                            content: this.content().val(),
                                                                            appId: self.element.data('app-id'),
                                                                            stream: this.stream().is(':checked') ? '1' : '0'
                                                                        })
                                                                        .done(function (item)
                                                                        {
                                                                            // Append item to the list.
                                                                            self.element.replaceWith(item);

                                                                            $(item).implement(EasySocial.Controller.Canvas.User.Apps.Projects.Item, {"{parent}": self});

                                                                            // Remove empty if required
                                                                            self.parent.checkEmpty();

                                                                            EasySocial.dialog().close();
                                                                        });
                                                            }
                                                        }
                                            });
                                },
                                
                                "{jobApply} click": function (el, event)
                                {
                                    console.log('job apply button');
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/projects/controllers/projects/applyJobDialog', 
                                                {
                                                    jobid: $(el).attr('data-apps-projects-job-aid')
                                                }),
                                                bindings:
                                                        {
                                                            "{sendButton} click": function ()
                                                            {
                                                                EasySocial.dialog({
                                                                    content: '<div class="fd-loading"><span>Loading</span></div>'
                                                                });

                                                                EasySocial.ajax('apps/user/projects/controllers/projects/applyJob', {
                                                                    pid: self.options.id,
                                                                    job_id: this.jobid().val(),
                                                                    full_name: this.fullName().val(),
                                                                    phone_num: this.phoneNum().val(),
                                                                    email: this.emailAddress().val(),
                                                                    website: this.websiteURL().val(),
                                                                    message: this.message().val()
                                                                }).done(function(content) {
                                                                    console.log(content);
                                                                    EasySocial.dialog({
                                                                        content: content.msg
                                                                    });

                                                                    setTimeout(function() {
                                                                        EasySocial.dialog().close();
                                                                    }, 2000);
                                                                }).fail(function(error) {
                                                                    EasySocial.dialog({
                                                                        content: error.message
                                                                    });
                                                                });
                                                            }
                                                        }
                                            })
                                },

                                "{delete} click": function (el, event)
                                {
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/projects/controllers/projects/confirmDelete'),
                                                bindings:
                                                        {
                                                            "{deleteButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/projects/controllers/projects/delete',
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
                                                content: EasySocial.ajax('apps/user/projects/controllers/projects/confirmFeature',
                                                        {
                                                            id: self.options.id

                                                        }),
                                                bindings:
                                                        {
                                                            "{featureButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/projects/controllers/projects/feature',
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
                $('[data-canvas-app-projects]').implement(EasySocial.Controller.Canvas.User.Apps.Projects);

            });

    EasySocial.require()
            .script('validate')
            .view('site/loading/small')
            .done(function ($) {

                EasySocial.Controller(
                        'Canvas.User.Apps.Project.Edit',
                        {
                            defaultOptions:
                                    {
                                        userid: null,

                                        "{stepContent}": "[data-project-edit-fields-content]",
                                        "{stepItem}": "[data-project-edit-fields-step]",

                                        '{inviteTeam}': '[data-action-invite-team-members]',

                                        '{inviteEmail}': '[data-action-email-invite]',
                                        
                                        '{removeMember}': '[data-action-remove-member]',

                                        // Forms.
                                        "{projectForm}": "[data-project-fields-form]",

                                        "{thumbUpload}": "[data-project-thumb-upload]",

                                        "{file}": "[data-field-project-file]",

                                        // Content for profile editing
                                        "{projectContent}": "[data-project-edit-fields]",

                                        "{fieldItem}": "[data-project-edit-fields-item]",

                                        // Submit buttons
                                        "{save}": "[data-project-fields-save]",

                                        "{saveNext}": "[data-project-fields-save-next]",
                                        // Delete Project
                                        "{deleteProject}": "[data-project-delete]",

                                        '{taskInput}': 'input[name="appTask"]',

                                        '{addLinks}': '.add_links_button',

                                        '{addJob}': '[data-add-job]',
                                        
                                        '{editJob}': '[data-action-edit-job]',
                                        
                                        '{deleteJob}': '[data-action-delete-job]',

                                        '{linksWrapper}': '.websites_wrapper',

                                        '{removeWLink}': '.remove_link_field',

                                        '{prIDInput}': 'input[name="prid"]',
                                        view: {
                                            loading: 'site/loading/small'
                                        }
                                    }
                        },
                        function (self)
                        {
                            return {

                                init: function ()
                                {
                                    self.fieldItem().addController('EasySocial.Controller.Field.Base', {
                                        userid: self.options.userid,
                                        mode: 'edit'
                                    });
                                },

                                errorFields: [],

                                "{addLinks} click": function (el, event)
                                {
                                    event.preventDefault();
                                    self.linksWrapper().append('<div data-field-textbox class="mb-10"><input type="text" name="website[]" class="form-control input-sm" data-field-textbox-input="" /><a href="#" class="remove_link_field btn btn-sm btn-es-danger">Remove</a></div>');
                                },

                                "{removeWLink} click": function (el, event)
                                {
                                    event.preventDefault();
                                    $(el).parent('div').remove();
                                },

                                "{stepItem} click": function (el, event)
                                {
                                    var id = $(el).data('for');
                                    console.log(id);
                                    // Profile form should be hidden
                                    self.projectContent().show();

                                    // Remove active class on step item
                                    self.stepItem().removeClass('active');

                                    // Add active class on the selected item.
                                    el.addClass('active');

                                    // Remove active class on step content
                                    self.stepContent().removeClass('active');

                                    // Get the step content element
                                    var stepContent = self.stepContent().filterBy('id', id);


                                    // Add active class on the selected content
                                    stepContent.addClass('active');

                                    // Trigger onShow on the field item in the content
                                    stepContent.find(self.fieldItem.selector).trigger('show');

                                    $('#current_step_input').val(id);

                                    if (id == $('#all_steps_input').val()) {
                                        $('button.save-exit').css('display', 'inline-block');
                                        $('button.save-next').css('display', 'none');
                                    } else if (id < $('#all_steps_input').val()) {
                                        $('button.save-exit').css('display', 'none');
                                        $('button.save-next').css('display', 'inline-block');
                                    }
                                },

                                "{stepItem} error": function (el) {
                                    el.addClass('error');
                                },

                                "{stepItem} clear": function (el) {
                                    if (self.errorFields.length < 1) {
                                        el.removeClass('error');
                                    }
                                },

                                "{file} change": function (el) {
                                    self.thumbUpload().val('true');
                                    /*if (el.files && (el.files[0])) {
                                     var reader = new FileReader();
                                     
                                     reader.onload = function (e) {
                                     $('#project_image_preview').attr('src', e.target.result);
                                     }
                                     
                                     reader.readAsDataURL(el.files[0]);
                                     }*/
                                },

                                "{inviteTeam} click": function (el, ev) {
                                    EasySocial.dialog({
                                        content: self.view.loading(),
                                        width: 400,
                                        heigth: 150
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/projects/inviteFriendsDialog', {
                                        'id': self.prIDInput().val()
                                    }).done(function (content) {
                                        EasySocial.dialog({
                                            content: content
                                        });
                                    });
                                },
                                
                                "{removeMember} click": function (el, ev) {
                                    EasySocial.dialog({
                                        content: self.view.loading(),
                                        width: 400,
                                        heigth: 150
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/projects/removeMemberDialog', {
                                        'pid': self.prIDInput().val(),
                                        'member_id': $( el ).data( 'memid' )
                                    }).done(function (content) {
                                        EasySocial.dialog({
                                            content: content,
                                            bindings:
                                                        {
                                                            "{deleteButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/projects/controllers/projects/removeMember',
                                                                        {
                                                                            'pid': self.prIDInput().val(),
                                                                            'member_id': $( el ).data( 'memid' ),
                                                                            'id':$( el ).data( 'id' )
                                                                        })
                                                                        .done(function ()
                                                                        {
                                                                            $(el).closest('li').remove();

                                                                            // Close the dialog
                                                                            EasySocial.dialog().close();
                                                                            //window.location.reload();
                                                                        });
                                                            }
                                                        }
                                        });
                                    });
                                },

                                "{addJob} click": function (el, ev) {
                                    EasySocial.dialog({
                                        content: self.view.loading(),
                                        width: 400,
                                        heigth: 150
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/projects/addJobDialog', {
                                        'id': self.prIDInput().val()
                                    }).done(function (content) {
                                        EasySocial.dialog({
                                            content: content
                                        });
                                    });
                                },
                                
                                "{editJob} click": function (el, ev) {
                                    EasySocial.dialog({
                                        content: self.view.loading(),
                                        width: 400,
                                        heigth: 150
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/projects/editJobDialog', {
                                        jobid: $(el).attr('data-job-id')
                                    }).done(function (content) {
                                        EasySocial.dialog({
                                            content: content
                                        });
                                    });
                                },
                                
                                "{deleteJob} click": function (el, ev) {
                                    EasySocial.dialog({
                                        content: self.view.loading(),
                                        width: 400,
                                        heigth: 150
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/projects/deleteJobDialog', {
                                        jobid: $(el).attr('data-job-id')
                                    }).done(function (content) {
                                        EasySocial.dialog({
                                            content: content,
                                            bindings:
                                                        {
                                                            "{deleteButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/projects/controllers/projects/deleteJob',
                                                                        {
                                                                            jobid: $(el).attr('data-job-id')
                                                                        })
                                                                        .done(function ()
                                                                        {
                                                                            $(el).closest('li').remove();

                                                                            // Close the dialog
                                                                            EasySocial.dialog().close();
                                                                        });
                                                            }
                                                        }
                                            
                                        })
                                    });
                                },

                                "{inviteEmail} click": function (el, ev) {
                                    EasySocial.dialog({
                                        content: self.view.loading(),
                                        width: 400,
                                        heigth: 150
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/projects/inviteEmailDialog', {
                                        'id': self.prIDInput().val()
                                    }).done(function (content) {
                                        EasySocial.dialog({
                                            content: content
                                        });
                                    });
                                },

                                "{save} click": function (el, event)
                                {
                                    // Run some error checks here.
                                    event.preventDefault();

                                    $(el).addClass('btn-loading');

                                    self.projectForm()
                                            .validate()
                                            .fail(function ()
                                            {
                                                $(el).removeClass('btn-loading');
                                                EasySocial.dialog(
                                                        {
                                                            content: EasySocial.ajax('apps/user/projects/controllers/projects/showFormError')
                                                        });
                                            })
                                            .done(function ()
                                            {
                                                self.taskInput().val('save');
                                                self.projectForm().submit();
                                            });

                                    return false;
                                },

                                "{saveNext} click": function (el, event)
                                {
                                    // Run some error checks here.
                                    event.preventDefault();

                                    self.stepItem().each(function () {
                                        //console.log($(this));
                                        if ($(this).hasClass('active')) {
                                            curr_el = $(this).next().attr('data-for');
                                        }
                                    });
                                    //return false;

                                    $(el).addClass('btn-loading');

                                    self.projectForm()
                                            .validate()
                                            .fail(function ()
                                            {
                                                $(el).removeClass('btn-loading');
                                                EasySocial.dialog(
                                                        {
                                                            content: EasySocial.ajax('apps/user/projects/controllers/projects/showFormError')
                                                        });
                                            })
                                            .done(function ()
                                            {
                                                if ($("#project_image").val() != '') {
                                                    //self.thumbUpload().val('true');
                                                }
                                                if (curr_el == null) {
                                                    self.taskInput().val('save');
                                                    self.projectForm().submit();
                                                } else {
                                                    $('.toggle-editor a').click();
                                                    $('.toggle-editor a').click();
                                                    EasySocial.ajax('apps/user/projects/controllers/projects/store',
                                                            {
                                                                post: self.projectForm().serialize(),
                                                                files: self.file()
                                                            }, {
                                                        type: 'iframe'
                                                    })
                                                            .done(function (html)
                                                            {
                                                                //console.log(JSON.stringify(html));
                                                                var sresult = JSON.parse(html);
                                                                //console.log(sresult.project_id);
                                                                self.prIDInput().val(sresult.project_id);
                                                                //sresult.project_url.route();
                                                                $(el).removeClass('btn-loading');
                                                                $('#current_step_input').val(curr_el);
                                                                self.stepItem().filterBy('for', curr_el).trigger('click');
                                                                //self.stepItem().next().click();
                                                                if (curr_el == $('#all_steps_input').val()) {
                                                                    $('button.save-exit').css('display', 'inline-block');
                                                                    $('button.save-next').css('display', 'none');
                                                                }
                                                            });
                                                }

                                            });

                                    return false;
                                },
                                "{deleteProject} click": function ()
                                {
                                    EasySocial.dialog(
                                            {
                                                content: EasySocial.ajax('apps/user/projects/controllers/projects/confirmDelete'),
                                                bindings:
                                                        {
                                                            "{deleteButton} click": function ()
                                                            {
                                                                EasySocial.ajax('apps/user/projects/controllers/projects/delete',
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
                                            });
                                }
                            }
                        });

            });
            
            EasySocial
                .require()
                .library('image')
                .language('PLG_FIELDS_COVER_VALIDATION_REQUIRED')
                .done(function($) {
                    EasySocial.Controller('Canvas.User.Apps.Projects.Cover', {
                        defaultOptions: {
                            id              : 0,
                            group           : 'project',
                            required        : false,
                            hasCover        : true,
                            defaultCover    : null,

                            ratio           : 3,

                            '{image}'       : '[data-field-cover-image]',

                            "{projectForm}": "[data-project-fields-form]",

                            '{prIDInput}': 'input[name="prid"]',

                            '{data}'        : '[data-field-cover-data]',
                            '{position}'    : '[data-field-cover-position]',
                            '{file}'        : '[data-field-cover-file]',

                            '{note}'        : '[data-field-cover-note]',

                            '{loader}'      : '[data-field-cover-loader]',
                            '{error}'       : '[data-field-cover-error]',

                            '{removeButton}': '[data-field-cover-remove-button]',

                            '{revertFrame}' : '[data-field-cover-revert]',
                            '{revertButton}': '[data-field-cover-revert-button]'
                        }
                    },
                    function(self) {
                        return {
                            init : function() {
                                self.setFrame();

                                self.setLayout();

                                self.origCover = self.options.hasCover ? $.uri(self.image().css('backgroundImage')).extract(0) : null;

                                self.origPosition = self.options.hasCover ? self.image().css('backgroundPosition') : null;
                            },

                            '{self} onShow': function() {
                                setTimeout(function() {
                                    self.setLayout();
                                }, 1);
                            },

                            setFrame: function() {
                                var frameWidth = self.image().width(),
                                    frameHeight = frameWidth / self.options.ratio;

                                self.image().css('height', frameHeight);
                            },

                            '{window} resize': $.debounce(function() {
                                self.setFrame();
                            }, 250),

                            imageLoaders: {},

                            '{file} change' : function( el , event ) {
                                if($.isEmpty(el.val())) {
                                    return;
                                }

                                var label = el.val().replace(/\\/g, '/').replace(/.*\//, '');

                                el.parents('.input-group').find(':text').val(label);

                                self.loader().show();

                                self.error().hide();

                                self.image().hide();

                                self.note().hide();

                                self.file().hide();

                                EasySocial.ajax( 'apps/user/projects/controllers/projects/upload' , {
                                    post: $('form[data-project-fields-form]').serialize(),
                                    files   : el
                                }, {
                                    type    : 'iframe'
                                }).done(function(rhtml){
                                    var sresult = JSON.parse(rhtml);
                                    //console.log(sresult);
                                    //console.log(sresult.project_id);
                                    var resultString    = JSON.stringify(sresult.result);

                                    $('input[name="prid"]').val(sresult.project_id);
                                    self.find('.project-image-field').css('visibility','hidden');

                                    var result = sresult.result;

                                    // Set the result in a string format
                                    self.data().val(resultString);

                                    var positionString = JSON.stringify({
                                        x: 0.5,
                                        y: 0.5
                                    });

                                    // Set the position in string format defaulting to 50 50
                                    self.position().val(positionString);

                                    // Set the position to 50 50 by default
                                    self.position().val()

                                    var url = result.large.uri,
                                        imageLoaders = self.imageLoaders,
                                        imageLoader = (imageLoaders[url] || (imageLoaders[url] = $.Image.get(url))).done(function(image) {
                                            self.setLayout.image = image;

                                            self.file().show();

                                            self.image().show();

                                            self.note().show();

                                            self.loader().hide();

                                            self.revertFrame().hide();

                                            self.removeButton().show();

                                            self.setCover(result.large.uri);
                                        });

                                }).fail(function(msg) {

                                    self.loader().hide();

                                    self.file().show();

                                    self.error().show().html(msg);
                                });
                            },

                            setLayout: function() {
                                var cover = self.image(),
                                    image = self.setLayout.image;

                                if(!image) {
                                    var url = $.uri(cover.css('backgroundImage')).extract(0);

                                    if(!url) return;

                                    var imageLoaders = self.imageLoaders,
                                        imageLoader =
                                            (imageLoaders[url] || (imageLoaders[url] = $.Image.get(url)))
                                                .done(function(image) {

                                                    // Set it as current image
                                                    self.setLayout.image = image;

                                                    // Then set layout again
                                                    self.setLayout();
                                                });

                                        return;
                                }

                                var imageWidth  = image.data("width"),
                                    imageHeight = image.data("height"),
                                    coverWidth  = cover.width(),
                                    coverHeight = cover.height(),
                                    size = $.Image.resizeProportionate(
                                        imageWidth, imageHeight,
                                        coverWidth, coverHeight,
                                        "outer"
                                    );

                                self.availableWidth  = coverWidth  - size.width;
                                self.availableHeight = coverHeight - size.height;

                                self.setFrame();
                            },

                            setCover: function(url, position) {
                                position = position || '50% 50%';

                                self.image()
                                    .css({
                                        backgroundImage: $.cssUrl(url),
                                        backgroundPosition: position
                                    });

                                self.setLayout();

                                self.image().addClass('cover-move');

                                self.note().show();
                            },

                            x: 0.5,
                            y: 0.5,

                            moveCover: function(dx, dy, image) {

                                if (!image) {
                                    image = self.image();
                                }

                                var w = self.availableWidth,
                                    h = self.availableHeight,
                                    x = (w==0) ? 0 : self.x + ((dx / w) || 0),
                                    y = (h==0) ? 0 : self.y + ((dy / h) || 0);

                                // Always stay within 0 to 1.
                                if (x < 0) x = 0; if (x > 1) x = 1;
                                if (y < 0) y = 0; if (y > 1) y = 1;

                                // Set position on cover
                                image.css("backgroundPosition",
                                    ((self.x = x) * 100) + "% " +
                                    ((self.y = y) * 100) + "% "
                                );

                                var position = {
                                    x: self.x,
                                    y: self.y
                                }

                                self.position().val(JSON.stringify(position));
                            },

                            '{image} mousedown': function(selection, event) {
                                if (event.target === self.image()[0]) {
                                    event.preventDefault();
                                }

                                // Initial cover position
                                var image = self.image(),
                                    position = image.css("backgroundPosition").split(" ");
                                    self.x = parseInt(position[0]) / 100;
                                    self.y = parseInt(position[1]) / 100;

                                // Initial cursor position
                                var x = event.pageX,
                                    y = event.pageY;

                                $(document)
                                    .on("mousemove.movingCover mouseup.movingCover", function(event) {

                                        self.moveCover(
                                            (x - (x = event.pageX)) * -1,
                                            (y - (y = event.pageY)) * -1,
                                            image
                                        );
                                    })
                                    .on("mouseup.movingCover", function() {

                                        $(document).off("mousemove.movingCover mouseup.movingCover");
                                    });
                            },

                            '{removeButton} click': function(el) {
                                var data = self.data().val();

                                if($.isEmpty(data)) {
                                    if(self.options.hasCover) {
                                        self.setCover(self.options.defaultCover);

                                        // Mark the data as delete

                                        self.data().val('delete');

                                        el.hide();

                                        self.revertFrame().show();

                                        self.find('.project-image-field').css('visibility','visible');
                                    }
                                } else {
                                    // Backup the data first
                                    self.origData = data;
                                    self.origPosition = self.position().val();

                                    self.data().val('');
                                    self.position().val('');
                                    self.file().val('');
                                    self.file().parents('.input-group').find(':text').val('');

                                    if(self.options.hasCover) {
                                        self.setCover(self.origCover);
                                    } else {
                                        self.setCover(self.options.defaultCover);

                                        // Mark the data as delete
                                        self.data().val('delete');

                                        el.hide();

                                        self.find('.project-image-field').css('visibility','visible');
                                    }
                                }
                            },

                            '{revertButton} click': function() {
                                self.setCover(self.origCover, self.origPosition);

                                self.revertFrame().hide();

                                self.removeButton().show();

                                self.data().val('');

                                self.position().val('');

                                self.file().val('');
                                self.file().parents('.input-group').find(':text').val('');
                            },

                            /*'{self} onSubmit': function(el, ev, register) {
                                if(self.options.required && !self.options.hasCover && $.isEmpty(self.data().val()))
                                {
                                    self.raiseError($.language('PLG_FIELDS_COVER_VALIDATION_REQUIRED'));
                                    register.push(false);
                                }
                            },*/

                            raiseError: function(msg) {
                                self.trigger('error', [msg]);
                            }
                        }
                    });
                });
            
            EasySocial.require()
                .library( 'tinyscrollbar' )
                .language( 'COM_EASYSOCIAL_FRIENDS_REQUEST_REJECTED' )
                .done(function($){
                    EasySocial.Controller(
			'Canvas.User.Apps.Projects.Notifications.Item',
			{
				defaultOptions:
				{
					"{actionsWrapper}" 	: "[data-project-item-actions]",
                                        "{projectItemText}" 	: "[data-project-item-text]",
                                        "{projectItemTitle}" 	: "[data-project-item-title]",
                                        "{projectDateTime}" 	: "[data-project-datetime]",
					"{acceptProject}"	: "[data-project-item-accept]",
					"{rejectProject}"	: "[data-project-item-reject]",
					"{actions}"		: "[data-project-item-action]",
					"{title}"		: "[data-project-item-title]",
					"{mutual}" 		: "[data-project-item-mutual]",
                                        "{loader}" 		: "i.loading-indicator",

					// Views
					view	:
					{
						loader 		: 'site/loading/small'
					},
				}
			},
			function( self ){
				return {

					init: function()
					{

					},

					"{acceptProject} click" : function( el , event )
					{
						// Stop other events from being triggered.
						event.stopPropagation();
						self.actionsWrapper().addClass( 'friend-adding' );
                                                self.loader().show();
						// Send an ajax request to approve the friend.
						EasySocial.ajax( 'apps/user/projects/controllers/projects/acceptInvite' ,
						{
							id : $( el ).data( 'id' ),
                                                        ntid : $( el ).data( 'ntid' )
						})
						.done(function( title )
						{
							// Update the current state
							self.actionsWrapper().removeClass( 'friend-adding' ).addClass( 'added-friends' );
                                                        self.projectItemText().html(' welcomes you!');
                                                        self.projectDateTime().html('');
                                                        self.projectItemTitle().html('');
							self.actionsWrapper().html( title );
                                                        self.loader().hide();
						})
						.fail( function( message )
						{
							// Append error message.
							self.element.html( message );
                                                        self.loader().hide();
						});

					},


					"{rejectProject} click" : function( el , event )
					{
						event.stopPropagation();
                                                self.loader().show();

						EasySocial.ajax( 'apps/user/projects/controllers/projects/rejectInvite' ,
						{
							id  : $( el ).data( 'id' ),
                                                        ntid : $( el ).data( 'ntid' )
						})
						.done( function( button )
						{
                                                        
                                                        self.projectItemText().html('');
                                                        self.projectItemTitle().html('');
                                                        self.projectDateTime().html('');
							self.actionsWrapper().html( $.language( 'Team invitation rejected' ) );
                                                        self.loader().hide();
						})
						.fail( function( mese4sage )
						{
							// Append error message.
							self.element.html( message );
                                                        self.loader().hide();
						});

					}
				}
			}
                    );
                });
            
})();