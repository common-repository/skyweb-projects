(function ($) {
    $(document).ready(function () {

        let projectsFilters = $(".projects-filters input[type='checkbox']");

        projectsFilters.change(function () {

            let body = $('body'),
                projectsTypes = [],
                projectsList = $('#skyweb_projects_list'),
                checkedFilters = $('.projects-filters input:checked'),
                allProjectsTypes = $('#all_projects_types'),
                allProjectsTypesName = allProjectsTypes.prop('name'),
                thisFilterName = $(this).prop('name');

            // If All Projects filter is checked.
            if (thisFilterName === allProjectsTypesName) {

                checkedFilters.prop('checked', false);
                allProjectsTypes.prop('checked', true);

            } else {

                allProjectsTypes.prop('checked', false);
            }

            // Preloader start.
            body.addClass('cursorWait');

            // Disable filters.
            projectsFilters.each(function () {
                $(this).attr('disabled', true);
            });

            // Fill filters array.
            $('.projects-filters input:checked').each(function () {
                projectsTypes.push($(this).attr('name'));
            });

            // If all filters are unchecked.
            if (projectsTypes.length === 0) {
                projectsTypes.push(allProjectsTypesName);
            }

            // Data for AJAX request.
            let data = {
                action: 'skyweb_ajax_project_filter',
                nonce: skyweb_projects_ajax.nonce,
                'projects_types': projectsTypes,
            };

            // AJAX request.
            $.ajax({
                type: 'post',
                url: skyweb_projects_ajax.url,
                data: data,
                success: function (response) {

                    // Preloader end.
                    body.removeClass('cursorWait');

                    // Enable filters.
                    projectsFilters.each(function () {
                        $(this).removeAttr('disabled');
                    });

                    if (!response) {
                        console.log('Projects filter: ', response);
                    }

                    if (response) {
                        projectsList.empty();
                        projectsList.html(response);
                    }
                },

                error: function (xhr, textStatus, errorThrown) {

                    // Preloader end.
                    body.removeClass('cursorWait');

                    // Enable filters.
                    projectsFilters.each(function () {
                        $(this).removeAttr('disabled');
                    });

                    console.log('Projects filter: ', errorThrown ? errorThrown : xhr.status);
                }
            });

        });

    }); /* $(document).ready */
})(jQuery);