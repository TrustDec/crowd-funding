<div class="selectbox1" id="selectbox1">
    <div class="selectbox" id="selectbox"></div>
    <div class="selectbj" id="selectbj">
        <div class="tav_nav webkit-box" id="top_search_hd">
            <?php if (app_conf ( "INVEST_STATUS" ) == 0): ?> 
            <a href="#" livalue="0" class="search_cate search_cate_l webkit-box-flex cur" checked="checked">回报众筹</a>
            <a href="#" livalue="1" class="search_cate search_cate_r webkit-box-flex"><?php echo $this->_var['gq_name']; ?></a>
            <?php endif; ?>
            <?php if (app_conf ( "INVEST_STATUS" ) == 1): ?>
            <a href="#" livalue="0" class="search_cate search_cate_all webkit-box-flex cur" checked="checked">回报众筹</a>
            <?php endif; ?>
            <?php if (app_conf ( "INVEST_STATUS" ) == 2): ?>
            <a href="#" livalue="1" class="search_cate search_cate_all webkit-box-flex cur" checked="checked"><?php echo $this->_var['gq_name']; ?></a>
            <?php endif; ?>
        </div>
        <div class="searchbox">
            <form action="<?php
echo parse_url_tag_wap("u:deals|"."".""); 
?>" method="post">
                <div class="search">
                    <div class="seach_text">
                        <input type="text" name="k" placeholder="请输入关键字搜索">
                    </div>
                    <div class="blank"></div>
                    <div class="seach_submit pr">
                        <i class="fa fa-search"></i>
                        <input type="submit" value="搜索" class="ps" style="opacity:0;width:100%;height:100%;left:0;">
                        <?php if (app_conf ( "INVEST_STATUS" ) == 0): ?> 
                            <input type="hidden" name="type" value="0"/>                
                            <input type="hidden" name="redirect" value="1"/>        
                        <?php endif; ?>
                        <?php if (app_conf ( "INVEST_STATUS" ) == 1): ?>
                            <input type="hidden" name="type" value="0"/>        
                        <?php endif; ?>
                        <?php if (app_conf ( "INVEST_STATUS" ) == 2): ?>
                            <input type="hidden" name="type" value="1"/>    
                        <?php endif; ?>      
                    </div>
                </div>
            </form>
        </div>
        <div id="top_search_bd">
            <ul>
                <?php if (app_conf ( 'INVEST_STATUS' ) == 0): ?>
                <li rel="0">
<!--                     <dl>
                        <dt>众筹类别</dt>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."".""); 
?>">全部回报众筹</a>
                        </dd>
                    </dl>
                    <p class="cl"></p> -->
                    <dl>
                        <dt>属性</dt>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=rec".""); 
?>">推荐项目</a>
                        </dd>
                        <dd class="c24">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=yure".""); 
?>">正在预热</a>
                        </dd>
                        <dd class="c23">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=new".""); 
?>">最新上线</a>
                        </dd>
                    </dl>
                    <p class="cl"></p>
                    <dl>
                        <dt>分类</dt>
                        <?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'cate');$this->_foreach['cate'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['cate']):
        $this->_foreach['cate']['iteration']++;
?>
                        <?php if ($this->_var['cate']['pid'] == 0): ?>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."id=".$this->_var['cate']['id']."".""); 
?>"  class="<?php if ($this->_var['k'] % 3 == 1): ?>c24<?php endif; ?> <?php if ($this->_var['k'] % 3 == 2): ?>c23<?php endif; ?>" ><?php echo $this->_var['cate']['name']; ?></a>
                        </dd>
                        <?php endif; ?>
                         <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </dl>
                </li>
                <li rel="1" style="display:none">
<!--                     <dl>
                        <dt>众筹类别</dt>
                        <dd class="c24">
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."type=1".""); 
?>">全部股权众筹</a>
                        </dd>
                    </dl>
                    <p class="cl"></p> -->
                    <dl>
                        <dt>属性</dt>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=rec&type=1".""); 
?>">推荐项目</a>
                        </dd>
                        <dd class="c24">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=yure&type=1".""); 
