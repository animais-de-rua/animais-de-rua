export const _prototypes = HTMLCollection.prototype.map = NodeList.prototype.map = Array.prototype.map;

export function ajax(url, data, success, error, method) {
    const params = typeof data == 'string' ? data : Object.keys(data).map(
            k => encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
        ).join('&');

    if(method === undefined)
        method = "POST";

    let xhr = new XMLHttpRequest();
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
}

export function indexOf(child) {
    let i = 0;
    while( (child = child.previousElementSibling) != null)
        i++;
    return i;
}