<?php 

namespace XyoosMigration;

defined( 'ABSPATH' ) || exit;

class Main extends Convert {


  public function register_hooks() {
    add_action( 'admin_menu', array( $this, 'add_menu' ) );
  }


  public function add_menu() {
    add_menu_page(
      'Migration Xyoos', 
      'Migration Xyoos', 
      'manage_options', 
      'xyoos-migration', 
      array( $this, 'router' ), 
      'dashicons-controls-skipforward', 
      80
    );
  }


  public function router() {

    if( !isset( $_GET['action'] ) ) {
      $this->route_options();
      return;
    }
    
    if ( $_GET['action'] == 'launch-content-migration' ) {
      $this->route_content_migration();
    }
    
    if ( $_GET['action'] == 'launch-acf-migration' ) {
      $this->route_acf_migration();
    }
  }

  
  public function route_options() {
    include plugin_dir_path(__FILE__) . '../templates/migration-options.php';
  }


  public function route_content_migration() {
    
    $content = '';
    $result  = '';
    
    $post_ID = 15108;

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
    
    include plugin_dir_path( __FILE__ ) . '../templates/content-migration-result.php';
  }


  public function route_acf_migration() {
    $groups = acf_get_local_field_groups();
    $json = [];

    foreach ( $groups as $group ) {

      // Fetch the fields for the given group key
      $fields = acf_get_local_fields( $group['key'] );

      // Remove unecessary key value pair with key "ID"
      unset( $group['ID'] );

      // Add the fields as an array to the group
      $group['fields'] = $fields;

      // Add this group to the main array
      $json[] = $group;
    }

    $json = json_encode( $json, JSON_PRETTY_PRINT );

    // Write output to file for easy import into ACF.
    $file = get_template_directory() . '/acf-import.json';
    file_put_contents( $file, $json );

    include plugin_dir_path( __FILE__ ) . '../templates/acf-migration-result.php';
  }
        
}