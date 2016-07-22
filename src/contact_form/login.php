<?PHP
/**
 * Page Name - Login Page
 */

$workingDir = realpath(dirname(__DIR__));
require_once("$workingDir/contact_form/include/login_form.php");

$login_form = new Loginform();

if(isset($_POST['Submit']))
{
    if($login_form->processLoginForm())
    {
        $login_form->RedirectToURL("index.php");
    }
    else
    {
        $msg = 'Invailed Credentials';
    }
}

require_once("$workingDir/header_login.php");
?>
    <body>

        <div class="register-container container">
            <div class="row">
                <div class="register span6" style="margin:auto; float:none;">
                    <form id='login_form' action='<?php echo $login_form->getSelfScript(); ?>' method='post' accept-charset='UTF-8'>
                        <h2>Login <span class="red"><strong>to Go In !</strong></span></h2>
                        <?php 
                            if(isset($msg) && $msg != '')
                            {
                                echo "<h3><span class='red'><strong>$msg</strong></span></h3>";                            }
                            ?>
                        <label for="user" >User Name</label>
                        <input type="text" id="username" name="user">
                        <label for="password" id="login_pass">Password</label>
                        <input type="password" id='password' name="password">
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

