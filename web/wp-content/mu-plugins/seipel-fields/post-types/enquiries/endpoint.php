<?php
class Enquiries_Route extends WP_REST_Controller {
  /**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
    $version = '1';
		$namespace = 'spritzapi/v' . $version;
		$base = 'enquiries';
		register_rest_route( $namespace, '/' . $base,
      [
  			[
  				'methods'         => WP_REST_Server::CREATABLE,
  				'callback'        => [ $this, 'submit_enquiry' ],
  				'args'            => [
            "fullname",
            "company",
            "email",
            "phone",
            "message"
          ]
  			],
  		]
    );
  }

  /**
   * Get Images
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function submit_enquiry( $request ) {

    $params = $request->get_params();
    $valid = [];
    $message = [];

    if(isset($params["email"]) && is_email($params["email"])){
      $valid[]=true;
    } else {
      $valid[]=false;
      $message[]= "invalid email";
    }

    if(isset($params["fullname"]) && strlen($params["fullname"]) > 0){
      $valid[]=true;
    } else {
      $valid[]=false;
      $message[]= "invalid email";
    }

    $is_valid = !in_array(false, $valid);

    $success = false;

    if($is_valid){
      $post_arr = [
        "post_title" => "New Enquiry from " . $params["fullname"] .
          " via the ".get_bloginfo("name")." website",
        "post_type"  => "enquiry",
        "meta_input" => [
          "fullname" => isset($params["fullname"]) ? $params["fullname"] : null ,
          "company"  => isset($params["company"]) ? $params["company"] : null,
          "email"    => isset($params["email"]) ? $params["email"] : null,
          "phone"    => isset($params["phone"]) ? $params["phone"] : null,
          "message"  => isset($params["message"]) ? $params["message"] : null
        ]
      ];

      $success = wp_insert_post($post_arr, true);
    }

    $admin_email = get_field('enquiry_email', 'option');
    $sender_email = $post_arr['meta_input']['email'];
    $from_email = 'From: '
      .get_bloginfo('name').' <'.get_field('api_from_email').'>';

    if($is_valid && is_int($success)){
      ob_start();
      include 'emailtemplate.php';
      $email = ob_get_contents();
      ob_end_clean();

      write_log($email);

      add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

      wp_mail($admin_email, "New enquiry from the ".get_bloginfo('name').' website', $email, $from_email);
      wp_mail($sender_email, "Thanks for your enquiry", $email, $from_email);

      remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

      return new WP_REST_Response( ["message"=>"Enquiry successfully submitted"], 200 );
    } else {
      return new WP_REST_Response( ["message"=>"No images found"], 404 );
    }
  }
}

function wpdocs_set_html_mail_content_type() {
  return 'text/html';
}

add_action( 'rest_api_init', function () {
  $enquiry_routes = new Enquiries_Route();
  $enquiry_routes->register_routes();
} );
