Node.prototype.hide = function hide() { this.classList.add('hide'); };
Node.prototype.show = function show() { this.classList.remove('hide'); };
Node.prototype.display = function display(disp) { if (disp) this.show(); else this.hide(); };

// App
export function ajax(url, data, success, error, method) {
  const params = typeof data === 'string' ? data : Object.keys(data).map(
    k => `${encodeURIComponent(k)}=${encodeURIComponent(data[k])}`
  ).join('&');

  if (method === undefined) { method = 'POST'; }

  const xhr = new XMLHttpRequest();
  xhr.open(method, url);
  xhr.onreadystatechange = () => {
    if (xhr.readyState > 3) {
      if (xhr.status === 200) {
        if (success !== undefined) {
          try {
            success(JSON.parse(xhr.responseText));
          } catch (e) {
            success(xhr.responseText);
          }
        }
      } else if (error !== undefined) {
        error();
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
