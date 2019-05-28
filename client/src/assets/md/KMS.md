#### Windows/Office激活服务（KMS）

*注：本服务和学校正版软件平台的激活服务采用同样的授权形式（批量授权），区别在于通过学校的正版软件平台的激活的软件只能在校园网环境下使用，离开校园网一段时间后需要使用其他方式重新激活，而本服务无此限制，也不需要在电脑上安装第三方客户端。*

激活服务器： `kms.as.xjtuana.com`

使用批量激活服务的前提是你的系统需要是批量授权/大客户版（VL）。一般来说，企业版都是VL版，零售版不能使用批量激活服务，需要使用VL Key先转化为VL版才能使用本服务。

*检查你的系统版本*

```
wmic os get caption
```

如果你的系统不是VL版，那么需要使用管理员权限运行cmd，输入VL Key:

```
slmgr /ipk xxxxx-xxxxx-xxxxx-xxxxx
```

- VL Key可在这里查询到： https://technet.microsoft.com/en-us/library/jj612867.aspx

如果你的Office不是VL版，那么需要先运行任意Office程序，点击文件-账户-更改产品密钥，输入对应的GVLK Key先转化为VL版才能使用本服务。

GVLK Key可在这里查询到：
- Office2016: https://technet.microsoft.com/zh-cn/library/dn385360(v=office.16).aspx
- Office2013: https://technet.microsoft.com/ZH-CN/library/dn385360.aspx
- Office2010: https://technet.microsoft.com/ZH-CN/library/ee624355(v=office.14).aspx

**Windows激活方式**

使用管理员权限运行cmd，执行以下命令

```
slmgr /skms kms.as.xjtuana.com
slmgr /ato
```

激活过程请保持网络连接正常。稍等30秒，出现“成功地激活了产品”字样提示则表示激活成功。

**Office激活方式**

如果通过以上1.1步骤激活Windows之后再安装的Office，则Office会自动激活，无需手动执行以下步骤。

进入Office安装目录，比如`C:\Program Files\Microsoft Office\Office16`，在该目录下以管理员身份打开cmd：

```
cscript ospp.vbs /sethst:kms.as.xjtuana.com
cscript ospp.vbs /act
```

激活过程请保持网络连接正常。稍等30秒，出现“successful”字样提示则表示激活成功。
