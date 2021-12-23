# twister-stat  ⠀
Statistic tools for twister network  ⠀

## Blockchain crawler  ⠀

Script written in python v2, that scans the blockchain and dump the data to the MySQL database.  ⠀

Index contain:  ⠀

- block number  ⠀
- block hash  ⠀
- time created  ⠀
- usernames  ⠀

### Configuration  ⠀

line 5 - MySQL database  ⠀

line 13 - blocks per step  ⠀

line 31 - twister API  ⠀

### Requirements  ⠀

`apt install python2 python2-dev php-mysql mysql-server`  ⠀

`pip2 install mysqlclient`  ⠀

###  Running  ⠀

`python2 crawler/blockchain.py`  ⠀

## Charts  ⠀

Tools for the data dumped visualization written in PHP  ⠀

![demo](https://raw.githubusercontent.com/twisterarmy/twister-stat/main/media/demo.png)

### Configuration  ⠀

line 8 - MySQL database  ⠀

### Requirements  ⠀

`apt install php-fpm php-curl php-mysql mysql-server`  ⠀

### Running  ⠀

create new server instance  ⠀

`cd twister-stat`  ⠀

`php -S localhost:8081`  ⠀

open in browser  ⠀

`http://localhost:8081/index.php`  ⠀

todo:  ⠀

## Static dumps directory  ⠀

https://github.com/twisterarmy/twister-stat/tree/main/dump