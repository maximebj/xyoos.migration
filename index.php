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
    $result  = '';
    

    $test_ID = 9123;

    // Get posts to migrate
    $args = array(
      'p' => $test_ID,
      'post_type' => 'cours'
    );
    
    $my_post = new WP_Query($args);
    
    if ($my_post->have_posts()):
      while ($my_post->have_posts()):
        $my_post->the_post();
        
        if (have_rows('cours')):
          while (have_rows('cours')):
            the_row();
            
            /* Migrate bloc ACF with Gutenberg*/
            
            // Definition
            if (get_row_layout() == 'definition'):
              $post_object = get_sub_field('definition');
              $content .= ' <!-- wp:xyoos/definition {"definitionID":' . $post_object . '} /-->';
            
            // Exercice
            elseif (get_row_layout() == 'exercice'):
              $exercice = get_sub_field('id');
              $content .= '<!-- wp:xyoos/exercice {"exerciceID":"' . $exercice . '"} /-->';
            
            // Notice
            elseif (get_row_layout() == 'bloc_information'):
              $type = get_sub_field_object('type_de_bloc');
              $key  = get_sub_field('type_de_bloc');
              $text = get_sub_field('contenu');

              // remove P from text to get gut compatible
              $text = str_replace( '<p>', '', $text );
              $text = str_replace( '</p>', '', $text );

              $content .= '
                <!-- wp:xyoos/notice -->
                <div class="wp-block-xyoos-notice hint-box hint-box--' . $key . '" data-type="' . $key . '">
                  <div class="hint-box__icon">';
              
              if ($key == 'tips'):
                $content .= "   
                  <svg fill='#FFFFFF' width='100pt' height='100pt' version='1.1' viewbox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'>
                    <g>
                      <path d='m50 5c-0.71094 0-1.4336 0.023438-2.1445 0.070312-15.316 0.99609-29.016 14.785-29.922 30.105-0.71094 12.016 5.25 23.32 15.555 29.508 3.5156 2.1094 5.6992 6.0859 5.6992 10.379 0 2.918 2.375 5.293 5.293 5.293h11.031c2.9219 0 5.3008-2.3789 5.3008-5.2969 0-4.2539 2.2656-8.2734 5.9062-10.5 9.6484-5.8906 15.406-16.145 15.406-27.434 0-17.715-14.41-32.125-32.125-32.125zm-4.7617 49.316h-2.1914c-1.2109 0-2.1914-0.98438-2.1914-2.1914 0-1.2109 0.98438-2.1914 2.1914-2.1914 1.2109 0 2.1914 0.98438 2.1914 2.1914zm3 3h3.5195v19.039h-3.5195zm16.398 3.8281c-4.8242 2.9453-7.8242 8.2773-7.8242 13.914 0 0.71484-0.58203 1.2969-1.3008 1.2969h-0.75391l0.003907-19.039h2.1914c2.8633 0 5.1914-2.3281 5.1914-5.1914s-2.3281-5.1914-5.1914-5.1914-5.1914 2.3281-5.1914 5.1914v2.1914h-3.5195v-2.1914c0-2.8633-2.3281-5.1914-5.1914-5.1914s-5.1914 2.3281-5.1914 5.1914 2.3281 5.1914 5.1914 5.1914h2.1914v19.039h-0.75781c-0.71484 0-1.293-0.57812-1.293-1.293 0-5.6875-2.9297-10.98-7.6445-13.809-9.0234-5.418-14.242-15.32-13.621-25.844 0.79297-13.41 12.785-25.477 26.188-26.348 0.63281-0.042969 1.2617-0.0625 1.8867-0.0625 15.508 0 28.125 12.617 28.125 28.125 0 9.8828-5.043 18.863-13.488 24.02zm-9.875-6.8281v-2.1914c0-1.2109 0.98438-2.1914 2.1914-2.1914 1.2109 0 2.1914 0.98438 2.1914 2.1914 0 1.2109-0.98438 2.1914-2.1914 2.1914z'></path>
                      <path d='m43.82 85.543h12.359c1.1055 0 2-0.89453 2-2s-0.89453-2-2-2h-12.359c-1.1055 0-2 0.89453-2 2s0.89453 2 2 2z'></path>
                      <path d='m43.82 90.977h12.359c1.1055 0 2-0.89453 2-2 0-1.1055-0.89453-2-2-2h-12.359c-1.1055 0-2 0.89453-2 2 0 1.1055 0.89453 2 2 2z'></path>
                      <path d='m47.406 92c-0.82812 0-1.5 0.67188-1.5 1.5s0.67188 1.5 1.5 1.5h5.1875c0.82812 0 1.5-0.67188 1.5-1.5s-0.67188-1.5-1.5-1.5z'></path>
                      <path d='m58.617 12.469c-2.7656-0.97266-5.6641-1.4688-8.6172-1.4688-0.82812 0-1.5 0.67188-1.5 1.5s0.67188 1.5 1.5 1.5c2.6133 0 5.1758 0.4375 7.6172 1.3008 0.16406 0.058594 0.33203 0.085938 0.5 0.085938 0.61719 0 1.1953-0.38672 1.4141-1 0.27734-0.78516-0.13281-1.6406-0.91406-1.918z'></path>
                      <path d='m65.508 16.152c-0.66406-0.49609-1.6055-0.35938-2.1016 0.30469-0.49609 0.66406-0.35938 1.6055 0.30469 2.0977 3.7383 2.793 6.5078 6.5859 8.0156 10.969 0.21484 0.62109 0.79688 1.0117 1.418 1.0117 0.16016 0 0.32422-0.027344 0.48828-0.082031 0.78125-0.26953 1.1992-1.1211 0.93359-1.9062-1.707-4.9531-4.8359-9.2422-9.0586-12.395z'></path>
                    </g>
                  </svg>
                ";
              elseif ($key == 'advice'):
                $content .= '
                  <svg fill="#FFF" width="100pt" height="100pt" viewbox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <path d="m50 5c-24.852 0-45 20.145-45 44.996 0 24.863 20.148 45.004 45 45.004s45-20.141 45-45.004c0-24.852-20.148-44.996-45-44.996zm19.754 33.406l-17.094 26.59c-1.5703 2.4414-4.0234 3.9531-6.7266 4.1406-0.1875 0.015625-0.37891 0.019531-0.56641 0.019531-2.5039 0-4.9062-1.1445-6.668-3.1953l-8.4922-9.9062c-1.7969-2.0977-1.5547-5.2539 0.54297-7.0508s5.2539-1.5547 7.0508 0.53906l7.375 8.6055 16.164-25.152c1.4922-2.3203 4.5859-2.9961 6.9102-1.5039 2.3242 1.4961 2.9961 4.5898 1.5039 6.9141z"></path>
                  </svg>
                ';
              elseif ($key == 'question'):
                $content .= '
                <svg fill="#FFF" width="100pt" height="100pt" viewbox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                  <path d="m50 3c-12.465 0-24.418 4.9531-33.234 13.766-8.8125 8.8164-13.766 20.77-13.766 33.234s4.9531 24.418 13.766 33.234c8.8164 8.8125 20.77 13.766 33.234 13.766s24.418-4.9531 33.234-13.766c8.8125-8.8164 13.766-20.77 13.766-33.234s-4.9531-24.418-13.766-33.234c-8.8164-8.8125-20.77-13.766-33.234-13.766zm-0.64062 77.922c-2.1758 0-4.1367-1.3125-4.9688-3.3242-0.83203-2.0078-0.375-4.3242 1.1641-5.8633 1.5391-1.5391 3.8555-1.9961 5.8633-1.1641 2.0117 0.83203 3.3203 2.793 3.3203 4.9688-0.003906 2.9688-2.4102 5.375-5.3789 5.3828zm5.8789-26.121s-0.53125 0.19141-0.53125 0.5v4.2695h0.003907c0 2.957-2.3984 5.3555-5.3555 5.3555s-5.3555-2.3984-5.3555-5.3555v-4.2695c0.046875-4.8594 3.1914-9.1445 7.8086-10.652 3.1914-1.0625 5.3008-4.0977 5.1914-7.457-0.21484-4.1094-3.5781-7.3438-7.6914-7.4023-2.9102 0.015626-5.5547 1.6914-6.8086 4.3203-1.2852 2.6641-4.4844 3.7852-7.1484 2.5-2.668-1.2852-3.7852-4.4844-2.5-7.1484 1.4766-3.0938 3.793-5.7109 6.6914-7.5469 2.8945-1.8359 6.25-2.8203 9.6758-2.8359h0.10938c4.7656 0.03125 9.332 1.8945 12.754 5.2109 3.4219 3.3125 5.4297 7.8203 5.6094 12.582 0.13281 3.9258-1.0078 7.793-3.25 11.023-2.2422 3.2266-5.4648 5.6445-9.1914 6.8945z">
                  </path>
                </svg>';
              elseif ($key == 'warning'):
                $content .= '
                  <svg width="90px" height="84px" viewbox="0 0 90 84" xmlns="http://www.w3.org/2000/svg"
                    fill="none" fillrule="evenodd" stroke="#FFFFFF" stroke-width="1" stroke-linecap="none" stroke-linejoin="none">
                    <g transform="translate(0.000000, -1.000000)" fill="#FFFFFF">
                      <path d="M35.08,46.026 C35.08,40.587 39.516,36.169 44.999,36.169 C50.477,36.169 54.927,40.587 54.927,46.026 C54.927,51.469 50.477,55.887 44.999,55.887 C39.516,55.888 35.08,51.47 35.08,46.026 L35.08,46.026 Z M0.007,45.695 C0,45.808 0,45.906 0,46.026 C0,62.778 9.287,77.374 23.029,85.005 L39.152,58.314 C34.502,56.15 31.285,51.456 31.285,46.027 C31.285,45.845 31.285,45.794 31.289,45.62 L0.007,45.695 L0.007,45.695 Z M67.185,6.654 C52.452,-1.502 35.102,-0.556 21.703,7.657 L37.172,34.11 C41.335,31.125 47.171,31.135 51.958,33.79 C52.108,33.877 52.15,33.899 52.305,34.008 L67.481,6.817 C67.385,6.752 67.282,6.709 67.185,6.654 L67.185,6.654 Z M58.707,45.619 C58.722,45.793 58.722,45.845 58.722,46.026 C58.722,51.455 55.502,56.149 50.856,58.313 L66.974,85.004 C80.72,77.373 89.999,62.777 89.999,46.025 L89.999,45.694 L58.707,45.619 L58.707,45.619 Z"></path>
                    </g>
                  </svg>';
              endif;
              
              $content .= '
                </div>
                  <div class="hint-box__content">
                    <p class="hint-box__title">' . $type['choices'][$key] . '</p>
                    <p class="hint-box__text">' . $text . '</p>
                  </div>
                </div><!-- /wp:xyoos/notice -->
              ';
            
            // Bouton
            elseif (get_row_layout() == 'bouton'):
              $url      = get_sub_field('url');
              $intitule = get_sub_field('intitule');
              $target   = get_sub_field('cible') ? '_blank' : '';
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
            elseif (get_row_layout() == 'texte' and get_sub_field('contenu') != ""):
              $text = get_sub_field('contenu');

              require_once plugin_dir_path(__FILE__) . 'lib/simple_html_dom.php';
              
              $html = str_get_html($text);

              // Captions
              $captions = array();
              
              foreach ($html->find('.image-caption') as $element):

                $captions[] = $element->innertext; 

                // Remove P from DOM
                $element->outertext = "";
              endforeach;
              
              // Img 
              $i = 0; 
              foreach ($html->find('img') as $element):

                // Get ID in classname
                preg_match_all( '!\d+!', $element->class, $matches);         
                $id = $matches[0][0];
                
                // Remove img from DOM
                $element->parent->outertext = '
                  <!-- wp:image {"id":' . $id . '} -->
                  <figure class="wp-block-image"><img src="' . $element->src . '" alt="' . $element->alt . '" class="wp-image-' . $id  . '"/><figcaption>' . $captions[$i] . '</figcaption></figure>
                  <!-- /wp:image -->
                ';

                $i++;
              endforeach;
              
              // Tree DOM to String
              $text = $html;

              echo "<code style='max-width: 600px'>" .  htmlspecialchars( nl2br( $html)) . "</code>";


              // P
              $text = str_replace('<p', '<!-- wp:paragraph --><p', $text);
              $text = str_replace('</p>', '</p><!-- /wp:paragraph -->', $text);

              // Hx
              $text = str_replace('<h2', '<!-- wp:heading --><h2', $text);
              $text = str_replace('</h2>', '</h2><!-- /wp:heading -->', $text);
              $text = str_replace('<h3', '<!-- wp:heading {"level":3} --><h3', $text);
              $text = str_replace('</h3>', '</h3><!-- /wp:heading -->', $text);
              $text = str_replace('<h4', '<!-- wp:heading {"level":4} --><h4', $text);
              $text = str_replace('</h4>', '</h4><!-- /wp:heading -->', $text);
              $text = str_replace('<h5', '<!-- wp:heading {"level":5} --><h5', $text);
              $text = str_replace('</h5>', '</h5><!-- /wp:heading -->', $text);
              $text = str_replace('<h6', '<!-- wp:heading {"level":6} --><h6', $text);
              $text = str_replace('</h6>', '</h6><!-- /wp:heading -->', $text);

              // Li
              $text = str_replace('<ul', '<!-- wp:list --><ul', $text);
              $text = str_replace('</ul>', '</ul><!-- /wp:list -->', $text);
              

              // Save in content
              $content .= $text;
            
            // Tableau 5 colonnes
            elseif (get_row_layout() == 'tableau_5'):
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
            elseif (get_row_layout() == 'tableau_4'):
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
            elseif (get_row_layout() == 'tableau_3'):
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
            elseif (get_row_layout() == 'texte_pub'):
              Xyoos_Lessons::$ads++;
              $position = get_sub_field('position_de_la_pub') == 'left';
              $text     = get_sub_field('contenu');
              
              if (get_sub_field('position_de_la_pub') == 'left'):
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
              else:
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
            elseif (get_row_layout() == 'texte_image'):
              $image    = get_sub_field('image');
              $text     = get_sub_field('contenu');
              $position = get_sub_field('position_de_limage') == 'left';
              
              if (get_sub_field('position_de_limage') == 'left'):
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
              else:
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
        $result .= '<li>Migré : ' . get_the_title() . '</li>';
      endwhile;
    endif;
    wp_reset_postdata();
    
    $mon_post = array(
      'ID' => $test_ID,
      'post_content' => $content
    );
    
    // Update the post into the database
    wp_update_post($mon_post);
    
    include plugin_dir_path(__FILE__) . 'templates/migration-result.php';
  } else {
    include plugin_dir_path(__FILE__) . 'templates/migration-options.php';
  }
}
