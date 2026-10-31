/**
 * Conversion events for the configured pixels.
 * "lead" is dispatched by the contact form after a successful submit.
 */
export function initTracking() {
    window.addEventListener('lead', () => {
        window.fbq?.('track', 'Lead');
        window.gtag?.('event', 'generate_lead');
        window.ttq?.track?.('SubmitForm');
        window.snaptr?.('track', 'SIGN_UP');
        window.dataLayer?.push({ event: 'lead' });
    });

    // Phone and WhatsApp clicks
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a[href^="tel:"], a[href*="wa.me"]');
        if (!link) return;
        const type = link.href.startsWith('tel:') ? 'phone' : 'whatsapp';
        window.fbq?.('track', 'Contact', { method: type });
        window.gtag?.('event', 'contact', { method: type });
        window.ttq?.track?.('Contact');
        window.dataLayer?.push({ event: 'contact', method: type });
    });
}
