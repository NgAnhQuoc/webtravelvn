# Category Page Components

## 1. Page Header
```html
<section class="cs-page-header">
    <div class="cs-container">
        <h1 class="cs-font-serif">Khám Phá <span class="cs-text-gold">Địa Danh</span></h1>
        <p>Tìm lại những ký ức tuyệt đẹp ẩn mình trong từng góc phố, ngọn đồi của dải đất hình chữ S.</p>
    </div>
</section>
```

## 2. Filter Sidebar
```html
<aside class="cs-filter-sidebar">
    <div class="cs-filter-group">
        <h4>Khu Vực</h4>
        <label><input type="checkbox" checked> Tất cả</label>
        <label><input type="checkbox"> Miền Bắc</label>
        <!-- ... -->
    </div>
</aside>
```

## 3. Destination Grid
```html
<div class="cs-grid-4" style="grid-template-columns: repeat(3, 1fr);">
    <div class="cs-card">
        <div class="cs-card-img"><img src="[MEDIA_URL]/ninhbinh.png"></div>
        <div class="cs-card-overlay">
            <h3 class="cs-card-title">Ký Ức Cố Đô Hoa Lư</h3>
            <div class="cs-card-meta"><i class="fas fa-map-marker-alt"></i> Miền Bắc</div>
        </div>
    </div>
</div>
```
