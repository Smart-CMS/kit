<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach (app('lang')->frontLanguages() as $lang)
        <sitemap>
            <loc>{{ route('sitemap.lang', $lang->slug) }}</loc>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
        </sitemap>
    @endforeach
</sitemapindex>
