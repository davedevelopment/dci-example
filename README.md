DCI Demo
========

This was just a little experiment, applying the theory behind
[DCI](http://en.wikipedia.org/wiki/Data,_Context,_and_Interaction) to a simple
example use case, following some guidelines on [The Right Way to Code DCI
Ruby](http://mikepackdev.com/blog_posts/24-the-right-way-to-code-dci-in-ruby).

Accompanying Blog Post:
http://davedevelopment.co.uk/2012/11/16/coding-dci-in-php.html

It requires PHP >=5.4 (tested with 5.4.8) and composer to install the
dependencies.

Install
-------

> git clone https://github.com/davedevelopment/dci-example
> cd dci-demo
> composer.phar install --dev
> screen -m -d php -S localhost:10001 web/index.php
> bin/behat


LICENCE
-------

MIT
