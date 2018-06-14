$(function() {
    // active menu
    var path = $(location).attr('pathname');
    $('.treeview-menu li a[href="' + path + '"]').parents().addClass('active');
});