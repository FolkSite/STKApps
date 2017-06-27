window.onload = function () {
    var copyAdName = document.getElementById("copyAdName");
    var copyAdDescription = document.getElementById("copyAdDescription");
    var removeFormattingBtn = document.getElementById("removeFormattingBtn");

    copyAdName.onclick = function () {
        copyArea("adName");
    }

    copyAdDescription.onclick = function () {
        copyArea("adDescription");
    }

    removeFormattingBtn.onclick = function () {
        removeFormatting("adDescription");
    }

    function copyArea(copyAreaID) {
        var copyArea = document.getElementById(copyAreaID);
        var range = document.createRange();
        range.selectNode(copyArea);
        window.getSelection().addRange(range);

        try {
            var successful = document.execCommand("copy");
            var msg = successful ? "successful" : "unsuccessful";
            console.log("Copy text ad " + msg);
        } catch (err) {
            console.log("Oops, unable to copy");
        }

        window.getSelection().removeAllRanges();
    }
    
    // удаляет теги <strong> и </strong>
    function removeFormatting(areaID) {
        var descriptionArea = document.getElementById(areaID);
        var descriptionContent = document.getElementById(areaID).innerHTML;
        
        descriptionContent = descriptionContent.replace(/&lt;strong&gt;/g, "");
        descriptionContent = descriptionContent.replace(/&lt;\/strong&gt;/g, "");
        
        descriptionArea.innerHTML = descriptionContent;
    }
}