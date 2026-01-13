jQuery(function($) {
    const $tableBody = $('#ajo-applicants-table-body');
    const $pagination = $('#ajo-pagination .tablenav-pages');
    //const itemsPerPage = 10;
    let currentPage = 1;

    function loadApplicants(page = 1) {
        const data = {
            action: 'ajo_get_applicants',
            security: ajo_ajax_obj.nonce,
            paged: page,
            search: $('#search').val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val(),
        };

        $.post(ajo_ajax_obj.ajax_url, data, function(response) {
            if (response.success) {
                $tableBody.html(response.data.table_rows);
                let totalPages = response.data.total_pages;
                let paginationHTML = '';
                if (totalPages > 1) {
                    if (page > 1) {
                        paginationHTML += `<a href="#" class="page-numbers prev" data-page="${page - 1}">« Prev</a>`;
                    }

                    for (let i = 1; i <= totalPages; i++) {
                        paginationHTML += `<a href="#" class="page-numbers ${i === page ? 'current' : ''}" data-page="${i}">${i}</a>`;
                    }

                    if (page < totalPages) {
                        paginationHTML += `<a href="#" class="page-numbers next" data-page="${page + 1}">Next »</a>`;
                    }
                }

                $pagination.html(paginationHTML);
                currentPage = page;
            } else {
                console.log(error);
                $tableBody.html('<tr><td colspan="5">Error loading data.</td></tr>');
                $pagination.html('');
            }
        });
    }

    loadApplicants(); // Initial load

    $('#ajo-filter-form').on('submit', function(e) {
        e.preventDefault();
        loadApplicants(1);
    });

    $pagination.on('click', 'a.page-numbers', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        if (page && page !== currentPage) {
            loadApplicants(page);
        }
    });
});
