<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($links as $link)
        <url>
            <loc>{{ url($link['link']) }}</loc>
            <lastmod>
                {{ $link['lastmod']->toAtomString() }}
            </lastmod>
            <changefreq>{{ $link['changefreq'] }}</changefreq>
            <priority>{{ $link['priority'] }}</priority>
        </url>
    @endforeach
</urlset>
