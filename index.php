<?php
/*
Plugin Name: Xyoos Migration
Description: un plugin pour facilité la migration du site Xyoos
Author: Zoé Poullenot
Version: 0.1
*/


function migration_menu()
{
  add_menu_page(
    'Migration Xyoos',
    'Migration Xyoos',
    'manage_options',
    'xyoos-migration',
    'migrate_xyoos',
    'dashicons-controls-skipforward',
    80
  );
}
add_action('admin_menu', 'migration_menu');

function migrate_xyoos()
{
  if (isset($_GET['action']) and $_GET['action'] == 'launch-migration') {
    // vars for the template
    $content = '';
    $result = '';

    // Get posts to migrate
    $args = array(
      'p' => 10133,
      'post_type' => "cours"
    );

    $my_post = new WP_Query($args);

    if ($my_post->have_posts()) : while ($my_post->have_posts()) : $my_post->the_post();

        if (have_rows('cours')) : while (have_rows('cours')) : the_row();

            /* Migrate bloc ACF with Gutenberg*/

            // Definition 
            if (get_row_layout() == 'definition') :

              $post_object = get_sub_field('definition');

              $content .= ' <!-- wp:xyoos/definition {"definitionID":' . $post_object . '} /-->';

            // Exercice
            elseif (get_row_layout() == 'exercice') :

              $exercice = get_sub_field('id');

              $content .= '<!-- wp:xyoos/exercice {"exerciceID":"' . $exercice . '"} /-->';

            // Notice
            elseif (get_row_layout() == 'bloc_information') :

              $type = get_sub_field_object('type_de_bloc');
              $key = get_sub_field('type_de_bloc');
              $text =  get_sub_field('contenu');

              $content .= '
                                    <!-- wp:xyoos/notice -->
                                    <div class="wp-block-xyoos-notice hint-box hint-box--' . $key . '" data-type="' . $key . '">
                                          <div class="hint-box__icon">
                              ';


                  if ($key == 'tips') :
                  $content .= '   
                                                <svg width="100px" height="101px" viewBox="0 0 100 101" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                                stroke="currentColor" stroke-width="1" stroke-linecap="none" stroke-linejoin="none">
                                                      <g transform="translate(0.000000, -1.000000)" fill="#FFFFFF">
                                                            <path d="M51.438,1.021 C23.834,0.227 0.814,21.96 0.021,49.563 C0.007,50.049 0,50.535 0,51.021 C0,78.635 22.386,101.021 50,101.021 C77.614,101.021 100,78.635 100,51.021 C100.012,23.958 78.488,1.799 51.438,1.021 L51.438,1.021 Z M72.281,26.271 L82.187,36.177 L52.469,65.865 L42.563,75.771 L32.688,65.865 L17.812,51.021 L27.718,41.115 L42.562,55.959 L72.281,26.271 L72.281,26.271 Z">
                                                            </path>
                                                      </g>
                                                      <line x1="12" y1="16" x2="12" y2="12"></line>
                                                      <line x1="12" y1="8" x2="12" y2="8"></line>
                                                </svg>
                                          
                                                ';
                  elseif ($key == 'advice') :
                  $content .= '
                                                <svg width="73px" height="101px" viewBox="0 0 73 101" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                                stroke="currentColor" stroke-width="1" stroke-linecap="none" stroke-linejoin="none">
                                                      <g fill="#FFFFFF">
                                                            <path d="M46.465,80.214 L26.612,80.214 C25.298,80.214 24.233,79.15 24.233,77.836 C24.233,77.749 24.238,77.664 
                                                            24.246,77.579 C23.587,73.506 21.185,69.911 19.056,68.395 C10.529,62.321 0.362,51.895 0.362,36.713 C0.362,16.746
                                                            16.607,0.5 36.575,0.5 C56.544,0.5 72.788,16.746 72.788,36.713 C72.788,36.926 72.76,37.131 72.708,37.327 C72.438,52.175  
                                                            62.432,62.403 54.019,68.395 C51.892,69.912 49.489,73.504 48.831,77.578 C48.839,77.663 48.844,77.748 48.844,77.835
                                                            C48.842,79.149 47.778,80.214 46.465,80.214 L46.465,80.214 Z M28.671,75.454 L44.404,75.454 C45.591,70.467 48.638,66.386 
                                                            51.258,64.518 C58.876,59.093 67.955,49.867 67.955,36.712 C67.955,36.515 67.977,36.323 68.024,36.14 C67.716,19.062 53.727,5.259 
                                                            36.575,5.259 C19.231,5.259 5.12,19.369 5.12,36.713 C5.12,49.867 14.201,59.094 21.817,64.519 C24.438,66.386 27.486,70.467 28.671,75.454 
                                                            L28.671,75.454 Z">
                                                            </path>
                                                            <path d="M46.094,93.063 C46.094,97.169 41.831,100.5 36.575,100.5 C32.58,100.5 29.162,98.58 27.749,95.855 C27.302,94.992 27.057,94.05 
                                                            27.057,93.064"></path><path d="M25.271,90.792 C24.642,90.792 24.117,90.299 24.083,89.665 C24.049,89.01 24.553,88.448 25.209,88.414 
                                                            L47.813,87.224 C48.454,87.185 49.03,87.693 49.065,88.35 C49.099,89.006 48.594,89.568 47.939,89.603 L25.335,90.79 C25.314,90.792 
                                                            25.292,90.792 25.271,90.792 L25.271,90.792 Z"></path><path d="M25.271,86.033 C24.642,86.033 24.117,85.542 24.083,84.907 C24.049,84.25 
                                                            24.553,83.689 25.209,83.654 L47.813,82.465 C48.454,82.423 49.03,82.935 49.065,83.591 C49.099,84.247 48.594,84.808 47.939,84.842 
                                                            L25.334,86.033 C25.314,86.033 25.292,86.033 25.271,86.033 L25.271,86.033 Z">
                                                            </path>
                                                      </g>
                                                </svg>
                                                
                                                ';
                  elseif ($key == 'warning') :
                  $content .= '
                                                <svg width="90px" height="84px" viewBox="0 0 90 84" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                                fillrule="evenodd" stroke="currentColor" stroke-width="1" stroke-linecap="none" stroke-linejoin="none">
                                                      <g transform="translate(0.000000, -1.000000)" fill="#FFFFFF">
                                                            <path d="M35.08,46.026 C35.08,40.587 39.516,36.169 44.999,36.169 C50.477,36.169 54.927,40.587 54.927,46.026 
                                                            C54.927,51.469 50.477,55.887 44.999,55.887 C39.516,55.888 35.08,51.47 35.08,46.026 L35.08,46.026 Z M0.007,45.695 
                                                            C0,45.808 0,45.906 0,46.026 C0,62.778 9.287,77.374 23.029,85.005 L39.152,58.314 C34.502,56.15 31.285,51.456 31.285,46.027 
                                                            C31.285,45.845 31.285,45.794 31.289,45.62 L0.007,45.695 L0.007,45.695 Z M67.185,6.654 C52.452,-1.502 35.102,-0.556 
                                                            21.703,7.657 L37.172,34.11 C41.335,31.125 47.171,31.135 51.958,33.79 C52.108,33.877 52.15,33.899 52.305,34.008 L67.481,6.817 
                                                            C67.385,6.752 67.282,6.709 67.185,6.654 L67.185,6.654 Z M58.707,45.619 C58.722,45.793 58.722,45.845 58.722,46.026 C58.722,51.455 
                                                            55.502,56.149 50.856,58.313 L66.974,85.004 C80.72,77.373 89.999,62.777 89.999,46.025 L89.999,45.694 L58.707,45.619 L58.707,45.619 
                                                            Z">
                                                            </path>
                                                      </g>
                                                </svg>
                                          
                                                ';
                  else :
                  $content .= '
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                stroke-width="2" stroke-linecap="none" stroke-linejoin="none">
                                                      <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                                      <line x1="12" y1="9" x2="12" y2="13"></line>
                                                      <line x1="12" y1="17" x2="12" y2="17"></line>
                                                </svg>
                                          
                                                ';

                  endif;

                  
                  
              $content .= '
                                          </div>
                                          <div class="hint-box__content">
                                                <p>
                                                      <span class="hint-box__title">' . $type['choices'][$key] . '</span>
                                                       '. $text .' 
                                                </p>
                                          </div>
                                    </div>
                                    <!-- /wp:xyoos/notice -->
                              ';
            
                             
            // Bouton
            elseif (get_row_layout() == 'bouton') :

              $url = get_sub_field('url');
              $intitule = get_sub_field('intitule');
              $target = get_sub_field('cible') ? '_blank' : '';

              $content .= '
                                    <!-- wp:xyoos/button {"buttonClass":"green"} -->
                                          <p class="wp-block-xyoos-button">
                                                <a href="' . $url . '" target="' . $target . '" class="button button--green" data-type="green" rel="noopener noreferrer">
                                                      <span class="dashicons dashicons-admin-post" data-icon="admin-post"></span>
                                                      <span>' . $intitule . '</span>
                                                </a>
                                          </p>
                                    <!-- /wp:xyoos/button -->
                              ';


            // Texte
            elseif (get_row_layout() == 'texte') :

                  $text = get_sub_field('contenu');

                  $text = str_replace( '<p', '<!-- wp:paragraph --><p', $text);
                  $text = str_replace( '</p>', '</p><!-- /wp:paragraph -->', $text);

                  $text = str_replace( '<h2', '<!-- wp:heading --><h2', $text);
                  $text = str_replace( '</h2>', '</h2><!-- /wp:heading -->', $text);

                  $text = str_replace( '<h3', '<!-- wp:heading {"level":3} --><h3', $text);
                  $text = str_replace( '</h3>', '</h3><!-- /wp:heading -->', $text);

                  $text = str_replace( '<h4', '<!-- wp:heading {"level":4} --><h4', $text);
                  $text = str_replace( '</h4>', '</h4><!-- /wp:heading -->', $text);

                  $text = str_replace( '<h5', '<!-- wp:heading {"level":5} --><h5', $text);
                  $text = str_replace( '</h5>', '</h5><!-- /wp:heading -->', $text);

                  $text = str_replace( '<h6', '<!-- wp:heading {"level":6} --><h6', $text);
                  $text = str_replace( '</h6>', '</h6><!-- /wp:heading -->', $text);

			require_once( plugin_dir_path( __FILE__ ) . "lib/simple_html_dom.php");

                 
                  $html = str_get_html($text);
                  
                      
                        foreach($html ->find('img')  as $element):
                              $element->outertext = '';
                              $text = str_replace($element,'<!-- wp:image -->
                              <figure class="wp-block-image"><img src="'. $image['sizes']['large'] .'" alt="' . $image['title'] . '"/></figure>
                              <!-- /wp:image -->', $html); 
                              $text = str_replace($element,'</figure> <!-- /wp:image -->', $html); 

                        endforeach;
                     
                  
                                    
                  $html->save();
                  
                

              $content .= $text;


            // Tableau 5 colonnes
            elseif (get_row_layout() == 'tableau_5') :

              $colonne1 = get_sub_field('colonne_1');
              $colonne2 = get_sub_field('colonne_2');
              $colonne3 = get_sub_field('colonne_3');
              $colonne4 = get_sub_field('colonne_4');
              $colonne5 = get_sub_field('colonne_5');

              $content .= ' 
                                    <!-- wp:table -->
                                          <table class="wp-block-table">
                                                <tbody>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                            <td>' . $colonne4 . '</td>
                                                            <td>' . $colonne5 . '</td>
                                                      </tr>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                            <td>' . $colonne4 . '</td>
                                                            <td>' . $colonne5 . '</td>
                                                      </tr>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                            <td>' . $colonne4 . '</td>
                                                            <td>' . $colonne5 . '</td>
                                                      </tr>
                                                </tbody>
                                          </table>
                                    <!-- /wp:table -->
                                    
                                    ';

            // Tableau 4 colonnes
            elseif (get_row_layout() == 'tableau_4') :

              $colonne1 = get_sub_field('colonne_1');
              $colonne2 = get_sub_field('colonne_2');
              $colonne3 = get_sub_field('colonne_3');
              $colonne4 = get_sub_field('colonne_4');

              $content .= ' 
                                    <!-- wp:table -->
                                          <table class="wp-block-table">
                                                <tbody>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                            <td>' . $colonne4 . '</td>
                                                            
                                                      </tr>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                            <td>' . $colonne4 . '</td>
                                                            
                                                      </tr>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                            <td>' . $colonne4 . '</td>
                                                            
                                                      </tr>
                                                </tbody>
                                          </table>
                                    <!-- /wp:table -->
                                    
                                    ';

            // Tableau 3 colonnes
            elseif (get_row_layout() == 'tableau_3') :

              $colonne1 = get_sub_field('colonne_1');
              $colonne2 = get_sub_field('colonne_2');
              $colonne3 = get_sub_field('colonne_3');

              $content .= ' 
                                    <!-- wp:table -->
                                          <table class="wp-block-table">
                                                <tbody>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                      </tr>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                      </tr>
                                                      <tr>
                                                            <td>' . $colonne1 . '</td>
                                                            <td>' . $colonne2 . '</td>
                                                            <td>' . $colonne3 . '</td>
                                                      </tr>
                                                </tbody>
                                          </table>
                                    <!-- /wp:table -->
                                    
                                    ';

            // Texte + pub    
            elseif (get_row_layout() == 'texte_pub') :

              Xyoos_Lessons::$ads++;
              $position = get_sub_field('position_de_la_pub') == 'left';
              $text = get_sub_field('contenu');

              if (get_sub_field('position_de_la_pub') == 'left') :
                $content .= '
                                          <!-- wp:columns -->
                                                <div class="wp-block-columns has-2-columns">
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column ' . $position . '">
                                                                  <!-- wp:xyoos/rectangle /-->
                                                            </div>
                                                      <!-- /wp:column -->
                                          
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column">
                                                                  <!-- wp:paragraph -->
                                                                      <p> ' . $text . '</p>
                                                                  <!-- /wp:paragraph -->
                                                            </div>
                                                      <!-- /wp:column -->
                                                </div>
                                          <!-- /wp:columns -->
                                          
                                          ';
              else :
                $content .= '
                                          <!-- wp:columns -->
                                                <div class="wp-block-columns has-2-columns">
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column">
                                                                  <!-- wp:paragraph -->
                                                                        <p>' . $text . '</p>
                                                                  <!-- /wp:paragraph -->
                                                            </div>
                                                      <!-- /wp:column -->
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column ' . $position . '">
                                                                  <!-- wp:xyoos/rectangle /-->
                                                            </div>
                                                      <!-- /wp:column -->
                                                </div>
                                          <!-- /wp:columns -->
                                          
                                          ';

              endif;

            elseif (get_row_layout() == 'texte_image') :

              $image = get_sub_field('image');
              $text = get_sub_field('contenu');
              $position = get_sub_field('position_de_limage') == 'left';

              if (get_sub_field('position_de_limage') == 'left') :
                $content .= '
                                          <!-- wp:columns -->
                                                <div class="wp-block-columns has-2-columns">
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column ' . $position . '">
                                                                  <!-- wp:image -->
                                                                        <figure class="wp-block-image">
                                                                              <img src="' . $image['sizes']['large'] . '" alt="' . $image['title'] . '"/>
                                                                        </figure>
                                                                  <!-- /wp:image -->                  
                                                            </div>
                                                      <!-- /wp:column -->
                                          
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column">
                                                                  <!-- wp:paragraph -->
                                                                        <p>' . $text . '</p>
                                                                  <!-- /wp:paragraph -->
                                                            </div>
                                                      <!-- /wp:column -->
                                                </div>
                                          <!-- /wp:columns -->
                                          
                                          ';
              else :
                $content .= '
                                          <!-- wp:columns -->
                                                <div class="wp-block-columns has-2-columns">
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column">
                                                                  <!-- wp:paragraph -->
                                                                        <p>' . $text . '</p>
                                                                  <!-- /wp:paragraph -->
                                                            </div>
                                                      <!-- /wp:column -->
                                                      <!-- wp:column -->
                                                            <div class="wp-block-column ' . $position . '">
                                                                  <!-- wp:image -->
                                                                        <figure class="wp-block-image">
                                                                              <img src="' . $image['sizes']['large'] . '" alt="' . $image['title'] . '"/>
                                                                        </figure>
                                                                  <!-- /wp:image -->                  
                                                            </div>
                                                      <!-- /wp:column -->
                                                </div>
                                          <!-- /wp:columns -->
                                          
                                          ';

              endif;


            endif;
          endwhile;
        endif;
        $result .= "<li>Migré : " . get_the_title() . "</li>";
      endwhile;
    endif;
    wp_reset_postdata();


    $mon_post = array(
      'ID'           => 10133,
      'post_content' => $content,
    );

    // Update the post into the database
    wp_update_post( $mon_post );

    include plugin_dir_path(__FILE__) . "templates/migration-result.php";
  } else {

    include plugin_dir_path(__FILE__) . "templates/migration-options.php";
  }
}
