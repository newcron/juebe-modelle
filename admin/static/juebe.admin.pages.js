/** Author: mhuttar@ebay.com */
(function () {
    var model;
    var editor;
    JuBe.Admin.Pages = {};
    JuBe.Admin.Pages.init = function(){
        listAllPages(function(result){
            var $selector = $("#page-selector");
            model = {};
            $.each(result, function(){
                model[this.id] = this;
                $('<option value="'+this.id+'">'+this.title+'</option>').appendTo($selector);
            });
            $selector.change(function(){
                var newSelection = $selector.val();
                if(newSelection) {
                    location.href="?r="+Math.random()+"#page/"+newSelection;
                }
            });
            editor = new wysihtml5.Editor("inputText", {
                toolbar: "wysihtml5-toolbar", // id of toolbar element
                parserRules:  wysihtml5ParserRules // defined in parser rules set
            });


            $("#textarea-select-image").click(function(e){
                e.preventDefault();
                JuBe.Admin.selectImage(function(selected, data){
                    var html = '<a href="'+data.full+'" target="_blank"><img class="img-polaroid" src="'+data.thumb+'" alt="'+selected+'"></a>';
                    console.log(html);

                    editor.composer.commands.exec("insertHTML", html);

                    // console.log(data);
                });
            });

            $("#pages-save-button").click(function(ev){
                ev.preventDefault();
                var id = $("#inputId").val();
                var title= $("#inputTitle").val();
                var text = $("#inputText").val();
                savePage(id, title, text);
            });
            JuBe.Admin.Pages.show();
        });
    }

    JuBe.Admin.Pages.show = function() {
        if(!model) {
            return
        };
        if(window.location.hash.startsWith("#page/")) {
            var id = window.location.hash.substr("#page/".length);
            var data = model[id];
            $("#inputId").val(data.id);
            $("#inputTitle").val(data.title);
            $("#inputText").val(data.content);
            $("#pages-area").show();

        }
    }

    function listAllPages(callback) {
        $.ajax("pages.list.json.php",{
            dataType: "json",
            success: callback,
            error: JuBe.Admin.errorAlert
        });
    }

    function savePage(id, title, text) {
        $.ajax("pages.save.json.php",{
            dataType: "json",
            data: {id: id, title: title, content: text},
            type: "POST",
            success: function(data){
                JuBe.Admin.OnlyOnSuccess(data, function(){
                    window.location.reload();
                });
            },
            error: JuBe.Admin.errorAlert
        });
    }
})();