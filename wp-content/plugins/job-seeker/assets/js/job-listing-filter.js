jQuery(document).ready(function($) {
    // Event handler for the filter form submit
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        // Get the filter values
        let jobType = $('#job-type').val();
        let industry = $('#industry').val();
        let skills = $('#skills').val();
        let jobsContainer = $('#jobs-container');
        // AJAX request to the API endpoint
        $.ajax({
            url: jobListingAjax.ajaxUrl,
            type : "post",
            data: {
                action: "filter_job_listing",
                job_type: jobType,
                industry: industry,
                skills: skills,
            },
            beforeSend: function() {
                $(".filter-submit").prop("disabled", true);
                jobsContainer.addClass('loading');
            },
            success:function(response) {
                // Process the response and update the job listings
                let object = JSON.parse(response);
                if (object.html_contents) {
                    jobsContainer.html(object.html_contents);
                } else {
                    jobsContainer.html('<li>No jobs found.</li>');
                }
                $(".filter-submit").prop("disabled", false);
                jobsContainer.removeClass('loading');
            },
            error: function() {
                alert('Error occurred while filtering job listings.');
                $(".filter-submit").prop("disabled", false);
                jobsContainer.removeClass('loading');
            }
        });
    });
});
