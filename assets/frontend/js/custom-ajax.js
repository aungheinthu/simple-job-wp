jQuery(document).ready(function ($) {
    const jobFilter = $("#sjw-job-filter");
    if (jobFilter.length) {
        var page = 1; // Track the current page

        // Function to load jobs and update URL
        function loadJobs(filterData, currentPage) {
            console.log(filterData.search_keyword);
            $.ajax({
                url: sjw_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'sjw_filter_jobs',
                    nonce: sjw_ajax_object.nonce,
                    job_category: filterData.job_category,
                    job_type: filterData.job_type,
                    job_location: filterData.job_location,
                    search_keyword: filterData.search_keyword,
                    page: currentPage,
                },
                beforeSend: function () {
                    // $('#sjw-job-results').html('<p>Loading...</p>'); // Optional: Show loading
                    // $('#sjw-pagination').html('');
                    $('body').append('<div class="sjw-loader"><div class="spinner"></div></div>');
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        $('#sjw-job-results').html(response.data.jobs); // Load filtered jobs
                        $('#sjw-pagination').html(response.data.pagination); // Load pagination links

						$('html, body').animate({ scrollTop: 0 }, 'slow');
                        // Update the URL with the current filters and page
                        updateUrl(filterData, currentPage);
                        $('.sjw-loader').remove();
                    } else {
                        $('#sjw-job-results').html('<p>No jobs found.</p>');
                        $('#sjw-pagination').html('');
                        $('.sjw-loader').remove();
                    }
                },
                error: function () {
                    $('#sjw-job-results').html('<p>Error loading jobs.</p>');
                    $('#sjw-pagination').html('');
                    $('.sjw-loader').remove();
                },
            });
        }

        // Function to get URL parameters
        function getUrlParams() {
            var params = {};
            var queryString = window.location.search.substring(1);
            var queryParams = queryString.split('&');

            queryParams.forEach(function (param) {
                var pair = param.split('=');
                if (pair.length === 2) {
                    params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
                }
            });

            return params;
        }

		setTimeout(function() {
            var urlParams = getUrlParams(); // Get the URL parameters

            // Set filter values from URL parameters (if they exist)
            var defaultFilterData = {
                job_category: urlParams.job_category || '', // Default to empty if not provided
                job_type: urlParams.job_type || '',
                job_location: urlParams.job_location || '',
                search_keyword: urlParams.search_keyword || '',
            };

            page = urlParams.page ? parseInt(urlParams.page) : 1; // Get page from URL or default to 1

            // Update filter inputs based on URL params
            $('#sjw-job-category-filter').val(defaultFilterData.job_category);
            $('#sjw-job-type-filter').val(defaultFilterData.job_type);
            $('#sjw-job-location-filter').val(defaultFilterData.job_location);
            $('#sjw-search-bar').val(defaultFilterData.search_keyword);

            loadJobs(defaultFilterData, page); // Load jobs based on URL params
		}, 1000);

        // Handle filter button click
        $('#sjw-filter-button').on('click', function (e) {
            e.preventDefault();
            page = 1; // Reset to the first page

            var filterData = {
                job_category: $('#sjw-job-category-filter').val(),
                job_type: $('#sjw-job-type-filter').val(),
                location: $('#sjw-job-location-filter').val(),
                search_keyword: $('#sjw-search-bar').val(),
            };

            loadJobs(filterData, page);
        });

        // Handle pagination link click
        $(document).on('click', '#sjw-pagination a', function (e) {
            e.preventDefault();
            page = $(this).data('page'); // Get the clicked page number

            var filterData = {
                job_category: $('#sjw-job-category-filter').val(),
                job_type: $('#sjw-job-type-filter').val(),
                location: $('#sjw-job-location-filter').val(),
                search_keyword: $('#sjw-search-bar').val(),
            };

            loadJobs(filterData, page);
        });

        // Function to update the URL with the filter data and page number
        function updateUrl(filterData, currentPage) {
            var queryString = '?page=' + currentPage;

            if (filterData.job_category) {
                queryString += '&job_category=' + encodeURIComponent(filterData.job_category);
            }

            if (filterData.job_type) {
                queryString += '&job_type=' + encodeURIComponent(filterData.job_type);
            }

            if (filterData.job_location) {
                queryString += '&location=' + encodeURIComponent(filterData.job_location);
            }

            if (filterData.search_keyword) {
                queryString += '&search_keyword=' + encodeURIComponent(filterData.search_keyword);
            }

            // Update the URL using pushState without reloading the page
            history.pushState(null, null, queryString);
        }
    }
});