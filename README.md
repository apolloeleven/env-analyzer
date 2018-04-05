# Env / PHP analyzer
Analyzes env dist file and allows to insert missing variables to env file through the console
# Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist apollo11/env-analyzer "~1.0"
```

or add

```
"apollo11/env-analyzer": "~1.0"
```

to the require section of your `composer.json` file and run `php composer.phar update` command.


The package offers:

1. [Env](https://github.com/apolloeleven/env-analyzer/blob/master/Env.php) class which can store `.env` and `.env.dist` file path, as well as, getting the difference between those files.
2. [Php](https://github.com/apolloeleven/env-analyzer/blob/master/Php.php) class which can store `env.php` and `env.dist.php` file path, as well as, getting the difference between those filese. Note: both `php` files should return Associative array either strings or integers.  
3. [Analyzer](https://github.com/apolloeleven/env-analyzer/blob/master/Analyzer.php) class which is used for getting the difference of files through the console

Basic Usage
-----
Generally, the best use case is to call [Analyzer](https://github.com/apolloeleven/env-analyzer/blob/master/Analyzer.php) from console, because it gives the ability to insert the value of the missing data, as well.

Add the following code to your console command

```
Analyzer::analyzeEnv($pathToEnv, $pathToEnvDist);
```

or add

```
Analyzer::analyzePhp($pathToPhp, $pathToDistPhp);
```

Usage from Composer
-----

You can also run [Analyzer](https://github.com/apolloeleven/env-analyzer/blob/master/Analyzer.php) on [Composer Scripts](https://getcomposer.org/doc/articles/scripts.md)

Add the following code to the `extra` in projects `composer.json` file

```
"apollo11-parameters": {
            //env-path and env-dist-path for analyzing env files
            "env-path": "/full/path/to/.env",
            "env-dist-path": "/full/path/to/.env.dist",
            //php-env-path and php-env-dist-path for analyzing php files
            "php-env-path": "/full/path/to/env.php",
            "php-env-dist-path": "/full/path/to/env.dist.php"
  },
```

You should also call the analyzer method from composer script. In this example, I call it from `post-install-cmd`, which is triggered after `composer install` is finished. Just add the following code to script in `composer.json` file

```
"post-install-cmd": [
            //Analyzer for env files
            "\\apollo11\\envAnalyzer\\Analyzer::analyzeEnvComposer",
            //Analyzer for php files
            "\\apollo11\\envAnalyzer\\Analyzer::analyzePhpComposer"
 ],
```