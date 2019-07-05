<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/freddiemixell
 * @since      1.0.0
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<form role="search" method="post" class="search-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e( 'Search for:', $data['textdomain'] ); ?></span>
        <input type="search" name="search-input" class="search-field"
            title="<?php esc_attr_e( 'Search for:', $data['textdomain'] ); ?>" />
    </label>
    <input type="hidden" name="action" value="google_search" />
    <input type="submit" class="search-submit"
        value="<?php esc_attr_e( 'Search', $data['textdomain'] ); ?>" />
</form>
