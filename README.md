### weixin-mini-program是一个使用简单、功能丰富的微信小程序开发sdk。

#### 包含功能：

1. 小程序登录

2. 微信加密数据解密

3. 获取accessToken

4. 生成小程序码

5. 发送订阅消息

6. 支付统一下单

7. 支付回调

8. 支付查询

9. 退款

10. 退款回调

11. 退款查询

#### 目录及文件介绍
1. src为源码目录，tests目录为测试脚本目录。该sdk中所封装的所有功能均有对应的测试脚本。

2. src/Common/Api封装了功能1、3、4、5，src/Common/Tools封装了功能2，src/Pay/Client封装了功能6、8、9、11，src/Pay/Notify封装了功能7、10

3. 文件ConfigInterface.php为配置接口类，使用该sdk必须实现该配置类。

4. 文件LogInterface.php为日志接口类，实现该接口可以记录支付接口日志。建议存储采用MongoDB。

5. NotifyHandleInterface.php为通知处理接口类，使用该sdk必须实现该接口类。其作用是根据业务具体情况，处理回调逻辑。

6. WxException.php为微信操作异常类。

#### 安装及使用
##### 安装
composer require taofeiyang/weixin-mini-program
##### 使用
tests目录中有完整的测试脚本，这些脚本可以作为demo直接参考使用，所有测试脚本均通过phpunit测试通过。
