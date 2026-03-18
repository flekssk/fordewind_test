export const ezplusTint = {
    async mounted(el, binding) {
        if (typeof window === 'undefined') return;

        // Ленивая загрузка jQuery и плагина
        if (!window.jQuery || !window.jQuery.fn?.ezPlus) {
            const $ = (await import('jquery')).default;
            window.$ = window.jQuery = $;
            await import('ez-plus');
        }

        const $ = window.jQuery;

        const opts = binding?.value || {};
        if (opts.zoomImage) {
            el.setAttribute('data-zoom-image', opts.zoomImage);
        }

        $(el).ezPlus({
            zoomType: opts.zoomType || 'lens',
            lensSize: opts.lensSize || 200,
            containLensZoom: opts.containLensZoom ?? true,

            tint: true,
            tintColour: opts.tintColour || '#3b82f6',
            tintOpacity: opts.tintOpacity ?? 0.35,

            borderSize: 1,
            responsive: true,
            scrollZoom: false,
            ...opts
        });
    },

    beforeUnmount(el) {
        if (typeof window === 'undefined' || !window.jQuery) return;
        const $ = window.jQuery;
        try {
            $(el).ezPlus('destroy');
        } catch (_) {
        }
    }
};
