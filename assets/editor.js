'use strict';

(function ($) {
    var editor = {
        'code': null,
        'init': null
    };

    var view_options = ['after-n-p', 'after-n-img', 'after-n-post', 'after-each'];

    function displayError(message) {
        var $error = $('#message-error');
        $error.hide();

        $error.find('p').html(message);
        $error.show();
    }

    function validateForm(formObject) {
        if ($('input#title', formObject).val().length < 2) {
            displayError(rpam_errors.title);
            $('input#title', formObject).focus();
            return false;
        }

        if ($('select[title=group]', formObject).val() === 'CREATENEW') {
            if ($('input#group').val().length < 2) {
                displayError(rpam_errors.group);
                $('input#group', formObject).focus();
                return false;
            }
        }

        var loop = true;

        $('select[title=policy]:visible', formObject).each(function (i, e) {
            var $container = $(this).closest('.place-options');

            if ($(e).val() !== 'none') {
                var check = $container.find('input[title=ids]');
                if (check.val() === '') {
                    displayError(rpam_errors.policy);
                    check.focus();
                    loop = false;
                    return false;
                }
            }
        });

        if (false === loop) {
            return false;
        }

        $('select[id$=-position]:visible', formObject).each(function () {
            var $container = $(this).closest('.place-options');

            if (-1 !== view_options.indexOf($(this).val())) {
                var check = $container.find('input[type=number]');
                if (!/^[\d]+$/.test(check.val())) {
                    displayError(rpam_errors.number);
                    check.focus();
                    loop = false;
                    return false;
                }
            }
        });

        if (false === loop) {
            return false;
        }

        if ($('textarea#rp-ads-manager-code', formObject).val().length < 10) {
            displayError(rpam_errors.code);
            editor.code.codemirror.focus();
            return false;
        }

        if ($('input#separate-init-code-checker', formObject).prop('checked')) {
            var textarea = $('textarea#rp-ads-manager-init-code', formObject);

            if (textarea.val() < 10) {
                displayError(rpam_errors.init);
                editor.init.codemirror.focus();
                return false;
            }
        }

        return test;
    }

    function resetViewOptions($container) {
        var $number = $container.find('input[type=number].view-option'),
            $checkbox = $container.find('input[type=checkbox].view-option');
        $number.val('').prop('disabled', true);
        $checkbox.prop('checked', false).prop('disabled', true);
        $container.find('.view-options').slideUp();
    }

    function resetPosition($container) {
        resetViewOptions($container);
        $('.f-block input', $container).val('').prop('disabled', true);
        $('select:not([title=policy])', $container).val('');
        $('select[title=policy]', $container).val('none');
        $('select', $container).each(function(i,e) {
            e.selectize.disable();
        });

        $container.slideUp();
    }

    editor.code = wp.codeEditor.initialize('rp-ads-manager-code');

    var initCodeTextArea = $('#rp-ads-manager-init-code');

    if (!initCodeTextArea.prop('disabled')) {
        editor.init = wp.codeEditor.initialize('rp-ads-manager-init-code');
        initCodeTextArea.data('editor', 'enabled');
    }

    $('#separate-init-code-checker').on('change', function () {
        var $blockWrapper = $('.separate-init-code-wrapper'),
            status = !!$(this).prop('checked'),
            $textAreaHolder = $blockWrapper.find('.text-area-holder');

        $blockWrapper.find('textarea, select').each(function (i, e) {
            $(e).prop('disabled', !status);
        });

        if (true === status) {
            $textAreaHolder.slideDown(400, function () {
                var $textArea = $textAreaHolder.find('textarea');

                if ('enabled' !== $textArea.data('editor')) {
                    editor.init = wp.codeEditor.initialize('rp-ads-manager-init-code');
                    $textArea.data('editor', 'enabled');
                }
            })
        } else {
            editor.init.codemirror.setValue('');
            $('#separate-init-code-place').val('');
            $textAreaHolder.slideUp()
        }

    });

    $('select[id$=-position]').on('change', function () {
        var $container = $(this).closest('.place-options');

        if (-1 !== view_options.indexOf($(this).val())) {
            $container.find('.view-options :disabled').prop('disabled', false);
            $container.find('.view-options').slideDown();
        } else {
            resetViewOptions($container);
        }
    });

    $('select[title=policy]').on('change', function () {
        var $container = $(this).closest('.place-options');
        if ($(this).val() !== 'none') {
            $('input[title=ids]', $container).prop('disabled', false)
        } else {
            $('input[title=ids]', $container).val('').prop('disabled', true);
        }
    });

    $('input[type=checkbox].show-positions').on('change', function () {
        var $container = $(this).closest('.place-container').find('.place-options');
        if ($(this).prop('checked')) {
            $('.f-block input:not([title=ids])', $container).prop('disabled', false);
            //$('select', $container).prop('disabled', false);
            $('select', $container).each(function(i,e) {
                e.selectize.enable();
            });//prop('disabled', false);
            $container.slideDown();
        } else {
            resetPosition($container);
        }
    });

    $('#rpam-form').on('submit', function (e) {
        $('#message-error').hide();
        if (false === validateForm($(this))) {
            e.preventDefault();
        }
    });

    $('.place-container select').selectize();

    $('select#rpam-group').selectize({
        create: true,
        allowEmptyOption: true
    });

})(jQuery);