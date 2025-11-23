<?php
/**
 * Homepage Template Wrapper
 * This file serves as a bridge between the plugin shortcode and the child theme template
 * It loads the homepage-lovable.php template from the child theme
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get the child theme directory path
$child_theme_dir = get_stylesheet_directory();
$homepage_template = $child_theme_dir . '/homepage-lovable.php';

// Check if the child theme template exists
if ( file_exists( $homepage_template ) ) {
    // Load the child theme template
    include $homepage_template;
} else {
    // Fallback content if template not found
    ?>
    <div class="homepage-lovable">
        <section class="hero-section">
            <div class="hero-container">
                <h1 class="hero-title">Nomad Visa Hub</h1>
                <p class="hero-subtitle">Your easiest path to making your travel dreams come true</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Countries</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Visa Programs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Happy Users</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
}
