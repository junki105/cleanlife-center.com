
// 「施工事例」 post type function
// function create_case_posttype() {
 
//   register_post_type( '施工事例',
//   // CPT Options
//       array(
//           'labels' => array(
//               'name' => __( '施工事例' ),
//               'singular_name' => __( '施工事例' )
//           ),
//           'public' => true,
//           'has_archive' => true,
//           'rewrite' => array('slug' => '施工事例'),
//           'show_in_rest' => true,

//       )
//   );
// }
// Hooking up our function to theme setup
//add_action( 'init', 'create_case_posttype' );

/*
* Creating a function to create our CPT
*/
 
function custom_case_post_type() {
 
  // Set UI labels for Custom Post Type
      $labels = array(
          'name'                => _x( '施工事例', 'Post Type General Name', 'CleanLife-Center' ),
          'singular_name'       => _x( '施工事例', 'Post Type Singular Name', 'CleanLife-Center' ),
          'menu_name'           => __( '施工事例', 'CleanLife-Center' ),
          'parent_item_colon'   => __( '親施工事例', 'CleanLife-Center' ),
          'all_items'           => __( '施工事例一覧', 'CleanLife-Center' ),
          'view_item'           => __( '表示', 'CleanLife-Center' ),
          'add_new_item'        => __( '新規追加', 'CleanLife-Center' ),
          'add_new'             => __( '新規追加', 'CleanLife-Center' ),
          'edit_item'           => __( '編集', 'CleanLife-Center' ),
          'update_item'         => __( '更新', 'CleanLife-Center' ),
          'search_items'        => __( '施工事例を検索', 'CleanLife-Center' ),
          'not_found'           => __( '投稿が見つかりませんでした。', 'CleanLife-Center' ),
          'not_found_in_trash'  => __( '投稿が見つかりませんでした。', 'CleanLife-Center' ),
      );
       
  // Set other options for Custom Post Type
       
      $args = array(
          'label'               => __( '施工事例', 'CleanLife-Center' ),
          'description'         => __( '施工事例', 'CleanLife-Center' ),
          'labels'              => $labels,
          // Features this CPT supports in Post Editor
          'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
          // You can associate this CPT with a taxonomy or custom taxonomy. 
          // 'taxonomies'          => array( 'genres' ),
          /* A hierarchical CPT is like Pages and can have
          * Parent and child items. A non-hierarchical CPT
          * is like Posts.
          */ 
          'hierarchical'        => false,
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'show_in_nav_menus'   => true,
          'show_in_admin_bar'   => true,
          'menu_position'       => 5,
          'can_export'          => true,
          'has_archive'         => true,
          'exclude_from_search' => false,
          'publicly_queryable'  => true,
          'capability_type'     => 'post',
          'show_in_rest' => true,
   
      );
       
      // Registering your Custom Post Type
      register_post_type( '施工事例', $args );
   
  }
   
  /* Hook into the 'init' action so that the function
  * Containing our post type registration is not 
  * unnecessarily executed. 
  */
   
  add_action( 'init', 'custom_case_post_type', 0 );
  