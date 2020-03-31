// $(document).load(function () {
    var frame = $('#frame');
    loadPage();
// });

function loadPage() {
    $.ajax({
        url: '/getWebsite',
        type: "GET",

        success: function (data) {
            $('#frame').html(data);
        }, error: function (data) {
            // var response = data.responseText;
            // response = JSON.parse(response);
            // console.log(response.data);
        }
    });
}
