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

    require_once plugin_dir_path(__FILE__) . '../lib/simple_html_dom.php';
    
    $post_type = $_GET['post_type'];
    $offset = intval( $_GET['offset'] );

    $nb_to_convert = 30;

    $content = ''; // HTML content to save
    $result  = ''; // Display Migration state


    // Get posts to migrate
    $args = array(
      'offset' => $offset,
      'posts_per_page' => $nb_to_convert,
      'post_type' => $post_type,
      'orderby' => "date",
      'order' => ASC,
    );
    
    $post = new \WP_Query($args);
    
    // Get total of posts
    $total = $post->found_posts;
    
    if ( $post->have_posts() ): while ( $post->have_posts() ): $post->the_post();
        
      if ( have_rows('cours') ): while ( have_rows('cours') ): the_row();

        $fn = 'convert_' . get_row_layout();
        $content .= $this->$fn();

      endwhile; endif; // Row

      // Update Post
      $args = array(
        'ID' => get_the_ID(),
        'post_content' => $content
      );

      wp_update_post( $args );
      
      $result .= '<li>Migré : <strong>' . get_the_title() . '</strong> [' . get_the_ID() . ']</li>';
    
    endwhile; endif; // Post
    wp_reset_postdata();

    include plugin_dir_path( __FILE__ ) . '../templates/content-migration-result.php';
  }


  public function route_acf_migration() {
    
    $json = "";

    // get all the local field groups 
    $field_groups = acf_get_local_field_groups();

    // loop over each of the gield gruops 
    foreach( $field_groups as $field_group ) {

      // get the field group key 
      $key = $field_group['key'];

      // if this field group has fields 
      if( acf_have_local_fields( $key ) ) {

        // append the fields 
        $field_group['fields'] = acf_get_fields( $key );

        $json .= json_encode( $field_group, JSON_PRETTY_PRINT );
      }
      
      // save the acf-json file to the acf-json dir 
      acf_write_json_field_group( $field_group );
    }

    include plugin_dir_path( __FILE__ ) . '../templates/acf-migration-result.php';
  }
        
}