$(document).ready(function () {
    var CREATE_USER_URL = "/adminHome/techMgmt/new",
        EDIT_USER_URL = "/adminHome/techMgmt/edit",
        DELETE_USER_URL = "/adminHome/techMgmt/delete";

    var no_role_err_msg = "No roles assigned to this technician.";

    var uuid;
    var d_uuid;

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

    $('.editUser').click(function () {
        uuid = this.getAttribute('id');
        var fname = $.trim($("#" + uuid + ".tb-fname").html());
        var lname = $.trim($("#" + uuid + ".tb-lname").html());
        var username = $("#" + uuid + ".tb-username").html();
        var roles = $("#" + uuid + ".tb-roles").html().split("<br>");
        if ($.trim(roles[0]) == no_role_err_msg) {
            roles = [];
        }

        $('#editUser-popup')[0].style.display = 'block';
        $('#editFname').val(fname);
        $('#editLname').val(lname);
        $('#editUsername').val(username);
        var roleSel = $('.editRoles');
        roleSel.prop('checked', false);
        for (var i = 0; i < roles.length - 1; i++) {
            var role = $.trim(roles[i]);
            roleSel.filter("#" + role).prop('checked', true);
        }
    });

    $('#editUser-popup-close-btn').click(function () {
        $('#editUser-popup')[0].style.display = 'none';
    });

    // form submission and validation
    $.validator.addMethod("nameformat", function (value, element) {
        return this.optional(element) || /^(?=.*[A-Za-z])([A-Za-z ]+)$/i.test(value);
    }, "Not a name format.");

    $.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^\w+$/i.test(value);
    }, "Letters, numbers, and underscores only please");

    $("#editUserForm").validate({
        rules: {
            editFname: {
                required: true,
                nameformat: true
            },
            editLname: {
                required: true,
                nameformat: true
            },
            editUsername: {
                required: true,
                alphanumeric: true
            }
        },
        submitHandler: function () {
            // TODO: dup check
            var fname = $.trim($("#editFname").val());
            var lname = $.trim($("#editLname").val());
            var username = $.trim($("#editUsername").val());
            var roles = [];
            $('.editRoles:checked').each(function () {
                roles.push($(this).val());
            });
            $('#editUser-popup')[0].style.display = 'none';
            $.ajax({
                url: EDIT_USER_URL,
                type: 'post',
                data: {"uuid": uuid, "fname": fname, "lname": lname, "username": username, "roles": roles},
                success: function () {
                    location.reload();
                },
                error: function () {
                    console.log('error - commit change');
                }
            });
        }
    });

    $("#newUserForm").validate({
        rules: {
            newUsername: {
                required: true,
                alphanumeric: true
            },
            newPassword: {
                required: true,
                alphanumeric: true,
                minlength: 4
            },
            retypePassword: {
                required: true,
                equalTo: "#newPassword"
            },
            newFname: {
                required: true,
                nameformat: true
            },
            newLname: {
                required: true,
                nameformat: true
            }
        },
        submitHandler: function () {
            var username = $.trim($("#newUsername").val());
            var password = $("#newPassword").val();
            var retypePassword = $("#retypePassword").val();
            var fname = $.trim($("#newFname").val());
            var lname = $.trim($("#newLname").val());
            var roles = [];
            $(".newRole:checked").each(function () {
                roles.push($(this).val());
            });
            // TODO: dup check
            $.ajax({
                url: CREATE_USER_URL,
                type: 'post',
                data: {"username": username, "password": password, "fname": fname, "lname": lname, "roles": roles},
                success: function () {
                    location.reload();
                },
                error: function () {
                    console.log('error - create new');
                }
            });
        }
    });

    $('.deleteUser').click(function () {
        d_uuid = this.getAttribute('id');
        $('#deleteUser-popup')[0].style.display = 'block';
    });

    $('#deleteUser-popup-close-btn').click(function () {
        $('#deleteUser-popup')[0].style.display = 'none';
    });

    $('#deleteUser-done').click(function () {
        $('#deleteUser-popup')[0].style.display = 'none';
        $.ajax({
            url: DELETE_USER_URL,
            type: 'post',
            data: {"uuid": d_uuid},
            success: function () {
                location.reload();
            },
            error: function () {
                console.log('error - delete');
            }
        });
    });

});