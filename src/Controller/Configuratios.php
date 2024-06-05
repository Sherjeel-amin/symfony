<!-- Symfony applications are configured with the files stored in the config/ directory, which has this default structure:

your-project/
├─ config/
│  ├─ packages/
│  ├─ bundles.php
│  ├─ routes.yaml
│  └─ services.yaml
The routes.yaml file defines the routing configuration;
The services.yaml file configures the services of the service container;
The bundles.php file enables/disables packages in your application;
The config/packages/ directory stores the configuration of every package installed in your application.
Packages (also called "bundles" in Symfony and "plugins/modules" in other projects) add ready-to-use features to your projects.

When using Symfony Flex, which is enabled by default in Symfony applications, packages update the bundles.php file and create new files in config/packages/ automatically during their installation. For example, this is the default file created by the "API Platform" bundle:

 
# config/packages/api_platform.yaml
api_platform:
    mapping:
        paths: ['%kernel.project_dir%/src/Entity']
Splitting the configuration into lots of small files might appear intimidating for some Symfony newcomers. However, you'll get used to them quickly and you rarely need to change these files after package installation.

To learn about all the available configuration options, check out the Symfony Configuration Reference or run the config:dump-reference command.

Configuration Formats
Unlike other frameworks, Symfony doesn't impose a specific format on you to configure your applications, but lets you choose between YAML, XML and PHP. Throughout the Symfony documentation, all configuration examples will be shown in these three formats.

There isn't any practical difference between formats. In fact, Symfony transforms all of them into PHP and caches them before running the application, so there's not even any performance difference.

YAML is used by default when installing packages because it's concise and very readable. These are the main advantages and disadvantages of each format:

YAML: simple, clean and readable, but not all IDEs support autocompletion and validation for it. Learn the YAML syntax;
XML: autocompleted/validated by most IDEs and is parsed natively by PHP, but sometimes it generates configuration considered too verbose. Learn the XML syntax;
PHP: very powerful and it allows you to create dynamic configuration with arrays or a ConfigBuilder.
By default Symfony loads the configuration files defined in YAML and PHP formats. If you define configuration in XML format, update the configureContainer() and/or configureRoutes() methods in the src/Kernel.php file to add support for the .xml file extension.

Importing Configuration Files
Symfony loads configuration files using the Config component, which provides advanced features such as importing other configuration files, even if they use a different format:

  
# config/services.yaml
imports:
    - { resource: 'legacy_config.php' }

    # glob expressions are also supported to load multiple files
    - { resource: '/etc/myapp/*.yaml' }

    # ignore_errors: not_found silently discards errors if the loaded file doesn't exist
    - { resource: 'my_config_file.xml', ignore_errors: not_found }
    # ignore_errors: true silently discards all errors (including invalid code and not found)
    - { resource: 'my_other_config_file.xml', ignore_errors: true }

# ...
Configuration Parameters
Sometimes the same configuration value is used in several configuration files. Instead of repeating it, you can define it as a "parameter", which is like a reusable configuration value. By convention, parameters are defined under the parameters key in the config/services.yaml file:

  
# config/services.yaml
parameters:
    # the parameter name is an arbitrary string (the 'app.' prefix is recommended
    # to better differentiate your parameters from Symfony parameters).
    app.admin_email: 'something@example.com'

    # boolean parameters
    app.enable_v2_protocol: true

    # array/collection parameters
    app.supported_locales: ['en', 'es', 'fr']

    # binary content parameters (encode the contents with base64_encode())
    app.some_parameter: !!binary VGhpcyBpcyBhIEJlbGwgY2hhciAH

    # PHP constants as parameter values
    app.some_constant: !php/const GLOBAL_CONSTANT
    app.another_constant: !php/const App\Entity\BlogPost::MAX_ITEMS

    # Enum case as parameter values
    app.some_enum: !php/enum App\Enum\PostState::Published

# ...
By default and when using XML configuration, the values between <parameter> tags are not trimmed. This means that the value of the following parameter will be '\n    something@example.com\n':

  
<parameter key="app.admin_email">
    something@example.com
</parameter>
If you want to trim the value of your parameter, use the trim attribute. When using it, the value of the following parameter will be something@example.com:

  
<parameter key="app.admin_email" trim="true">
    something@example.com
