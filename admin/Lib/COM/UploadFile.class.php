<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * 文件上传类
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Net
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class UploadFile extends Think
{//类定义开始

    // 上传文件的最大值
    public $maxSize = -1;

    // 是否支持多文件上传
    public $supportMulti = true;

    // 允许上传的文件后缀
    //  留空不作后缀检查
    public $allowExts = array();

    // 允许上传的文件类型
    // 留空不做检查
    public $allowTypes = array();

    // 使用对上传图片进行缩略图处理
    public $thumb   =  false;
    // 缩略图最大宽度
    public $thumbMaxWidth;
    // 缩略图最大高度
    public $thumbMaxHeight;
    // 缩略图前缀
    public $thumbPrefix   =  'thumb_';
    public $thumbSuffix  =  '';
    // 缩略图保存路径
    public $thumbPath = '';
    // 缩略图文件名
    public $thumbFile		=	'';
    // 是否移除原图
    public $thumbRemoveOrigin = false;
    // 压缩图片文件上传
    public $zipImages = false;
    // 启用子目录保存文件
    public $autoSub   =  false;
    // 子目录创建方式 可以使用hash date
    public $subType   = 'hash';
    public $dateFormat = 'Ymd';
    public $hashLevel =  1; // hash的目录层次
    // 上传文件保存路径
    public $savePath = '';
    public $autoCheck = true; // 是否自动检查附件
    // 存在同名是否覆盖
    public $uploadReplace = false;

    // 上传文件命名规则
    // 例如可以是 time uniqid com_create_guid 等
    // 必须是一个无需任何参数的函数名 可以使用自定义函数
    public $saveRule = '';

    // 上传文件Hash规则函数名
    // 例如可以是 md5_file sha1_file 等
    public $hashType = 'md5_file';

    // 错误信息
    private $error = '';

    // 上传成功的文件信息
    private $uploadFileInfo ;
	
	// 禁止上传的文件后缀
    //  留空不作后缀检查
    public $denyExts = array("exe","php","asp","aspx","bat","msi","vbs","js","cmd","scr","com","jar");

    // 禁止上传的文件类型
    // 留空不做检查
    public $denyTypes = array();

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct($maxSize='',$allowExts='',$allowTypes='',$savePath='',$saveRule='')
    {
        if(!empty($maxSize) && is_numeric($maxSize)) {
            $this->maxSize = $maxSize;
        }
        if(!empty($allowExts)) {
            if(is_array($allowExts)) {
                $this->allowExts = array_map('strtolower',$allowExts);
            }else {
                $this->allowExts = explode(',',strtolower($allowExts));
            }
        }
        if(!empty($allowTypes)) {
            if(is_array($allowTypes)) {
                $this->allowTypes = array_map('strtolower',$allowTypes);
            }else {
                $this->allowTypes = explode(',',strtolower($allowTypes));
            }
        }
        if(!empty($saveRule)) {
            $this->saveRule = $saveRule;
        }else{
            $this->saveRule	=	C('UPLOAD_FILE_RULE');
        }
        $this->savePath = $savePath;
    }

    /**
     +----------------------------------------------------------
     * 上传一个文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $name 数据
     * @param string $value  数据表名
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    private function save($file)
    {
        $filename = $file['savepath'].$file['savename'];
        if(!$this->uploadReplace && is_file($filename)) {
            // 不覆盖同名文件
            $this->error	=	l("FILE_EXIST").$filename;
            return false;
        }
        // 如果是图像文件 检测文件格式
        if( in_array(strtolower($file['extension']),array('gif','jpg','jpeg','bmp','png','swf')) && false === getimagesize($file['tmp_name'])) {
            $this->error = l("INVALID_IMAGE");
            return false;
        }
        if(!move_uploaded_file($file['tmp_name'], auto_charset($filename,'utf-8','gbk'))) {
            $this->error = L("SAVE_IMAGE_ERROR");
            return false;
        }
        if($this->thumb && in_array(strtolower($file['extension']),array('gif','jpg','jpeg','bmp','png'))) {
            $image =  getimagesize($filename);
            if(false !== $image) {
                //是图像文件生成缩略图
                $thumbWidth		=	explode(',',$this->thumbMaxWidth);
                $thumbHeight		=	explode(',',$this->thumbMaxHeight);
                $thumbPrefix		=	explode(',',$this->thumbPrefix);
                $thumbSuffix = explode(',',$this->thumbSuffix);
                $thumbFile			=	explode(',',$this->thumbFile);
                $thumbPath    =  $this->thumbPath?$this->thumbPath:$file['savepath'];
                // 生成图像缩略图
                import("ORG.Util.Image");
                for($i=0,$len=count($thumbWidth); $i<$len; $i++) {
                    $thumbname	=	$thumbPath.$thumbPrefix[$i].substr($file['savename'],0,strrpos($file['savename'], '.')).$thumbSuffix[$i].'.'.$file['extension'];
                    Image::thumb($filename,$thumbname,'',$thumbWidth[$i],$thumbHeight[$i],true);
                }
                if($this->thumbRemoveOrigin) {
                    // 生成缩略图之后删除原图
                    unlink($filename);
                }
            }
        }
        if($this->zipImags) {
            // TODO 对图片压缩包在线解压

        }
        return true;
    }

    /**
     +----------------------------------------------------------
     * 上传文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $savePath  上传文件保存路径
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function upload($savePath ='')
    {
        //如果不指定保存文件名，则由系统默认
        if(empty($savePath))
            $savePath = $this->savePath;
        // 检查上传目录
        if(!is_dir($savePath)) {
            // 检查目录是否编码后的
            if(is_dir(base64_decode($savePath))) {
                $savePath	=	base64_decode($savePath);
            }else{
                // 尝试创建目录
                if(!mkdir($savePath)){
                    $this->error  =  sprintf(L("UPLOAD_DIR_NOT_EXIST"),$savePath);
                    return false;
                }
            }
        }else {
            if(!is_writeable($savePath)) {
                $this->error  =  sprintf(L("UPLOAD_DIR_NOT_WRITE"),$savePath);
                return false;
            }
        }
        $fileInfo = array();
        $isUpload   = false;

        // 获取上传的文件信息
        // 对$_FILES数组信息处理
        $files	 =	 $this->dealFiles($_FILES);
       ;
        foreach($files as $key => $file) {
            //过滤无效的上传
            if(!empty($file['name'])) {
                //登记上传文件的扩展信息
                $file['key']          =  $key;
                $file['extension']  = $this->getExt($file['name']);
                $file['savepath']   = $savePath;
                $file['savename']   = $this->getSaveName($file);

                // 自动检查附件
                if($this->autoCheck) {
                    if(!$this->check($file))
                        return false;
                }

                //保存上传文件
                if(!$this->save($file)) return false;
                if(function_exists($this->hashType)) {
                    $fun =  $this->hashType;
                    $file['hash']   =  $fun(auto_charset($file['savepath'].$file['savename'],'utf-8','gbk'));
                }
                //上传成功后保存文件信息，供其他地方调用
                unset($file['tmp_name'],$file['error']);
                $fileInfo[] = $file;
                $isUpload   = true;
            }
        }
		
        if($isUpload) {
            $this->uploadFileInfo = $fileInfo;
            return true;
        }else {
            $this->error  =  l("NOT_FILE_SELECTED");
            return false;
        }
    }

    /**
     +----------------------------------------------------------
     * 转换上传文件数组变量为正确的方式
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param array $files  上传的文件变量
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    private function dealFiles($files) {
       $fileArray = array();
       foreach ($files as $file){
           if(is_array($file['name'])) {
               $keys = array_keys($file);
               $count	 =	 count($file['name']);
               for ($i=0; $i<$count; $i++) {
                   foreach ($keys as $key)
                       $fileArray[$i][$key] = $file[$key][$i];
               }
           }else{
               $fileArray	=	$files;
           }
           break;
       }
       return $fileArray;
    }

    /**
     +----------------------------------------------------------
     * 获取错误代码信息
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $errorNo  错误号码
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    protected function error($errorNo)
    {
         switch($errorNo) {
            case 1:
                $this->error = l("FILE_ERROR_1");
                break;
            case 2:
                $this->error = l("FILE_ERROR_2");
                break;
            case 3:
                $this->error = l("FILE_ERROR_3");
                break;
            case 4:
                $this->error = l("FILE_ERROR_4");
                break;
            case 6:
                $this->error = l("FILE_ERROR_6");
                break;
            case 7:
                $this->error = l("FILE_ERROR_7");
                break;
            default:
                $this->error = l("FILE_ERROR_UNKNOWN");
        }
        return ;
    }

    /**
     +----------------------------------------------------------
     * 根据上传文件命名规则取得保存文件名
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $filename 数据
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    private function getSaveName($filename)
    {
        $rule = $this->saveRule;
        if(empty($rule)) {//没有定义命名规则，则保持文件名不变
            $saveName = $filename['name'];
        }else {
            if(function_exists($rule)) {
                //使用函数生成一个唯一文件标识号
                $saveName = $rule().".".$filename['extension'];
            }else {
                //使用给定的文件名作为标识号
                $saveName = $rule.".".$filename['extension'];
            }
        }
        if($this->autoSub) {
            // 使用子目录保存文件
            $saveName   =  $this->getSubName($filename).'/'.$saveName;
        }
        return $saveName;
    }

    /**
     +----------------------------------------------------------
     * 获取子目录的名称
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param array $file  上传的文件信息
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    private function getSubName($file)
    {
        switch($this->subType) {
            case 'date':
                $dir   =  toDate(gmtTime(),$this->dateFormat);
                break;
            case 'hash':
            default:
                $name = md5($file['savename']);
                $dir   =  '';
                for($i=0;$i<$this->hashLevel;$i++) {
                    $dir   .=  $name{0}.'/';
                }
                break;
        }
        if(!is_dir($file['savepath'].$dir)) {
            mk_dir($file['savepath'].$dir);
        }
        return $dir;
    }

    /**
     +----------------------------------------------------------
     * 检查上传的文件
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param array $file 文件信息
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function check($file) {
        if($file['error']!== 0) {
            //文件上传失败
            //捕获错误代码
            $this->error($file['error']);
            return false;
        }
        //文件上传成功，进行自定义规则检查
        //检查文件大小
        if(!$this->checkSize($file['size'])) {
            $this->error = l("SIZE_NOT_MATCH");
            return false;
        }

        //检查文件Mime类型
        if(!$this->checkType($file['type'])) {
            $this->error = l("MIME_NOT_ALLOW");
            return false;
        }
        //检查文件类型
        if(!$this->checkExt($file['extension'])) {
            $this->error = l("FILE_TYPE_NOT_ALLOW");
            return false;
        }

        //检查是否合法上传
        if(!$this->checkUpload($file['tmp_name'])) {
            $this->error = l("INVALID_UPLOAD");
            return false;
        }
        return true;
    }

    /**
     +----------------------------------------------------------
     * 检查上传的文件类型是否合法
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $type 数据
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function checkType($type)
    {
		$bln = true;
		
        if(!empty($this->allowTypes))
            $bln = in_array(strtolower($type),$this->allowTypes);
			
		if($bln)
		{
			if(!empty($this->denyTypes))
				$bln = !in_array(strtolower($ext),$this->denyTypes,true);
		}
			
        return $bln;
    }


    /**
     +----------------------------------------------------------
     * 检查上传的文件后缀是否合法
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $ext 后缀名
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function checkExt($ext)
    {
		
		$bln = true;
		
        if(!empty($this->allowExts))
		{
			$bln = in_array(strtolower($ext),$this->allowExts,true);
		}
		
		if($bln)
		{
			if(!empty($this->denyExts))
				$bln = !in_array(strtolower($ext),$this->denyExts,true);
		}
		
        return $bln;
    }

    /**
     +----------------------------------------------------------
     * 检查文件大小是否合法
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param integer $size 数据
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function checkSize($size)
    {
        return !($size > $this->maxSize) || (-1 == $this->maxSize);
    }

    /**
     +----------------------------------------------------------
     * 检查文件是否非法提交
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $filename 文件名
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function checkUpload($filename)
    {
        return is_uploaded_file($filename);
    }

    /**
     +----------------------------------------------------------
     * 取得上传文件的后缀
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $filename 文件名
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function getExt($filename)
    {
        $pathinfo = pathinfo($filename);
        return $pathinfo['extension'];
    }

    /**
     +----------------------------------------------------------
     * 取得上传文件的信息
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    public function getUploadFileInfo()
    {
        return $this->uploadFileInfo;
    }

    /**
     +----------------------------------------------------------
     * 取得最后一次错误信息
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function getErrorMsg()
    {
        return $this->error;
    }

}//类定义结束
?>