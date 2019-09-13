# Kookaburra
Symfony implementation of <a href="http://gibbonedu.org" target="_blank">Gibbon v18.0.01</a>

### Installation
* At the moment, during development, you can use Github as a source of the project. <a href="https://github.com/crayner/kookaburra" target="_blank">https://github.com/crayner/kookaburra</a>
* Download as a zip and extract the code to your project directory.  You will need to configure your web server to deliver a Symfony project.  Details at <a href="https://symfony.com/doc/current/setup/web_server_configuration.html" target="_blank">https://symfony.com/doc/current/setup/web_server_configuration.html</a>
* You will also need <a href="https://getcomposer.org/download/" target="_blank">__Composer__. </a>
* Run _composer install_ in the project directory.
* Open a web browser and run the base url of your site.  The install check screen should open.  ___Installation - Step 1___
* Your on your way.  Follow the on screen instuctions.

### Development
This software, being based on __<a href="https://symfony.com/" target="_blank">Symfony</a>__, requires composer to manage library software.  It also uses REACT so I use <a href="https://yarnpkg.com/lang/en/docs/install/#windows-stable" target="_blank">__YARN__</a> as a javascript library manager.  Webpack is already included with the Symfony software, so if you wish to contribute with development, then do a ___yarn install___ to load required node modules.  Prewritten yarn watch, yarn build are already included in the package.json file.