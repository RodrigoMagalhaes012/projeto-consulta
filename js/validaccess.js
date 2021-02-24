$(document).ready(function () {

    $('#c_senha').keyup(function () {
        var password = $('#c_senha').val();
        if (checkStrength(password) == false) {
            $('#sign-up').attr('disabled', true);
        }
    });
    $('#c_senha2').blur(function () {
        if ($('#c_senha').val() !== $('#c_senha2').val()) {
            $('#popover-cpassword').removeClass('hide');
            $('#sign-up').attr('disabled', true);
        } else {
            $('#popover-cpassword').addClass('hide');
        }
    });



    function checkStrength(password) {
        var strength = 0;


        // SEQUENCIAL
        function duplicateCount(text) {
            text = text.toLowerCase().split('').sort().join('');
            let count = i = 0;
            while (i < text.length) {
                let p1 = text.indexOf(text[i]);
                let p2 = text.lastIndexOf(text[i]);
                var len = text.substr(p1, p2 - p1 + 1).length;
                len != 1 ? (count++, i += len) : i++;
            }
            return count;
        }





        // MINUSCULA
        if (password.match(/([a-z])/)) {
            strength += 1;
            $('.low-case').addClass('text-primary');
            $('.low-case i').removeClass('fa-times').addClass('fa-check');
            $('#popover-password-top').addClass('hide');
        } else {
            $('.low-case').removeClass('text-primary');
            $('.low-case i').addClass('fa-times').removeClass('fa-check');
            $('#popover-password-top').removeClass('hide');
        }

        // MAIUSCULA
        if (password.match(/([A-Z])/)) {
            strength += 1;
            $('.upper-case').addClass('text-primary');
            $('.upper-case i').removeClass('fa-times').addClass('fa-check');
            $('#popover-password-top').addClass('hide');
        } else {
            $('.upper-case').removeClass('text-primary');
            $('.upper-case i').addClass('fa-times').removeClass('fa-check');
            $('#popover-password-top').removeClass('hide');
        }

        //If it has numbers and characters, increase strength value.
        if (password.match(/([0-9])/)) {
            strength += 1;
            $('.one-number').addClass('text-primary');
            $('.one-number i').removeClass('fa-times').addClass('fa-check');
            $('#popover-password-top').addClass('hide');

        } else {
            $('.one-number').removeClass('text-primary');
            $('.one-number i').addClass('fa-times').removeClass('fa-check');
            $('#popover-password-top').removeClass('hide');
        }

        //If it has one special character, increase strength value.
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
            strength += 1;
            $('.one-special-char').addClass('text-primary');
            $('.one-special-char i').removeClass('fa-times').addClass('fa-check');
            $('#popover-password-top').addClass('hide');

        } else {
            $('.one-special-char').removeClass('text-primary');
            $('.one-special-char i').addClass('fa-times').removeClass('fa-check');
            $('#popover-password-top').removeClass('hide');
        }

        if (password.length > 7) {
            strength += 1;
            $('.eight-character').addClass('text-primary');
            $('.eight-character i').removeClass('fa-times').addClass('fa-check');
            $('#popover-password-top').addClass('hide');

        } else {
            $('.eight-character').removeClass('text-primary');
            $('.eight-character i').addClass('fa-times').removeClass('fa-check');
            $('#popover-password-top').removeClass('hide');
        }


        // If value is less than 2

        switch (strength) {
            case 1:
                $('#result').removeClass();
                $('#result').addClass('text-danger').text('Fraca');

                $('#passbar').removeClass("progress-bar-primary");
                $('#passbar').removeClass("progress-bar-warning");
                $('#passbar').removeClass("progress-bar-danger");
                $('#passbar').addClass('progress-bar-danger');

                $("#c_senha2").prop("disabled", true);
                $("#btn_login").prop("disabled", true);
                $("#passbar")
                .css("width", "20%")
                .attr("aria-valuenow", 20);
                break;
            case 2:
                $('#result').removeClass();
                $('#result').addClass('text-danger').text('Fraca');

                $('#passbar').removeClass("progress-bar-primary");
                $('#passbar').removeClass("progress-bar-warning");
                $('#passbar').removeClass("progress-bar-danger");
                $('#passbar').addClass('progress-bar-danger');

                $("#c_senha2").prop("disabled", true);
                $("#btn_login").prop("disabled", true);
                $("#passbar")
                .css("width", "40%")
                .attr("aria-valuenow", 40);
                break;
            case 3:
                $('#result').removeClass();
                $('#result').addClass('text-danger').text('Boa');

                $('#passbar').removeClass("progress-bar-primary");
                $('#passbar').removeClass("progress-bar-warning");
                $('#passbar').removeClass("progress-bar-danger");
                $('#passbar').addClass('progress-bar-warning');

                $("#c_senha2").prop("disabled", true);
                $("#btn_login").prop("disabled", true);
                $("#passbar")
                .css("width", "60%")
                .attr("aria-valuenow", 60);
                break;
            case 4:
                $('#result').removeClass();
                $('#result').addClass('text-danger').text('Boa');

                $('#passbar').removeClass("progress-bar-primary");
                $('#passbar').removeClass("progress-bar-warning");
                $('#passbar').removeClass("progress-bar-danger");
                $('#passbar').addClass('progress-bar-warning');

                $("#c_senha2").prop("disabled", true);
                $("#btn_login").prop("disabled", true);
                $("#passbar")
                .css("width", "80%")
                .attr("aria-valuenow", 80);
                break;
            case 5:
                $('#result').removeClass();
                $('#result').addClass('text-primary').text('Excelente');

                $('#passbar').removeClass("progress-bar-primary");
                $('#passbar').removeClass("progress-bar-warning");
                $('#passbar').removeClass("progress-bar-danger");
                $('#passbar').addClass('progress-bar-primary');

                $("#c_senha2").prop("disabled", false);
                $("#btn_login").prop("disabled", false);
                $("#passbar")
                .css("width", "100%")
                .attr("aria-valuenow", 100);
                break;

            default:
                $('#result').removeClass();
                $('#result').addClass('text-danger').text('Fraca');

                $('#passbar').removeClass("progress-bar-primary");
                $('#passbar').removeClass("progress-bar-warning");
                $('#passbar').removeClass("progress-bar-danger");
                $('#passbar').addClass('progress-bar-danger');

                $("#c_senha2").prop("disabled", true);
                $("#btn_login").prop("disabled", true);
                $("#passbar")
                .css("width", "0%")
                .attr("aria-valuenow", 0);
        }
    }
});