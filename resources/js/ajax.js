$(document).ready(function () {

    $('#btn-login').on('click', function () {
        clearLoginFormLabels();
        var arr = $('#form_login').serializeArray();
        var formData = new FormData();

        $.each(arr, function () {
            formData.append(this.name, this.value);
        });

        $.ajax({
            url: '/auth/login',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                
                    if (typeof data.path == "undefined") {
                        if (typeof data.authorization_failed_message != "undefined") {

                            $('#form_login').find("label[name='lbl-authorization-fail']").text(data.authorization_failed_message);
                        } else {
                            $.each(data, function () {
                                if (typeof this.login_error_message != "undefined") {
                                    $('#form_login').find("label[name='lbl-login-error']").text(this.login_error_message);
                                }
                                if (typeof this.password_error_message != "undefined") {
                                    $('#form_login').find("label[name='lbl-password-error']").text(this.password_error_message);
                                }
                            });
                        }
                    } else {
                        window.location.href = data.path;
                    }
            }
        });

    });

    $('#btn-reg').on('click', function () {
        clearRegisterFormLabels();

        var arr = $('#register_form').serializeArray();
        var formData = new FormData();

        $.each(arr, function () {
            formData.append(this.name, this.value);
        });

        $.ajax({
            url: '/auth/register',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                if (typeof data.path == "undefined") {
                    $.each(data, function () {
                        if (typeof this.register_login_message != "undefined") {
                            $('#register_form').find("label[name='lbl-login-error']").text(this.register_login_message);
                        }
                        if (typeof this.register_name_message != "undefined") {
                            $('#register_form').find("label[name='lbl-name-error']").text(this.register_name_message);
                        }
                        if (typeof this.register_password_message != "undefined") {
                            $('#register_form').find("label[name='lbl-password-error']").text(this.register_password_message);
                        }
                        if (typeof this.register_exists_message != "undefined") {
                            $('#register_form').find("label[name='lbl-login-error']").text(this.register_exists_message);
                        }
                        if (typeof this.register_email_exists_message != "undefined") {
                            $('#register_form').find("label[name='lbl-email-error']").text(this.register_email_exists_message);
                        }
                        if (typeof this.register_email_message != "undefined") {
                            $('#register_form').find("label[name='lbl-email-error']").text(this.register_email_message);
                        }
                    });
                } else {
                    window.location.href = data.path;
                }
            }
        });

    });

    $('#btn-logout').on('click', function () {

        $.ajax({
            url: '/logout',
            processData: false,
            contentType: false,
            dataType: 'json',
            type: 'GET',
            success: function (data) {
                window.location.href = data.path;
            }
        });

    });


    function clearLoginFormLabels() {
        $('#form_login').find("label[name='lbl-login-error']").text('');
        $('#form_login').find("label[name='lbl-password-error']").text('');
        $('#form_login').find("label[name='lbl-authorization-fail']").text('');
    }

    function clearRegisterFormLabels() {
        $('#register_form').find("label[name='lbl-login-error']").text('');
        $('#register_form').find("label[name='lbl-name-error']").text('');
        $('#register_form').find("label[name='lbl-password-error']").text('');
        $('#register_form').find("label[name='lbl-email-error']").text('');
    }

});