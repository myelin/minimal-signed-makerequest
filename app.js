/* This will make a signed request to ROOT_URL/signed_endpoint.php. */

gadgets.util.registerOnLoadHandler(function() {
    var status = $("#status");

    status.append('<li>Sending request</li>');
    var params = {};
    params[gadgets.io.RequestParameters.METHOD] = gadgets.io.MethodType.POST;
    params[gadgets.io.RequestParameters.AUTHORIZATION] = gadgets.io.AuthorizationType.SIGNED;
    params[gadgets.io.RequestParameters.CONTENT_TYPE] = gadgets.io.ContentType.JSON;
    var post_args = {f: "test_method"};
    $.each({
        arg1: "test arg1",
        arg2: "test arg2"
    }, function(k, v) { post_args[k] = v; });
    params[gadgets.io.RequestParameters.POST_DATA] = gadgets.io.encodeValues(post_args);
    gadgets.io.makeRequest(ROOT_URL + "/signed_endpoint.php", function(resp) {
        if (!resp.data) { alert("unknown error"); return; }
        status.append($('<li></li>').text("It worked!  Message: " + resp.data.msg));
    }, params);
});
