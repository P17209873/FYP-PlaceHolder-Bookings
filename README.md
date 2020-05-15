# PlaceHolder-Bookings
  Final Year Project - Matthew Trim 
  
  ## How to use
  
  * On a Linux machine, install PHP 7.4, the most recent version of MySQL, Apache, and the following dependencies:
    * php7.4-xml
    * php7.4-mbstring 
    * php7.4-mysql
    
  * Enable the apache rewrite module (`sudo a2enmod rewrite`)
  * Move the contents of the `public` folder into the `/var/www/html` directory (assuming the test server is running 
  Ubuntu, otherwise move the contents of this folder to the web server document root)
  * Move the `private` and `vendor` directories, and `composer.phar`, `composer.json`, and `composer.lock` files to the 
  parent directory of the web server Document Root (for example, `/var/www` on an Ubuntu installation)
  * Run the CreateDatabase.sql script to create the application database
  * Run `./composer.phar update` to ensure application dependencies are up to date
  * Visit `http://localhost` in a web browser
    
  
  Or, visit [here](https://place-holder.tech), where all the configuration has already been performed. 
  The code is the exact same on the deployed version, with the exception of the JavaScript fetch API call URLs, which 
  has been modified to call the routes from the deployment server.
    