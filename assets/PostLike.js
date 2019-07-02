function joapp_api_post_like(site, img, id) {

    img.style.display = "none";
    var parent = img.parentElement;
    var fnWhenDone = function (R) {
        parent.innerHTML = R.responseText;
    };
    var fnWhenError = function (sType, R) {
        img.style.display = "auto";
    };
    Net.get({url: site, vars: {joapp_api_post_like: id}, onsuccess: fnWhenDone, onerror: fnWhenError});

}