<?php

    $page_title    = 'Forgot Password';
    $page_class    = 'login';
    
    // If the form is submitted, INSERT into USERS.
    if (isset($_POST["submit"])){
        
        // Define the email address via the form.
        $email = mysqli_real_escape_string($db, $_POST['user_email']);
        
        // If the email is valid, submit the token.
        $result  = mysqli_query($db, "SELECT * FROM users where user_email='".$email."'");
        $Results = mysqli_fetch_array($result);

        if (count($Results)>=1){

            // Create a new token.
            $resetToken = md5(time().rand(10000, 500000));

            // Update the user's data with the new token.
            $updated = mysqli_query($db, "UPDATE users SET user_token = '$resetToken' WHERE user_id=". $Results['user_id']);
            if ($updated)
            {
                $to       = $email;
                $subject  = 'Forgotten Password';
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: '.$co_name.' <'.$co_email.'>' . "\r\n";
                $headers .= 'Reply-To: '.$co_name.' <'.$co_email.'>' . "\r\n";
                $message  =  '<table style="width: 100%; height: 100%; border: 0; background: #29313c !important;">
                        <tr><td style="height: 60px;">&nbsp;</td></tr>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" style="margin: auto; width: 600px; border: 0; font-family: Open Sans, Segoe UI, Arial, Helvetica; line-height: 1.75;">
                                    <tr>
                                        <td style="padding: 10px 40px; background: #40c79f;">
                                            <h1 style="color: #fff; font-size: 30px; margin:0; padding:10px 0; text-transform: uppercase;">Password Recovery</h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background: #fff; padding: 30px 40px 40px; color: #666 !important; font-size: 16px;"> 
                                            <p>Hi '.ucwords($Results['user_fname']).',</p>
                                            <p>It looks like you\'ve forgotten your password and requested to have it reset. Please click or copy/paste the URL below to 
                                                choose a new password. Your password change will take effect immediately and you will be brought back to the login page.
                                            </p>
                                            <p>http://lukefilewalker.xyz/polytech/login.php?mode=reset&id='.$Results['user_id'].'&token='.$resetToken.'</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><td style="height: 60px;">&nbsp;</td></tr>
                    </table>
                ';
                if (mail($to, $subject, $message, $headers) == true) {
                    $message = '<div style="padding: 20px 40px; max-width: 450px; font-size: 16px;">An email with a password reset link was sent to <strong>'.$Results['user_email'].'</strong>. Please check your email and follow the instructions within.</div>';
                } else {
                    $message = '<div style="padding: 20px 40px; max-width: 450px; font-size: 16px;">Theres an error :D</div>';
                }
                $showForm = '0';
            }
            else {

                // Catch all error.
                $message = '<div class="alert alert-danger" role="alert"><i class="pe-7s-close"></i><span class="icon icon-arrows-deny"></span> Sorry, we are unable to reset your password now.</div>';

            }
        }

        if (count($Results)==0) {

            //If the user does not exist, show error.
            $message = '<div class="alert alert-danger" role="alert"><i class="pe-7s-close"></i><span class="icon icon-arrows-deny"></span> Email address not found in our system.</div>';

        }

    }

    // Include the theme files.
    include_once ('./assets/template/website/main.php');
?>
        
<!-- Begin the page content. -->
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <a href="./">
                <img src="./assets/img/logo-primary.png" alt="<?php echo $co_name; ?>" />
            </a>
        </div>
    </div>
    <div class="row">
        <div class="login_wrap col-md-5">
            <h1><?php echo $page_title; ?></h1>
            <?php 
                echo $message; 
                if ($showForm == '1') { 
            ?>
            
                <!-- Forgot Password Form -->
                <form id="login-form" method="post" action="">
                    <input type="hidden" name="action" value="password">

                    <div class="form-group">
                        <label for="user_email">Email Address <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                            <input type="email" name="user_email" class="form-control" />
                        </div>  
                    </div>
                    <div class="form-group text-center">
                        <input class="btn btn-lg btn-primary" type="submit" name="submit" value="Submit" /> 
                    </div>
                </form>

            <?php } ?>
            <p class="form-footer">
                <a href="<?php echo $domain; ?>/login.php?page=register">Back to Login</a>
            </p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#login-form').validate({
            rules: { 
                "user_email": { required:true, email: true },
            }
        });
    });
</script>
