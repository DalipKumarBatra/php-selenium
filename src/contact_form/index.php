<?PHP
/**
 * Page Name - Contact Form
 */

$workingDir = realpath(dirname(__DIR__));
require_once("$workingDir/contact_form/include/contact_form.php");

$contact_form = new ContactForm();

if(isset($_POST['Submit']))
{
   if($contact_form->processForm())
   {
        $contact_form->RedirectToURL("thank-you.php");
   }
}

require_once("$workingDir/header.php");
?>
    <body>

        <div class="register-container container">
            <div class="row">
                <div class="register span6" style="margin:auto; float:none;">
                    <form id='contactus' action='<?php echo $contact_form->getSelfScript(); ?>' method='post' accept-charset='UTF-8'>
                        <h2>Contact <span class="red"><strong>US</strong></span></h2>
                        <label for="firstname" id='firstname_label'>First Name</label>
                        <input type="text" id="firstname" name="firstname" placeholder="enter your first name..." value = <?php echo $contact_form->safeDisplay('name'); ?>>
                        <label for="lastname" id='lastname_label'>Last Name</label>
                        <input type="text" id="lastname" name="lastname" placeholder="enter your first name..." value = <?php echo $contact_form->safeDisplay('lastname'); ?>>
                        <label for="username" id='username_label'>Username</label>
                        <input type="text" id="username" name="username" placeholder="choose a username..." value = <?php echo $contact_form->safeDisplay('username'); ?>>
                        <label for="email" id='email_label'>Email</label>
                        <input type="text" id="email" name="email" placeholder="enter your email..." value = <?php echo $contact_form->safeDisplay('email'); ?>>
                        <button type='submit' name='Submit' value='Submit' id='submit' />SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Javascript -->
        <script src="assets/js/jquery-1.8.2.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <!--script src="assets/js/jquery.backstretch.min.js"></script-->
        <script src="assets/js/scripts.js"></script>

    </body>

</html>

