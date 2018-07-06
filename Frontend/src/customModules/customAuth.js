module.exports = {
    request: function (req, token) {
        token = token.split(';');
        token = token[0];

        this.options.http._setHeaders.call(this, req, { Authorization: 'Bearer ' + token });
    },
    response: function (res) {
        if (res.data.access_token && res.data.refresh_token && res.data.expires_in) {    
            return res.data.access_token + ';' + res.data.refresh_token + ';' + res.data.expires_in;
        }
    }
}
