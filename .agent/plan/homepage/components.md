# Homepage Components

## 1. Hero Section
```html
<section class="cs-hero" id="heroSlider">
    <div class="cs-hero-bg">
        <img src="[MEDIA_URL]/boat.png" alt="Việt Nam Landscape" id="heroImage">
    </div>
    <div class="cs-hero-overlay"></div>
    <div class="cs-container">
        <div class="cs-hero-content">
            <h1>VIỆT NAM<br><span class="cs-text-gold" id="heroTitle">VẺ ĐẸP BẤT TẬN</span></h1>
            <p id="heroDesc">Hành trình khám phá vẻ đẹp lịch sử, văn hóa, và cảnh quan thiên nhiên tráng lệ trải dài khắp 3 miền đất nước.</p>
            <div class="cs-hero-buttons">
                <a href="category.html" class="cs-btn cs-btn-solid"><i class="fas fa-compass"></i> KHÁM PHÁ NGAY</a>
                <a href="#" class="cs-btn cs-btn-outline">XEM BẢN ĐỒ</a>
            </div>
        </div>
    </div>
    <div class="cs-hero-indicators">
        <div class="cs-dot cs-active"></div>
        <div class="cs-dot"></div>
        <div class="cs-dot"></div>
        <div class="cs-dot"></div>
    </div>
</section>
```

## 2. Featured Destinations
```html
<section class="cs-section">
    <div class="cs-container">
        <div class="cs-section-header">
            <h2 class="cs-section-title">ĐIỂM ĐẾN NỔI BẬT</h2>
            <a href="category.html" class="cs-view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="cs-scroll-container">
            <!-- Card Item -->
            <div class="cs-card">
                <div class="cs-card-img"><img src="[MEDIA_URL]/ninhbinh.png"></div>
                <div class="cs-card-overlay">
                    <h3 class="cs-card-title">CON NGƯỜI<br>MIỀN BẮC</h3>
                    <div class="cs-card-meta">12 ĐỊA ĐIỂM</div>
                </div>
            </div>
            <!-- ... more cards ... -->
        </div>
    </div>
</section>
```

## 3. Explore Landmarks (Grid)
```html
<div class="cs-scroll-container cs-grid-5">
    <div class="cs-card">
        <div class="cs-card-img"><img src="[MEDIA_URL]/boat.png"></div>
        <div class="cs-card-overlay">
            <div class="cs-card-meta"><i class="fas fa-map-marker-alt"></i> TRÀNG AN</div>
        </div>
    </div>
</div>
```
