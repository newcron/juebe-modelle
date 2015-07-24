/** Author: mhuttar@ebay.com */
(function () {
    var categoryMetadata = {
        PERSONENWAGEN: {prefix: "01/"},
        PACKWAGEN: {prefix: "02/"},
        POSTWAGEN: {prefix: "03/"},
        SPEISEWAGEN: {prefix: "04/"},
        BAUWAGEN: {prefix: "09/"},
        GUETERWAGEN: {prefix: "10/"},
        TELEGRAFENMASTE: {prefix: "05/"},
        SIGNALE: {prefix: "06/"},
        VERKEHRSZEICHEN: {prefix: "07/"},
        ZUBEHOER: {prefix: "08/"},
        FENSTER: {prefix: "11/"},
        LAMPEN: {prefix: "12/"},
        LITERATUR: { prefix: "13/"}
    };

    var currentItem=null;

    var $mainArea;
    var $menuList;
    var allItems;
    JuBe.Admin.Items = {};
    JuBe.Admin.Items.init = function() {
        $mainArea = $("#items-area");
        $menuList = $("#items-menu");
        $("#items-save-button").click(function(e){
            e.preventDefault();
            saveItem(readForm());
        });
        $("#items-add-new-button").click(function(){
            setupNewEntryForm()
        });
        listItems(buildMenu);

        $("#item-edit-form").hide();
    }
    JuBe.Admin.Items.show = function(){
        $mainArea.show();
        $("#item-edit-form").hide();
    }

    function buildMenu(items) {
        allItems = items;
        var lastCategory = "";
        $menuList.children().remove();
        $.each(items, function(){
            if(this.category != lastCategory) {
                $menuList.append('<li class="nav-header">'+this.category+'</li>');
                lastCategory = this.category;
            }
            $item = $('<li><a href="javascript: void(0);">'+this.title+'</a></li>');
            var current = this;
            $item.click(function(e){
                e.preventDefault();
                editItem(current);
            });
            $menuList.append($item);
        });
    }


    function generateNextNewId(newCat) {
        var categoryIndex = categoryMetadata[newCat].prefix;
        var maxSubId = 0;
        $.each(allItems, function () {

            if (this.id.startsWith(categoryIndex)) {
                var idSegs = this.id.split("/");
                maxSubId = Math.max(maxSubId, idSegs[1]);
            }
        });
        $("#itemId").val(categoryIndex + subsegment(maxSubId+1));
    }

    function setupNewEntryForm(){
        editItem({
            id: null,
            title: "",
            category: "",
            description: "",
            price: 0
        });
        $("#itemCategory").change(function(){
            var newCat = $(this).val();
            generateNextNewId(newCat);
        });
        generateNextNewId($("#itemCategory").val());

    }

    function subsegment(maxSubId) {
        return maxSubId < 10 ? "00"+maxSubId : maxSubId < 100 ? "0"+maxSubId : maxSubId;
    }


    function editItem(item) {
        currentItem = item;
        $("#item-edit-form").show();

        $("#itemId").val(item.id);
        $("#itemTitle").val(item.title);
        $("#itemCategory").val(item.category);
        $("#itemCategory").unbind("change");

        $("#select-image-button").unbind("click");
        $("#select-image-button").click(function(){
            JuBe.Admin.selectImage(pickImage);
        });
        pickImage(item.image);
        $("#itemDescription").val(item.description);
        $("#itemPrice").val(formatPrice(item.price));
    }



    function readForm() {
        return {
            id: $("#itemId").val(),
            title: $("#itemTitle").val(),
            category: $("#itemCategory").val(),
            description:  $("#itemDescription").val(),
            price: parsePrice($("#itemPrice").val()),
            originalId: currentItem.id,
            image: $("#selected-image-name").val()
        }
    }

    function parsePrice(priceString) {
        if(priceString.indexOf(",") == -1 && priceString.indexOf(".") == -1) {
            return priceString * 100;
        }
        priceString = priceString.replace(",", ".");
        return priceString*100;
    }

    function formatPrice(p) {
        return p/100;
    }

    function listItems(successCallback){
        $.ajax("items.list.json.php", {
            dataType: "json",
            success: successCallback,
            error: JuBe.Admin.errorAlert
        });
    }

    function pickImage(imageName) {
        var $formField = $("#selected-image-name");
        var $preview = $("#item-image-preview");
        if(!imageName) {
            $preview.hide();
            $formField.val("");
            return;
        }
        var thumbPath = '../media/'+imageName+'/size_thumb.jpg';
        $preview.children().remove();
        $('<div><img src="'+thumbPath+'"><br>'+imageName+'</div>').appendTo($preview);
        $preview.show();
        $formField.val(imageName);
    }

    function saveItem(item) {
        $.ajax("items.save.json.php", {
            dataType: "json",
            data: item,
            type: "POST",
            success: function(data){
                console.log(data);
                JuBe.Admin.OnlyOnSuccess(data, function(){
                    window.location.reload();
                });
            },
            error: JuBe.Admin.errorAlert
        });

    }
})()