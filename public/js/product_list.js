$(document).ready(function () {
    $('.add_to_cart_js').on('click', function (e) {
        e.preventDefault();
        var $link = $(e.currentTarget);
        $link.toggleClass('btn-info').toggleClass('btn-block');
// console.log($link.attr('href'))
        $.ajax({
            method: 'POST',
            url: $link.attr('href'),
        })
        alert("Dodałeś produkt do koszyka!!!!")
    });
});