</parameter>
Once defined, you can reference this parameter value from any other configuration file using a special syntax: wrap the parameter name in two % (e.g. %app.admin_email%):

  
# config/packages/some_package.yaml
some_package:
    # any string surrounded by two % is replaced by that parameter value
    email_address: '%app.admin_email%'
If some parameter value includes the % character, you need to escape it by adding another %, so Symfony doesn't consider it a reference to a parameter name:

  
# config/services.yaml
parameters:
    # Parsed as 'https://symfony.com/?foo=%s&amp;bar=%d'
    url_pattern: 'https://symfony.com/?foo=%%s&amp;bar=%%d'
Due to the way in which parameters are resolved, you cannot use them to build paths in imports dynamically. This means that something like the following does not work:

  
# config/services.yaml
imports:
    - { resource: '%kernel.project_dir%/somefile.yaml' }
Configuration parameters are very common in Symfony applications. Some packages even define their own parameters (e.g. when installing the translation package, a new locale parameter is added to the config/services.yaml file).

By convention, parameters whose names start with a dot . (for example, .mailer.transport), are available only during the container compilation. They are useful when working with Compiler Passes to declare some temporary parameters that won't be available later in the application.

Later in this article you can read how to get configuration parameters in controllers and services.

Configuration Environments
You have only one application, but whether you realize it or not, you need it to behave differently at different times:

While developing, you want to log everything and expose nice debugging tools;
After deploying to production, you want that same application to be optimized for speed and only log errors.
The files stored in config/packages/ are used by Symfony to configure the application services. In other words, you can change the application behavior by changing which configuration files are loaded. That's the idea of Symfony's configuration environments.

A typical Symfony application begins with three environments:

dev for local development,
prod for production servers,
test for automated tests.
When running the application, Symfony loads the configuration files in this order (the last files can override the values set in the previous ones):

