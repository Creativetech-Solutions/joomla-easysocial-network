var isMenuOpen = false, isSrchOpen = false;
jQuery(document).ready(function ($) {
    jQuery('.es-video-response a.acomments').on('click', function () {
        //console.log(jQuery(this).parents( 'div.es-video-item').parent().find('.es-comments-wrap'));
        jQuery(this).parents('div.es-video-item').find('.es-comments-wrap').toggle();
    });

    $('span.birthday-help').click(function () {
        $(this).parent().siblings().find('.text-note').slideToggle("slow");
    });


    $('#menu_pull').on('click', function (e) {
        e.preventDefault();
        $('.header_links_mobile').slideToggle().promise().done(function () {
            isMenuOpen = !isMenuOpen;
            if (!isMenuOpen) {
                $('.header_links_mobile').removeAttr('style');
            }
        });
        if (isSrchOpen) {
            $('.serch-div-res').slideUp().promise().done(function () {
                isSrchOpen = false;
                $('.serch-div-res input[type="text"]').val('');
            });
        }
    });

    $('.serch-div').on('click', function () {
        if ($(window).width() <= 767) {
            $('.serch-div-res').slideToggle().promise().done(function () {
                isSrchOpen = !isSrchOpen;
                if (isSrchOpen) {
                    $('.serch-div-res input[type="text"]').focus();
                } else {
                    $('.serch-div-res input[type="text"]').val('');
                }
            });
        }
    });

    $(document).on('click', function (e) {
        if (isMenuOpen) {
            var container = $(".header_links_mobile");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.slideUp().promise().done(function () {
                    isMenuOpen = false;
                    $('.header_links_mobile').removeAttr('style');
                });
            }
        }
        if (isSrchOpen) {
            var container = $(".serch-div-res");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('.serch-div-res').slideUp().promise().done(function () {
                    isSrchOpen = false;
                    $('.serch-div-res input[type="text"]').val('');
                });
            }
        }
    });
    $(window).on('resize', function (e) {
        var w = $(window).width();
        if (w > 640 && isMenuOpen) {
            isMenuOpen = false;
            $('.header_links_mobile').removeAttr('style');
        }

        if (w > 640 && isSrchOpen) {
            isSrchOpen = false;
            $('.serch-div-res input[type="text"]').val('');
            $('.serch-div-res').removeAttr('style');
        }
    });

    $(document).ready(function () {
        /*jQuery('.es-album-info .is-editable').click(function(){
         jQuery('li.album-edit-btn').trigger('click');
         });	*/

        jQuery('body').on('click', '.es-album-info .is-editable', function (event) {
            //console.log(jQuery(event.target));
            if (jQuery(event.target).parent().hasClass('es-album-title')) {
                //console.log('es-album-title');
            }
            if (jQuery(event.target).hasClass('es-album-caption')) {
                //console.log('es-album-caption');
            }
            jQuery('li.album-edit-btn').trigger('click');
        });

        jQuery(document).on('click', function (e) {
            //console.log(jQuery('.es-album-item').attr('data-album-id'));
            //console.log('form is visible or not : '+jQuery(".es-album-form").is(':visible'));	
            //hide the form if click outside the form.
            /*if ( jQuery(".es-album-form").is(':visible') && !jQuery(e.target).addBack().is(".es-album-form") ) {  
             $('.es-album-menu .album-cancel').trigger('click');
             }*/
            if (jQuery('.es-album-form').is(':visible') && !jQuery(e.target).addBack().is('.es-album-form') && !jQuery(e.target).parents().addBack().is('.es-album-form') && !jQuery(e.target).is('.es-album-title a') && !jQuery(e.target).is('.es-album-caption') && !jQuery(e.target).is('li.album-edit-btn a') && jQuery('.es-album-item').attr('data-album-id') != '') {
                //jQuery(".imgmap-dialog-wrapper").hide();
                $('.es-album-menu .album-cancel').trigger('click');
                //console.log('form visible? '+jQuery(".es-album-form").is(':visible'));	
            }
        });
    });
});

EasySocial.module('site/profile/subscriptions', function ($) {

    var module = this;

    EasySocial.Controller(
        'Profile.Subscriptions',
        {
            defaultOptions: {
                // Properties
                id: null,

                "{follow}": "[data-subscription-follow]",
                "{unfollow}": "[data-subscription-unfollow]",
                "{followmob}": "[data-subscription-follow-mobile]",
                "{unfollowmob}": "[data-subscription-unfollow-mobile]",
                "{message}": "[data-subscription-message]",
                "{confirmunfollow}": "[data-follow-unfollow]",
                "{button}": "[data-subscription-button]"
            }
        },
        function (self) {
            return {

                init: function () {
                    self.options.id = self.element.data('id');
                },

                toggleDropDown: function () {
                    self.element.toggleClass('open');
                },

                showDropDown: function () {
                    console.log('override showDropDown');
                    self.element.append('<ul class="dropdown-menu dropdown-arrow-topleft dropdown-follow" data-follow-dropdown><li><a href="javascript:void(0);" data-follow-unfollow>Unfollow</a></li></ul>');
                },

                removeDropDown: function () {
                    console.log('override removeDropDown');
                    self.element.find('ul.dropdown-follow').remove();
                },

                "{confirmunfollow} click": function () {
                    console.log('override confirmunfollow');
                    // Toggle dropdown.
                    self.toggleDropDown();

                    // Let's do an ajax call to follow the user.
                    EasySocial.ajax('site/controllers/profile/unfollow',
                        {
                            "id": self.options.id,
                            "type": 'user'
                        })
                        .done(function (button) {
                            self.removeDropDown();
                            self.button().replaceWith(button);
                        })
                },

                "{unfollow} click": function () {
                    console.log('override unfollow');
                    // Toggle dropdown.
                    self.toggleDropDown();

                    self.showDropDown();

                    // Let's do an ajax call to follow the user.
                    /*EasySocial.ajax( 'site/controllers/profile/unfollow' ,
                     {
                     "id"	: self.options.id,
                     "type"	: 'user'
                     })
                     .done(function(button)
                     {
                     self.button().replaceWith( button );
                     })*/
                },

                "{unfollowmob} click": function () {
                    console.log('override unfollow mob');
                    // Toggle dropdown.
                    self.toggleDropDown();

                    // Let's do an ajax call to follow the user.
                    EasySocial.ajax('site/controllers/profile/unfollow',
                        {
                            "id": self.options.id,
                            "type": 'user'
                        })
                        .done(function (button) {
                            self.removeDropDown();
                            console.log(self.button());
                            self.button().replaceWith(button);
                            self.button().removeAttr('data-subscription-follow');
                            self.button().attr('data-subscription-follow-mobile', '');
                            self.button().addClass('not-is-following');
                        })
                },

                "{follow} click": function () {
                    // Toggle dropdown.
                    //self.toggleDropDown();

                    // Let's do an ajax call to follow the user.
                    EasySocial.ajax('site/controllers/profile/follow',
                        {
                            "id": self.options.id,
                            "type": 'user'
                        })
                        .done(function (button) {
                            console.log('override follow');
                            self.button().replaceWith(button);
                        });

                },
                "{followmob} click": function () {
                    // Toggle dropdown.
                    //self.toggleDropDown();

                    // Let's do an ajax call to follow the user.
                    EasySocial.ajax('site/controllers/profile/follow',
                        {
                            "id": self.options.id,
                            "type": 'user'
                        })
                        .done(function (button) {
                            console.log('override follow mob');
                            self.button().replaceWith(button);
                            console.log(self.button());
                            self.button().removeAttr('data-subscription-unfollow');
                            self.button().attr('data-subscription-unfollow-mobile', '');
                        });

                }

            }
        });

    module.resolve();

});

