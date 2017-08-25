EasySocial.require().script("admin").done();


EasySocial.ready(function($){
    // Fix the header for mobile view
    $('.container-nav').appendTo($('.header'));

    // Get the current version of EasySocial
    var versionSection = $('[data-version-status]');

    if (versionSection.length > 0) {
        
        // Get the current version of EasySocial
        $.ajax({
            url: "<?php echo SOCIAL_SERVICE_VERSION;?>",
            jsonp: "callback",
            dataType: "jsonp",
            data: {
                "apikey": "<?php echo $this->config->get('general.key');?>"
            },
            success: function(data) {

                // Update the latest version
                $('[data-latest-version]').html(data.version);

                var versionSection = $('[data-version-status]');
                var currentVersion = $('[data-current-version]');
                var installedSection = $('[data-version-installed]');

                var version = {
                    "latest": data.version,
                    "installed": "<?php echo $version;?>"
                };

                var outdated = version.installed < version.latest;

                if (versionSection.length > 0) {
                    currentVersion.html(version.installed);
                    installedSection.removeClass('hide');
                    versionSection.removeClass('is-loading');

                    // Update version checking
                    if (outdated) {
                        versionSection.addClass('is-outdated');
                    } else {
                        versionSection.addClass('is-updated');
                    }

                    // Retrieve news
                    EasySocial
                        .ajax('admin/views/easysocial/getMetaData')
                        .done(function(news) {

                            var placeholder = $("[data-widget-news-placeholder]");
                            var appNews = $("[data-widget-app-news] > [data-widget-news-items]");

                            // Append the app news
                            appNews.append(news);

                            // Hide placeholder
                            placeholder.remove();
                        });

                }

                // Get app news
            }
        });
    }

    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('.header').addClass('header-stick');
        } else if ($(this).scrollTop() < 50) {
            $('.header').removeClass('header-stick');
        }
    });

    $('.nav-sidebar-toggle').click(function(){
        $('html').toggleClass('show-easysocial-sidebar');
        $('.subhead-collapse').removeClass('in').css('height', 0);
    });

    $('.nav-subhead-toggle').click(function(){
        $('html').removeClass('show-easysocial-sidebar');
        $('.subhead-collapse').toggleClass('in').css('height', 'auto');
    });
});
