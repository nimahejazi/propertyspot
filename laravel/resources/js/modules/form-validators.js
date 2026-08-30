const {RKFormValidator, Validator}  = require('rk-form-validator');

module.exports = (path, $) => {
    let addSubmitEvent = false;
    if (/^\/signup\/?$/.test(path)) {
        const rkFormValidator = new RKFormValidator();
        rkFormValidator.addValidator(new Validator(
            {id: 'email', title: 'Email'},
            ['required', 'email'],
            {id: 'email-err'},
            'is-danger'
        ));
        rkFormValidator.addValidator(new Validator(
            {id: 'password', title: 'Password'},
            ['required', 'min:8', 'max:40', 'include:2:1234567890|Your password must contain at least 2 numbers.'],
            {id: 'password-err'},
            'is-danger'
        ));
        rkFormValidator.addValidator(new Validator(
            {id: 'password_confirmation', title: 'Password confirmation'},
            ['required', 'compare:eq:password|Passwords do not match.'],
            {id: 'password_confirmation-err'},
            'is-danger'
        ));
        $('form').on('submit', e => {
            rkFormValidator.checkAll();
            if (rkFormValidator.hasErrors()) {
                e.preventDefault();
            } else {
                $('#submit').addClass('is-loading');
            }
        });
        return;
    }

    if (/^\/signin\/?$/.test(path)) {
        const rkFormValidator = new RKFormValidator();
        rkFormValidator.addValidator(new Validator(
            {id: 'email', title: 'Email'},
            ['required', 'email'],
            {id: 'email-err'},
            'is-danger'
        ));
        rkFormValidator.addValidator(new Validator(
            {id: 'password', title: 'Password'},
            ['required'],
            {id: 'password-err'},
            'is-danger'
        ));
        $('form').on('submit', e => {
            rkFormValidator.checkAll();
            if (rkFormValidator.hasErrors()) {
                e.preventDefault();
            } else {
                $('#submit').addClass('is-loading');
            }
        });
        return;
    }

    if (/^\/users\/profile\/?$/.test(path)) {
        const rkFormValidator = new RKFormValidator();
        rkFormValidator.addValidator(new Validator(
            {id: 'email', title: 'Email'},
            ['required', 'email'],
            {id: 'email-err'},
            'is-danger'
        ));
        $('form').on('submit', e => {
            rkFormValidator.checkAll();
            if (rkFormValidator.hasErrors()) {
                e.preventDefault();
            } else {
                $('#submit').addClass('is-loading');
            }
        });
        return rkFormValidator;
    }

    if (path === 'simple') {
        const rkFormValidator = new RKFormValidator();
        const rkModalFormValidator = new RKFormValidator();
        rkFormValidator.addValidator(new Validator(
            {id: 'name', title: 'Name'},
            ['required|Please enter your name.'],
            {id: 'name-err'},
            'is-invalid'
        ));
        rkFormValidator.addValidator(new Validator(
            {id: 'email', title: 'Email'},
            ['required|Please enter your email.', 'email|Please enter a valid email address, e.g. jsmith@example.com.'],
            {id: 'email-err'},
            'is-invalid'
        ));
        rkFormValidator.addValidator(new Validator(
            {id: 'phone', title: 'Phone'},
            ['required|Please enter your phone number.'],
            {id: 'phone-err'},
            'is-invalid'
        ));
        rkFormValidator.addValidator(new Validator(
            {id: 'message', title: 'Message'},
            ['required|Please enter your message.'],
            {id: 'message-err'},
            'is-invalid'
        ));

        rkModalFormValidator.addValidator(new Validator(
            {id: 'name-modal', title: 'Name'},
            ['required|Please enter your name.'],
            {id: 'name-modal-err'},
            'is-invalid'
        ));
        rkModalFormValidator.addValidator(new Validator(
            {id: 'email-modal', title: 'Email'},
            ['required|Please enter your email.', 'email|Please enter a valid email address, e.g. jsmith@example.com.'],
            {id: 'email-modal-err'},
            'is-invalid'
        ));
        rkModalFormValidator.addValidator(new Validator(
            {id: 'phone-modal', title: 'Phone'},
            ['required|Please enter your phone number.'],
            {id: 'phone-modal-err'},
            'is-invalid'
        ));

        // Will check for errors in simple.js

        return [rkFormValidator, rkModalFormValidator];
    }

    if (/^\/forgot-password\/?$/.test(path)) {
        const rkFormValidator = new RKFormValidator();
        rkFormValidator.addValidator(new Validator(
            {id: 'email', title: 'Email'},
            ['required', 'email'],
            {id: 'email-err'},
            'is-danger'
        ));
        $('form').on('submit', e => {
            rkFormValidator.checkAll();
            if (rkFormValidator.hasErrors()) {
                e.preventDefault();
            } else {
                $('#submit').addClass('is-loading');
            }
        });
        return;
    }
    if (/^\/reset-password\/?.*/.test(path)) {
        const rkFormValidator = new RKFormValidator();
        rkFormValidator.addValidator(new Validator(
            {id: 'password', title: 'Password'},
            ['required', 'min:8', 'max:40', 'include:2:1234567890|Your password must contain at least 2 numbers.'],
            {id: 'password-err'},
            'is-danger'
        ));
        rkFormValidator.addValidator(new Validator(
            {id: 'password_confirmation', title: 'Password confirmation'},
            ['required', 'compare:eq:password|Passwords do not match.'],
            {id: 'password_confirmation-err'},
            'is-danger'
        ));
        $('form').on('submit', e => {
            rkFormValidator.checkAll();
            if (rkFormValidator.hasErrors()) {
                e.preventDefault();
            } else {
                $('#submit').addClass('is-loading');
            }
        });
        return;
    }

}
