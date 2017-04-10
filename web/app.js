/**
 * Created by botev on 10.04.17.
 */
jQuery(function() {


    var template =   jQuery("#comment-template").html();

    function appendReactions(reactions) {

        if (reactions.length) {


            jQuery("#reactions-container .boxes").html("");

            for (var key in reactions) {
                if (reactions.hasOwnProperty(key)) {

                    var reaction = reactions[key];

                    var html = template;

                    html = html.replace("#email#", reaction.user.email);
                    html = html.replace("#stars#", reaction.reaction.stars);
                    html = html.replace("#comment#", reaction.reaction.comment);

                    jQuery("#reactions-container .boxes").append(html);

                }
            }
        }

    }

    function appendErrors(errors) {

        jQuery("#reactions-errors-container").html("");

        if (errors.length) {

            for (var c = 0; c < errors.length; c++) {

                var error = errors[c];


                jQuery("#reactions-errors-container").append("<div class='alert alert-danger'><span>" + error + " </span></div>")


            }
        }

    }


    jQuery("#react-form").on("submit", function (e) {

        jQuery("#reactions-errors-container").html("");

        var data = {

            "email": jQuery(this).find("#email").val(),
            "stars": jQuery(this).find("#stars").val(),
            "comment": jQuery(this).find("#comment").val()


        };

        window.reactor.addReaction(data, function (success, errors) {


            if(success) {

                window.reactor.getReactions(appendReactions);
            } else {

                appendErrors(errors);
            }

        });

        e.preventDefault();
    });


    window.reactor.getReactions(appendReactions);
});