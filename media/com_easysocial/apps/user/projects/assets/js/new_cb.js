



//############################################//
/* callbacks */
//############################################//

function hapSetupDone(instance, instanceName) {
    /* called when component is ready to use public API. Returns player instance, sound id. */
    //console.log('hapSetupDone: ', instanceName);

    var isIE = HAPUtils.isIE(), settings = instance.getSettings();

    if (instanceName == 'default' || instanceName == 'default_light' || instanceName == 'default_multiple' || instanceName == 'default_multiple2' || instanceName == 'artwork') {
        //playlist selector
        instance.find(".hap-select").change(function () {
            instance.loadPlaylist($(this).val());
        });
    }
    if (instanceName == 'circle_slideshow' || instanceName == 'bg_slideshow') {
        initSlideshow();
    }
    if (instanceName == 'fixed_bottom6_continous') {
        //instance.show('slow');
    } else if (instanceName == 'fixed_top' || instanceName == 'fixed_bottom' || instanceName == 'fixed_bottom6' || instanceName == 'fixed_bottom7' || instanceName == 'fixed_bottom8' || instanceName == 'fixed_bottom9') {

        instance.find('.hap-player-toggle').on('click', function (e) {
            e.preventDefault();
            togglePlayer(jQuery(this));
        }).show();
        instance.find('.hap-playlist-toggle').on('click', function (e) {
            togglePlaylist();
        });

        if (instanceName == 'fixed_bottom7') {
            instance.find('.hap-share-toggle').on('mouseenter', function (e) {
                instance.find('.hap-random-toggle').hide();//because share opens above them with transparent bg
                instance.find('.hap-popup-toggle').hide();

                instance.find('.hap-share-holder').show();
            }).on('mouseleave', function (e) {
                if (!isIE) {
                    instance.find('.hap-share-holder').hide();
                } else {
                    var rt = e.toElement || e.relatedTarget;
                    if (jQuery(rt).hasClass('hap-tooltip') || jQuery(rt).hasClass('hap-tooltip-inner'))
                        return;

                    instance.find('.hap-share-holder').hide();
                }
                instance.find('.hap-random-toggle').show();
                instance.find('.hap-popup-toggle').show();
            });
        }

        if (typeof hapOpenPopup == 'function') {

            instance.find('.hap-popup-toggle').on('click', function (e) {
                e.preventDefault();
                openPopup(instance, settings);
            });

            if (settings.autoOpenPopup)
                instance.find('.hap-popup-toggle').click();
        }

        //position playlist
        if (instanceName == 'fixed_bottom' || instanceName == 'fixed_bottom6' || instanceName == 'fixed_bottom7') {
            if (!settings.isPopup)
                instance.getWrapper().css({'bottom': -(instance.find('.hap-playlist-holder').height()) + 'px', opacity: 1});
        } else if (instanceName == 'fixed_bottom8') {
            instance.find('.hap-playlist-holder').css('bottom', instance.find('.hap-player-outer').height() + 'px');
        } else if (instanceName == 'fixed_top') {
            instance.getWrapper().css('top', -(instance.find('.hap-playlist-holder').height()) + 'px');
        }

    } else if (instanceName == 'fixed_bottom2' || instanceName == 'fixed_bottom3' || instanceName == 'fixed_bottom4' || instanceName == 'fixed_bottom5' || instanceName == 'fixed_bottom10' || instanceName == 'wall1') {

        instance.find('.hap-info-toggle').on('click', function (e) {
            instance.find('.hap-info-bar').toggle();
        }).on('mouseenter', function (e) {
            instance.overControls(e);
        }).on('mouseleave', function (e) {
            instance.outControls(e);
        });

        instance.find('.hap-info-close').on('click', function (e) {
            instance.find('.hap-info-bar').hide();
        }).on('mouseenter', function (e) {
            instance.overControls(e);
        }).on('mouseleave', function (e) {
            instance.outControls(e);
        });

        instance.find('.hap-playlist-toggle').on('click', function (e) {
            if (instance.find('.hap-playlist-holder').is(':visible')) {
                instance.find('.hap-playlist-holder').hide();
            } else {
                instance.find('.hap-playlist-holder').show();
                if (instanceName == 'fixed_bottom4' || instanceName == 'fixed_bottom5' || instanceName == 'fixed_bottom10')
                    instance.getPlaylistContent().masonry('layout');
            }
        });

    } else if (instanceName == 'seekbar_playlist' || instanceName == 'seekbar_playlist2') {

        instance.find('.hap-player-thumb, .hap-bigplay').on('click', function (e) {
            instance.togglePlayback();
        });

        instance.find('.hap-share-toggle').on('mouseenter', function (e) {
            instance.find('.hap-share-holder').show();
        }).on('mouseleave', function (e) {
            if (!isIE) {
                instance.find('.hap-share-holder').hide();
            } else {
                var rt = e.toElement || e.relatedTarget;
                if (jQuery(rt).hasClass('hap-tooltip') || jQuery(rt).hasClass('hap-tooltip-inner'))
                    return;
                instance.find('.hap-share-holder').hide();
            }
        });

    } else if (instanceName == 'classic' || instanceName == 'classic_light' || instanceName == 'classic2' || instanceName == 'classic_thumb' || instanceName == 'classic2_thumb' || instanceName == 'artwork6' || instanceName == 'artwork9') {

        instance.find('.hap-playlist-toggle').on('click', function (e) {
            instance.togglePlaylist();
        });

    } else if (instanceName == 'artwork' || instanceName == 'artwork2' || instanceName == 'artwork3' || instanceName == 'poster_seek' || instanceName == 'poster_seek2' || instanceName == 'circle2' || instanceName == 'circle2_flip' || instanceName == 'circle3' || instanceName == 'modern9' || instanceName == 'modern' || instanceName == 'modern2' || instanceName.indexOf("instance-") >= 0 || instanceName == 'modern4' || instanceName == 'modern5' || instanceName == 'modern6' || instanceName == 'modern7' || instanceName == 'modern8' || instanceName == 'modern_flip' || instanceName == 'modern_flip2' || instanceName == 'bg' || instanceName == 'fixed_bottom7' || instanceName == 'poster' || instanceName == 'artwork7') {

        instance.find('.hap-share-toggle').on('mouseenter', function (e) {
            if (instanceName == 'artwork7')
                instance.find('.hap-controls-left').hide();
            instance.find('.hap-share-holder').show();
        }).on('mouseleave', function (e) {
            if (!isIE) {
                instance.find('.hap-share-holder').hide();
            } else {
                var rt = e.toElement || e.relatedTarget;
                if (jQuery(rt).hasClass('hap-tooltip') || jQuery(rt).hasClass('hap-tooltip-inner'))
                    return;
                instance.find('.hap-share-holder').hide();
            }
            if (instanceName == 'artwork7')
                instance.find('.hap-controls-left').show();
        });

        if (instanceName == 'circle2' || instanceName == 'circle2_flip' || instanceName == 'circle3' || instanceName == 'modern9' || instanceName == 'modern' || instanceName == 'modern4' || instanceName == 'modern5' || instanceName == 'modern6' || instanceName == 'modern7' || instanceName == 'modern8' || instanceName == 'modern_flip' || instanceName == 'modern_flip2') {

            instance.find('.hap-playlist-toggle').on('click', function (e) {
                instance.togglePlaylist();
            });
            instance.find('.hap-playlist-close').on('click', function (e) {
                instance.togglePlaylist();
            }).on('mouseenter', function (e) {
                instance.overControls(e);
            }).on('mouseleave', function (e) {
                instance.outControls(e);
            });

        } else if (instanceName == 'artwork2') {

            //show/hide new playlist items

            var playlist = instance.find('.hap-playlist-content'),
                    size,
                    step = 5,
                    x = step,
                    more = jQuery('#showmore'),
                    less = jQuery('#showless');

            playlist.find('.hap-playlist-item:lt(' + x + ')').show();

            more.click(function () {
                size = instance.getPlaylistLength();
                if (x >= size)
                    return false;
                x += step;
                if (x >= size)
                    x = size;
                if (x == size)
                    more.css('opacity', 0.3);
                playlist.find('.hap-playlist-item:lt(' + x + ')').fadeIn(250);
                less.css('opacity', 1);
            });
            less.click(function () {
                size = instance.getPlaylistLength();
                if (x - step <= 0)
                    return false;
                x -= step;
                if (x <= step)
                    x = step;
                if (x <= step)
                    less.css('opacity', 0.3);
                playlist.find('.hap-playlist-item').not(':lt(' + x + ')').fadeOut(250);
                more.css('opacity', 1);
            });

        } else if (instanceName == 'bg') {

            instance.find('.hap-lyrics-toggle').on('click', function (e) {
                toggleLyrics();
            }).on('mouseenter', function (e) {

                instance.overControls(e);
            }).on('mouseleave', function (e) {
                instance.outControls(e);
            });

        } else if (instanceName.indexOf("instance-") >= 0) {

            instance.find('.hap-search-toggle').on('click', function (e) {

            }).on('mouseenter', function (e) {
                instance.overControls(e);
            }).on('mouseleave', function (e) {
                instance.outControls(e);
            });

        } else if (instanceName == 'artwork7') {
            //manually add duration to playlist items
            var z = 0, data = instance.getPlaylistData(), duration;
            instance.find('.hap-playlist-content').find('.hap-playlist-non-selected, .hap-playlist-selected').each(function () {
                if ($(this).find('span.hap-duration').length == 0 && data[z].data.duration) {
                    duration = HAPUtils.formatDuration(data[z].data.duration);
                    $(this).append(jQuery('<span class="hap-duration">' + duration + '</span>'));
                }
                z++;
            });
        }


    } else if (instanceName == 'video_vb') {

        lyricsCont = instance.find(".hap-lyrics-cont");

        instance.find('.hap-lyrics-toggle').on('click', function (e) {
            toggleLyrics();
        }).on('mouseenter', function (e) {
            instance.overControls(e);
        }).on('mouseleave', function (e) {
            instance.outControls(e);
        });

    } else if (instanceName == 'inline' || instanceName.lastIndexOf('inline2', 0) === 0 || instanceName.lastIndexOf('inline3', 0) === 0) {
        checkInlineLinks(instance);
    } else if (instanceName == 'panel') {
        instance.find('.hap-sidebar-toggle').on('mouseenter', function (e) {
            instance.overControls(e);
        }).on('mouseleave', function (e) {
            instance.outControls(e);
        });
    }

    jQuery('#preset_preloader_wrap').css('opacity', 0);



    if (window.parent && typeof window.parent.setInstance !== 'undefined') {
        var css = $('link[href="css/hap_' + settings.instanceName + '.css"]')[0].sheet;
        window.parent.setInstance(instance, css);
    }

}
function hapPlaylistEnd(instance, instanceName) {
    /* called when current playlists reaches end. Returns player instance, sound id. */
    //console.log('hapPlaylistEnd: ');
}
function hapMediaStart(instance, instanceName, counter) {
    /* called when media starts. Returns player instance, instanceName, media counter. */
    //console.log('hapMediaStart: ', counter);

    if (instanceName == 'seekbar_playlist') {
        instance.find('.hap-bigplay').remove();
    }

}
function hapMediaPlay(instance, instanceName, counter) {
    /* called when media is played. Returns player instance, instanceName, media counter. */
    //console.log('hapMediaPlay: ', instanceName);

    if (instanceName == 'fixed_top' || instanceName == 'fixed_bottom' || instanceName == 'fixed_bottom6' || instanceName == 'fixed_bottom7' || instanceName == 'fixed_bottom8' || instanceName == 'fixed_bottom9') {
        if (typeof setData !== 'undefined')
            setData(true);
    } else if (instanceName == 'text') {
        instance.find('.toggle').html('Pause');
    } else if (instanceName == 'poster_seek') {
        _HAPSlideshow.play();
    } else if (instanceName == 'inline') {
        setIcons(true);
    }

    if (typeof hap_mediaArr != undefined && hap_mediaArr.length && hap_mediaArr.length > 1) {
        var i, len = hap_mediaArr.length;
        for (i = 0; i < len; i++) {
            if (instance != hap_mediaArr[i].inst) {
                hap_mediaArr[i].inst.checkMedia('pause');
            }
        }
    }

}
function hapMediaPause(instance, instanceName, counter) {
    /* called when media is paused. Returns player instance, instanceName, media counter. */
    //console.log('hapMediaPause: ', instanceName);

    if (instanceName == 'fixed_top' || instanceName == 'fixed_bottom' || instanceName == 'fixed_bottom6' || instanceName == 'fixed_bottom7' || instanceName == 'fixed_bottom8' || instanceName == 'fixed_bottom9') {
        if (typeof setData !== 'undefined')
            setData(false);
    } else if (instanceName == 'text') {
        instance.find('.toggle').html('Play');
    } else if (instanceName == 'poster_seek') {
        _HAPSlideshow.stop(true);
    } else if (instanceName == 'inline') {
        setIcons(false);
    }
}
function hapMediaEnd(instance, instanceName, counter) {
    /* called when media ends. Returns player instance, instanceName, media counter. */
    //console.log('hapMediaEnd: ', counter);
}
function hapPlaylistStartLoad(instance, instanceName) {
    /* called when playlist load starts. Returns player instance, instanceName. */
    //console.log('hapPlaylistStartLoad: ', instanceName);
}
function hapPlaylistEndLoad(instance, instanceName, playlistContent) {
    /* called when playlist load ends. Returns player instance, instanceName. */
    //console.log('hapPlaylistEndLoad: ', instanceName);

    var settings = instance.getSettings(),
            sourcePath = settings.sourcePath;

    if (instanceName == 'metro' || instanceName == 'metro2' || instanceName == 'fixed_bottom4') {
        playlistContent.find('.hap-buy').off('click').click(function (e) {
            e.preventDefault();
            alert('Custom content in playlist items!');
        });

        if (instanceName == 'fixed_bottom4') {
            setTimeout(function () {
                clearTimeout(this);
                playlistContent.masonry({
                    itemSelector: '.hap-playlist-item',
                });
            }, 50);
        }

    } else if (instanceName == 'fixed_bottom10' || instanceName == 'wall1') {

        playlistContent.masonry({
            itemSelector: '.hap-playlist-item',
        });

        if (instanceName == 'wall1') {
            playlistContent.find('.hap-playlist-item').each(function () {
                var item = $(this);
                if (item.find('.hap-centre').length == 0) {
                    item.find('.hap-playlist-thumb').append(jQuery('<div class="hap-centre"/>'));//fake holes
                }
            });
        }
    } else if (instanceName == 'modern_flip' || instanceName == 'modern_flip2') {
        setTimeout(function () {//webkit playlist thumbs onload visibility fix
            clearTimeout(this);
            instance.find('.hap-playlist-holder').css('display', 'block');
        }, 200);
    } else if (instanceName == 'fixed_bottom5' || instanceName == 'wall2' || instanceName == 'wall3') {

        if (instanceName == 'wall2' || instanceName == 'wall3') {
            playlistItems = instance.getPlaylistItems();
            playlistLength = instance.getPlaylistLength();
        }

        var grid = playlistContent.masonry({
            itemSelector: '.hap-playlist-item',
            columnWidth: '.hap-grid-sizer'
        });

        grid.imagesLoaded(function () {
            if (instanceName == 'wall2' || instanceName == 'wall3') {
                if (typeof doneResizing !== 'undefined')
                    doneResizing();
                grid.masonry('layout');
            }
        }).progress(function () {
            grid.masonry('layout');
        });

        if (instanceName == 'wall2' || instanceName == 'wall3') {
            playlistContent.find('.hap-playlist-non-selected, .hap-playlist-selected').each(function () {
                var item = $(this);
                if (item.find('.hap-wall-preview').length == 0) {
                    item.find('.hap-playlist-thumb').after(jQuery('<div class="hap-wall-preview"/>'));
                }
                if (item.find('.hap-wall-overlay').length == 0) {
                    item.find('.hap-playlist-thumb').after(jQuery('<div class="hap-wall-overlay"></div>'));
                }
            });
        }
    } else if (instanceName == 'thumbs_h' || instanceName == 'thumbs_v') {
        playlistContent.find('.hap-playlist-non-selected, .hap-playlist-selected').each(function () {
            var item = $(this);
            if (item.find('.hap-wall-preview').length == 0) {
                item.find('.hap-playlist-thumb').after(jQuery('<div class="hap-wall-preview"/>'));
            }
            if (item.find('.hap-wall-overlay').length == 0) {
                item.find('.hap-playlist-thumb').after(jQuery('<div class="hap-wall-overlay"></div>'));
            }
        });
    } else if (instanceName == 'thumbs_h2' || instanceName == 'thumbs_h3') {

        instance.setPlaylistSize('w');

        defaultPiW = instance.getPlaylistItemSize('w');
        playlistItems = instance.getPlaylistItems();
        playlistLength = instance.getPlaylistLength();

        var data = instance.getPlaylistData(),
                info = jQuery('.hap-info'),
                item;

        playlistContent.find('.hap-playlist-non-selected, .hap-playlist-selected').each(function () {
            item = $(this);

            if (instanceName == 'thumbs_h2') {
                info.clone().show().appendTo(item);
                counter = parseInt(item.parent().attr('data-id'), 10);
                item.find('.hap-player-artist').html(data[counter].data.artist);
                item.find('.hap-player-title').html(data[counter].data.title);
            }

            if (item.find('.hap-wall-preview').length == 0) {
                jQuery('<div class="hap-wall-preview"/>').appendTo(item.find('.hap-playlist-thumb'));
            }

        });

        setTimeout(function () {
            clearTimeout(this);
            doneResizing();
            checkPlaylistScroll();
        }, 50);

    }


}

