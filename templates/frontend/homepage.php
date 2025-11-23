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
    echo '<div class="homepage-lovable">';
    echo '<section class="hero-section">';
    echo '<div class="hero-container">';
    echo '<h1 class="hero-title">Nomad Visa Hub</h1>';
    echo '<p class="hero-subtitle">Your easiest path to making your travel dreams come true</p>';
    echo '<div class="hero-stats">';
    echo '<div class="stat-item">';
    echo '<div class="stat-number">50+</div>';
    echo '<div class="stat-label">Countries</div>';
    echo '</div>';
    echo '<div class="stat-item">';
    echo '<div class="stat-number">100+</div>';
    echo '<div class="stat-label">Visa Programs</div>';
    echo '</div>';
    echo '<div class="stat-item">';
    echo '<div class="stat-number">10K+</div>';
    echo '<div class="stat-label">Happy Users</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</section>';
    echo '</div>';
}
