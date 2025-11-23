<?php
/**
 * Template Name: Nomad Visa Hub - Homepage (Lovable Style)
 * Description: Modern homepage design inspired by Lovable.dev
 */

get_header(); ?>

<style>
/* Hero Section - Lovable Style */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
    background-size: cover;
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 1;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #FFD700;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Features Section */
.features-section {
    padding: 80px 0;
    background: #f8fafc;
}

.features-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 3rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.feature-card {
    background: white;
    padding: 40px 30px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.feature-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 24px;
    color: white;
}

.feature-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 15px;
}

.feature-description {
    color: #64748b;
    line-height: 1.6;
}

/* Countries Section */
.countries-section {
    padding: 80px 0;
    background: white;
}

.countries-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.countries-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 40px;
}

.country-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.country-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.country-flag {
    width: 100%;
    height: 160px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.country-status {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-open {
    background: #10b981;
    color: white;
}

.status-pending {
    background: #f59e0b;
    color: white;
}

.status-restricted {
    background: #ef4444;
    color: white;
}

.country-info {
    padding: 20px;
}

.country-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
}

.country-programs {
    color: #64748b;
    font-size: 0.9rem;
}

/* Process Section */
.process-section {
    padding: 80px 0;
    background: #f8fafc;
}

.process-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    margin-top: 50px;
}

.process-step {
    text-align: center;
    position: relative;
}

.step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 20px;
}

.step-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 15px;
}

.step-description {
    color: #64748b;
    line-height: 1.6;
}

/* Trust Section */
.trust-section {
    padding: 60px 0;
    background: white;
}

.trust-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
    text-align: center;
}

.trust-logos {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #64748b;
    font-weight: 500;
}

.trust-icon {
    width: 20px;
    height: 20px;
    background: #10b981;
    border-radius: 50%;
    position: relative;
}

