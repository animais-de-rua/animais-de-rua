// Prototype
NodeList.prototype.__proto__ = HTMLCollection.prototype.__proto__ = Array.prototype;

// Query
window.query = document.querySelector.bind(document);
window.queryAll = document.querySelectorAll.bind(document);
Node.prototype.query = function(selector) { return this.querySelector(selector) }
Node.prototype.queryAll = function(selector) { return this.querySelectorAll(selector) }
NodeList.prototype.query = function(selector) { return this.queryAll(selector)[0] }
NodeList.prototype.queryAll = function(selector) {
    let results = [];
    this.map(elem => results.push(...elem.first(selector)));
    return results;
}

// Utils
Node.prototype.sibling = function(query) { return this.siblings(query)[0] }
Node.prototype.siblings = function(query) {
    let elems = query ? this.parentElement.queryAll(query) : this.parentElement.children;
    return elems.filter(e => this != e);
}

Node.prototype.index = function() {
    let elem = this, i = 0;
    while(elem = elem.previousElementSibling)
        i++;
    return i;
}

// App
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

export function template(selector) {
    return query(selector).content.cloneNode(true);
}