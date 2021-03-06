<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$plang['scriptlang'] = array(
	'shopmodel_list' => '商家模型列表',
	'shopmodelname' => '模型标识',
	'shopmodeltitle' => '模型名称',
	'add_shopmodel' => '新建模型',
	'shopsort_select' => '选择分类',
	'shopsort_top' => '顶级分类',
	'quanguocity' => '全国',
	'shoptemp_default' => '默认',
	'shoptemp_qianhuang' => '浅黄风格',
	'shoptemp' => '商家联盟模板',
	'goods_caiji_ing' => '正在采集的页面是：',
	'goods_caiji_page' => '正在采集的页数为：',
	'lashou_goods_caiji' => '采集',
	'dealtime' => '过期时间',
	'newprice' => '折扣价',
	'edit_goods_tips' => '对商品进行编辑',
	'goods_option' => '商品选项',
	'goodsstatus' => '是否上架',
	'goodsstatus_comment' => '是否上架',
	'shopid' => '商家编号',
	'shopid_comment' => '所属商家对应的商家ID，请查询商家信息',
	'goodstitle' => '商品标题',
	'goodstitle_comment' => '商品的标题，将在前台加重显示',
	'youxiaotime_comment' => '选择商品上架的到期日期',
	'youxiaotime' => '到期时间',
	'goodssort_comment' => '选择商品的分类',
	'goodssort' => '商品分类',
	'goodsimages_comment' => '选择商品的封面图片',
	'newprice_comment' => '填写商品的折扣价格，单位：元',
	'price_comment' => '填写商品的零售价，单位：元',
	'goodsimages' => '商品封面图片',
	'add_new_goods' => '增加商品',
	'edit_goods_succeed' => '商品编辑成功',
	'edit_goodscaiji_tips' => '手动审核采集信息',
	'goodscaijistatus' => '是否直接上架',
	'goodscaijistatus_comment' => '选择是，将直接上架',
	'goodscaijiname_comment' => '原商品的标题，可以修改标题',
	'goodscaijiimages_comment' => '原商品的封面图片',
	'goodsjoin' => '是否加入商品库',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'shopgroup_edit_tips' => '<li>对商家组进行设置，当该商家组已经有了商家之后，尽量不要再次修改，以免数据出错</li><li>初始余额是因为商家支付了入住费用，所以尽量初始余额不要高于入住费用，除非在促销期</li><li>初始积分建议也不要高于商家的入住费用</li><li>商家组简介的内容将在前台页面显示，故可以使用html代码</li><li>店长、财务、收银员默认都具有刷卡消费的权限，当什么也不选择的时候，则标识该商家组不能添加店员</li>',
	'shopgroup_edit' => '编辑商家组',
	'shopgroup_list_tips' => '<li>可以对商家根据是否收费、是否可以发放会员卡等等进行分类设置</li><li>当商家组下已经有商家时，请不要删除商家组和改变商家组的开启状态</li>',
	'shopgroup_list' => '商家组',
	'shopgroupname' => '商家组名称',
	'shopnum' => '商家数量',
	'shopgroupquanxian' => '权限',
	'status' => '状态',
	'add_shopgroup' => '增加新的商家组',
	'shopgroup_edit' => '编辑商家组',
	'shopgroup_edit_tips' => '<li>对商家组进行设置，当该商家组已经有了商家之后，尽量不要再次修改，以免数据出错</li><li>初始余额是因为商家支付了入住费用，所以尽量初始余额不要高于入住费用，除非在促销期</li><li>初始积分建议也不要高于商家的入住费用</li><li>商家组简介的内容将在前台页面显示，故可以使用html代码</li><li>店长、财务、收银员默认都具有刷卡消费的权限，当什么也不选择的时候，则标识该商家组不能添加店员</li>',
	'inshoufei' => '入住收费',
	'inshoufei_comment' => '成为一卡通的会员商家，收取的费用数，单位为转账用积分积分数',
	'inshoufeiqixian' => '收费周期',
	'inshoufeiqixian_comment' => '可以是每天、每月等，填写的数字要求单位为天，30天为月；365天为年',
	'shopgroupdescription' => '商家组简介',
	'shopgroupdescription_comment' => '用于向商家展示的简短说明',
	'shopgroupico' => '商家组图标',
	'shopgroupico_comment' => '大小为120X90 px，格式为jpg、png，用于商家组的介绍页面的一个图标显示',
	'cardfeiyong' => '会员卡激活费用',
	'cardfeiyong_comment' => '本组商家派送出的会员卡，会员在激活的时候所需的费用，单位：元（不能有小数）',
	'cardpice' => '售价',
	'cardpice_comment' => '允许商家出售会员卡的最高价钱，当为0时，则不允许出售',
	'shopgroup_edit_succeed' => '商家组编辑成功',
	'shopgroupname_nonull' => '商家组名称不能为空',
	'status_comment' => '该商家组是否启用',
	'shopgroupname_comment' => '必填，可以是汉字，如：普通组、VIP商家组、黄金商家...',
	'edit' => '编辑',
	'shopgroup' => '商家组',
	'enxiaofeitype' => '可以使用的结算消费类型',
	'enshopnum' => '允许创建店铺的数量',
	'enshopnum_comment' => '允许创建店铺的数量，当不允许创建分店时，数量是创建地理的店铺的数量，当允许创建分店的时候，则为总点与分店的总数之和的数量。如果不填写则默认为1',
	'enfendian' => '是否允许创建分店',
	'enfendian_comment' => '是否允许创建分店',
	'dianzhang_comment' => '店长权限',
	'dianzhang' => '店长权限',
	'caiwu_comment' => '财务权限',
	'caiwu' => '财务权限',
	'shouyin_comment' => '收银员权限',
	'shouyin' => '收银员权限',
	'dianyuan_kaika' => '开卡',
	'dianyuan_buka' => '补卡',
	'dianyuan_zhuxiaoka' => '注销卡',
	'dianyuan_kachongzhi' => '卡充值',
	'dianyuan_jifenzengsong' => '积分赠送',
	'dianyuan_goodssetting' => '商品设置',
	'dianyuan_viewmember' => '查看会员',
	'dianyuan_viewxiaofei' => '查看消费记录',
	'zhanghaoyue' => '初始余额',
	'zhanghaoyue_comment' => '初始余额',
	'zhanghaojifen' => '初始积分',
	'zhanghaojifen_comment' => '初始积分',
	'dianyuanshenhe' => '修改店员信息时是否审核',
	'dianyuanshenhe_comment' => '新建或者修改店员信息时，是否需要审核',
	'xiaofeitypeshenhe' => '修改消费类型时是否需要审核',
	'xiaofeitypeshenhe_comment' => '修改消费类型时是否需要审核',
	'contractsample_comment' => '请上传合同样本，以便商家在入驻的时候下载，格式为word的doc格式',
	'contractsample' => '合同样本',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//一卡通新的商家管理
	'shop_list_tips' => '<li>商家审核与管理</li><li>需：所需资金；余：账户余额；积：账户积分；个：个人照片；身：身份证照；合：合同照片；</li>',
	'shop_list' => '商家列表',
	'shopname' => '商家名称',
	'relname' => '姓名',
	'caiwuinfo' => '财务信息',
	'man' => '男',
	'woman' => '女',
	'shopgroup' => '商家组',
	'shopinfo' => '商家信息',
	'sxzjs' => '需',
	'zhyes' => '余',
	'zhjfs' => '积',
	'photo' => '图片',
	'gerenphotos' => '个',
	'zanwu' => '无',
	'yishangchuan' => '有',
	'shenfenphotos' => '身',
	'contractimages' => '合',
	'shopinfo' => '店铺信息',
	'goodsinfo' => '商品信息',
	'cardinfo' => '卡信息',
	'yuebuzu' => '余额不足',
	'ziliaobuquan' => '资料不全',
	'daishen' => '待审核',
	'tongguo' => '已通过',
	'add_shop' => '新增商家',
	'shop_edit_tips' => '<li>上部分为商家的基本信息，请仔细查看，并且管理员有最高权限，可以修改任意项</li><li>下部分为商家的扩展选项</li>',
	'shop_edit' => '商家选项',
	'shopname_comment' => '商家名称',
	'uid_comment' => '商家所对应的用户 ID',
	'sex' => '性别',
	'phone' => '电话',
	'address' => '地址',
	'gerenphoto' => '个人近期照片',
	'shenfenno' => '身份证号',
	'shenfenphoto' => '身份证照片',
	'shopsummary' => '商家简介',
	'contractimage' => '合同影印件',
	'baomi' => '保密',
	'shop_edit_succeed' => '商家信息编辑成功',
	'shop_shenhe' => '商家审核项',
	'shop_status' => '商家审核',
	'shop_status_comment' => '审核通过该商家',
	'shop_fields_error' => '商家管理所需的基本设置项不全，请先进行设置',
	'shop_spnum' => '审',
	'shop_weinum' => '未',
	'shop_zongnum' => '总',
	'shop_base_info' => '商家基本信息',
	'shopuid' => '所属用户',
	'yikatong_shopname_comment' => '店铺名称的修改请到商家联盟中进行',
	'upshop' => '上级商铺',
	'fendian' => '分店',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'system_setting' => '系统设置',
	'system_mokuai' => '模块管理',
	'edit' => '编辑',
	'view' => '查看',
	'status' => '状态',
	'mokuai_setting' => '设置',
	'import' => '导入',
	'export' => '导出',
	'select' => '请选择',
	'all' => '全部',
	'renling_no' => '未认领',
	'renling_yes' => '已认领',
	'shaixuan_no' => '未筛选',
	'shaixuan_yes' => '已筛选',
	'chanpin' => '产品',
	'dianping' => '点评',
	'' => '',
	'' => '',
	'' => '',
	//系统首页
	'base_index_mingxie' => '特别鸣谢',
	'base_index_system' => '系统简介',
	'base_index_status' => '当前状态',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//插件注册
	'reg_edit_tips' => '本插件需要注册',
	'reg_edit' => '注册选项',
	'realname' => '真实姓名',
	'realname_comment' => '请填写站长的真实姓名',
	'phone_comment' => '请填写您的有效的联系电话，手机、固话均可',
	'address_comment' => '请填写您的联系地址',
	'regdistrict' => '所在地区',
	'regdistrict_comment' => '请填写您所在的地区',
	'jianyi_comment' => '在这里填写您对插件的建议，字数为100字之内',
	'jianyi' => '建议',
	'' => '',
	'' => '',
	'' => '',
	//基础设置
	'base_setting_tips' => '基础设置',
	'base_setting' => '设置选项',
	'temp_default' => '默认模板',
	'temp_juhuang' => '清新橘黄',
	'thistemplate' => '使用的模板',
	'thistemplate_comment' => '选择使用的模板',
	'base_setting_succeed' => '基础设置成功',
	'' => '',
	'' => '',
	'' => '',
	//基础设置之模块
	'mokuai_list_tips' => '所安装的模块管理',
	'yes_mokuai_list' => '已安装模块',
	'mokuaititle' => '模块名称',
	'upmokuai' => '所属模块',
	'mokuaiver' => '版本',
	'mokuaipice' => '价格',
	'no_mokuai_list' => '未安装模块列表',
	'mokuai_install' => '安装',
	'mokuai_uninstall' => '卸载',
	'mokuai_open' => '启用',
	'mokuai_close' => '关闭',
	'mokuai_edit_succeed' => '模块编辑成功',
	'mokuai_setting_error' => '模块设置文件有误',
	'nav_menu' => '导航菜单',
	'nav_menu_comment' => '是否在导航菜单中显示',
	'top_menu' => '顶部菜单',
	'top_menu_comment' => '是否在顶部菜单显示',
	'mokuai_setting_succeed' => '模块设置成功',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//会员管理前台
	'member_manage' => '会员中心',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//商家基础设置
	'jiaoyijifen' => '交易积分',
	'zengsongjifen' => '赠送积分',
	'jifenyouxiaoqi' => '积分有效期',
	'yikatong_setting_tips' => '一卡通基础设置',
	'yikatong_setting' => '设置选项',
	'shopguanliyuan' => '商家管理员',
	'dianzhu' => '店主',
	'dianyuan' => '店员',
	'shopguanliyuan_comment' => '设置是否开启店主和店员',
	'jiaoyijifen_comment' => '要与diacuz自带的交易积分一致',
	'zengsongjifen_comment' => '消费者在店铺进行消费时，店铺赠送的积分',
	'jifenyouxiaoqi_comment' => '消费者得到的积分，在什么时间自动清零',
	'botton1' => '首页按钮图片1',
	'botton1_comment' => '首页右上角的按钮图片，如果觉得默认的图片不美观，可以在这里上传自己设计的图片，尺寸：宽度为145px、高度为30px，链接是：',
	'botton2' => '首页按钮图片2',
	'botton2_comment' => '首页右上角的按钮图片，如果觉得默认的图片不美观，可以在这里上传自己设计的图片，尺寸：宽度为145px、高度为30px，链接是：',
	'botton3' => '首页按钮图片3',
	'botton3_comment' => '首页右上角的按钮图片，如果觉得默认的图片不美观，可以在这里上传自己设计的图片，尺寸：宽度为145px、高度为30px，链接是：',
	'botton4' => '首页按钮图片4',
	'botton4_comment' => '首页右上角的按钮图片，如果觉得默认的图片不美观，可以在这里上传自己设计的图片，尺寸：宽度为145px、高度为30px，链接是：',
	'brand_setting_tips' => '联盟商家基础设置',
	'isbusiness' => '是否必须输入补充资料',
	'isbusiness_comment' => '是否必须输入补充资料',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//商家管理
	'shop_edit_tips' => '编辑商家基本信息',
	'shop_edit' => '商家（店铺）选项',
	'shopname' => '商家（店铺）名称',
	'shortshopname' => '商家',
	'shopname_comment' => '填写商家（店铺）的全称',
	'brand_shop_list_tips' => '商家列表',
	'brand_shop_list' => '商家列表',
	'add_shop' => '添加新的商家（店铺）',
	'shopsort' => '商家分类',
	'shopsort_comment' => '选择商家分类',
	'shopaddress' => '地址',
	'shopaddress_comment' => '地址',
	'shopphone' => '电话',
	'shopphone_comment' => '电话',
	'shoplianxiren' => '联系人',
	'shoplianxiren_comment' => '联系人',
	'shop_edit_succeed' => '商家（店铺）编辑成功',
	'shopdescription' => '商家（店铺）简介',
	'pass' => '通过',
	'nopass' => '不通过',
	'shoplogo' => '商家LOGO',
	'shoplogo_comment' => '请上传商家的LOGO图片',
	'shopdistrict' => '所在地区',
	'shopdistrict_comment' => '请选择所在的地区',
	'shopsort' => '分类',
	'shopalias' => '别名',
	'shopvideo' => '视频地址',
	'shopvideo_comment' => '如果有视频地址，请填写视频的地址',
	'shopintroduction' => '商家的简单介绍',
	'shopintroduction_comment' => '简单介绍，100字以内',
	'shoplocation' => '地理位置',
	'shopinformation' => '详细介绍',
	'shoprecommend' => '推荐度',
	'shoprecommend_comment' => '推荐度介绍',
	'shoptemplate' => '使用模版',
	'shoptemplate_comment' => '选择使用模版',
	'shoplevel' => '等级',
	'shoplevel_comment' => '选择等级',
	'shopalias_comment' => '商家的别名',
	'shopstatus_comment' => '审核通过',
	'shenhe' => '审核',
	'shenheed' => '已审核',
	'noshenhe' => '未审核',
	'renling' => '认领',
	'renlinged' => '已认领',
	'norenling' => '未认领',
	'shoptime' => '时间相关',
	'create' => '创建',
	'uid_error' => '用户UID不能为空',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//公告管理
	'gonggaoname' => '公告标题',
	'add_gonggao' => '增加公告',
	'gonggao_list_tips' => '公告管理',
	'gonggao_list' => '公告列表',
	'gonggao_edit_tips' => '编辑公告',
	'gonggao_edit' => '公告选项',
	'gonggaoname_comment' => '公告的标题，不要超过20个字',
	'youxiaoqi_comment' => '显示有效期',
	'gonggaotext' => '公告内容',
	'gonggao_edit_succeed' => '首页公告设置成功',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//商家分类
	'shopsort_list_tips' => '商家分类',
	'sort_list' => '分类列表',
	'sortname' => '分类标识',
	'sortname_comment' => '必须是英文，用于标识',
	'sorttitle' => '分类名称',
	'sorttitle_comment' => '中英文均可，用于显示',
	'add_sort' => '新增商家分类',
	'sort_edit_tips' => '商家分类编辑',
	'sort_edit' => '商家分类选项',
	'sortupid' => '上级分类',
	'displayorder' => '排序',
	'sort_edit_succeed' => '商家分类编辑成功',
	'mokuai' => '',
	'shopsort_list' => '分类列表',
	'shopsortname' => '分类标识',
	'shopsorttitle' => '分类名称',
	'add_shopsort' => '增加分类',
	'shopsort_edit_tips' => '编辑分类',
	'shopsort_edit' => '分类选项',
	'sortupid_comment' => '请选择上级分类',
	'shopsortname_comment' => '必须为英文',
	'shopsorttitle_comment' => '分类的名称，可以是中文',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',

	//产品管理
	'add_goods' => '增加新的商品',
	'brand_goodstype_list_tips' => '<li>商家系统能够使用的模块，可以到基础设置中的模块管理中进行增加或者减少</li><li></li><li></li>',
	'goodstype_list' => '商品类型列表',
	'goodstype' => '商品类型',
	'content_minlen' => '点评内容最少字数',
	'content_minlen_comment' => '定义点评内容的字符限制,点评内容最少字数',
	'content_maxlen' => '点评内容最多字数',
	'content_maxlen_comment' => '定义点评内容的字符限制,点评内容最多字数',
	'recontent' => '审核回应内容',
	'recontent_comment' => '开启审核功能后，未审核的信息将暂时不在前台显示和操作。',
	'recontent_minlen' => '回应点评内容最少字数',
	'recontent_minlen_comment' => '定义回应点评内容的字符限制,回应点评内容最少字数',
	'recontent_maxlen' => '回应点评内容最多字数',
	'recontent_maxlen_comment' => '定义回应点评内容的字符限制,回应点评内容最多字数',
	'dianping_setting_tips' => '点评设置',
	'recontent_num' => '回应显示数',
	'recontent_num_comment' => '回应中每页显示回应数目',
	'tiao' => '条',
	'shijinzhi' => '十进制',
	'baifenzhi' => '百分制',
	'wufenzhi' => '五分制',
	'subtype' => '总分类型',
	'subtype_comment' => '列表页和详细页面显示主题的各个评分项的数值形式。默认为百分制。',
	'xiaoshu' => '分数小数点',
	'xiaoshu_comment' => '各项得分的显示是否显示小数点。',
	'nodispaly' => '不显示',
	'yiwei' => '1位',
	'erwei' => '2位',
	'dianping_option' => '点评项设置',
	'optionname' => '标识',
	'optiontitle' => '名称',
	'add_optionname' => '新建点评项',
	'mokuai_manage' => '管理',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',

	//联盟商家之用户管理
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//联盟商家之产品库
	'chanpinkuname' => '产品名称',
	'chanpinkuname_comment' => '产品名称',
	'chanpinkulogo' => '产品封面图片',
	'chanpinkulogo_comment' => '产品封面图片',
	'chanpinkupice' => '产品价格',
	'chanpinkupice_comment' => '产品价格',
	'chanpinku_setting_tips' => '<li>产品库基础设置</li><li>在产品库的字段设置中，产品ID(chanpinkuid)、所属商家(shopid)、产品名称(chanpinkuname)、产品封面图片(logo)、产品分类(cats)、产品价格(pice)、产品详情(information)、产品数量(chanpinkunum)、新建时间(createtime)、产品状态(chanpinkustatus)为固定字段.在添加新的字段时，请不要和上述字段重复。</li><li>本版本中暂不做重复判断，请站长在设置时注意不要设置重复的字段。选择框内容的格式同产品分类的格式</li>',
	'chanpinku_sort_comment' => '格式：<br />1=数码<br />1.1=电脑<br />1.1.1=台式机<br />1.1.2=笔记本<br />1.2=手机<br />2=服装<br />',
	'chanpinku_sort' => '产品分类',
	'chanpinku_fields' => '产品库字段',
	'fieldsname' => '字段标识',
	'fieldstitle' => '字段名称',
	'fieldstype' => '字段类型',
	'add_fieldsname' => '增加字段',
	'f_calendar' => '日期',
	'f_text' => '单行文本',
	'f_textarea' => '多行文本',
	'f_select' => '选择框',
	'f_number' => '数字',
	'f_edit' => '编辑框',
	'selectconmet' => '选择框内容',
	'brand_chanpinku_edit_tips' => '产品库编辑',
	'brand_chanpinku_edit' => '编辑选项',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//一卡通设置
	'yikatong_setting_succeed' => '一卡通设置成功',
	'yikatong_data_setting' => '数据表设置',
	'shop_table' => '对应商家的数据表',
	'shop_table_comment' => '填写商家所对应的数据表的表名，请不要填写表前缀',
	'table_fields' => '对应商家字段',
	'newfieldsname' => '商家字段',
	'bfieldsname' => '商家系统字段',
	'field_shopid' => '商家编号(shopid)',
	'field_shopname' => '商家名称(shopname)',
	'field_yktshoplogo' => '商家LOGO',
	'field_shopmanage' => '店主(uid)',
	'field_uid' => '店主(uid)',
	'field_shoplocation' => '地理位置(baidumap)',
	'add_bfieldsname' => '新建字段规则',
	'shop_url' => '商家查看链接地址',
	'shop_url_comment' => '填写商家系统的商家浏览页的地址，商家的编号用{shopid}代替',
	'shop_list_tips' => '商家筛选',
	'shop_list' => '商家列表',
	'shaixuan' => '筛选',
	'noshaixuan' => '未筛选',
	'shaixuaned' => '已筛选',
	'shop_select_tips' => '<li>请在筛选之前进行商家字段设置；</li><li>不要频繁操作，审核以及认领可到商家管理页面进行</li>',
	'shop_select' => '筛选商家列表',
	'shop_list_tips' => '<li>这里是已经筛选过的商家列表；</li><li>如需增加新的商家请到商家系统进行</li>',
	'field_shaixuantime' => '筛选时间',
	'money_comment' => '对应交易积分',
	'money' => '对应交易积分',
	'jifen_comment' => '对应赠送积分',
	'jifen' => '对应赠送积分',
	'member_info' => '会员信息',
	'shop_addurl' => '增加店铺网址',
	'shop_addurl_comment' => '在前台增加店铺的网址',
	'shop_logourl' => '商家LOGO地址',
	'shop_logourl_comment' => '如果选择了商家LOGO的字段，则设置商家字段的地址',
	'goods_table' => '对应商品的数据表',
	'goods_table_comment' => '填写商品所对应的数据表的表名，请不要填写表前缀',
	'goods_url' => '商家查看链接地址',
	'goods_url_comment' => '填写商家系统的商家浏览页的地址，商家的编号用{goodsid}代替',
	'goods_addurl' => '增加商品网址',
	'goods_addurl_comment' => '在前台增加商品的网址',
	'goods_logourl' => '商品封面地址',
	'goods_logourl_comment' => '如果选择了商品封面的字段，则设置商品字段的地址',
	'goods_fields' => '对应商品字段',
	'newgoodsfieldsname' => '商品字段',
	'goodsfieldsname' => '商家系统中的商品字段',
	'field_goodsid' => '商品编号(goodsid)',
	'field_goodsshopid' => '商家编号(shopid)',
	'field_goodsname' => '商品名称(goodsname)',
	'field_goodslogo' => '商品封面(goodslogo)',
	'field_goodsnum' => '库存(goodsnum)',
	'field_goodspice' => '商品价格(pice)',
	'jicihelp_comment' => '计次消费帮助信息',
	'jicihelp' => '计次消费帮助内容',
	'shijianhelp' => '时间限制帮助内容',
	'shijianhelp_comment' => '就是常说的年卡和月卡的帮助信息',
	'liangkahelp' => '亮卡打折帮助信息',
	'liangkahelp_comment' => '亮卡打折帮助信息',
	'xianjinhelp' => '现金消费帮助信息',
	'xianjinhelp_comment' => '现金消费帮助信息',
	'yuehelp' => '余额消费帮助信息',
	'yuehelp_comment' => '余额消费帮助信息',
	'jifenhelp' => '积分消费帮助信息',
	'jifenhelp_comment' => '积分消费帮助信息',
	'joinbusiness' => '商家入住简介',
	'joinbusiness_text' => '<strong>&nbsp;&nbsp;欢迎入住一卡通商家系统</strong>',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//一卡通之商家组
	'businessgroup_list_tips' => '<li>可以对商家根据是否收费、是否可以发放会员卡等等进行分类设置</li><li>当商家组下已经有商家时，请不要删除商家组和改变商家组的开启状态</li>',
	'businessgroup_list' => '商家组',
	'businessgroupname' => '商家组名称',
	'businessnum' => '商家数量',
	'businessgroupquanxian' => '权限',
	'status' => '状态',
	'add_businessgroup' => '增加新的商家组',
	'businessgroup_edit' => '编辑商家组',
	'businessgroup_edit_tips' => '<li>对商家组进行设置，当该商家组已经有了商家之后，尽量不要再次修改，以免数据出错</li><li>初始余额是因为商家支付了入住费用，所以尽量初始余额不要高于入住费用，除非在促销期</li><li>初始积分建议也不要高于商家的入住费用</li><li>商家组简介的内容将在前台页面显示，故可以使用html代码</li><li>店长、财务、收银员默认都具有刷卡消费的权限，当什么也不选择的时候，则标识该商家组不能添加店员</li>',
	'inshoufei' => '入住收费',
	'inshoufei_comment' => '成为一卡通的会员商家，收取的费用数，单位为转账用积分积分数',
	'inshoufeiqixian' => '收费周期',
	'inshoufeiqixian_comment' => '可以是每天、每月等，填写的数字要求单位为天，30天为月；365天为年',
	'businessgroupdescription' => '商家组简介',
	'businessgroupdescription_comment' => '用于向商家展示的简短说明',
	'businessgroupico' => '商家组图标',
	'businessgroupico_comment' => '大小为120X90 px，格式为jpg、png，用于商家组的介绍页面的一个图标显示',
	'cardfeiyong' => '会员卡激活费用',
	'cardfeiyong_comment' => '本组商家派送出的会员卡，会员在激活的时候所需的费用，单位：元（不能有小数）',
	'cardpice' => '售价',
	'cardpice_comment' => '允许商家出售会员卡的最高价钱，当为0时，则不允许出售',
	'businessgroup_edit_succeed' => '商家组编辑成功',
	'businessgroupname_nonull' => '商家组名称不能为空',
	'status_comment' => '该商家组是否启用',
	'businessgroupname_comment' => '必填，可以是汉字，如：普通组、VIP商家组、黄金商家...',
	'edit' => '编辑',
	'businessgroup' => '商家组',
	'enxiaofeitype' => '可以使用的结算消费类型',
	'enbusinessnum' => '允许创建店铺的数量',
	'enbusinessnum_comment' => '允许创建店铺的数量，当不允许创建分店时，数量是创建地理的店铺的数量，当允许创建分店的时候，则为总点与分店的总数之和的数量。如果不填写则默认为1',
	'enfendian' => '是否允许创建分店',
	'enfendian_comment' => '是否允许创建分店',
	'dianzhang_comment' => '店长权限',
	'dianzhang' => '店长权限',
	'caiwu_comment' => '财务权限',
	'caiwu' => '财务权限',
	'shouyin_comment' => '收银员权限',
	'shouyin' => '收银员权限',
	'dianyuan_kaika' => '开卡',
	'dianyuan_buka' => '补卡',
	'dianyuan_zhuxiaoka' => '注销卡',
	'dianyuan_kachongzhi' => '卡充值',
	'dianyuan_jifenzengsong' => '积分赠送',
	'dianyuan_goodssetting' => '商品设置',
	'dianyuan_viewmember' => '查看会员',
	'dianyuan_viewxiaofei' => '查看消费记录',
	'zhanghaoyue' => '初始余额',
	'zhanghaoyue_comment' => '初始余额',
	'zhanghaojifen' => '初始积分',
	'zhanghaojifen_comment' => '初始积分',
	'dianyuanshenhe' => '修改店员信息时是否审核',
	'dianyuanshenhe_comment' => '新建或者修改店员信息时，是否需要审核',
	'xiaofeitypeshenhe' => '修改消费类型时是否需要审核',
	'xiaofeitypeshenhe_comment' => '修改消费类型时是否需要审核',
	'contractsample_comment' => '请上传合同样本，以便商家在入驻的时候下载，格式为word的doc格式',
	'contractsample' => '合同样本',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//一卡通新的商家管理
	'business_list_tips' => '<li>商家审核与管理</li><li>需：所需资金；余：账户余额；积：账户积分；个：个人照片；身：身份证照；合：合同照片；</li>',
	'business_list' => '商家列表',
	'businessname' => '商家名称',
	'relname' => '姓名',
	'caiwuinfo' => '财务信息',
	'man' => '男',
	'woman' => '女',
	'businessgroup' => '商家组',
	'businessinfo' => '商家信息',
	'sxzjs' => '需',
	'zhyes' => '余',
	'zhjfs' => '积',
	'photo' => '图片',
	'gerenphotos' => '个',
	'zanwu' => '无',
	'yishangchuan' => '有',
	'shenfenphotos' => '身',
	'contractimages' => '合',
	'shopinfo' => '店铺信息',
	'goodsinfo' => '商品信息',
	'cardinfo' => '卡信息',
	'yuebuzu' => '余额不足',
	'ziliaobuquan' => '资料不全',
	'daishen' => '待审核',
	'tongguo' => '已通过',
	'add_business' => '新增商家',
	'business_edit_tips' => '<li>上部分为商家的基本信息，请仔细查看，并且管理员有最高权限，可以修改任意项</li><li>下部分为商家的扩展选项</li>',
	'business_edit' => '商家选项',
	'businessname_comment' => '商家名称',
	'uid_comment' => '商家所对应的用户 ID',
	'sex' => '性别',
	'phone' => '电话',
	'address' => '地址',
	'gerenphoto' => '个人近期照片',
	'shenfenno' => '身份证号',
	'shenfenphoto' => '身份证照片',
	'businesssummary' => '商家简介',
	'contractimage' => '合同影印件',
	'baomi' => '保密',
	'business_edit_succeed' => '商家信息编辑成功',
	'business_shenhe' => '商家审核项',
	'business_status' => '商家审核',
	'business_status_comment' => '审核通过该商家',
	'business_fields_error' => '商家管理所需的基本设置项不全，请先进行设置',
	'shop_spnum' => '审',
	'shop_weinum' => '未',
	'shop_zongnum' => '总',
	'business_base_info' => '商家基本信息',
	'shopuid' => '所属用户',
	'yikatong_shopname_comment' => '店铺名称的修改请到商家联盟中进行',
	'upshop' => '上级商铺',
	'fendian' => '分店',
	'' => '',
	'' => '',
	//商家管理
	'isfendian' => '是否是分店',
	'isfendian_comment' => '是否是分店，只有在一点店主多个商家的时候菜有效',
	'brand_shop_edit_tips' => '商家编辑',
	'shopsort_edit_succeed' => '商铺分类编辑成功',
	'' => '',
	'' => '',
	//商品管理
	'goodsname' => '商品名称',
	'goods_list_tips' => '一卡通商品管理',
	'goods_list' => '商品列表',
	'xiaofeisetting' => '消费设置',
	'yktgoodssetting_shopname_comment' => '商家名称，不能修改',
	'goodsname_comment' => '商品名称，不能修改',
	'yktgoodssetting' => '设置选项',
	'goodspice' => '原价',
	'yktlksetting' => '亮卡打折设置',
	'lkpice' => '亮卡后价格',
	'yktxjsetting' => '现金消费',
	'xjpice' => '刷卡后价格',
	'shoptojifen' => '商家赠送积分',
	'shoptojifen_comment' => '当用户刷卡消费时，商家账户内赠送给用户的积分数，不赠送则填写0',
	'goodspice_comment' => '商品的原价',
	'lkpice_comment' => '亮卡消费的商品价格',
	'xjpice_comment' => '一卡通用户采用线下现金直接交易，并刷卡消费，商品的价格',
	'xiaofeisname' => '消费类型',
	'shuaka' => '刷卡',
	'zhehoupice' => '折后现金价',
	'yue' => '卡内金额',
	'sitetojifen' => '网站赠送积分',
	'jifenpice' => '积分价格',
	'youxiaoqi' => '有效期',
	'xiaofei_jici' => '计次消费',
	'xiaofei_shijian' => '计时消费',
	'xiaofei_liangka' => '亮卡打折',
	'xiaofei_xianjin' => '现金消费',
	'xiaofei_yue' => '余额消费',
	'xiaofei_jifen' => '积分兑换',
	'xiaofeisetting' => '消费设置',
	'available' => '启用',
	'daoqishijian' => '到期时间',
	'cishu' => '次数',
	'dangqian' => '当前',
	'xiugai' => '修改',
	'xianjinfeiyong' => '现金费用',
	'kaneijifen' => '卡内积分',
	'kaneiyue' => '卡内余额',
	'jifenzengsong' => '积分赠送',
	'kaneichongzhi' => '卡内充值',
	'rmbyuan' => '元',
	'rmb' => '￥',
	'goodsnum' => '商品数量',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//卡分类
	'cardcat_list_tips' => '<li>对会员卡进行管理</li><li>会员卡分为本网通、全网通两类</li>',
	'cardcat_list' => '会员卡分类列表',
	'cardcatname' => '会员卡分类名称',
	'add_cardcat' => '增加新的会员卡分类',
	'cardcat_edit_tips' => '',
	'cardcat_edit' => '编辑会员卡分类',
	'cardcat_edit_tips' => '<li>对会员卡分类进行管理</li><li>会员卡分为本网通、全网通两类</li><li>在最后的简单介绍中，如果部位空的话，将按照输入的形式显示一卡通会员卡种类的介绍</li>',
	'cardcatico' => '会员卡图标',
	'cardcatico_comment' => '会员卡的背景图的图标，大小为425X270 px 格式为jpg',
	'cardcatname_comment' => '必填，可以是汉字，如：普通卡、VIP卡、贵宾卡...',
	'cardcatdescription' => '简单介绍',
	'cardcatdescription_comment' => '会员卡办理的时候的简单介绍',
	'cardtype_comment' => '选择不同功能的卡的种类',
	'cardtype' => '会员卡种类',
	'benwangka' => '本网通',
	'quanwangka' => '全网通',
	'xunika' => '虚拟卡',
	'makecard' => '制卡',
	'cardcat_edit_succeed' => '会员卡种类编辑成功',
	'card_pre' => '卡号前缀',
	'card_start' => '起始号码',
	'card_len' => '卡号长度',
	'card_pre_comment' => '填写卡号前缀',
	'card_start_comment' => '起始号码',
	'card_len_comment' => '卡号长度，最少8位，最多11位',
	'cardpass_len' => '密码长度',
	'cardpass_len_comment' => '密码长度，至少6位',
	'cardpass_type' => '密码类型',
	'cardpass_type_comment' => '设置密码的类型，可以随机产生，也可设置为后几位',
	'suiji' => '随机',
	'houwei' => '后几位',
	'card_num' => '制卡张数',
	'card_num_comment' => '制卡张数',
	'card_islogin' => '是否直接可以登录',
	'card_islogin_comment' => '选择直接登录时，将生成以卡号为用户名的论坛用户，请确保卡号没有与现已有用户冲突',
	'makecard_edit_tips' => '制卡说明',
	'makecard_edit' => '制卡选项',
	'cardcat_make_succeed' => '张会员卡已经生成',
	'cardnum' => '数量',
	'cardno' => '卡号',
	'cardpass' => '激活码',
	'cardjine_comment' => '设置卡内的初始金额',
	'cardjine' => '金额',
	'cardyouxiaoqi' => '固定卡有效期',
	'carddzyouxiaoqi' => '自动卡有效期',
	'cardyouxiaoqi_comment' => '填写该卡的指定的固定有效期',
	'carddzyouxiaoqi_comment' => '填写自会员卡激活后的多少时间内有效，格式为XX日、XX月、XX年',
	'carduid' => '用户名',
	'bindtime' => '绑定时间',
	'no_bind' => '未绑定',
	'xuhao' => '序号',
	'cardjifen' => '积分',
	'cardjifen_comment' => '设置卡内的初始积分',
	'cardkaishi' => '卡开始时间',
	'cardkaishi_comment' => '设置此会员卡在什么时间开始有效',
	'cardqingling' => '积分清零时间',
	'cardqingling_comment' => '设置什么时候积分自动清零，设置为0时，则不自动清零',
	'kaishi' => '开始',
	'jieshu' => '结束',
	'qingling' => '清零',
	'cardpici' => '批次',
	'card_list_tips' => '查看管理会员卡',
	'card_list' => '会员卡列表',
	'cardbind' => '绑定',
	'cardbinded' => '已绑定',
	'nocardbind' => '未绑定',
	'piliang' => '批量',
	'carddelete' => '删除选中的会员卡，请谨慎操作，一旦删除已经绑定的会员卡，则该会员的积分等信息将全部消失',
	'cardfafang' => '填写发放给哪个用户的UID，并确保该UID对应的用户存在,以及所发放的会员卡没有发放出去',
	'carddelete_tips' => '请填写删除码',
	'card_del_succeed' => '张会员卡删除成功',
	'card_fafang_error' => '发放会员卡错误，请查看是否填写的用户id正确',
	'card_edit_succeed' => '会员卡编辑成功',
	'card_fafang_succeed' => '张会员卡发放成功',
	'cardfafanguid' => '所属',
	'no_fafang' => '未发放',
	'card_status' => '是否在前台显示',
	'card_status_comment' => '是否在前台显示',
	'' => '',
	'' => '',
	'' => '',
	//一卡通之卡导入
	'cardimport_edit_tips' => '<li>导入的execle文件要求</li>',
	'cardimport_edit' => '上传文件',
	'cardcat_daoru_succeed' => '张会员卡导入成功',
	'' => '',
	'' => '',
	//一卡通之卡团购
	'ykttuangou_list_tips' => '<A href="'.ADMINSCRIPT.'?action='."\$this_page".'">设置</A>',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//一卡通前台商家入驻
	'joinbusiness_exists' => '您已经是一卡通的商家，即将跳转至商家管理中心',
	'joinbusiness_rzstep2_succeed' => '您的一卡通商家申请已经成功提交，请耐心等待，并进行下一步上传资料',
	'joinbusiness_rzstep3_succeed' => '您的一卡通商家资料上传成功，请耐心等待',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',

	//微信墙
	'wxq123_setting_tips' => '微信墙123基础设置',
	'wxq123_setting' => '设置选项',
	'shibiema' => '微信识别码',
	'token' => 'Token值',
	'weixinimg' => '微信二维码图片',
	'wxqshopset_list_tips' => '<li>微信墙123系统，适合其他系统的商家和本系统自带的商家系统；</li><li>在设置其他系统的商家设置中，要设置对应商家数据表的表名、商家id字段、商家名称字段、地理位置字段</li><li>地理位置采用的是百度地图</li><li>设置商家分类的链接地址</li><li>在相应的位置添加相应的代码</li>',
	'wxqshopset_list' => '微信墙123商家设置',
	'wxqfield' => '微信墙字段',
	'tablename' => '对应表名',
	'fieldname' => '对应字段名称',
	'diliweizhi' => '地理位置',
	'wxq_shopset_succeed' => '微信墙商家设置成功',
	'shop_tablename' => '商家对应数据表',
	'shop_tablename_comment' => '请不要加表前缀',
	'wxq123_data_setting' => '数据表设置',
	'shop_shopid' => '商家编号对应字段',
	'shop_shopname' => '商家（店铺）名称对应字段',
	'shop_condition' => '条件',
	'userreg' => '用户注册',
	'userreg_comment' => '微信用户首次关注或搜索本站时，是否自定分配7位识别码并注册成为本站会员，确保discuz没有7位数字组成的用户',
	'firsttype' => '首次关注回复类型',
	'firsttype_comment' => '微信用户首次关注或直接数字搜索到的时候，回复微信的类型',
	'wxtext' => '文本',
	'wxmusic' => '音乐',
	'wxnews' => '图文',
	'shoptype' => '商家类型',
	'bendi' => '本网商家',
	'quanguo' => '联盟商家',
	'wxq_shoptype_comment' => '选择本网商家，则系统产生5位站内唯一微信识别码，只能在本网内的微信用户查询；选择联盟商家，则系统产生6位唯一微信识别码，可以在联盟的网站之间进行查询。',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//微信墙123商家管理
	'tablename_set_error' => '请先设置商家参数',
	'shopsbm' => '商家识别码',
	'shopsbm_comment' => '系统自动生成4位数字的识别码，如果商家已经开通则不能修改',
	'shoptoken' => '微信Token',
	'shoptoken_comment' => '系统自动生成的微信公共帐号的token',
	'wxqshopname_comment' => '商家（店铺）名称，修改请到商家系统修改',
	'shoptypename_comment' => '不同的商家组的权限不一样，请到商家组进行设置',
	'' => '',
	'' => '',
	//微信墙123之商家组
	'wxq_shoptype_list_tips' => '微信墙123的商家组',
	'wxq_shoptype_list' => '商家组列表',
	'shoptypename' => '商家组名称',
	'add_shoptype' => '增加商家组',
	'shoptypequanxian' => '商家组权限',
	'shopnum' => '商家数',
	'shoptype_edit_tips' => '商家组设置',
	'shoptype_edit' => '设置选项',
	'shoptypeico' => '商家组图标',
	'shoptypeico_comment' => '图标文件格式为png、jpg；大小为40X40 px',
	'' => '',
	'' => '',
	//微信墙之设置
	'wxq_setting' => '微信墙设置',
	'shibiema_comment' => '设置微信墙123的站长识别码，为4位，系统随机生成的4位识别码，一经确定不能修改，在微信公共平台上设置为http://www.wxq123.com/weixin/?sbm=',
	'token_comment' => '设置微信公共平台帐号上的Token值，确保与在微信公共平台上设置的一样，否则不能使用',
	'weixinimg_comment' => '请上传微信图片',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//微信墙之微信记录
	'jilu_list_tips' => '微信记录',
	'jilu_list' => '记录列表',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//前台的会员中心
	'membercenter' => '会员中心',
	'g_guest' => '普通用户',
	'g_business' => '一卡通商家',
	'g_caiwu' => '财务人员',
	'g_dianzhang' => '店铺店长',
	'g_shouyin' => '收银员',
	'g_kaxiaoshou' => '卡销售员',
	'card_search' => '会员卡搜索',
	'card_create' => '办理会员卡',
	'card_again' => '补办会员卡',
	'card_locking' => '会员卡锁定',
	'card_charge' => '会员卡充值',
	'card_quit' => '注销会员卡',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	//产品库列表
	'brand_chanpinku_list_tips' => '产品库列表',
	'brand_chanpinku_list' => '产品库列表',
	'pice' => '产品价格',
	'upshop' => '所属商铺',
	'brand_chanpinku_edit_succeed' => '产品库产品编辑成功',
	'chanpinku_edit_succeed' => '产品库产品编辑成功',
	'brand_setting' => '设置选项',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'shop' => '商家联盟',
	'' => '',
);
$plang['templatelang'] = array(
	'goodssort' => '分类：',
	'diqu' => '地区：',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
	'' => '',
);
$plang['installlang'] = array(
	'' => '',
);
$plang['systemlang'] = array(
	'file' => array(
		'' => '',
		),
	);
?>