.trust-icon::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-stats {
        gap: 20px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .countries-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    
    .process-steps {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-container">
        <h1 class="hero-title">Nomad Visa Hub</h1>
        <p class="hero-subtitle">آپ کے سفر کے خوابوں کو حقیقت میں بدلنے کا سب سے آسان راستہ</p>
        
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">ممالک</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-label">ویزا پروگرام</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">10K+</div>
                <div class="stat-label">خوش صارفین</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="features-container">
        <h2 class="section-title">کیوں منتخب کریں Nomad Visa Hub؟</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3 class="feature-title">ذہین تلاش</h3>
                <p class="feature-description">جدید فلٹرز کے ساتھ آپ کی ضروریات کے مطابق بہترین ممالک تلاش کریں</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3 class="feature-title">مکمل چیک لسٹ</h3>
                <p class="feature-description">ہر مملکت کے لیے تفصیلی دستاویزات کی لسٹ اور ایپلیکیشن کے مراحل</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">فوری اپڈیٹس</h3>
                <p class="feature-description">ویزا کی پالیسیوں میں تبدیلیوں کی فوری اطلاع حاصل کریں</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3 class="feature-title">لاگت کا حساب</h3>
                <p class="feature-description">ہر مملکت میں رہائش اور زندگی کی لاگت کا تخمینہ</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📞</div>
                <h3 class="feature-title">ماہر مشاورت</h3>
                <p class="feature-description">ویزا ایکسپرٹس سے مفت مشاورت اور رہنمائی</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3 class="feature-title">دنیا بھر میں</h3>
                <p class="feature-description">50+ ممالک کی ویزا معلومات ایک ہی جگہ</p>
            </div>
        </div>
    </div>
</section>

<!-- Countries Section -->
<section class="countries-section">
    <div class="countries-container">
        <h2 class="section-title">مقبول ترین ممالک</h2>
        
        <div class="countries-grid">
            <a href="?country=portugal" class="country-card">
                <div class="country-flag" style="background-image: linear-gradient(45deg, #006600 33%, #FF0000 33%, #FF0000 66%, #FFFF00 66%, #FFFF00 100%);">
                    <div class="country-status status-open">Open</div>
                </div>
                <div class="country-info">
                    <h3 class="country-name">پرتگال</h3>
                    <p class="country-programs">5 ویزا پروگرام دستیاب</p>
                </div>
            </a>
            
            <a href="?country=spain" class="country-card">
                <div class="country-flag" style="background-image: linear-gradient(45deg, #FF0000 33%, #FFFF00 33%, #FFFF00 66%, #FF0000 66%, #FF0000 100%);">
                    <div class="country-status status-open">Open</div>
                </div>
                <div class="country-info">
                    <h3 class="country-name">سپین</h3>
                    <p class="country-programs">4 ویزا پروگرام دستیاب</p>
                </div>
            </a>
            
            <a href="?country=dubai-uae" class="country-card">
                <div class="country-flag" style="background-image: linear-gradient(45deg, #FF0000 25%, #FFFFFF 25%, #FFFFFF 75%, #FF0000 75%);">
                    <div class="country-status status-open">Open</div>
                </div>
                <div class="country-info">
                    <h3 class="country-name">دبئی، UAE</h3>
                    <p class="country-programs">6 ویزا پروگرام دستیاب</p>
                </div>
            </a>
            
            <a href="?country=malta" class="country-card">
                <div class="country-flag" style="background-image: linear-gradient(45deg, #FFFFFF 33%, #FF0000 33%, #FF0000 66%, #FFFFFF 66%, #FFFFFF 100%);">
                    <div class="country-status status-open">Open</div>
                </div>
                <div class="country-info">
                    <h3 class="country-name">مالٹا</h3>
                    <p class="country-programs">3 ویزا پروگرام دستیاب</p>
                </div>
            </a>
            
            <a href="?country=canada" class="country-card">
                <div class="country-flag" style="background-image: linear-gradient(90deg, #FF0000 25%, #FFFFFF 25%, #FFFFFF 75%, #FF0000 75%);">
                    <div class="country-status status-pending">Processing</div>
                </div>
                <div class="country-info">
                    <h3 class="country-name">کینیڈا</h3>
                    <p class="country-programs">8 ویزا پروگرام دستیاب</p>
                </div>
            </a>
            
            <a href="?country=australia" class="country-card">
                <div class="country-flag" style="background-image: linear-gradient(45deg, #000080 25%, #FFFFFF 25%, #FFFFFF 50%, #FF0000 50%, #FF0000 75%, #FFFFFF 75%, #FFFFFF 100%);">
                    <div class="country-status status-pending">Processing</div>
                </div>
                <div class="country-info">
                    <h3 class="country-name">آسٹریلیا</h3>
                    <p class="country-programs">7 ویزا پروگرام دستیاب</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process-section">
    <div class="process-container">
        <h2 class="section-title">کیسے شروع کریں؟</h2>
        
        <div class="process-steps">
            <div class="process-step">
                <div class="step-number">1</div>
                <h3 class="step-title">اپنا مملکت منتخب کریں</h3>
                <p class="step-description">ہماری تلاش کی خصوصیت استعمال کرکے اپنی ضروریات کے مطابق بہترین مملکت تلاش کریں</p>
            </div>
            
            <div class="process-step">
                <div class="step-number">2</div>
                <h3 class="step-title">تفصیلات دیکھیں</h3>
                <p class="step-description">ویزا کی شرائط، دستاویزات، اور اپلائی کرنے کے طریقوں کی مکمل معلومات حاصل کریں</p>
            </div>
            
            <div class="process-step">
                <div class="step-number">3</div>
                <h3 class="step-title">اپلائی کریں</h3>
                <p class="step-description">ہماری چیک لسٹ کی مدد سے اپنا ایپلیکیشن مکمل کریں اور سفر شروع کریں</p>
            </div>
        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="trust-section">
    <div class="trust-container">
        <h2 class="section-title">اعتماد کے ساتھ</h2>
        
        <div class="trust-logos">
            <div class="trust-item">
                <div class="trust-icon"></div>
                <span>محفوظ پلیٹ فارم</span>
            </div>
            <div class="trust-item">
                <div class="trust-icon"></div>
                <span>24/7 سپورٹ</span>
            </div>
            <div class="trust-item">
                <div class="trust-icon"></div>
                <span>دستیاب تازہ معلومات</span>
            </div>
            <div class="trust-item">
                <div class="trust-icon"></div>
                <span>ماہر ٹیم</span>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>