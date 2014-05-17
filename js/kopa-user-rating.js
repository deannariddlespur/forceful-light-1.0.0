/**
 * Kopa User Rating
 * Author: Kopatheme
 */
if (typeof Object.create !== 'function') {
    Object.create = function (o) {
        function F() {};
        F.prototype = o;
        return new F();
    };
}

(function(){
    var KopaUserRating = {
        init: function (options, elem) {
            this.ratingList = jQuery(elem.ratingList);
            this.totalRatingList = jQuery(elem.totalRatingList);
            this.anchorRatingList = this.ratingList.find('span');
            this.parentList = this.ratingList.find('li');
            this.wpNonce = options.wpNonce;
            this.bindEvents();
        },
        bindEvents: function () {
            var self = this,
                kopaData = {mySelf: self};
            if (!self.ratingList.hasClass('already-voted')) {
                self.anchorRatingList.on('mouseenter', kopaData, self.kopa_mouseenter_rating)
                    .on('mouseout', kopaData, self.kopa_mouseout_rating)
                    .on('click', kopaData, self.kopa_click_rating);
            }
        },
        kopa_mouseenter_rating: function (e) {
            var self = e.data.mySelf,
                $this = jQuery(this),
                $thisParent = $this.parent('li');
            self.anchorRatingList.removeClass('fa-star').addClass('fa-star-o');
            $this.removeClass('fa-star-o').addClass('fa-star');
            var selfIndex = self.parentList.index($thisParent),
                i;
            for (i = 0; i < selfIndex; i++) {
                self.parentList.eq(i).find('span').removeClass('fa-star-o').addClass('fa-star');
            }
        },
        kopa_mouseout_rating: function (e) {
            var self = e.data.mySelf,
                $this = jQuery(this),
                $thisParent = $this.parent('li');
            $this.removeClass('fa-star').addClass('fa-star-o');
            var selfIndex = self.parentList.index($thisParent),
                i;
            for (i = 0; i < selfIndex; i++) {
                self.parentList.eq(i).find('span').removeClass('fa-star').addClass('fa-star-o');
            }
            
            // restore to current rating
            for (i = 0; i < self.ratingList.data('current-rating'); i++) {
                self.parentList.eq(i).find('span').removeClass('fa-star-o').addClass('fa-star');
            }
        },
        kopa_click_rating: function (e) {
            e.preventDefault();
            var self = e.data.mySelf,
                $this = jQuery(this),
                $thisParent = $this.parent('li');
            $this.removeClass('fa-star-o').addClass('fa-star');
            var selfIndex = self.parentList.index($thisParent),
                i;
            for (i = 0; i < selfIndex; i++) {
                self.parentList.eq(i).find('span').removeClass('fa-star-o').addClass('fa-star');
            }

            // send rating index, rating value to server
            jQuery.ajax({
                type: 'POST',
                url: kopa_front_variable.ajax.url,
                data: {
                    ratingIndex: self.ratingList.data('rating-index'),
                    ratingValue: selfIndex + 1,
                    action: 'kopa_set_user_rating',
                    post_id: kopa_front_variable.template.post_id,
                    wpnonce: self.wpNonce
                },
                beforeSend: function() {
                    // fadeout waiting for server data
                    self.ratingList.fadeOut();
                    self.totalRatingList.fadeOut();
                },
                success: function(responses) {
                    responses = jQuery.parseJSON(responses);
                    
                    if ( 'success' == responses.status ) {
                        var total_current_rating = Math.round( responses.total_current_rating ),
                            total_all_rating = Math.round( responses.total_all_rating );

                        self.anchorRatingList.removeClass('active');
                        for (var i = 0; i < total_current_rating; i++) {
                            self.parentList.eq(i).find('span').addClass('active');
                        }
                        // fadein to show new current rating value
                        self.ratingList.attr('title', responses.total_current_rating_title)
                            .data('current-rating', total_current_rating)
                            .fadeIn();

                        self.totalRatingList.children().remove();
                        totalMarkup = self.kopa_build_total_markup(total_all_rating);
                        self.totalRatingList.append(totalMarkup)
                            .attr('title', responses.total_all_rating_title)
                            .fadeIn();
                    } else if ( 'error' == responses.status ) {
                        self.ratingList.fadeIn().attr('title', responses.error_message);
                        self.totalRatingList.fadeIn();
                    }
                },
                error: function() {
                    // fadein to show new rating value
                    self.anchorRatingList.fadeIn();
                    self.ratingList.attr('title', 'Sorry an error has occurred!');
                }
            });

            // turn off rating events
            self.anchorRatingList.off('mouseenter', self.kopa_mouseenter_rating)
                .off('mouseout', self.kopa_mouseout_rating)
                .off('click', self.kopa_click_rating);
        },
        kopa_build_total_markup: function(total_all_rating) {
            var totalMarkup = '',
                i;
            for (i = 0; i < total_all_rating; i++) {
                totalMarkup += '<li><span class="fa fa-star"></span></li>';
            }
            for (i = 0; i < 5 - total_all_rating; i++) {
                totalMarkup += '<li><span class="fa fa-star-o"></span></li>';
            }
            return totalMarkup;
        }
    };

    jQuery('.kopa-user-rating').each(function() {
        var userRating = Object.create(KopaUserRating);
        userRating.init({
            wpNonce: jQuery('#kopa_set_user_rating_wpnonce').val()
        },{
            ratingList: this,
            totalRatingList: '#kopa-user-total-rating'
        });
    });
}());











