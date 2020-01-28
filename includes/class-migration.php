<?php 

namespace XyoosMigration;

defined( 'ABSPATH' ) || exit;

class Main extends Convert {


  public function register_hooks() {
    add_action( 'admin_menu', array( $this, 'add_menu' ) );
  }


  public function add_menu() {
    add_menu_page(
      'Migration Gutenberg', 
      'Migration Gutenberg', 
      'manage_options', 
      'xyoos-migration', 
      array( $this, 'router' ), 
      'dashicons-controls-skipforward', 
      80
    );
  }


  public function router() {
    
    if ( isset( $_GET['action'] ) and $_GET['action'] == 'launch-migration' ) {
      $this->route_migration();
    } else {
      $this->route_options();
    }
  }

  
  public function route_options() {
    include plugin_dir_path(__FILE__) . '../templates/migration-options.php';
  }


  public function route_migration() {
    
    $content = '';
    $result  = '';
    
    $post_ID = 209;

    // Get posts to migrate
    $args = array(
      'p' => $post_ID,
      'post_type' => 'cours'
    );
    
    $post = new \WP_Query($args);
    
    if ( $post->have_posts() ): while ( $post->have_posts() ): $post->the_post();
        
      if ( have_rows('cours') ): while ( have_rows('cours') ): the_row();

        $fn = 'convert_' . get_row_layout();
        $content .= $this->$fn();

      endwhile; endif; // Row
      
      $result .= '<li>Migré : ' . get_the_title() . '</li>';
    
    endwhile; endif; // Post
    wp_reset_postdata();
    
    // Update Post
    $args = array(
      'ID' => $post_ID,
      'post_content' => $content
    );
    
    wp_update_post( $args );
    
    include plugin_dir_path( __FILE__ ) . '../templates/migration-result.php';
  }
        
}