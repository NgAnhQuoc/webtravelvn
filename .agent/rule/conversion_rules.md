# Conversion Rules for Flatsome UX Builder

1. **Class Prefixing**: Always use the `cs-` prefix for custom CSS classes to avoid conflicts with Flatsome's internal classes.
2. **Component Isolation**: Each HTML section should be treated as a potential UX Builder "HTML" element or a custom shortcode.
3. **Image Paths**: Replace local `assets/images/` paths with WordPress Media Library URLs or dynamic `wp_get_attachment_url()` calls in PHP.
4. **Responsive Design**: Ensure `cs-` prefixed grid and flex classes handle mobile breakpoints correctly without relying on Flatsome's native grid if custom behavior is desired.
5. **UX Blocks**: Use Flatsome "UX Blocks" for global components like Footer and shared CTA sections.
