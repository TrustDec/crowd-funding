<?php

// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)#######

// cvn2加密 1：加密 0:不加密
define("SDK_CVN2_ENC",0);
// 有效期加密 1:加密 0:不加密
define("SDK_DATE_ENC",0);
// 卡号加密 1：加密 0:不加密
define("SDK_PAN_ENC",0);

// 签名证书路径
define("SDK_SIGN_CERT_PATH",APP_ROOT_PATH.'unionpay_web/certs_cs/PM_700000000000001_acp.pfx');
// 签名证书密码
 define("SDK_SIGN_CERT_PWD",'000000');
// 验签证书
define("SDK_VERIFY_CERT_PATH",APP_ROOT_PATH.'unionpay_web/certs_cs/verify_sign_acp.cer');
// 密码加密证书
define("SDK_ENCRYPT_CERT_PATH",APP_ROOT_PATH.'unionpay_web/certs_cs/encrypt.cer');
// 验签证书路径
define("SDK_VERIFY_CERT_DIR",APP_ROOT_PATH.'unionpay_web/certs_cs/');
// 前台请求地址
define("SDK_FRONT_TRANS_URL",'https://101.231.204.80:5000/gateway/api/frontTransReq.do');
// 后台请求地址
define("SDK_BACK_TRANS_URL",'https://101.231.204.80:5000/gateway/api/backTransReq.do');
// 批量交易
define("SDK_BATCH_TRANS_URL",'https://101.231.204.80:5000/gateway/api/batchTrans.do');
//单笔查询请求地址
define("SDK_SINGLE_QUERY_URL",'https://101.231.204.80:5000/gateway/api/queryTrans.do');

//文件传输请求地址
define("SDK_FILE_QUERY_URL",'https://101.231.204.80:9080/');
//有卡交易地址
define("SDK_Card_Request_Url",'https://101.231.204.80:5000/gateway/api/cardTransReq.do');
//App交易地址
define("SDK_App_Request_Url",'https://101.231.204.80:5000/gateway/api/appTransReq.do');

//文件下载目录 
define("SDK_FILE_DOWN_PATH",'d:/file/');

//日志级别
define("SDK_LOG_LEVEL",'INFO');

	
?>