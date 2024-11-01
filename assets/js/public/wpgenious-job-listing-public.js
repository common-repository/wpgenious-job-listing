jQuery(document).ready(function($) {
	'use strict';

    /*******************************/
    // Common js
    /******************************/
    const ajax_url = script_info.ajaxurl;
    const jobs_per_page = script_info.jobs_per_page;
    const job_list_wrapper = $('#wpgenious-job-listing-job-list-wrapper');
    const search_form = $('#wpgenious-job-listing-search-form');

    /*******************************/
    // Display or hide job paginator btn
    /******************************/
    function display_or_hide_load_more_jobs_btn(paged, max_page) {
        if(load_more_jobs_btn) {
            if(paged >= max_page || max_page == 1) {
                load_more_jobs_btn.hide();
            }else{
                load_more_jobs_btn.show();
                load_more_jobs_btn.attr('data-max-page', max_page);
            }
        }
    }

	/*******************************/
	// Job View
	/******************************/
    const jobId = Number(script_info.job_id);
    if (jobId && !isNaN(jobId)) {
        $.post(ajax_url, {
            action: 'job_views_count',
            'job_id': jobId
        });
    }

    /*******************************/
    // Jobs pagination
    /******************************/
    const load_more_jobs_btn = $('#wpgenious-job-listing-load-more-jobs');
    const load_more_jobs_response_text = script_info.messages.loading;
    const load_more_jobs_text = load_more_jobs_btn.text();
    let paged = 1;

    load_more_jobs_btn.on('click', function (event){
        event.preventDefault();
        paged++;

        load_more_jobs_btn.text(load_more_jobs_response_text);

        //const max_page = load_more_jobs_btn.data('max-page');
        const formData = new FormData(search_form[0]);
        formData.append('paged', paged);
        formData.append('jobs_per_page', jobs_per_page);

        $.ajax({
            url: ajax_url,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'json',
            type: 'POST',
        })
        .done(function (response) {
            job_list_wrapper.append(response.response);
            display_or_hide_load_more_jobs_btn(paged, response.max_num_pages);
        })
        .always(function () {
            load_more_jobs_btn.text(load_more_jobs_text);
        });
    });

    /*******************************/
    // Google rCaptcha
    /******************************/
    if(script_info.g_recaptcha_enabled && script_info.job_id != 0 && grecaptcha) {
        grecaptcha.ready(function() {
            grecaptcha.execute(script_info.g_recaptcha_key, {action: 'submit'}).then(
                token => {
                    const input = document.createElement('input');
                    input.type  = 'hidden';
                    input.name  = 'g-recaptcha-response';
                    input.value = token;

                    $('#wpgenious-job-listing-apply-form').append(input);
                }
            );
        });
    }

    const search_button = $('#wpgenious-job-listing-search-btn');
    const search_button_text = search_button.val();
    const search_button_response_text = search_button.data('responseText');

    search_form.submit(function (event){
        event.preventDefault();
        search_button.val(search_button_response_text);
        paged = 1;

        const  search_params = $('.search-jobs');
        const size = search_params.length;
        let params = [];
        let url_params = '?';

        for (let i = 0; i < size; i++) {
            const value = search_params.eq(i).val();
            if(value.length) {
                params[search_params.eq(i).data('name')] = value;
                url_params += `${search_params.eq(i).data('name')}_field=${value}&`;
            }
        }

        window.history.pushState("", "", url_params);

        const formData = new FormData(search_form[0]);
        formData.append('jobs_per_page', jobs_per_page);

        $.ajax({
            url: ajax_url,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'json',
            type: 'POST',
        })
        .done(function (response) {
            job_list_wrapper.html(response.response);
            display_or_hide_load_more_jobs_btn(1, response.max_num_pages);
        })
        .always(function () {
            search_button.val(search_button_text);
        });
    });

    /*******************************/
    // Apply form
    /******************************/
    const apply_form = $('#wpgenious-job-listing-apply-form');
    const apply_form_message = $('#wpgenious-job-listing-apply-message');
    const apply_form_submit_btn = $('#wpgenious-job-listing-application-submit-btn');
    const apply_form_submit_btn_text = apply_form_submit_btn.val();
    const apply_form_submit_btn_response_text = apply_form_submit_btn.data('responseText');
    const apply_form_max_upload_size = ( script_info.wp_max_upload_size ) ? script_info.wp_max_upload_size : false;
    const error_class = 'wpgenious-job-listing-error-message';
    const success_class = 'wpgenious-job-listing-success-message';

    apply_form.submit(function (event) {
        event.preventDefault();
        apply_form_message.hide();
        let fileCheck = true;

        // verify file size
        if($('.wpgenious-job-listing-form-file-control').length > 0) {
            $('.wpgenious-job-listing-form-file-control').each(function() {
                let $fileField = $(this);
                let fileSize = (typeof $fileField.prop('files')[0] !== 'undefined' && $fileField.prop('files')[0]) ? $fileField.prop('files')[0].size : 0;
                if (fileSize > apply_form_max_upload_size) {
                    fileCheck = false;
                }
            });
        }

        if (fileCheck === false) {
            apply_form_message.addClass(error_class).html(script_info.messages.form_error.file_size).fadeIn();
        }else {
            let formData = new FormData(apply_form[0]);
            apply_form_message.removeClass(success_class + ' ' + error_class).hide();
            apply_form_submit_btn.prop('disabled', true).val(apply_form_submit_btn_response_text);

            // Ajax Request
            $.ajax({
                url: ajax_url,
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                type: 'POST',
            })
                .done(function (response) {
                    if(response) {
                        let class_name = 'wpgenious-job-listing-default-message';
                        let msg = '';
                        let msg_array = [];

                        if (response.error.length > 0) {
                            class_name = error_class;
                            msg_array = response.error;
                        } else if (response.success.length > 0) {
                            class_name = success_class;
                            msg_array = response.success;
                            apply_form.trigger("reset");
                        }

                        $(msg_array).each(function(index, value) {
                            msg += `<p> ${value} </p>`;
                        });

                        apply_form_message
                            .addClass(class_name)
                            .html(msg)
                            .fadeIn();
                    }
                })
                .fail(function () {
                    apply_form_message
                        .addClass(error_class)
                        .html(script_info.messages.form_error.general)
                        .fadeIn();
                })
                .always(function () {
                    apply_form_submit_btn.prop('disabled', false).val(apply_form_submit_btn_text);
                });
        }
    });

});
