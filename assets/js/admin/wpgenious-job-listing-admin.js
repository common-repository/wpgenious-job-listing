'use strict';

jQuery(document).ready(
    function ($) {

        // For admin tabs
        $('.tabs__heading').on(
            'click', function () {
                const index = $(this).data('tab-index');
                $('.is-active').removeClass('is-active');
                $(`.${index}`).addClass('is-active');
            }
        );

        // Job Fields
        const wrapper = $('#table-body-custom-fields');

        $('#add-new-job-field').on(
            'click', function () {
                const next = wrapper.find('tr:last').data('index') + 1;
                const template = wp.template('wpgenious-job-listing-job-field');
                $('#table-body-custom-fields').append(template({ index: next }));

                // Apply select2 to new field
                applySelect2();
            }
        );

        wrapper.on(
            'click', '.delete-job-field', function () {
                const index = $(this).data('field-index');
                $(`#job-wrapper-field-${index}`).remove();
            }
        );

        wrapper.sortable();

        // Toggle row
        $('.toggle-row').on(
            'change', function () {
                const element = $(this).data('toggle');
                const action = $(this).data('action');

                if(action === 'hide') {
                    $(`#${element}`).addClass('hidden');
                }else {
                    $(`#${element}`).removeClass('hidden');
                }
            }
        ).change();

        // Application rating
        $('input[name="applicant_rating"]').on('change', function (){
            if(this.checked) {
                $(this).parent().removeClass('empty-rating');
            }
        });

        // select 2
        function applySelect2() {
            let select2 = $('.select2.select2-icons');
            if(select2.length) {
                select2.select2({
                    templateResult: select2template,
                    templateSelection: select2template
                });
            }

            let select2Simple = $('.select2.simple');
            if(select2Simple.length) {
                select2Simple.select2();
            }
        }

        function select2template(state) {
            return (!state.id) ? state.text : $(`<i class="${state.id}"></i> <span> ${state.text} </span>`);
        }

        applySelect2();
    }
);
