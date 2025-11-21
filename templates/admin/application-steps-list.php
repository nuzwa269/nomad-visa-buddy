<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap nvb-admin-wrap">
    <h1>Application Steps 
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=nvb_application_steps&action=add' ) ); ?>" 
           class="page-title-action">Add New</a>
    </h1>

    <form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
        <input type="hidden" name="page" value="nvb_application_steps" />
        <p class="search-box">
            <label class="screen-reader-text" for="nvb-search-input">Search Steps:</label>
            <input type="search" id="nvb-search-input" name="s" 
                   value="<?php echo isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : ''; ?>" />
            <input type="submit" class="button" value="Search" />
        </p>
    </form>

    <table class="nvb-list-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Country</th>
                <th>Step #</th>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( $steps ) : foreach ( $steps as $st ) : ?>
            <tr>
                <td><?php echo esc_html( $st->id ); ?></td>
                <td><?php echo esc_html( $st->country_name ); ?></td>
                <td><?php echo esc_html( $st->step_number ); ?></td>
                <td><?php echo esc_html( $st->title ); ?></td>
                <td>
                    <a href="<?php 
                        echo esc_url( admin_url( 'admin.php?page=nvb_application_steps&action=edit&id=' . intval( $st->id ) ) ); 
                    ?>">Edit</a> 
                    |
                    <a class="nvb-confirm-delete" 
                       href="<?php 
                         echo esc_url( wp_nonce_url(
                             admin_url( 'admin-post.php?action=nvb_delete_application_step&id=' . intval( $st->id ) ),
                             'nvb_delete_application_step'
                         ) ); 
                       ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; else : ?>
            <tr>
                <td colspan="5">No Application Steps found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
