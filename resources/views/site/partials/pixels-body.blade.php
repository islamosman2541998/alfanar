@if ($gtm = setting('gtm_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ urlencode($gtm) }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
@if ($fb = setting('facebook_pixel'))
    <noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id={{ urlencode($fb) }}&ev=PageView&noscript=1"></noscript>
@endif
