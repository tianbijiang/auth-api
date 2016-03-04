$(document).ready(function () {
    var EDIT_ROLE_URL = "/adminHome/roleMgmt/edit",
        NEW_ROLE_URL = "/adminHome/roleMgmt/new",
        DELETE_ROLE_URL = "/adminHome/roleMgmt/delete";
    var TECHNICIAN_RNAME = "TECHNICIAN";

    var newRnameForm = $("#newRnameForm");
    var newDescForm = $("#newDescForm");
    var editRnameForm = $("#editRnameForm");
    var editDescForm = $("#editDescForm");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // tabs
    var links = [];
    var buttons = $('a.show-tab');
    buttons.each(function () {
        var link = this.getAttribute("href");
        links.push(link);
        $(link).hide();
    });
    $(links[0]).show();

    buttons.click(function () {
        var link = this.getAttribute("href");
        for (var i = 0; i < links.length; i++) {
            if (links[i] == link)
                $(links[i]).show();
            else
                $(links[i]).hide();
        }
    });

    var role;
    var d_role;

    $('#logout').click(function () {
        $.ajax({
            url: '../logout',
            type: 'get',
            success: function () {
                window.location = "/";
            },
            error: function () {
                console.log('error');
            }
        });
    });

    $('.editRole').click(function () {
        role = this.getAttribute('id');
        var rname = $("#" + role + ".tb-rname").html();
        var desc = $("#" + role + ".tb-desc").html();

        $('#editRole-popup')[0].style.display = 'block';
        $('#editRname').val($.trim(rname));
        $('#editDesc').val($.trim(desc));
    });

    $('#editRole-popup-close-btn').click(function () {
        $('#editRole-popup')[0].style.display = 'none';
    });

    // form submission and validation
    $.validator.addMethod("rolenameformat", function (value, element) {
        return this.optional(element) || /^TECHNICIAN\_\w+$/.test(value);
    }, "Please use letters, underscores, numbers, and start with 'TECHNICIAN_'.");

    $("#editRoleForm").validate({
        rules: {
            editRname: {
                required: true,
                rolenameformat: true
            },
            editDesc: {
                required: true
            }
        },
        submitHandler: function () {
            // TODO: dup check
            var rname = $.trim($("#editRname").val());
            var desc = $.trim($("#editDesc").val());

            $('#editRole-popup')[0].style.display = 'none';
            $.ajax({
                url: EDIT_ROLE_URL,
                type: 'post',
                data: {"role": role, "newRole": rname, "desc": desc},
                success: function () {
                    location.reload();
                },
                error: function () {
                    console.log('error - commit change');
                }
            });
        }
    });

    $("#newRoleForm").validate({
        rules: {
            newRname: {
                required: true,
                rolenameformat: true
            },
            newDesc: {
                required: true
            }
        },
        submitHandler: function () {
            // TODO: dup check
            var rname = $.trim($("#newRname").val());
            var desc = $.trim($("#newDesc").val());
            $.ajax({
                url: NEW_ROLE_URL,
                type: 'post',
                data: {"role": rname, "desc": desc},
                success: function () {
                    location.reload();
                },
                error: function () {
                    console.log('error - create new');
                }
            });
        }
    });

    $('.deleteRole').click(function () {
        d_role = this.getAttribute('id');
        $('#deleteRole-popup')[0].style.display = 'block';
    });

    $('#deleteRole-popup-close-btn').click(function () {
        $('#deleteRole-popup')[0].style.display = 'none';
    });

    $('#deleteRole-done').click(function () {
        $('#deleteRole-popup')[0].style.display = 'none';
        $.ajax({
            url: DELETE_ROLE_URL,
            type: 'post',
            data: {"role": d_role},
            success: function () {
                location.reload();
            },
            error: function () {
                console.log('error - delete');
            }
        });
    });

    //function checkNewRoleEntry(rname, desc) {
    //    var error = [];
    //    newRnameForm.removeClass("has-error");
    //    newDescForm.removeClass("has-error");
    //    if (rname == "" ||
    //        rname == (TECHNICIAN_RNAME + "_")) {
    //        error.push("Empty entry for role name.");
    //        newRnameForm.addClass("has-error");
    //    }
    //    if (desc == "") {
    //        error.push("Empty entry for role description.");
    //        newDescForm.addClass("has-error");
    //    }
    //    if (rname.substring(0, TECHNICIAN_RNAME.length + 1) != (TECHNICIAN_RNAME + "_")) {
    //        error.push("Role names have to start with 'TECHNICIAN_'.");
    //        newRnameForm.addClass("has-error");
    //    }
    //    return error;
    //}
    //
    //function checkEditRoleEntry(rname, desc) {
    //    var error = [];
    //    editRnameForm.removeClass("has-error");
    //    editDescForm.removeClass("has-error");
    //    if (rname == "" ||
    //        rname == (TECHNICIAN_RNAME + "_")) {
    //        error.push("Empty entry for role name.");
    //        editRnameForm.addClass("has-error");
    //    }
    //    if (desc == "") {
    //        error.push("Empty entry for role description.");
    //        editDescForm.addClass("has-error");
    //    }
    //    if (rname.substring(0, TECHNICIAN_RNAME.length + 1) != (TECHNICIAN_RNAME + "_")) {
    //        error.push("Role names have to start with 'TECHNICIAN_'.");
    //        editRnameForm.addClass("has-error");
    //    }
    //    return error;
    //}

});