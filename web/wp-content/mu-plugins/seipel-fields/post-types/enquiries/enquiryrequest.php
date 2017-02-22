<?php
class EnquiryPostListener {
  private $valid = false;
  private $errors = array();

  /**
  * @param array $postdata $_POST array
  */
  public function __construct(array $postdata) {
    $this->valid = $this->validatePostData($postdata);
    if($this->valid){
      $this->submitEnquiry($postdata);
    }
    write_log($this->errors);
  }

  /**
   * @param array $postdata $_POST array
   * @return bool
   */
  private function validatePostData(array $postdata) {
    // check here the $_POST data, e.g. if the post data actually comes
    // from the api, autentication and so on
    if(!isset($postdata['_wpnonce']) || !wp_verify_nonce($postdata['_wpnonce'], 'sendenquiry')){
      $this->errors[]= "Invalid form submission";
    }
    if(!isset($postdata['fullname']) || trim($postdata['fullname']) === ''){
      $this->errors[]= "Please provide your name";
    }
    if(!isset($postdata['email']) || !filter_var($postdata['email'], FILTER_VALIDATE_EMAIL)){
      if(!isset($postdata['email']) || trim($postdata['email']) === ''){
        $this->errors[]= "Please provide an email address";
      } else {
        $this->errors[]= "Please provide a valid email address";
      }
    }
    if(!isset($postdata['enquiry_type']) || trim($postdata['enquiry_type']) === ''){
      $this->errors[]= "Please select and enquiry type";
    }
    if(!isset($postdata['message']) || trim($postdata['message'] === '')){
      $this->errors[]= "Please add a message to your enquiry";
    }
    if(isset($postdata['specialcontact']) && trim($postdata['specialcontact'] !== '')){
      write_log("Invalid");
      return false;
    }

    if(count($this->errors) > 0){
      return false;
    }

    return true;
  }

  private function submitEnquiry(array $params){
    $post_arr = [
      "post_title" => "New ".$params["enquiry_type"]." from " . $params["fullname"] .
        " via the ".get_bloginfo("name")." website",
      "post_type"  => "enquiry",
      "meta_input" => [
        "full_name" => isset($params["fullname"]) ? $params["fullname"] : null ,
        "enquiry_type"  => isset($params["enquiry_type"]) ? $params["enquiry_type"] : null,
        "email_address"    => isset($params["email"]) ? $params["email"] : null,
        "phone_number"    => isset($params["phone"]) ? $params["phone"] : null,
        "message"  => isset($params["message"]) ? $params["message"] : null
      ]
    ];
    $success = wp_insert_post($post_arr, true);

    $contact_email = get_field('contact_form_email', 'option');
    $sender_email = $post_arr['meta_input']['email_address'];
    $from_email = 'From: '
      .get_bloginfo('name').' <'.$contact_email.'>';

    if($this->valid && is_int($success)){
      ob_start();
      include 'emailtemplate.php';
      $email = ob_get_contents();
      ob_end_clean();

      write_log($email);

      add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

      wp_mail($contact_email, "New enquiry from the ".get_bloginfo('name').' website', $email, $from_email);
      wp_mail($sender_email, "Thanks for your enquiry", $email, $from_email);

      remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );


    } else {
      return new WP_REST_Response( ["message"=>"No images found"], 404 );
    }
  }

  public function isValid(){
    return $this->valid;
  }

}

add_action( 'wp_loaded', function() {
  if ( is_user_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendenquiry'])) {
    // fire the custom action
    $enquiry = new EnquiryPostListener($_POST);
    do_action('sendenquiry', $enquiry);
    if($enquiry->isValid()){
      write_log("Valid");
      unset($_POST['fullname']);
      unset($_POST['email']);
      unset($_POST['phone']);
      unset($_POST['company']);
      unset($_POST['message']);
    }
  }
});
