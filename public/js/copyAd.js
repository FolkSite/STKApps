window.onload = function () {
    var copyAdName = document.getElementById("copyAdName");
    var copyAdDescription = document.getElementById("copyAdDescription");

    copyAdName.onclick = function () {
        copyArea("adName");
    }

    copyAdDescription.onclick = function () {
        copyArea("adDescription");
    }

    function copyArea(copyAreaID) {
        var copyArea = document.getElementById(copyAreaID);
        console.log(copyArea);
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
}