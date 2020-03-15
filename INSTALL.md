# 系统环境要求
- nginx
- mysql ^8.0
- php ^7.3
- Node
- NPM

# 系统安装操作
### 从分支导入代码
> git clone -b backend git@github.com:z1px/laravel6.git 
### 修改文件用户组和权限
> chown -R www: * laravel6
### 切换到系统根目录下
> cd laravel6
### 增加配置文件，将系统根目录下.env.example文件复制命名为.env，修改数据库配置和其它配置
> cp .env.example .env
### 安装插件
> composer install
### 生成应用密钥
> php artisan key:generate
### 创建数据库
> php artisan migrate:create
### 数据库迁移并填充数据
> php artisan migrate --seed
### 安装项目前端依赖
> npm install
### 编译前端资源
> npm run production


# 系统优化
### 自动加载器改进
> composer install --optimize-autoloader --no-dev
### 优化配置加载
> php artisan config:cache
### 优化路由加载
> php artisan route:cache


# 开发新功能步骤（后台接口）
### 添加数据迁移数据库
### 添加数据库模型
### 添加服务层
### 添加控制层
### 添加路由

# 开发新功能步骤（前台页面）
### 添加api接口
### 添加lang多语言
### 添加router菜单路由
### 添加view视图层模版