function hapItemTriggered(instance, instanceName, counter) {
    /* called when new sound is triggered. Returns player instance, instanceName, media counter. */
    //console.log('hapItemTriggered: ', instanceName, counter);

    var data = instance.getPlaylistData()[counter].data;

    if (instanceName == 'artwork' || instanceName == 'artwork2' || instanceName == 'artwork3' || instanceName == 'artwork4' || instanceName == 'artwork5' || instanceName == 'artwork6' || instanceName == 'artwork7' || instanceName == 'artwork8' || instanceName == 'artwork9' || instanceName == 'video' || instanceName == 'video_h' || instanceName == 'video_vb' || instanceName == 'seekbar_playlist' || instanceName == 'seekbar_playlist2' || instanceName == 'fixed_top' || instanceName == 'fixed_bottom' || instanceName == 'fixed_bottom6' || instanceName == 'fixed_bottom7' || instanceName == 'fixed_bottom8' || instanceName == 'fixed_bottom9' || instanceName == 'fixed_bottom10' || instanceName == 'metro2' || instanceName == 'poster_seek2' || instanceName == 'circle2' || instanceName == 'circle2_flip' || instanceName == 'circle3' || instanceName == 'modern9' || instanceName == 'classic_thumb' || instanceName == 'classic2_thumb' || instanceName == 'modern' || instanceName == 'modern2' || instanceName.indexOf("instance-") >= 0 || instanceName == 'modern4' || instanceName == 'modern5' || instanceName == 'modern6' || instanceName == 'modern7' || instanceName == 'modern8' || instanceName == 'modern_flip' || instanceName == 'modern_flip2' || instanceName == 'poster' || instanceName == 'panel' || instanceName == 'video2' || instanceName == 'video3' || instanceName == 'video4') {

        var playerThumb = instance.find('.hap-player-thumb'),
                thumb = data.thumb || data.thumbDefault;

        if (playerThumb.length && typeof thumb !== 'undefined') {
            var img = new Image();
            img.className = "hap-hidden";
            img.onload = function () {
                this.className = "hap-visible";
                if (instanceName == 'video' || instanceName == 'video_h' || instanceName == 'video_vb' || instanceName == 'seekbar_playlist' || instanceName == 'artwork5' || instanceName == 'artwork' || instanceName == 'artwork7' || instanceName == 'artwork9' || instanceName == 'video2' || instanceName == 'video3' || instanceName == 'video4') {
                    instance.doResize();
                }
            }
            img.src = thumb;
            playerThumb[0].appendChild(img);

            if (instanceName == 'modern8') {
                jQuery(img).clone().removeClass('hap-hidden').addClass('hap-visible').appendTo(instance.find('.hap-player-thumb-double').empty());
            }
        }

        if (instanceName == 'artwork3' || instanceName == 'artwork4' || instanceName == 'circle2' || instanceName == 'circle3' || instanceName == 'modern9' || instanceName == 'modern' || instanceName == 'modern2' || instanceName == 'modern4' || instanceName == 'modern5' || instanceName == 'modern_flip' || instanceName == 'modern_flip2') {
            instance.find('.hap-player-title').html(data.title);
            instance.find('.hap-player-artist').html('By ' + data.artist);
        } else if (instanceName.indexOf("instance-") >= 0 || instanceName == 'modern6' || instanceName == 'modern8' || instanceName == 'fixed_bottom6' || instanceName == 'fixed_bottom7' || instanceName == 'fixed_bottom8' || instanceName == 'modern7' || instanceName == 'poster' || instanceName == 'artwork5' || instanceName == 'artwork6' || instanceName == 'artwork8' || instanceName == 'artwork9') {
            instance.find('.hap-player-title').html(data.title);
            instance.find('.hap-player-artist').html(data.artist);

            if (instanceName == 'fixed_bottom7' || instanceName == 'poster') {
                if (jQuery('#hap-bg').length) {
                    jQuery('#hap-bg').fadeOut(300, function () {
                        $(this).css('backgroundImage', 'url(' + data.thumb + ')').fadeIn(300);
                    });
                }
            }
        } else if (instanceName == 'seekbar_playlist' || instanceName == 'seekbar_playlist2') {
            var item = instance.find('.hap-playlist-content').children('div.hap-playlist-item[data-id=' + counter + ']');
            instance.find('.hap-seekbar-inner').show().prependTo(item);
        } else if (instanceName == 'video_vb') {
            if (lyricsCont.length)
                initLyrics(counter);
        } else if (instanceName == 'artwork7') {
            instance.find('.hap-player-bg').fadeOut(300, function () {
                $(this).css('backgroundImage', 'url(' + data.thumb + ')').fadeIn(300);
            });
        }


    } else if (instanceName == 'fixed_bottom2' || instanceName == 'fixed_bottom3' || instanceName == 'fixed_bottom4' || instanceName == 'fixed_bottom5') {

        instance.find('.hap-player-title').html(instance.getTitle(instance.getActiveItemId()));
        if (data.description && !HAPUtils.isEmpty(data.description))
            instance.find('.hap-player-desc').html(data.description);

    } else if (instanceName == 'poster_seek' || instanceName == 'bg') {

        initSlideshow(counter);

        if (instanceName == 'bg') {
            instance.find('.hap-player-title').html(data.title);
            instance.find('.hap-player-artist').html('By ' + data.artist);
            initLyrics(counter);
        }
    } else if (instanceName == 'wall1') {
        var item = instance.find('.hap-playlist-content').children('div.hap-playlist-item[data-id=' + counter + ']');

        instance.find('.hap-circle-player').addClass('hap-circle-player-set').prependTo(item);
        item.find('.hap-centre').hide();
        instance.find('.hap-playback-toggle-wrapper').show().appendTo(item);

        instance.find('.hap-player-title').html(instance.getTitleFormatted(data, ' - '));
        if (data.description && !HAPUtils.isEmpty(data.description))
            instance.find('.hap-player-desc').html(data.description);

    } else if (instanceName == 'wall2' || instanceName == 'thumbs_h' || instanceName == 'thumbs_v') {
        var item = instance.find('.hap-playlist-content').children('div.hap-playlist-item[data-id=' + counter + ']');
        if (instanceName == 'thumbs_h' || instanceName == 'thumbs_v')
            instance.find('.hap-volume-wrapper').show().appendTo(item);
        instance.find('.hap-circle-player').addClass('hap-circle-player-set').appendTo(item);
    } else if (instanceName == 'wall3') {
        var item = instance.find('.hap-playlist-content').children('div.hap-playlist-item[data-id=' + counter + ']');
        instance.find('.hap-player-holder').show().appendTo(item);
    } else if (instanceName == 'thumbs_h2' || instanceName == 'thumbs_h3') {
        var item = instance.find('.hap-playlist-content').children('div.hap-playlist-item[data-id=' + counter + ']');
        instance.find('.hap-circle-player').addClass('hap-circle-player-set').appendTo(item.find('.hap-playlist-thumb'));
    }

}
function hapPlaylistItemEnabled(instance, instanceName, item, id) {
    /* called on playlist item enable. Returns player instance, instanceName, playlist item, playlist item id (playlist items have data-id attributes starting from 0). */
    //console.log('hapPlaylistItemEnabled: ');
    if (instanceName == 'wall' || instanceName == 'fixed_bottom2' || instanceName == 'fixed_bottom5') {
        hapPlaylistItemRollout(instance, instanceName, item, id);
    } else if (instanceName == 'metro' || instanceName == 'metro2' || instanceName == 'seekbar_playlist' || instanceName == 'seekbar_playlist2' || instanceName == 'fixed_bottom4' || instanceName == 'fixed_bottom7' || instanceName == 'fixed_bottom9' || instanceName == 'fixed_bottom10' || instanceName == 'wall1' || instanceName == 'wall2' || instanceName == 'wall3' || instanceName == 'thumbs_h' || instanceName == 'thumbs_h2' || instanceName == 'thumbs_v' || instanceName == 'thumbs_h3') {
        item.removeClass('hap-active');

        if (instanceName == 'wall1')
            item.find('.hap-centre').show();

        else if (instanceName == 'seekbar_playlist' || instanceName == 'seekbar_playlist2')
            item.find('.hap-playlist-seek-helper').remove();
    }


}
function hapPlaylistItemDisabled(instance, instanceName, item, id) {
    /* called on playlist item disable. Returns player instance, instanceName, playlist item, playlist item id (playlist items have data-id attributes starting from 0). */
    //console.log('hapPlaylistItemDisabled: ');
    if (instanceName == 'wall' || instanceName == 'fixed_bottom2' || instanceName == 'fixed_bottom5') {
        hapPlaylistItemRollover(instance, instanceName, item, id);
    } else if (instanceName == 'metro' || instanceName == 'metro2' || instanceName == 'seekbar_playlist' || instanceName == 'seekbar_playlist2' || instanceName == 'fixed_bottom4' || instanceName == 'fixed_bottom7' || instanceName == 'fixed_bottom9' || instanceName == 'fixed_bottom10' || instanceName == 'wall1' || instanceName == 'wall2' || instanceName == 'wall3' || instanceName == 'thumbs_h' || instanceName == 'thumbs_h2' || instanceName == 'thumbs_v' || instanceName == 'thumbs_h3') {
        item.addClass('hap-active');

        if (instanceName == 'seekbar_playlist' || instanceName == 'seekbar_playlist2') {
            item.append('<div class="hap-playlist-seek-helper"/>').on('click', function (e) {
                var currentTarget = $(e.currentTarget),
                        sv = Math.min(Math.max(((e.pageX - currentTarget.offset().left) / currentTarget.width()), 0), 1);
                instance.seek(sv, false);
            });
        }

    }

}
function hapPlaylistItemClick(instance, instanceName, target, counter) {
    /* called on playlist item click. Returns player instance, instanceName, playlist item (target), media counter. */
    //console.log('hapPlaylistItemClick: ', counter);
}
function hapPlaylistItemRollover(instance, instanceName, target, id) {
    /* called on playlist item mouseenter. Returns player instance, instanceName, playlist item (target), playlist item id (playlist items have data-id attributes starting from 0). */
    //console.log('hapPlaylistItemRollover: ', id);
    if (instanceName == 'wall' || instanceName == 'fixed_bottom2' || instanceName == 'fixed_bottom5') {
        var title = target.find('.hap-playlist-title');
        if (title.length)
            title.css({display: 'block', top: -title.outerHeight(true) + 'px'}).stop().animate({'top': 0 + 'px'}, {duration: 200});
    }

}
function hapPlaylistItemRollout(instance, instanceName, target, id, active) {
    /* called on playlist item mouseleave. Returns player instance, instanceName, playlist item (target), playlist item id (playlist items have data-id attributes starting from 0), active (is active playlist item, boolean). */
    //console.log('hapPlaylistItemRollout: ', id);
    if (instanceName == 'wall' || instanceName == 'fixed_bottom2' || instanceName == 'fixed_bottom5') {
        var title = target.find('.hap-playlist-title');
        if (title.length)
            title.stop().animate({'top': -title.outerHeight(true) + 'px'}, {duration: 200, complete: function () {
                    jQuery(this).css({display: 'none'});
                }});
    }


}
function hapMediaEmpty(instance, instanceName) {
    /* called when active media is removed from the playlist. Returns player instance, instanceName. */
    //console.log('hapMediaEmpty: ', instanceName);
}
function hapPlaylistEmpty(instance, instanceName, playlistContent) {
    /* called when playlist becomes empty (no items in playlist after new playlist has been created or last playlist item removed from playlist, NOT after destroyPlaylist!). Returns player instance, instanceName. */
    //console.log('hapPlaylistEmpty: ', instanceName);
}
function hapCleanMedia(instance, instanceName) {
    /* called when active media is destroyed. Returns player instance, instanceName. */
    //console.log('hapCleanMedia: ', instanceName);
}
function hapDestroyPlaylist(instance, instanceName, playlistContent) {
    /* called when active playlist is destroyed. Returns player instance, instanceName. */
    //console.log('hapDestroyPlaylist: ', instanceName);
    if (instanceName == 'fixed_bottom4' || instanceName == 'fixed_bottom5' || instanceName == 'fixed_bottom10' || instanceName == 'wall1' || instanceName == 'wall2' || instanceName == 'wall3') {
        playlistContent.masonry('destroy');
    }

}
function hapVolumeChange(instance, instanceName, volume) {
    /* called on volume change. Returns player instance, instanceName, volume. */
    if (instanceName == 'text') {
        var v = parseInt(Math.round(volume * 100)).toString() + '%';
        instance.find('.volvalue').html(v);
    } else if (instanceName == 'metro' || instanceName == 'metro2' || instanceName == 'poster_seek' || instanceName == 'poster_seek2' || instanceName == 'circle2' || instanceName == 'circle2_flip' || instanceName == 'circle3' || instanceName == 'modern9' || instanceName == 'modern' || instanceName == 'modern2' || instanceName.indexOf("instance-") >= 0 || instanceName == 'modern4' || instanceName == 'modern5' || instanceName == 'modern6' || instanceName == 'modern8' || instanceName == 'modern_flip' || instanceName == 'modern_flip2' || instanceName == 'artwork5' || instanceName == 'artwork8') {
        //fake drag
        var drag = instance.find('.hap-volume-drag'),
                v = parseInt(Math.round(volume * instance.getVolumeSize())) - drag.outerWidth(true) / 2,
                p = instance.getVolumeOrientation() ? 'left' : 'bottom';
        drag.css(p, v + 'px');
    }
}

