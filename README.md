SF2C3
=====

Repository
----------

    https://bitbucket.org/hhamon/wakeonweb

Install
-------

    $ cd /var/www
    $ git clone https://bitbucket.org/hhamon/wakeonweb.git training
    $ cd training
    $ php composer.phar install
    $ php app/console d:d:c
    $ php app/console d:s:u --force
    $ php app/console s:r
