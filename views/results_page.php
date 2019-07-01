<?php
/**
 * Template Name: Results Page
 */

get_header();
?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">
      <article class="entry">
        <header class="entry-header">
          <h1 class="entry-title">Search Results</h1>
        </header>
        <div class="entry-content">
          <form action="<?php esc_url( admin_url( "admin-post.php" ) ) ?>" method="post">
            <input type="hidden" name="action" value="google_search" />
            <input name="google-search-input" placeholder="Search..." required type="text" />
            <?php wp_nonce_field( 'fm_form_submit', 'fm_nonce', true, false ); ?>
            <button type="submit">Search</button>
          </form>
          <ul class="google-list">
            <?php
              if ( !empty( $items ) ) {
                foreach( $items as $item ) {
                  echo '<li class="google-list-item">';
                  echo '<a href="' . esc_html__( $item['link'], $this->textdomain ) . '">' . esc_html__( $item['title'], $this->textdomain ) . '</a>';
                  echo '<p class="google-list-url">' . esc_html__( $item['displayLink'], $this->textdomain ) . '</p>';
                  echo '<p class="google-list-snippet">' . esc_html__( $item['snippet'], $this->textdomain ) . '</p>';
                  echo '</li>';
                }
              }
            ?>
          </ul>
        </div>
      </article>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