function hapProgressChange(instance, instanceName, percent) {
    /* called on progress change. Returns player instance, instanceName, percent. */
}

function hapDropReceive(instance, instanceName) {
    /* called when item gets dropped into the playlist. Returns player instance, instanceName. */
    //console.log('hapDropReceive: ');
}

function hapFilterChange(instance, instanceName, playlistContent) {
    /* called after filter playlist items. Returns player instance, instanceName. */
    if (instanceName == 'fixed_bottom5' || instanceName == 'wall1' || instanceName == 'wall2' || instanceName == 'wall3') {
        playlistContent.masonry('layout');
    }
}
function hapPlaybackRateChange(instance, instanceName, rate) {
    /* called on playback rate change. Returns player instance, instanceName, playback rate. */
    //console.log('hapPlaybackRateChange: ', instanceName, rate);
}
function hapSeeked(instance, instanceName) {
    /* called on media seek. Returns player instance, instanceName. */
    /* Not available for Youtube! */
    //console.log('hapSeeked: ', instanceName);
    if (instanceName == 'bg' || instanceName == 'video_vb') {
        if (lyricsCont.length)
            hideLyrics();
    }
}
function hapResize(instance, instanceName) {
    /* called on window resize. Returns player instance, instanceName. */
    //console.log('hapResize: ', instanceName);
    if (instanceName == 'video' || instanceName == 'video_h' || instanceName == 'video_vb' || instanceName == 'seekbar_playlist' || instanceName == 'artwork5' || instanceName == 'artwork' || instanceName == 'artwork7' || instanceName == 'artwork9' || instanceName == 'video2' || instanceName == 'video3' || instanceName == 'video4') {
        instance.doResize();
    }
}

