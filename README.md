# deployer
一个使用git的简易项目发布工具

## 使用方法
1. 修改`public/index.php`中的`$path`，配置成你的项目目录

2. 添加php脚本的执行用户sudoers中

比如你使用nginx+php-fpm的服务器架构，你的php-fpm的用户是www-data
```sh
visudo
--------------------------------
...
#Defaults   !visiblepw   #注释掉这句 这句是限制sudo只能在命令行执行的
www-data ALL=(ALL:ALL) NOPASSWD: /usr/bin/git
```


3. 开发浏览器访问，输入git tag版本号发布。