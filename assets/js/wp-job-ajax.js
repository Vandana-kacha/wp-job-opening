jQuery(function($) {
    function loadJobs(page = 1) {
        let formData = {
            action: "wp_filter_jobs",
            security: wpAjax.security,
            keyword: $("#job-keyword").val(),
            industry: $("[name=industry]").val(),
            location: $("[name=location]").val(),
            zipcode: $("[name=zipcode]").val(),
            page: page
        };

        $("#wp-job-results").html("<p>Loading…</p>");

        $.post(wpAjax.ajaxurl, formData, function(response) {
            //console.log("AJAX response:", response); // 👀 Debug
            if (response.success) {
                $("#wp-job-results").html(response.data.html);
            } else {
                $("#wp-job-results").html("<p>Error loading jobs.</p>");
            }
        });
    }

    // Sidebar filters
    $("#wp-filter-form").on("submit", function(e) {
        e.preventDefault();
        loadJobs(1);
    });

    // Pagination
    $(document).on("click", ".job-pagination .page-link", function(e) {
        e.preventDefault();
        loadJobs($(this).data("page"));
    });

    // Top search
    $("#wp-top-search").on("submit", function(e) {
        e.preventDefault();
        loadJobs(1);
    });

    // Clear filters
    $(document).on("click", "#clear-filters", function(e) {
        e.preventDefault();
        $("#wp-filter-form")[0].reset();
        $("#job-keyword").val(""); // reset top search too
        loadJobs(1);
    });

    // Initial load
    loadJobs();
});

