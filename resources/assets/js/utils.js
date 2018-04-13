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

    dateDiff: (d1, d2) =>
    {
        return Math.abs(Math.round( (new Date(Date.parse(d2))) - (new Date(Date.parse(d1))) ));
    },

    timestampBeautify: (timestamp) =>
    {
        var d = new Date(Date.parse(timestamp));
        return d.getDate() + " " + window.translations.month[d.getMonth()] + ", " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
    },

    timestampToClock: (timestamp) =>
    {
        var d = new Date(Date.parse(timestamp));
        return ("0" + d.getHours()).slice(-2) + "h" + ("0" + d.getMinutes()).slice(-2);
    },

    getLatLong: (address, success) =>
    {
        window.utils.ajax("https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA_SPrS2FbV7zUJhHB-mljKo8LsdvjktJg&address=" + address, {}, e => {
            e = JSON.parse(e);
            success(e.results.length > 0 ? {
                formatted_address: e.results[0].formatted_address,
                location: e.results[0].geometry.location
            } : {});
        });
    }
};