The files in config/packages/*.<extension>;
the files in config/packages/<environment-name>/*.<extension>;
config/services.<extension>;
config/services_<environment-name>.<extension>.
Take the framework package, installed by default, as an example:

First, config/packages/framework.yaml is loaded in all environments and it configures the framework with some options;
In the prod environment, nothing extra will be set as there is no config/packages/prod/framework.yaml file;
In the dev environment, there is no file either ( config/packages/dev/framework.yaml does not exist).
In the test environment, the config/packages/test/framework.yaml file is loaded to override some of the settings previously configured in config/packages/framework.yaml.
In reality, each environment differs only somewhat from others. This means that all environments share a large base of common configuration, which is put in files directly in the config/packages/ directory.

You can also define options for different environments in a single configuration file using the special when keyword:

  
# config/packages/webpack_encore.yaml
webpack_encore:
    # ...
    output_path: '%kernel.project_dir%/public/build'
    strict_mode: true
    cache: false

# cache is enabled only in the "prod" environment
when@prod:
    webpack_encore:
        cache: true

# disable strict mode only in the "test" environment
when@test:
    webpack_encore:
        strict_mode: false

# YAML syntax allows to reuse contents using "anchors" (&some_name) and "aliases" (*some_name).
# In this example, 'test' configuration uses the exact same configuration as in 'prod'
when@prod: &webpack_prod
    webpack_encore:
        # ...
when@test: *webpack_prod
See the configureContainer() method of the Kernel class to learn everything about the loading order of configuration files.

Selecting the Active Environment
Symfony applications come with a file called .env located at the project root directory. This file is used to define the value of environment variables and it's explained in detail later in this article.

Open the .env file (or better, the .env.local file if you created one) and edit the value of the APP_ENV variable to change the environment in which the application runs. For example, to run the application in production:

# .env (or .env.local)
APP_ENV=prod
This value is used both for the web and for the console commands. However, you can override it for commands by setting the APP_ENV value before running them:


php bin/console command_name


APP_ENV=prod php bin/console command_name
Creating a New Environment
The default three environments provided by Symfony are enough for most projects, but you can define your own environments too. For example, this is how you can define a staging environment where the client can test the project before going to production:

Create a configuration directory with the same name as the environment (in this case, config/packages/staging/);
Add the needed configuration files in config/packages/staging/ to define the behavior of the new environment. Symfony loads the config/packages/*.yaml files first, so you only need to configure the differences to those files;
Select the staging environment using the APP_ENV env var as explained in the previous section.
It's common for environments to be similar to each other, so you can use symbolic links between config/packages/<environment-name>/ directories to reuse the same configuration.

Instead of creating new environments, you can use environment variables as explained in the following section. This way you can use the same application and environment (e.g. prod) but change its behavior thanks to the configuration based on environment variables (e.g. to run the application in different scenarios: staging, quality assurance, client review, etc.)

Configuration Based on Environment Variables
Using environment variables (or "env vars" for short) is a common practice to:

Configure options that depend on where the application is run (e.g. the database credentials are usually different in production versus your local machine);
Configure options that can change dynamically in a production environment (e.g. to update the value of an expired API key without having to redeploy the entire application).
In other cases, it's recommended to keep using configuration parameters.

Use the special syntax %env(ENV_VAR_NAME)% to reference environment variables. The values of these options are resolved at runtime (only once per request, to not impact performance) so you can change the application behavior without having to clear the cache.

This example shows how you could configure the application secret using an env var:

  
# config/packages/framework.yaml
framework:
    # by convention the env var names are always uppercase
    secret: '%env(APP_SECRET)%'
    # ...
Your env vars can also be accessed via the PHP super globals $_ENV and $_SERVER (both are equivalent):

  
$databaseUrl = $_ENV['DATABASE_URL']; // mysql://db_user:db_password@127.0.0.1:3306/db_name
$env = $_SERVER['APP_ENV']; // prod
However, in Symfony applications there's no need to use this, because the configuration system provides a better way of working with env vars.

The values of env vars can only be strings, but Symfony includes some env var processors to transform their contents (e.g. to turn a string value into an integer).

To define the value of an env var, you have several options:

Add the value to a .env file;
Encrypt the value as a secret;
Set the value as a real environment variable in your shell or your web server.
If your application tries to use an env var that hasn't been defined, you'll see an exception. You can prevent that by defining a default value for the env var. To do so, define a parameter with the same name as the env var using this syntax:

  
# config/packages/framework.yaml
parameters:
    # if the SECRET env var value is not defined anywhere, Symfony uses this value
    env(SECRET): 'some_secret'

# ...
Some hosts - like Platform.sh - offer easy utilities to manage env vars in production.

Some configuration features are not compatible with env vars. For example, defining some container parameters conditionally based on the existence of another configuration option. When using an env var, the configuration option always exists, because its value will be null when the related env var is not defined.

Beware that dumping the contents of the $_SERVER and $_ENV variables or outputting the phpinfo() contents will display the values of the environment variables, exposing sensitive information such as the database credentials.

The values of the env vars are also exposed in the web interface of the Symfony profiler. In practice this shouldn't be a problem because the web profiler must never be enabled in production.

Configuring Environment Variables in .env Files
Instead of defining env vars in your shell or your web server, Symfony provides a convenient way to define them inside a .env (with a leading dot) file located at the root of your project.

The .env file is read and parsed on every request and its env vars are added to the $_ENV & $_SERVER PHP variables. Any existing env vars are never overwritten by the values defined in .env, so you can combine both.

For example, to define the DATABASE_URL env var shown earlier in this article, you can add:

# .env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
This file should be committed to your repository and (due to that fact) should only contain "default" values that are good for local development. This file should not contain production values.

In addition to your own env vars, this .env file also contains the env vars defined by the third-party packages installed in your application (they are added automatically by Symfony Flex when installing packages).

Since the .env file is read and parsed on every request, you don't need to clear the Symfony cache or restart the PHP container if you're using Docker.

.env File Syntax
Add comments by prefixing them with #:

# database credentials
DB_USER=root
DB_PASS=pass # this is the secret password
Use environment variables in values by prefixing variables with $:

DB_USER=root
DB_PASS=${DB_USER}pass # include the user as a password prefix
The order is important when some env var depends on the value of other env vars. In the above example, DB_PASS must be defined after DB_USER. Moreover, if you define multiple .env files and put DB_PASS first, its value will depend on the DB_USER value defined in other files instead of the value defined in this file.

Define a default value in case the environment variable is not set:

DB_USER=
DB_PASS=${DB_USER:-root}pass # results in DB_PASS=rootpass
Embed commands via $() (not supported on Windows):

START_TIME=$(date)
Using $() might not work depending on your shell.

As a .env file is a regular shell script, you can source it in your own shell scripts:

source .env
Overriding Environment Values via .env.local
If you need to override an environment value (e.g. to a different value on your local machine), you can do that in a .env.local file:

# .env.local
DATABASE_URL="mysql://root:@127.0.0.1:3306/my_database_name"
This file should be ignored by git and should not be committed to your repository. Several other .env files are available to set environment variables in just the right situation:

.env: defines the default values of the env vars needed by the application;
.env.local: overrides the default values for all environments but only on the machine which contains the file. This file should not be committed to the repository and it's ignored in the test environment (because tests should produce the same results for everyone);
.env.<environment> (e.g. .env.test): overrides env vars only for one environment but for all machines (these files are committed);
.env.<environment>.local (e.g. .env.test.local): defines machine-specific env var overrides only for one environment. It's similar to .env.local, but the overrides only apply to one environment.
Real environment variables always win over env vars created by any of the .env files. Note that this behavior depends on the variables_order configuration, which must contain an E to expose the $_ENV superglobal. This is the default configuration in PHP.

The .env and .env.<environment> files should be committed to the repository because they are the same for all developers and machines. However, the env files ending in .local (.env.local and .env.<environment>.local) should not be committed because only you will use them. In fact, the .gitignore file that comes with Symfony prevents them from being committed.
 -->


<!-- 
 Configuration Directory Structure

Symfony applications are configured with files stored in the config/ directory, which has the following default structure:

arduino

your-project/
├─ config/
│  ├─ packages/
│  ├─ bundles.php
│  ├─ routes.yaml
│  └─ services.yaml

    routes.yaml: Defines the routing configuration.
    services.yaml: Configures the services of the service container.
    bundles.php: Enables/disables packages in the application.
    packages/: Stores the configuration of every installed package.

Packages (Bundles)

    Packages (also called "bundles") add ready-to-use features to your projects.
    Symfony Flex automates updating bundles.php and creating files in config/packages/ during package installation.

Configuration Formats

Symfony supports three configuration formats:

    YAML: Default format, simple and clean.
    XML: Autocompleted/validated by most IDEs, but can be verbose.
    PHP: Powerful, allows dynamic configuration.

There is no performance difference between these formats as Symfony transforms them into PHP and caches them before running the application.
Importing Configuration Files

Symfony loads configuration files using the Config component. You can import other configuration files using:

yaml

# config/services.yaml
imports:
    - { resource: 'legacy_config.php' }
    - { resource: '/etc/myapp/*.yaml' }
    - { resource: 'my_config_file.xml', ignore_errors: not_found }
    - { resource: 'my_other_config_file.xml', ignore_errors: true }

Configuration Parameters

Parameters are reusable configuration values defined under the parameters key in config/services.yaml:

yaml

# config/services.yaml
parameters:
    app.admin_email: 'something@example.com'
    app.enable_v2_protocol: true
    app.supported_locales: ['en', 'es', 'fr']

Reference parameters using %parameter_name% syntax in other configuration files:

yaml

# config/packages/some_package.yaml
some_package:
    email_address: '%app.admin_email%'

Configuration Environments

Symfony applications have different environments to change behavior based on context:

    dev: Local development.
    prod: Production servers.
    test: Automated tests.

Configuration files are loaded in this order:

    config/packages/*.<extension>
    config/packages/<environment-name>/*.<extension>
    config/services.<extension>
    config/services_<environment-name>.<extension>

You can also define environment-specific configurations using the when keyword:

yaml

# config/packages/webpack_encore.yaml
webpack_encore:
    output_path: '%kernel.project_dir%/public/build'
    strict_mode: true
    cache: false

when@prod:
    webpack_encore:
        cache: true

when@test:
    webpack_encore:
        strict_mode: false

Environment Variables

Use environment variables for dynamic configuration based on where the application is run:

yaml

# config/packages/framework.yaml
framework:
    secret: '%env(APP_SECRET)%'

Define environment variables in .env files located at the root of your project:

dotenv

# .env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"

Override environment values in .env.local:

dotenv

# .env.local
DATABASE_URL="mysql://root:@127.0.0.1:3306/my_database_name" -->
