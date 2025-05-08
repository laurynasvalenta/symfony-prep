# HTTP Caching

## Official Curriculum

### Cache types (browser, proxies and reverse-proxies)
* https://symfony.com/doc/7.0/http_cache.html
### Expiration (Expires, Cache-Control)
* https://symfony.com/doc/6.0/http_cache/expiration.html
### Validation (ETag, Last-Modified)
* https://symfony.com/doc/6.0/http_cache/validation.html
### Client side caching
* https://symfony.com/doc/7.0/http_cache/cache_vary.html
### Server side caching
* https://symfony.com/doc/6.0/http_cache/ssi.html
### Edge Side Includes
* https://symfony.com/doc/7.0/http_cache/esi.html

## Additional Resources
* https://datatracker.ietf.org/doc/html/rfc9111

## ChatGPT-summarized RFC 9111

# RFC 9111 - HTTP Caching: 15 Key Points for Developers

1. **Applies to GET and HEAD**: Only these methods are cacheable by default; others like POST require explicit headers (e.g., `Cache-Control: public`).

2. **Cacheability Controlled by Headers**: Primarily `Cache-Control`, `Expires`, `Vary`, and presence of validators like `ETag`.

3. **Freshness Lifetime**: Defined by `Cache-Control: max-age`, `s-maxage`, or `Expires`. Absent explicit directives, caches may use heuristics.

4. **no-store vs no-cache**:
    - `no-store`: Do not cache at all.
    - `no-cache`: Cacheable, but must revalidate on every use.

5. **Validation Mechanism**: Uses `ETag` + `If-None-Match` or `Last-Modified` + `If-Modified-Since` to avoid resending full content.

6. **304 Not Modified**: Sent by server if cache content is still valid. Client reuses stored response body.

7. **Vary Header**: Indicates which request headers affect the cache (e.g., `Vary: Accept-Encoding` means separate entries for each variation).

8. **Immutable Responses**: `Cache-Control: immutable` signals content won't change over its lifetime, skipping revalidation.

9. **Stale Responses**:
    - `stale-while-revalidate`: Serve stale while revalidating in background.
    - `stale-if-error`: Serve stale if revalidation fails (e.g., 5xx or timeout).

10. **Shared vs Private Caching**:
    - `public`: Can be stored by any cache.
    - `private`: Only for end-user caches; intermediaries must not store.

11. **Authenticated Responses**: Not cacheable by shared caches unless marked `public`.

12. **Must-Revalidate**: Caches must revalidate stale content before reuse if this directive is present, regardless of network conditions.

13. **Heuristic Freshness**: Allowed when no explicit freshness is present. Example: 10% of time since `Last-Modified`.

14. **Negative Caching**: 404, 410, and some other status codes can be cached if headers allow, aiding performance.

15. **Prevention Techniques**: Use `Cache-Control: no-store`, `Pragma: no-cache`, or vary keys to disable or isolate cache entries when needed.

