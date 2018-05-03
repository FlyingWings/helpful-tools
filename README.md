# helpful-tools
Useful tools using composer
- Before using the tool, please run composer.
- Don't forget to add secret_config.php in the default directory to access your local database settings:
- Example for secret_config.php:
    - $GLOBALS["db_config"]["DB_HOST"] = "locahost";

    - GLOBALS["db_config"]["DB_USER"] = "root";

    - $GLOBALS["db_config"]["DB_PASS"] = "root";

    - $GLOBALS["db_config"]["DB_DEFAULT"] = "test";

