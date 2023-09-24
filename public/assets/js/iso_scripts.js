function isoGetCurrentURL () {
    return window.location.href
}

function isoSetStorage(id, array) {
    //localStorage.setItem(id, JSON.stringify(array));
    localStorage.setItem(id, array);
}

function isoGetStorage(id) {
    var ls = localStorage.getItem(id);
    //return JSON.parse(ls);
    return ls;
}

