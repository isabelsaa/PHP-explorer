## Explore with AST 🔍

In this sub-project I'm experimenting with the php-ast extension, 
which allows me to analyze PHP code by working in its abstract syntax tree (AST).
To get it working properly we need the php-ast:

```
git clone https://github.com/nikic/php-ast.git
cd php-ast
phpize
./configure
make
sudo make install
```
Enable de extension in your php.ini
```extension=ast.so```

Remember you can confirm it checking like this and seeing that the output is "ast":
```php -m | grep ast```