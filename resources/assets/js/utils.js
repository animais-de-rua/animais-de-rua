HTMLCollection.prototype.map = NodeList.prototype.map = Array.prototype.map;

module.exports = {
    ajax: (url, data, success, error, method) =>
    {
        var params = typeof data == 'string' ? data : Object.keys(data).map(
                k => encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
            ).join('&');

        if(method === undefined)
            method = "POST";

        var xhr = new XMLHttpRequest();
        xhr.open(method, url);
        xhr.onreadystatechange = e => {
            if (xhr.readyState > 3) {
                if(xhr.status == 200) {
                    if(success != undefined) {
                        try {
                            success(JSON.parse(xhr.responseText));
                        } catch(e) {
                            success(xhr.responseText);
                        }
                    }
                } else {
                    error != undefined ? error() : 0;
                }
            }
        };
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(params);
        return xhr;
    },
};