<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>
<div class="nvb-application-guide">
  <h2>Application Steps for <?php echo esc_html( $country->name ); ?></h2>

  <?php if ( $steps ) : ?>
    <ul class="nvb-steps">
      <?php foreach ( $steps as $s ) : ?>
        <li class="nvb-step">
          <h4><?php echo esc_html( $s->title ); ?></h4>

          <div>
            <?php echo wp_kses_post( $s->description ); ?>
          </div>

          <?php if ( $s->external_link ) : ?>
            <p>
              <a
                href="<?php echo esc_url( $s->external_link ); ?>"
                target="_blank"
                rel="noopener noreferrer"
              >
                Official link
              </a>
            </p>
          <?php endif; ?>

          <?php if ( $s->screenshot_url ) : ?>
            <div class="nvb-screenshot">
              <img src="<?php echo esc_url( $s->screenshot_url ); ?>" alt="" />
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else : ?>
    <p>No steps available.</p>
  <?php endif; ?>
</div>