EasySocial.module('site/profile/edit', function ($) {

    var module = this;

    EasySocial.require()
        .script('validate', 'field', 'oauth/facebook')
        .done(function ($) {

            EasySocial.Controller(
                'Profile.Edit',
                {
                    defaultOptions: {
                        userid: null,

                        "{stepContent}": "[data-profile-edit-fields-content]",
                        "{stepItem}": "[data-profile-edit-fields-step]",

                        // Forms.
                        "{profileForm}": "[data-profile-fields-form]",

                        // Content for profile editing
                        "{profileContent}": "[data-profile-edit-fields]",

                        "{fieldItem}": "[data-profile-edit-fields-item]",

                        // Submit buttons
                        "{save}": "[data-profile-fields-save]",
                        "{settingSave}": "[data-profile-fields-settings-save]",
                        "{saveClose}": "[data-profile-fields-save-close]",
                        "{saveSettings}": "[data-profile-fields-save-settings]",

                        // Delete Profile
                        "{deleteProfile}": "[data-profile-edit-delete]",

                        '{taskInput}': 'input[name="task"]',
                        '{settingSaveInput}': 'input[name="setting_save"]'
                    }
                },
                function (self) {
                    return {

                        init: function () {
                            self.fieldItem().addController('EasySocial.Controller.Field.Base', {
                                userid: self.options.userid,
                                mode: 'edit'
                            });
                        },

                        errorFields: [],

                        // Support field throwing error internally
                        '{fieldItem} error': function (el, ev) {
                            self.triggerStepError(el);
                        },

                        // Support for field resolving error internally
                        '{fieldItem} clear': function (el, ev) {
                            self.clearStepError(el);
                        },

                        // Support validate.js throwing error externally
                        '{fieldItem} onError': function (el, ev) {
                            self.triggerStepError(el);
                        },

                        triggerStepError: function (el) {
                            var fieldid = el.data('id'),
                                stepid = el.parents(self.stepContent.selector).data('id');

                            if ($.inArray(fieldid, self.errorFields) < 0) {
                                self.errorFields.push(fieldid);
                            }

                            self.stepItem().filterBy('for', stepid).trigger('error');
                        },

                        clearStepError: function (el) {
                            var fieldid = el.data('id'),
                                stepid = el.parents(self.stepContent.selector).data('id');

                            self.errorFields = $.without(self.errorFields, fieldid);

                            self.stepItem().filterBy('for', stepid).trigger('clear');
                        },

                        "{stepItem} click": function (el, event) {
                            var id = $(el).data('for');
                            //console.log(id);
                            // Profile form should be hidden
                            self.profileContent().show();

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
                                self.settingSave().css('display', 'inline-block');
                            } else {
                                $('button.save-exit').css('display', 'none');
                                $('button.save-next').css('display', 'inline-block');
                                self.settingSave().css('display', 'inline-block');
                            }
                            if (id == 5 || id == 4) {
                                $('button.save-exit').css('display', 'none');
                                $('button.save-next').css('display', 'none');
                                //self.settingSave().css('display', 'none');
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

                        "{save} click": function (el, event) {
                            // Run some error checks here.
                            event.preventDefault();

                            $(el).addClass('btn-loading');

                            self.profileForm()
                                .validate()
                                .fail(function () {
                                    $(el).removeClass('btn-loading');
                                    EasySocial.dialog(
                                        {
                                            content: EasySocial.ajax('site/views/profile/showFormError')
                                        });
                                })
                                .done(function () {
                                    self.taskInput().val('save');
                                    self.profileForm().submit();
                                });

                            return false;
                        },
                        "{settingSave} click": function (el, event) {
                            // Run some error checks here.
                            event.preventDefault();

                            $(el).addClass('btn-loading');

                            self.profileForm()
                                .validate()
                                .fail(function () {
                                    $(el).removeClass('btn-loading');
                                    EasySocial.dialog(
                                        {
                                            content: EasySocial.ajax('site/views/profile/showFormError')
                                        });
                                })
                                .done(function () {
                                    self.settingSaveInput().val('custom_save_settings');
                                    self.taskInput().val('saveSettings');
                                    self.profileForm().submit();
                                });

                            return false;
                        },

                        "{saveClose} click": function (el, event) {
                            // Run some error checks here.
                            event.preventDefault();

                            self.stepItem().each(function () {
                                if ($(this).hasClass('active')) {
                                    curr_el = $(this).next().attr('data-for');
                                }
                            });

                            $(el).addClass('btn-loading');

                            self.profileForm()
                                .validate()
                                .fail(function () {
                                    $(el).removeClass('btn-loading');
                                    EasySocial.dialog(
                                        {
                                            content: EasySocial.ajax('site/views/profile/showFormError')
                                        });
                                })
                                .done(function () {
                                    if (curr_el == null) {
                                        self.taskInput().val('saveclose');
                                        self.profileForm().submit();
                                    } else {
                                        EasySocial.ajax('site/controllers/profilenew/saveNext',
                                            {
                                                "post": self.profileForm().serialize()
                                            })
                                            .done(function (html) {
                                                $(el).removeClass('btn-loading');
                                                $('#current_step_input').val(curr_el);
                                                self.stepItem().filterBy('for', curr_el).trigger('click');
                                                if (curr_el == $('#all_steps_input').val()) {
                                                    $('button.save-exit').css('display', 'inline-block');
                                                    $('button.save-next').css('display', 'none');
                                                }
                                            });
                                    }

                                });

                            return false;
                        },
                        "{saveSettings} click": function (el, event) {
                            // Run some error checks here.
                            event.preventDefault();

                            self.stepItem().each(function () {
                                if ($(this).hasClass('active')) {
                                    curr_el = $(this).next().attr('data-for');
                                }
                            });

                            $(el).addClass('btn-loading');

                            self.profileForm()
                                .validate()
                                .fail(function () {
                                    $(el).removeClass('btn-loading');
                                    EasySocial.dialog(
                                        {
                                            content: EasySocial.ajax('site/views/profile/showFormError')
                                        });
                                })
                                .done(function () {
                                    if (curr_el == null) {
                                        self.taskInput().val('saveSettings');
                                        self.profileForm().submit();
                                    } else {
                                        EasySocial.ajax('site/controllers/profilenew/saveSettings',
                                            {
                                                "post": self.profileForm().serialize()
                                            })
                                            .done(function (html) {
                                                console.log(html);
                                                $(el).removeClass('btn-loading');
                                                $('#current_step_input').val(curr_el);
                                                self.stepItem().filterBy('for', curr_el).trigger('click');
                                                //self.stepItem().next().click();
                                                if (curr_el == $('#all_steps_input').val()) {
                                                    $('button.save-exit').css('display', 'inline-block');
                                                    $('button.save-next').css('display', 'none');
                                                }
                                                var offs = $('.profile-wrapper').offset();
                                                $('html, body').stop().animate({scrollTop: offs.top - 100}, 500);
                                                if (curr_el == 5 || curr_el == 4) {
                                                    $('button.save-exit').css('display', 'none');
                                                    $('button.save-next').css('display', 'none');
                                                    //self.settingSave().css('display', 'none');
                                                }
                                                console.log(curr_el);
                                            });
                                    }

                                });

                            return false;
                        },
                        "{deleteProfile} click": function () {
                            EasySocial.dialog(
                                {
                                    content: EasySocial.ajax('site/views/profile/confirmDelete')
                                });
                        }
                    }
                });

            module.resolve();
        });
});

EasySocial.module("photos/editor", function ($) {

    var module = this;

    EasySocial.require()
        .done(function () {

            var Controller =
                EasySocial.Controller("Photos.Editor",
                    {
                        defaultOptions: {

                            view: {
                                uploadItem: "upload.item",
                                photoForm: "site/albums/photo.form"
                            },

                            "{titleField}": "[data-photo-title-field]",
                            "{captionField}": "[data-photo-caption-field]",
                            "{albumField}": "[data-album-selection]",

                            "{location}": "[data-photo-location]",
                            "{locationCaption}": "[data-photo-location-caption]",
                            "{addLocationButton}": "[data-photo-addLocation-button]",
                            "{date}": "[data-photo-date]",
                            "{dateCaption}": "[data-photo-date-caption]",
                            "{addDateCaption}": "[data-photo-adddate-button]",

                            "{locationWidget}": ".es-photo-location-form .es-locations",
                            "{latitude}": "[data-location-lat]",
                            "{longitude}": "[data-location-lng]",

                            "{dateDay}": "[name=date-day]",
                            "{dateMonth}": "[name=date-month]",
                            "{dateYear}": "[name=date-year]",

                            "{tags}": "[data-textboxlist-item]",

                            "{actionsMenu}": "[data-item-actions-menu]",
                            "{featureButton}": "[data-photo-feature-button]",
                            "{coverButton}": "[data-photo-cover-button]",

                            "{editButton}": "[data-photo-edit-button]",
                            "{editButtonLink}": "[data-photo-edit-button] > a",

                            "{cancelButton}": "[data-photo-cancel-button]",

                            "{doneButton}": "[data-photo-done-button-mod]",
                            "{doneButtonLink}": "[data-photo-done-button-mod] > a",

                            "{moveButton}": "[data-photo-move-button]",
                            "{deleteButton}": "[data-photo-delete-button]",

                            "{rotateLeftButton}": "[data-photo-rotateLeft-button]",
                            "{rotateRightButton}": "[data-photo-rotateRight-button]",

                            "{profileAvatarButton}": "[data-photo-profileAvatar-button]",
                            "{profileCoverButton}": "[data-photo-profileCover-button]"
                        }
                    },
                    function (self, opts, base) {
                        return {

                            init: function () {
                            },

                            data: function () {

                                return {
                                    id: self.photo.id,
                                    title: self.titleField().val(),
                                    caption: self.captionField().val(),
                                    album: self.albumField().val(),
                                    tags: self.tags().map(function () {
                                        return $(this).find(":input").val();
                                    })
                                        .get()
                                        .join(",")
                                }
                            },

                            save: function () {

                                var data = self.data();

                                //self.clearMessage();

                                var task =
                                    EasySocial.ajax(
                                        "site/controllers/photoedit/update",
                                        data
                                    )
                                        .done(function (photo) {
                                            self.photo.setLayout("item");
                                        })
                                        .fail(function () {

                                            self.setMessage(message, "error");
                                        })
                                        .progress(function (message, type) {

                                            if (type == "success") {
                                                //self.setMessage(message);
                                            }
                                        });

                                self.trigger("photoSave", [task, self]);

                                return task;
                            },

                            enable: function () {

                                self.photo.setLayout("form");

                                // If we are running under an album frame
                                var album = self.photo.album;

                                if (album) {
                                    base.addClass("active");
                                }

                                self.trigger("enabled", [self]);
                            },

                            disable: function () {

                                self.photo.setLayout("item");

                                // If we are running under an album frame
                                var album = self.photo.album;

                                if (album) {
                                    base.removeClass("active");
                                }

                                self.trigger("disabled", [self]);
                            },

                            imageLoader: {},

                            setImage: function (type) {

                                var image = self.photo.image(),
                                    imageCss = self.photo.imageCss(),
                                    imageSource = image.data(type + "Src"),
                                    imageLoader = self.imageLoader[imageSource];

                                // If this image hasn't been loaded before
                                if (!imageLoader) {

                                    // Create an image loader
                                    imageLoader = $.Image.get(imageSource);

                                    // Store a reference of the loader within the element
                                    self.imageLoader[imageSource] = imageLoader;
                                }

                                imageLoader
                                    .done(function () {
                                        image.attr("src", imageSource);
                                        imageCss.css({
                                            backgroundImage: $.cssUrl(imageSource)
                                        });
                                    });

                                return imageLoader;
                            },

                            "{featureButton} click": function (featureButton, event) {
                                console.log('feature btn clicked');

                                event.stopPropagation();

                                var isPopup =
                                    self.photo.element.parents("[data-photo-popup]").length > 0 ||
                                    self.photo.element.parents("[data-photo-browser-content]").length > 0;

                                $(featureButton).toggleClass("featured");

                                var isFeatured = $(featureButton).hasClass("featured");

                                //!isPopup && self.setImage((isFeatured) ? "thumbnail" : "featured");

                                // Perform an ajax call to mark the photo as featured
                                var task =
                                    EasySocial.ajax(
                                        "site/controllers/photos/feature", {
                                            id: self.photo.id
                                        }
                                    )
                                        .done(function (message, isFeatured) {

                                            // If this is not under album, show a message
                                            // if (!self.photo.album) {
                                            // 	self.clearMessage();
                                            // 	self.setMessage( message );
                                            // }
                                            console.log(isFeatured);
                                            if (isFeatured) {
                                                //$(featureButton).toggleClass("featured", !isFeatured);
                                                $(featureButton).find('a').text('Un-feature Photo');
                                            } else {
                                                //$(featureButton).toggleClass("featured", !isFeatured);
                                                $(featureButton).find('a').text('Feature Photo');
                                            }

                                            console.log(featureButton);
                                            //featureButton.toggleClass('btn-es-primary', isFeatured);
                                        })
                                        .fail(function () {

                                            //base.removeClass("featured");
                                            //!isPopup && self.setImage((!isFeatured) ? "thumbnail" : "featured");
                                        });

                                //self.trigger("photoFeature", [task, self.photo, !isFeatured]);
                            },

                            "{coverButton} click": function () {

                                var album = self.photo.album;

                                // When viewing photos invidually,
                                // there is no reference to album,
                                // the button itself should't be visible anyway.
                                if (!album)
                                    return;

                                // If the editor is available, set cover.
                                album.editor && album.editor.setCover(self.photo.id);
                            },

                            "{dateDay} keyup": function () {
                                self.updateDate();
                            },

                            "{dateMonth} change": function () {
                                self.updateDate();
                            },

                            "{dateYear} keyup": function () {
                                self.updateDate();
                            },

                            updateDate: function () {

                                setTimeout(function () {
                                    self.date().addClass("has-data");
                                    var dateCaption = self.dateDay().val() + ' ' + $.trim(self.dateMonth().find(":selected").text() + ' ' + self.dateYear().val());
                                    self.dateCaption().html(dateCaption);
                                }, 1);
                            },

                            formatDate: function () {
                                var day = self.dateDay().val() || self.dateDay().data('date-default'),
                                    month = self.dateMonth().val() || self.dateMonth().data('date-default'),
                                    year = self.dateYear().val() || self.dateYear().data('date-default');

                                return year + '-' + month + '-' + day;
                            },

                            "{locationWidget} locationChange": function (el, event, location) {

                                var address = location.address || location.fulladdress || location.formatted_address;
                                self.locationCaption().html(address);
                                self.location().addClass("has-data");
                            },

                            rotate: function (angle) {

                                var photo = self.photo;

                                self.rotateLeftButton().disabled(true);
                                self.rotateRightButton().disabled(true);

                                // Show loading indicator
                                photo.content().addClass("loading");

                                var task =
                                    EasySocial.ajax(
                                        "site/controllers/photos/rotate",
                                        {
                                            id: photo.id,
                                            angle: angle
                                        }
                                    )
                                        .done(function (photoObj) {

                                            var url;

                                            if (self.photo.album) {
                                                url = photoObj.sizes.thumbnail.url;
                                            } else {
                                                url = photoObj.sizes.large.url;
                                            }

                                            // So that it actual loads a new one
                                            url += "?" + $.uid();

                                            // Replace image url
                                            photo.image()
                                                .attr("src", url);

                                            photo.imageCss()
                                                .css({
                                                    backgroundImage: $.cssUrl(url)
                                                });

                                            base
                                                .addTransitionClass("rotating-ready", 150)
                                                .removeClass("rotating-right rotating-left");
                                        })
                                        .fail(function (message, type) {

                                            self.setMessage(message, type);
                                        })
                                        .always(function () {

                                            photo.content().removeClass("loading");
                                            self.rotateLeftButton().disabled(false);
                                            self.rotateRightButton().disabled(false);
                                        });

                                self.trigger("photoRotate", [task, angle, photo])
                            },

                            "{rotateRightButton} click": function () {

                                base.addClass("rotating-right");
                                self.rotate(90);
                            },

                            "{rotateLeftButton} click": function () {

                                base.addClass("rotating-left");
                                self.rotate(-90);
                            },

                            "{moveButton} click": function () {

                                var photo = self.photo;

                                var dialog =
                                    EasySocial.dialog({
                                        content: EasySocial.ajax(
                                            "site/views/photos/moveToAnotherAlbum",
                                            {
                                                id: photo.id
                                            }
                                        ),
                                        bindings: {
                                            "{moveButton} click": function () {

                                                var targetAlbumId = this.albumSelection().val();

                                                var task =
                                                    EasySocial.ajax(
                                                        "site/controllers/photos/move",
                                                        {
                                                            id: photo.id,
                                                            albumId: targetAlbumId
                                                        }
                                                    )
                                                        .always(function () {
                                                            dialog.close();
                                                        });

                                                self.trigger("photoMove", [task, photo, targetAlbumId]);
                                            }
                                        }
                                    });
                            },

                            "{deleteButton} click": function () {

                                var photo = self.photo;

                                EasySocial.dialog({
                                    content: EasySocial.ajax(
                                        "site/views/photos/confirmDelete",
                                        {
                                            id: photo.id
                                        }
                                    ),
                                    bindings: {
                                        "{deleteButton} click": function (deleteButton) {

                                            var dialog = this.parent;

                                            deleteButton.disabled(true);

                                            var task =
                                                EasySocial.ajax(
                                                    "site/controllers/photos/delete",
                                                    {
                                                        id: photo.id
                                                    }
                                                )
                                                    .always(function () {
                                                        dialog.close();
                                                    });

                                            self.trigger("photoDelete", [task, photo]);
                                        }
                                    }
                                });
                            },

                            "{editButton} click": function () {

                                // Change viewer layout
                                self.photo.setLayout("form");

                                // Change address bar url
                                self.editButtonLink().route();
                            },

                            "{editButtonLink} click": function (editButtonLink, event) {

                                event.preventDefault();
                            },

                            "{cancelButton} click": function () {
                                // Change album layout
                                self.photo.setLayout("item");

                                // Change address bar url
                                self.doneButtonLink().route();
                            },

                            "{doneButton} click": function () {

                                self.save()
                                    .done(function () {

                                        // Change album layout
                                        self.photo.setLayout("item");

                                        // Change address bar url
                                        self.doneButtonLink().route();
                                    })
                                    .fail(function () {

                                    });
                            },

                            "{doneButtonLink} click": function (doneButtonLink, event) {
                                event.preventDefault();
                            },

                            "{profileAvatarButton} click": function () {
                                EasySocial.photos.createAvatar(self.photo.id);
                            }
                        }
                    });

            module.resolve(Controller);

        });
});

EasySocial.module('photos/form', function ($) {

    var module = this;

    EasySocial.require()
        .script('site/friends/suggest')
        .library('mentions')
        .done(function ($) {

            EasySocial.Controller('Photos.Form', {
                defaultOptions: {
                    // Mentions
                    "{mentions}": "[data-mentions]"
                }
            }, function (self, opts, base) {
                return {

                    init: function () {
                        self.initMentions();
                    },

                    initMentions: function () {

                        self.mentions()
                            .addController("EasySocial.Controller.Friends.Suggest", {
                                "showNonFriend": false,
                                "includeSelf": true,
                                "name": "tags[]",
                                "exclusion": opts.tagsExclusion
                            });
                    }

                }
            });

            module.resolve();
        });

});

//album item page
EasySocial.module("albums/item", function ($) {

    var module = this;

    // Non-essential dependencies
    EasySocial.require()
        .script("albums/editor")
        .done();

    // Essential dependencies
    EasySocial.require()
        .library(
            "masonry"
        )
        .done(function () {

            EasySocial.Controller("Albums.Item",
                {
                    hostname: "album",

                    defaultOptions: {

                        tilesPerRow: 4,
                        editable: false,
                        multipleSelection: false,

                        "{header}": "[data-album-header]",
                        "{content}": "[data-album-content]",
                        "{footer}": "[data-album-footer]",

                        "{info}": "[data-album-info]",

                        "{title}": "[data-album-title]",
                        "{caption}": "[data-album-caption]",
                        "{location}": "[data-album-location]",
                        "{date}": "[data-album-date]",
                        "{cover}": "[data-album-cover]",
                        "{favouriteButton}": "[data-album-favourite-button]",

                        "{photoItemGroup}": "[data-photo-item-group]",
                        "{photoItem}": "[data-photo-item]",
                        "{photoImage}": "[data-photo-image]",
                        "{photoImageCss}": "[data-photo-image-css]",
                        "{featuredItem}": "[data-photo-item].featured",
                        "{featuredImage}": "[data-photo-item].featured [data-photo-image]",
                        "{featuredImageCss}": "[data-photo-item].featured [data-photo-image-css]",
                        "{uploadItem}": "[data-photo-upload-item]",

                        "{moreButton}": "[data-album-more-button]",
                        "{viewButton}": "[data-album-view-button]",

                        "{share}": "[data-repost-action]",
                        "{likes}": "[data-likes-action]",
                        "{likeContent}": "[data-likes-content]",
                        "{repostContent}": "[data-repost-content]",
                        "{counterBar}": "[data-stream-counter]"
                    }
                },
                function (self, opts, base) {
                    return {

                        init: function () {
                            self.id = base.data("album-id");

                            self.nextStart = base.data("album-nextstart") || -1;

                            // If this viewer is editable, load & implement editor.
                            if (self.options.editable) {
                                EasySocial.module("albums/editor")
                                    .done(function (EditorController) {
                                        self.editor = self.addPlugin("editor", EditorController);
                                    });
                            }

                            // Set layout when window is resized
                            self.setLayout();

                            // Show load more button
                            // Quick monkey fix for load more button showing
                            // on the right corner before layout is set.
                            self.moreButton().show();

                            // Attach existing photo items as subscribers
                            self.addSubscriber(
                                self.photoItem()
                                    .controllers("EasySocial.Controller.Photos.Item")
                            );
                        },

                        "{window} resize": $.debounce(function () {
                            self.setLayout();
                        }, 250),

                        currentLayout: function () {

                            return base.data("albumLayout");
                        },

                        setLayout_: $.debounce(function () {

                            self.setLayout();
                        }, 100),

                        setLayout: function (layoutName) {

                            var photoItemGroup = self.photoItemGroup(),
                                // Build layout state
                                currentLayout = self.currentLayout(),
                                layoutName = layoutName || currentLayout,
                                seed = self.setLayout.seed,
                                intact = (seed == photoItemGroup.width() && currentLayout == layoutName)
                            hasPhotoItem = self.photoItem().length > 0,
                                hasUploadItem = self.uploadItem().length > 0,
                                hasItem = hasPhotoItem || hasUploadItem,
                                masonry = $.data(photoItemGroup[0], "masonry"),
                                // Put them in an object
                                layout = {
                                    currentLayout: currentLayout,
                                    seed: seed,
                                    intact: intact,
                                    hasPhotoItem: hasPhotoItem,
                                    hasUploadItem: hasUploadItem,
                                    hasItem: hasItem,
                                    masonry: masonry
                                };

                            // Determine if we need to switch layout
                            if (!intact) {

                                // Switch layout
                                base
                                    .data("albumLayout", layoutName)
                                    .switchClass("layout-" + layoutName);

                                // Switch all photo item's layout
                                /*self.photoItem()
                                 .switchClass("layout-" + layoutName);*/

                                // Reset viewport width to force layout redraw
                                self.setLayout.seed = layout.seed = null;

                                // Only trigger layout change when layout has really changed.
                                if (currentLayout !== layoutName) {
                                    // Trigger layout change event
                                    self.trigger("layoutChange", [layoutName, layout]);
                                }
                            }

                            // Show upload hint when content is empty
                            base.toggleClass("has-photos", hasItem);

                            // If there's no item from the list
                            if (!hasItem) {

                                // If this is coming from deleting the last item
                                // from the list, we need to keep the container
                                // on zero height.
                                photoItemGroup.css("opacity", 1);
                            }

                            // Execute layout handler
                            var layoutHandler = "set" + $.String.capitalize(layoutName) + "Layout";
                            self[layoutHandler](layout);

                            // Save current layout
                            self.setLayout.seed = photoItemGroup.width();
                        },

                        setItemLayout: function (layout) {

                            //self.photoItem().attr("data-es-photo-disabled", 0);

                            // Get photoItemGroup
                            var tilesPerRow = 4,
                                photoItemGroup = self.photoItemGroup(),
                                viewportWidth = base.width(),
                                containerWidth = Math.floor(viewportWidth / tilesPerRow) * tilesPerRow;

                            self.photoItemGroup()
                                .width(containerWidth);

                            if (layout.masonry) {

                                photoItemGroup.masonry("reloadItems").masonry("layout");
                            } else {

                                photoItemGroup
                                    .masonry({
                                        columnWidth: ".es-photo-item.grid-sizer",
                                        itemSelector: self.photoItem.selector + ", " + self.uploadItem.selector,
                                        isOriginLeft: !self.options.rtl
                                    });
                            }
                        },

                        setFormLayout: function (layout) {

                            self.photoItem().attr("data-es-photo-disabled", 1);

                            // Destroy masonry if we are on form layout
                            layout.masonry && layout.masonry.destroy();

                            // Reset layout
                            self.clearLayout();
                        },

                        setDialogLayout: function () {

                            self.photoItem().attr("data-es-photo-disabled", 1);

                            // Destroy masonry if we are on form layout
                            layout.masonry && layout.masonry.destroy();

                            // Reset layout
                            self.clearLayout();
                        },

                        setThumbnailLayout: function () {

                        },

                        setRowLayout: function () {

                            self.photoItem().attr("data-es-photo-disabled", 0);

                            self.clearLayout();
                        },

                        clearLayout: function () {

                            self.photoItemGroup()
                                .addClass("no-transition");

                            self.photoItem
                                .css().remove();

                            self.photoImage
                                .css().remove();

                            self.photoImageCss
                                .css().remove();

                            self.featuredItem
                                .css().remove();

                            self.featuredImage
                                .css().remove();

                            self.featuredImageCss
                                .css().remove();

                            self.uploadItem
                                .css().remove();

                            self.setLayout.seed = null;
                        },

                        getSelectedItems: function () {

                            var selectedPhotos = self.photoItem(".selected");

                            var data = [];

                            selectedPhotos.each(function (i, photo) {
                                data.push($(photo).controller("EasySocial.Controller.Photos.Item").data());
                            });

                            return data;
                        },

                        "{photoItem} init.photos.item": function (el, event, photoItem) {

                            self.addSubscriber(photoItem);
                        },

                        "{photoItem} destroyed": function () {

                            self.setLayout();
                        },

                        "{photoItem} activate": function (photoItem, event, photo) {

                            // Activate is a non-standard IE event,
                            // if photo is undefined then it is coming
                            // from the browser not photo item controller.
                            if (!photo)
                                return;

                            var currentLayout = self.currentLayout();

                            switch (currentLayout) {

                                case "item":
                                case "row":

                                    // Show loading indicator
                                    photoItem.addClass("loading");

                                    // If browser is available, ask browser
                                    // to load photo view via ajax.
                                    if (self.browser) {

                                        // View photo
                                        self.browser
                                            .open("photo", photo.id)
                                            .always(function () {

                                                // Remove loading indicator
                                                photoItem.removeClass("loading");
                                            });

                                        // Change address bar url
                                        photo.imageLink().route();

                                        // If browser is not available,
                                        // just load the photo view normally.
                                    } else {
                                        window.location = photo.imageLink().attr("href");
                                    }
                                    break;

                                case "form":
                                    // photo.editor && photo.editor.enable();
                                    break;

                                case "dialog":

                                    var selectedPhotos = self.photoItem(".selected");

                                    if (!self.options.multipleSelection) {

                                        var selected = photoItem.hasClass("selected");

                                        // In case it came from multiple selection
                                        selectedPhotos.removeClass("selected");

                                        photoItem.toggleClass("selected", !selected);

                                    } else {

                                        photoItem.toggleClass("selected");
                                    }
                                    break;
                            }
                        },

                        "{photoItem} photoFeature": function (el, event, task, photo, featured) {

                            // Set layout to accomodate double size photo item
                            self.setLayout();

                            // When a photo fail to be featured, it shrinks
                            task
                                .fail(function () {

                                    // So we're resetting layout again
                                    self.setLayout();
                                });
                        },

                        "{photoItem} photoMove": function (el, event, task, photo, targetAlbumId) {

                            self.clearMessage();

                            task
                                .done(function () {

                                    // Remove photo
                                    photo.element.remove();

                                    // Set layout
                                    self.setLayout();

                                    // If there are no more photos, remove cover
                                    if (self.photoItem().length < 1) {
                                        self.trigger("coverRemove", [self]);
                                    }
                                })
                                .fail(function (message, type) {
                                    self.setMessage(message, type);
                                });
                        },

                        "{photoItem} photoDelete": function (el, event, task, photo) {

                            self.clearMessage();

                            task
                                .done(function () {

                                    // Remove photo
                                    photo.element.remove();

                                    // Set layout
                                    self.setLayout();

                                    // If there are no more photos, remove cover
                                    if (self.photoItem().length < 1) {
                                        self.trigger("coverRemove", [self]);
                                    }
                                })
                                .fail(function (message, type) {
                                    self.setMessage(message, type);
                                });
                        },

                        // These are coming from album editor
                        "{self} albumSave": function (el, event, task) {

                            task.done(function (album) {
                                self.id = album.id;
                            });
                        },

                        "{self} coverChange": function (el, event, photo, album) {

                            self.cover()
                                .css("backgroundImage", $.cssUrl(photo.sizes.thumbnail.url));
                        },

                        "{self} coverRemove": function () {

                            self.cover()
                                .css("backgroundImage", "");
                        },

                        "{viewButton} click": function (viewButton, event) {
                            if (self.browser) {
                                event.preventDefault();
                                base.addClass("loading");
                                self.browser.open("Album", self.id);
                            }
                        },

                        "{moreButton} click": function (moreButton) {

                            // If nextStart is -1, means no more photos
                            if (self.nextStart == -1) {
                                return;
                            }

                            if (moreButton.disabled()) {
                                return;
                            }

                            // Disable this button
                            moreButton.toggleClass('loading');
                            moreButton.disabled(true);

                            // Set the button into loading state
                            // moreButton.addClass('loading');

                            // Get the new photos content
                            EasySocial.ajax(
                                "site/controllers/albums/loadMore",
                                {
                                    albumId: self.id,
                                    start: self.nextStart,
                                    layout: self.currentLayout()
                                })
                                .done(function (htmls, nextStart) {

                                    self.nextStart = nextStart;

                                    var photoItemGroup = self.photoItemGroup();

                                    $.each(htmls, function (i, html) {
                                        $.buildHTML(html).appendTo(photoItemGroup);
                                    });

                                    moreButton.toggleClass('loading');

                                    // If there is no more photos to load, hide the button
                                    if (nextStart < 0) {
                                        moreButton.hide();
                                    }

                                    self.setLayout();
                                })
                                .always(function () {

                                    moreButton.disabled(false);
                                });
                        },

                        "{share} create": function (el, event, itemHTML) {
                            self.counterBar().removeClass('hide');
                        },

                        "{likes} onLiked": function (el, event, data) {

                            //need to make the data-stream-counter visible
                            self.counterBar().removeClass('hide');
                        },

                        "{likes} onUnliked": function (el, event, data) {

                            var isLikeHide = self.likeContent().hasClass('hide');
                            var isRepostHide = self.repostContent().hasClass('hide');

                            if (isLikeHide && isRepostHide) {
                                self.counterBar().addClass('hide');
                            }
                        },

                        "{favouriteButton} click": function () {
                            EasySocial.ajax(
                                "site/controllers/albums/favourite",
                                {
                                    id: self.id
                                }
                            )
                                .done(function (favourite) {

                                    self.favouriteButton().toggleClass("is-fav btn-es-primary");
                                });
                        }

                    }
                });

            module.resolve();
        });
});

EasySocial.module('site/events/guestState', function ($) {
    var module = this;

    EasySocial
        .require()
        .language('COM_EASYSOCIAL_EVENTS_GUEST_PENDING')
        .done(function ($) {

            EasySocial.Controller('Events.GuestState', {
                defaultOptions: {
                    id: null,

                    allowMaybe: 1,

                    allowNotGoingGuest: 1,

                    hidetext: 1,

                    refresh: false,

                    '{guestAction}': '[data-guest-action]',

                    '{guestState}': '[data-guest-state]',

                    '{request}': '[data-guest-request]',

                    '{withdraw}': '[data-guest-withdraw]',

                    '{respond}': '[data-guest-respond]'
                }
            }, function (self) {
                return {
                    init: function () {
                        self.options.id = self.element.data('id');

                        self.options.allowMaybe = self.element.data('allowmaybe');
                        self.options.allowNotGoingGuest = self.element.data('allownotgoingguest');
                        self.options.hidetext = self.element.data('hidetext');

                        // Determines if the page requires a refresh
                        // If this is a item page, then the element will have a data-refresh flag
                        // If this is a listing page, then no refresh is required
                        self.options.refresh = self.element.is('[data-refresh]');

                        // self.initPopbox();
                    },

                    showError: function (msg) {
                        EasySocial.dialog({
                            content: msg
                        });
                    },

                    stateClasses: {
                        'going': 'btn-es-orange',
                        'maybe': 'btn-es-orange',
                        'notgoing': 'btn-es-danger'
                    },

                    '{guestAction} click': function (el) {
                        // Depending on the action

                        var action = el.data('guestAction');

                        if (action === 'state') {
                            var state = el.data('guestState');

                            self.guestAction().removeClass('btn-es-success btn-es-info btn-es-danger btn-es-orange');

                            el.addClass(self.stateClasses[state]);

                            if (state === 'notgoing' && !self.options.allowNotGoingGuest) {
                                EasySocial.dialog({
                                    content: EasySocial.ajax('site/views/events/notGoingDialog', {
                                        id: self.options.id
                                    }),
                                    bindings: {
                                        '{closeButton} click': function () {
                                            EasySocial.ajax('site/views/events/refreshGuestState', {
                                                id: self.options.id,
                                                hidetext: self.options.hidetext
                                            }).done(function (html) {
                                                self.element.html(html);

                                                EasySocial.dialog().close();
                                            });
                                        },
                                        '{submitButton} click': function () {
                                            self.response('notgoing')
                                                .done(function () {
                                                    if (self.options.refresh) {
                                                        return location.reload();
                                                    }

                                                    EasySocial.ajax('site/views/events/refreshGuestState', {
                                                        id: self.options.id,
                                                        hidetext: self.options.hidetext
                                                    }).done(function (html) {
                                                        self.element.html(html);

                                                        EasySocial.dialog().close();
                                                    });
                                                });
                                        }
                                    }
                                });
                            } else {
                                self.response(state)
                                    .done(function () {
                                        if (self.options.refresh) {
                                            return location.reload();
                                        }
                                    })
                                    .fail(function (error) {
                                        el.removeClass(self.stateClasses[action]);

                                        self.showError(error.message);
                                    });
                            }
                        }

                        if (action === 'request') {
                            EasySocial.dialog({
                                content: EasySocial.ajax('site/views/events/requestDialog', {
                                    id: self.options.id
                                }),
                                bindings: {
                                    '{submitButton} click': function () {
                                        el
                                            .attr('data-guest-action', 'withdraw')
                                            .data('guestAction', 'withdraw')
                                            .removeAttr('data-guest-request')
                                            .attr('data-guest-withdraw', '')
                                            .text($.language('COM_EASYSOCIAL_EVENTS_GUEST_PENDING'));

                                        self.response(action);

                                        EasySocial.dialog().close();
                                    }
                                }
                            });
                        }

                        if (action === 'withdraw') {
                            EasySocial.dialog({
                                content: EasySocial.ajax('site/views/events/withdrawDialog', {
                                    id: self.options.id
                                }),
                                bindings: {
                                    '{submitButton} click': function () {
                                        self.response('withdraw')
                                            .done(function () {
                                                EasySocial.ajax('site/views/events/refreshGuestState', {
                                                    id: self.options.id,
                                                    hidetext: self.options.hidetext
                                                }).done(function (html) {
                                                    self.element.html(html);

                                                    EasySocial.dialog().close();
                                                });
                                            });
                                    }
                                }
                            });
                        }

                        if (action === 'attend') {
                            var state = el.data('guestState');
                            self.guestAction().removeClass('btn-es-success btn-es-info btn-es-danger btn-es-orange');
                            el.addClass(self.stateClasses[state]);

                            self.response('going').done(function () {
                                EasySocial.ajax('site/views/events/refreshGuestState', {
                                    id: self.options.id,
                                    hidetext: self.options.hidetext
                                }).done(function (html) {
                                    if (self.options.refresh) {
                                        return location.reload();
                                    }

                                    if (html !== undefined) {
                                        self.element.html(html);

                                        EasySocial.dialog().close();
                                    }
                                });
                            });
                        }
                    },

                    response: function (action) {
                        return EasySocial.ajax('site/controllers/events/guestResponse', {
                            id: self.options.id,
                            state: action
                        });
                    }
                }
            });

            module.resolve();
        });
});

EasySocial.module('customEMod', function ($) {
    var module = this;
    EasySocial.Controller('CustomEMod', {
            defaultOptions: {
                "{block}": "[data-blocks-link]",
                "{report}": "[data-reports-link]",
            }
        },
        function (self) {
            return {

                init: function () {
                    console.log('custom mod loaded');
                },

                "{block} click": function (el, event) {
                    var target = $(el).data('target');
                    console.log(target);

                    EasySocial.dialog({

                        content: EasySocial.ajax(
                            "site/views/blocks/confirmBlock",
                            {
                                "target": target
                            }),

                        selectors: {
                            "{reason}": "[data-block-reason]",
                            "{blockButton}": "[data-block-button]",
                            "{cancelButton}": "[data-cancel-button]"
                        },

                        bindings: {

                            "{blockButton} click": function () {

                                var reason = this.reason().val();

                                EasySocial.dialog({
                                    content: EasySocial.ajax(
                                        "site/controllers/blocks/store",
                                        {
                                            "target": target,
                                            "reason": reason
                                        })
                                });
                            },

                            "{cancelButton} click": function () {
                                EasySocial.dialog().close();
                            }
                        }
                    });
                },
                "{report} click": function (el, event) {
                    var button = $(el);
                    var props = "url,extension,uid,type,object,title,description".split(",");
                    var data = {};

                    $.each(props, function(i, prop){
                        data[prop] = button.data(prop);
                    });

                    EasySocial.dialog({

                        content: EasySocial.ajax("site/views/reports/confirmReport", {
                            title: data.title,
                            description: data.description
                        }),
                        selectors: {
                            "{message}": "[data-reports-message]",
                            "{reportButton}": "[data-report-button]",
                            "{cancelButton}": "[data-cancel-button]"
                        },
                        bindings: {

                            "{reportButton} click": function() {

                                var message	= this.message().val();

                                EasySocial.dialog({
                                    content: EasySocial.ajax("site/controllers/reports/store", {
                                        url      : data.url,
                                        extension: data.extension,
                                        uid      : data.uid,
                                        type     : data.type,
                                        title    : data.object,
                                        message  : message
                                    })
                                });
                            },

                            "{cancelButton} click": function() {
                                EasySocial.dialog().close();
                            }
                        }
                    });
                }
            }
        });

    module.resolve();
});
// used for Event Feature/Unfeature functionality, as
// by default EasySocial doesn't provide this in the current version.
EasySocial.module('eventExtrs', function ($) {
    var module = this;
    EasySocial.Controller('eventExtrs', {
            defaultOptions: {
                "{feature}": "[data-event-feature]",
                "{unfeature}": "[data-event-unfeature]",
            }
        },
        function (self) {
            return {
                init: function () {
                    console.log('eventExtrs loaded');
                    self.options.id = self.element.data('id');
                },
                "{feature} click": function (el, event) {
                    EasySocial.dialog({
                        content: EasySocial.ajax("apps/user/projects/controllers/events/confirmFeature", {
                            "id": self.options.id
                        }),
                        bindings:
                            {
                                "{featuredButton} click": function ()
                                {
                                    EasySocial.dialog({
                                        content: '<div class="fd-loading"><span>Loading</span></div>'
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/events/feature', {
                                        id: self.options.id,
                                        "feature": true
                                    }).done(function(content) {
                                        EasySocial.dialog().close();
                                        window.location.reload();
                                    }).fail(function(error) {
                                        EasySocial.dialog({
                                            content: error
                                        });
                                    });
                                }
                            }
                    });
                },
                "{unfeature} click": function (el, event) {
                    EasySocial.dialog({
                        content: EasySocial.ajax("apps/user/projects/controllers/events/confirmFeature", {
                            "id": self.options.id
                        }),
                        bindings:
                            {
                                "{featuredButton} click": function ()
                                {
                                    EasySocial.dialog({
                                        content: '<div class="fd-loading"><span>Loading</span></div>'
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/events/feature', {
                                        id: self.options.id,
                                        "feature": false
                                    }).done(function(content) {
                                        EasySocial.dialog().close();
                                        window.location.reload();
                                    }).fail(function(error) {
                                        EasySocial.dialog({
                                            content: error
                                        });
                                    });
                                }
                            }
                    });
                }
            }
        });

    module.resolve();
});
// used for Videos Feature/Unfeature functionality, as
// it only allows site admin to feature/ufeature video
EasySocial.module('videosExtrsMod', function ($) {
    var module = this;
    EasySocial.Controller('videosExtrsMod', {
            defaultOptions: {
                "{feature}": "[data-cvideo-feature]",
                "{unfeature}": "[data-cvideo-unfeature]",
            }
        },
        function (self) {
            return {
                init: function () {
                    console.log('videosExtrs loaded');
                    self.options.id = self.element.data('id');
                },
                "{feature} click": function (el, event) {
                    EasySocial.dialog({
                        content: EasySocial.ajax("apps/user/projects/controllers/videos/confirmFeature", {
                            "id": self.options.id
                        }),
                        bindings:
                            {
                                "{featuredButton} click": function ()
                                {
                                    EasySocial.dialog({
                                        content: '<div class="fd-loading"><span>Loading</span></div>'
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/videos/feature', {
                                        id: self.options.id,
                                        "feature": true
                                    }).done(function(content) {
                                        EasySocial.dialog().close();
                                        window.location.reload();
                                    }).fail(function(error) {
                                        EasySocial.dialog({
                                            content: error
                                        });
                                    });
                                }
                            }
                    });
                },
                "{unfeature} click": function (el, event) {
                    EasySocial.dialog({
                        content: EasySocial.ajax("apps/user/projects/controllers/videos/confirmFeature", {
                            "id": self.options.id
                        }),
                        bindings:
                            {
                                "{featuredButton} click": function ()
                                {
                                    EasySocial.dialog({
                                        content: '<div class="fd-loading"><span>Loading</span></div>'
                                    });

                                    EasySocial.ajax('apps/user/projects/controllers/videos/feature', {
                                        id: self.options.id,
                                        "feature": false
                                    }).done(function(content) {
                                        EasySocial.dialog().close();
                                        window.location.reload();
                                    }).fail(function(error) {
                                        EasySocial.dialog({
                                            content: error
                                        });
                                    });
                                }
                            }
                    });
                }
            }
        });

    module.resolve();
});

