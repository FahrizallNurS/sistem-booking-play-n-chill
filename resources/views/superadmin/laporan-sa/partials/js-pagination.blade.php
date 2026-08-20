<script>
    $(document).on('click', '#pagination-links a', function (e) {
        e.preventDefault();

        // Skip kalau disabled atau href-nya cuma "#" (ujung awal/akhir halaman)
        if ($(this).hasClass('disabled')) return;
        let url = $(this).attr('href');
        if (!url || url === '#') return;

        let formData = $('#filter-form').serialize();
        url += (url.includes('?') ? '&' : '?') + formData;

        $.get(url, function (res) {
            $('#table-body').html(res.html);
            $('#pagination-links').html(res.pagination);
            $('#pagination-info').text(res.info);
        });
    });
</script>