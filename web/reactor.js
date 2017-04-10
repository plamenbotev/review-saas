/**
 * Created by botev on 09.04.17.
 */



(function(root, factory) {

    'use strict';
    if (typeof define === 'function' && define.amd) {

        define(['jquery'], factory);
    } else if (typeof exports !== 'undefined') {
        module.exports = factory(require('jquery'));
    } else {

        root.reactor = factory(jQuery);
    }

}(window, function($) {
    'use strict';

    if(window.reactor){
        return window.reactor;
    }

    var script = $("script[data-reactor-api=1]");


    function reactor(){

        var token = script.data("reactor-token");
        var endpoint = script.data("reactor-endpoint");



        this.getToken = function(){
            return token;
        }

        this.getUrl = function(){
            return window.location.href;
        }

        this.getReactions = function(callable){

            var uri = encodeURIComponent(this.getUrl());
            uri = uri.split("%2F");
            uri = uri.join("/");

            var url = endpoint+"/get-reactions/"+token+"/"+uri;

            var _ = this;

            $.ajax({
                url: url,
                type:"GET",
                crossDomain: true,

                dataType:"json",
                success: function(data){
                    callable.call(_, data);

                }

            })
        }

        this.addReaction = function(data, callable){
            var _ = this;

            var url = endpoint+"/add-reaction/"+token;

            data.url = this.getUrl();

            $.ajax({
                url: url,
                type:"POST",
                crossDomain: true,
                data: JSON.stringify(data),
                contentType:"application/json; charset=utf-8",
                dataType:"json",
                success: function(data){

                    if(!data.success){

                        callable.call(_, false, data.errors);

                    } else {
                        callable.call(_, true, []);
                    }
                }
            })

        }

    }



   return new reactor();



}));


