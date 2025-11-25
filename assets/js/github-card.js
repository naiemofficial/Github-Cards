
function get_or_null(obj, key) {
    return obj && obj.hasOwnProperty(key) ? obj[key] : null;
}