/* END PLAYER CALLBACKS */



//############################################//
/* popup */
//############################################//

var hap_popup, hap_player;
function hapOpenPopup(instance, settings) {

    if (hap_player)
        hap_player.destroyPlayer();//yt fix

    hap_player = instance;

    var url = settings.popupUrl, w = settings.popupWidth, h = settings.popupHeight, cw = (window.screen.width - w) / 2, ch = (window.screen.height - h) / 2;

    if (!hap_popup || hap_popup.closed) {
        hap_popup = window.open(url, 'hap_popup_window', 'menubar=no,toolbar=no,location=no,scrollbars=1,resizable,width=' + w + ',height=' + h + ',left=' + cw + ',top=' + ch + '');

        if (!hap_popup) {
            alert("Player can not be opened in a popup window because your browser is blocking Pop-Ups. You need to allow Pop-Ups in browser for this site to use the Player.");
            return false;
        }

    }
}

function hapNotifyParent() {//called from popup window when popup window has opened!

    if (hap_player && hap_popup && hap_popup.initPopup != undefined) {

        //copy current playlist to popup
        var settings = hap_player.getSettings(),
                current_playlist = hap_player.getPlaylistContent().clone(true, true).wrap('<p>').parent().children().html(),
                playlist_data = hap_player.getPlaylistData();
        settings.activeItem = hap_player.getActiveItemId();
        settings.current_playlist = current_playlist;
        settings.playlist_data = playlist_data;

        //clean current player
        hap_player.destroyPlaylist();
        if (settings.usePlaylistScroll)
            hap_player.destroyPlaylistScroll();
        if (HAPUtils.isMobile())
            hap_player.setAutoPlay(false);//reset

        //transfer elements to popup
        var player = hap_player.getWrapper(),
                playlist = hap_player.getPlaylistList(),
                popupCss = jQuery(settings.popupCss);

        if (HAPUtils.isIE()) {//HIERARCHY_REQUEST_ERROR
            player = player.remove().clone(true, true).wrap('<p>').parent().html();
            playlist = playlist.remove().clone(true, true).wrap('<p>').parent().html();
            popupCss = popupCss.remove().clone(true, true).wrap('<p>').parent().html();
        }

        try {
            hap_player = hap_popup.initPopup(player, playlist, settings, css, popupCss);
        } catch (e) {
            alert('initPopup error: ' + e.message);
            return false;
        }

    }
}




