import $ from 'jquery';

/** Instant filtering of the FAQ list. */
export function initFaqSearch() {
    const $input = $('#faqSearch');
    if (!$input.length) return;

    const $items = $('#faqList .faq-item');
    const $empty = $('#faqEmpty');

    $input.on('input', function () {
        const term = $(this).val().trim().toLowerCase();
        let visible = 0;

        $items.each(function () {
            const match = !term || $(this).text().toLowerCase().includes(term);
            $(this).toggle(match);
            if (match) visible++;
        });

        $empty.toggleClass('d-none', visible > 0);
    });
}
