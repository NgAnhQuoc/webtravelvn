# Migration Workflow

1. **Phase 1: Asset Preparation**
    - Copy CSS to Child Theme.
    - Upload images to Media Library.
    - Enqueue JS in `functions.php`.

2. **Phase 2: Global Components**
    - Setup Header via Flatsome Header Builder.
    - Create Footer UX Block using custom HTML.

3. **Phase 3: Page Building**
    - Homepage: Use UX Builder with custom HTML elements for complex sections.
    - Category: Create a custom `archive.php` or use Flatsome's blog layout settings.
    - Single Post: Customize `single.php` or use UX Builder for posts.

4. **Phase 4: Dynamic Integration**
    - Replace static text/images with WordPress loops and metadata.
    - Test form submissions (Contact Form 7).
