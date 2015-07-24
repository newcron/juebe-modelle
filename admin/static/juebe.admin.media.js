/** Author: mhuttar@ebay.com */
(function () {
    var $mediaArea;
    var $uploadArea;
    var uploader;
    JuBe.Admin.Media = {};

    JuBe.Admin.Media.init = function() {
        $mediaArea = $("#media-area");
        var opts = {
            request: {
                endpoint: 'media.upload.php'
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'png', 'gif'],
            }, messages: {
                typeError: "{file} ist nicht erlaubt. Nur Bilder vom Typ: {extensions}.",
                sizeError: "{file} ist zu groß. Maximum {sizeLimit} bytes.",
                minSizeError: "{file} ist zu klein. Minimum {minSizeLimit} bytes . ",
                emptyError: "{file} scheint leer zu sein",
                noFilesError: "Keine Dateien ausgewählt",
                tooManyItemsError: "zu viele Dateien ({netItems}) ausgewählt. Maximum {itemLimit}.",
                retryFailTooManyItems: "Fehler beim Upload.",
                onLeave: "Achtung: Bilder werden noch hochgeladen. Wenn die Seite verlassen wird, schlagen die uploads fehl."
            },
            autoUpload: false,
            text: {
                uploadButton: 'Datei'
            }
        };

        uploader = $("#media-upload-area").fineUploader(opts);

        $('#triggerUpload').click(function() {
            uploader.fineUploader('uploadStoredFiles');
        });


    }
    JuBe.Admin.Media.show = function() {
        $mediaArea.show();
        refreshImageList()
    }

    function refreshImageList() {
        console.log("refres")
        $thumbArea = $("#image-thumbnails");
        $thumbArea.children().remove();
        $.ajax("images.list.json.php").success(function(data){
            $.each(data, function(){
                console.log(this);
                $('<li class="thumb-selectable"><div class="caption"><strong>'+this.name+'</strong></div><a href="'+this.full+'" target="_blank"><img src="'+this.thumb+'" /></a></li>').appendTo($thumbArea);
            });
        });
    }


})()