?>">正在预热</a>
                        </dd>
                        <dd class="c23">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=new&type=1".""); 
?>">最新上线</a>
                        </dd>
                    </dl>
                    <p class="cl"></p>
                    <dl>
                        <dt>分类</dt>
                        <?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'cate');$this->_foreach['cate'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['cate']):
        $this->_foreach['cate']['iteration']++;
?>
                        <?php if ($this->_var['cate']['pid'] == 0): ?>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."id=".$this->_var['cate']['id']."&type=1".""); 
?>"  class="<?php if ($this->_var['k'] % 3 == 1): ?>c24<?php endif; ?> <?php if ($this->_var['k'] % 3 == 2): ?>c23<?php endif; ?>" ><?php echo $this->_var['cate']['name']; ?></a>
                        </dd>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </dl>
                </li>
                <?php endif; ?>
                <?php if (app_conf ( 'INVEST_STATUS' ) == 1): ?>
                 <li rel="0">
<!--                     <dl>
                        <dt>众筹类别</dt>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."".""); 
?>">全部回报众筹</a>
                        </dd>
                    </dl>
                    <p class="cl"></p> -->
                    <dl>
                        <dt>属性</dt>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=rec".""); 
?>">推荐项目</a>
                        </dd>
                        <dd class="c24">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=yure".""); 
?>">正在预热</a>
                        </dd>
                        <dd class="c23">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=new".""); 
?>">最新上线</a>
                        </dd>
                    </dl>
                    <p class="cl"></p>
                    <dl>
                        <dt>分类</dt>
                        <?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'cate');$this->_foreach['cate'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['cate']):
        $this->_foreach['cate']['iteration']++;
?>
                        <?php if ($this->_var['cate']['pid'] == 0): ?>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."id=".$this->_var['cate']['id']."".""); 
?>"  class="<?php if ($this->_var['k'] % 3 == 1): ?>c24<?php endif; ?> <?php if ($this->_var['k'] % 3 == 2): ?>c23<?php endif; ?>" ><?php echo $this->_var['cate']['name']; ?></a>
                        </dd>
                        <?php endif; ?>
                         <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </dl>
                </li>
                <?php endif; ?>
                <?php if (app_conf ( 'INVEST_STATUS' ) == 2): ?>
                <li rel="1">
<!--                     <dl>
                        <dt>众筹类别</dt>
                        <dd class="c24">
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."type=1".""); 
?>">全部股权众筹</a>
                        </dd>
                    </dl>
                    <p class="cl"></p> -->
                    <dl>
                        <dt>属性</dt>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=rec&type=1".""); 
?>">推荐项目</a>
                        </dd>
                        <dd class="c24">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=yure&type=1".""); 
?>">正在预热</a>
                        </dd>
                        <dd class="c23">
                            <a href="<?php
echo parse_url_tag_wap("u:deals|"."r=new&type=1".""); 
?>">最新上线</a>
                        </dd>
                    </dl>
                    <p class="cl"></p>
                    <dl>
                        <dt>分类</dt>
                        <?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'cate');$this->_foreach['cate'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['cate']):
        $this->_foreach['cate']['iteration']++;
?>
                        <?php if ($this->_var['cate']['pid'] == 0): ?>
                        <dd>
                            <a href="<?php
echo parse_url_tag_wap("u:deals#index|"."id=".$this->_var['cate']['id']."&type=1".""); 
?>"  class="<?php if ($this->_var['k'] % 3 == 1): ?>c24<?php endif; ?> <?php if ($this->_var['k'] % 3 == 2): ?>c23<?php endif; ?>" ><?php echo $this->_var['cate']['name']; ?></a>
                        </dd>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </dl>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="blank"></div>
</div>
<script type="text/javascript">
    $("#top_search_hd .search_cate").bind('click',function(){
        var $obj=$(this);
        var i=$obj.index();
        $obj.attr("checked",true).addClass("cur").siblings().attr("checked",false).removeClass("cur");
        $obj.parent().parent().find("input[name='type']").val($(this).attr("livalue"));
        $("#top_search_bd li").eq(i).show().siblings().hide();
    });
</script>