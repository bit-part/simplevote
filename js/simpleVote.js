(function($){
    // -------------------------------------------------
    //  .simpleVote() : v1.0.0
    // -------------------------------------------------
    $.fn.simpleVote = function(options){
        var op = $.extend({}, $.fn.simpleVote.defaults, options);

        return this.each(function(){
            var $this = $(this);
            var params = $.extend({}, op);

            // Store an original innerHTML of the target Element
            $this.data('orgHtml', $this.html());

            // Set properties to a paramsuration object
            if (!params.voteId) {
                params.voteId = $this.attr('data-voteId');
            }
            if (!params.voteToken) {
                params.voteToken = $this.attr('data-voteToken');
            }
            if (!params.voteRectId) {
                params.voteRectId = $this.attr('data-voteRectId');
            }

            // Make a cookie key
            var cookieKey = 'vote_' + params.voteId;

            // When loading window, vote type is "get"
            params.voteType = 'get';

            // Get a current voted value
            getVoteCount(params);

            // If you had already voted, change the innerHTML of the target element.
            if (getCookie(cookieKey)) {
                $this.html(op.votedText).addClass('voted');
            }


            // Attach a handler with namespace to an click event
            $this.on('click.simpleVote', function(){
                // If the target element has the voted class, that is, you had already voted
                if ($this.hasClass('voted')) {
                    setCookie(cookieKey, '', op.cookieDays, op.cookiePath);
                    params.voteType = 'dec';
                    getVoteCount(params);
                    $this.html($this.data('orgHtml'));
                }
                // This click is first voting.
                else {
                    setCookie(cookieKey, 1, op.cookieDays, op.cookiePath);
                    params.voteType = 'inc';
                    getVoteCount(params);
                    $this.html(op.votedText);
                }
                $(this).toggleClass('voted');
            });
        }); // each

        function getVoteCount(obj){
            $.ajax({
                url: obj.url,
                data: {
                    vote_id: obj.voteId,
                    vote_type: obj.voteType,
                    vote_token: obj.voteToken
                },
                type: 'POST',
                dataType: 'text'
            })
            .done(function(response){
                var text = response != 0 ? response : obj.notVotedText;
                document.getElementById(obj.voteRectId).innerHTML = text;
            })
            .fail(function(){
                document.getElementById(obj.voteRectId).innerHTML = obj.ajaxErrorText;
            });
        }

        function setCookie (key, val, days, path) {
            var cookie = encodeURIComponent(key) + '=' + encodeURIComponent(val);
            if (days != null) {
                var expires = new Date();
                expires.setDate(expires.getDate() + days);
                cookie += ';expires=' + expires.toGMTString();
            }
            cookie += (path != null) ? '; path=' + path + ';': '; path=/;';
            document.cookie = cookie;
        }

        function getCookie (key) {
            if (document.cookie) {
                var cookies = document.cookie.split(";");
                for (var i=0; i<cookies.length; i++) {
                    var cookie = cookies[i].replace(/\s/g,"").split("=");
                    if (cookie[0] == encodeURIComponent(key)) {
                        var value = "" + decodeURIComponent(cookie[1]);
                        return (value == "undefined" || value == "null" || value == "") ? "": value;
                    }
                }
            }
            return "";
        }
    };
    $.fn.simpleVote.defaults = {
        url: null,
        voteId: null,
        voteToken: null,
        voteRectId: null,
        votedText: 'Unlike',
        notVotedText: '',
        ajaxErrorText: '',
        cookieDays: null,
        cookiePath: '/'
    };
    // end - .simpleVote();
})(jQuery);