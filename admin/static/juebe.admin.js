(function(){
    JuBe = {Admin: {}};
    var $mainArea;

    String.prototype.startsWith = function(prefix){return this.indexOf(prefix) == 0}

    JuBe.Admin.init = function() {
        $mainArea = $("#content-area");
        window.onhashchange = hashChangeListener;
        JuBe.Admin.Pages.init();
        JuBe.Admin.Items.init();
        JuBe.Admin.Media.init();
        hashChangeListener();
    }

    JuBe.Admin.clear = function() {
        $("#main-area").children().hide();
    }

    JuBe.Admin.errorAlert = function(xhr, status, errorThrown) {
        alert("Ein Fehler ist aufgetreten: "+status);
    }

    JuBe.Admin.selectImage = function(selectedItemCallback) {
        var $selectorDialog = $("#image-selector-modal");
        var $thumbList = $("#modal-thumb-selection");
        $thumbList.children().remove();
        $.ajax("images.list.json.php").success(function(data){
            $.each(data, function(){
                var current = this;
                var $item = $('<li class="thumb-selectable" data-image="'+this.name+'"><div class="caption"><strong>'+this.name+'</strong></div><a class="image-link" href="#"><img src="'+this.thumb+'"/></a></li>');
                $item.click(function(e){
                    e.preventDefault();
                    var imageName = $(this).attr("data-image");
                    $selectorDialog.modal('hide');
                    selectedItemCallback(imageName, current);
                });
                $item.appendTo($thumbList);
            });
        });
        $selectorDialog.modal();

    }


    function hashChangeListener(){
        JuBe.Admin.clear();
        if(window.location.hash.startsWith("#page") ) {
            JuBe.Admin.Pages.show();
        } else if(window.location.hash == "#items") {
            JuBe.Admin.Items.show();
        } else if(window.location.hash.startsWith("#media")) {
            JuBe.Admin.Media.show();
        }
    }

    JuBe.Admin.OnlyOnSuccess = function(resp, callback) {
        if(!resp.success) {
            alert("Konnte nicht speichern: "+resp.error);
        } else {
            callback();
        }
    }

})();

