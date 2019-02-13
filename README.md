Kotny CMS
==============
Content management system based on Phalcon Framework

![CS PSR2 badge](https://img.shields.io/badge/CS-PSR%202-orange.svg)
![Phalcon badge](https://img.shields.io/badge/Phalcon-v3.x-green.svg)

Requirements
------------
To run this application, you need at least:
- PHP 5.6+
- Phalcon 3.0.0+
- mysql
- apache(+mod_rewrite) / nginx

## Installation

### Composer
```
composer create-project magnxpyr/kotny
```

After some time, do not forget run composer update for update dependencies:
```
composer update
```

### NPM
```
npm install
```

To recompile changes on a theme
```
grunt dev
```


For a new environment create a file in `config` directory named `config-env` and set in `config-default.php` `$environment = env`