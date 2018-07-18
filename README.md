# deployer
一个使用git的简易项目发布工具

## 使用方法
1. 修改`public/index.php`中的`$path`，配置成你的项目目录
2. 找到你的web服务器解析php的用户，比我的是www-data，添加sudoers
```
visudo
```
--------------------------------
...

www-data ALL=(ALL:ALL) NOPASSWD: /usr/bin/git


3. 开发浏览器访问，输入git tag版本号发布。