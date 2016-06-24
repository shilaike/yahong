<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class DiscountController extends Controller {
    public function index(){
     // var_dump(M()->table('Product')->select());
        $this->display();
        //echo 'this is Discount';
    }
    public function discountList(){
        $ck=A('CheckInput');
        $map=array();
        $customid =I('customid');
        $kindid=I('kindid');
        $areaid=I('areaid');
        $page=$ck->in('当前页','page','intval','1',0,0);
        $rows=$ck->in('每页记录数','rows','intval','',0,0);
        if(!empty($areaid[0])){
            $Area = M()->table('Area')->where(array('s_ID'=>$areaid[0]))->field('s_fullid,s_layer,u_Name')->find();
            if(!empty($Area)&&($Area['s_layer']==1))
            {
                $childerArea =M()->table('Area')->where(array('s_ParentID'=>$Area['s_fullid']))->field('s_id')->select();
                $temp =array();
                foreach($childerArea as $val)
                {
                    $temp[]=$val['s_id'];
                }
                $unitmap['Area_ID'] =array('in',$temp);
                $list =M()->table('Units')->where($unitmap)->field('s_id,s_fullid,u_Name')->select();
                foreach($list as $lval)
                {
                    $unit_id[]=$lval['s_id'];
                }
                $map['customid']=array('in',$unit_id);
            }else
            {
                $list =M()->table('Units')->where(array('Area_ID'=>$areaid[0]))->field('s_id,s_fullid,u_Name')->select();
                foreach($list as $lval)
                {
                    $unit_id[]=$lval['s_id'];
                }
                $map['customid']=array('in',$unit_id);
            }
        }
        if(!empty($customid[0])){

            $map['customid']=$customid[0];
        }
        if(!empty($kindid[0])){
            if($kindid[0]!='00000')
            {
                $map['productkindid']=$kindid[0];
            }
        }


        $count=M()->table('CustomDiscount')->count();
        $info=M()->table('CustomDiscount')->page($page.','.$rows)->where($map)->select();
        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }

        $this->ajaxReturn($data);
    }
    public function  add(){
        $customId = I('customid');
        $kindId = I('kindid');
        $discount = I('discount');
        $customName = M()->table('Units')->where(array('s_ID'=>$customId))->field('u_Name')->find();
        $kindName = M()->table('Product')->where(array('s_FullID'=>$kindId))->field('u_Name')->find();
        $isChildren = M()->table('Product')->where(array('s_ParentID'=>$kindId))->select();
        $isfind =M()->table('CustomDiscount')->where(array('customid'=>$customId,'productkindid'=>$kindId))->find();
        if(empty($isfind))
        {

            $result=M()->table('CustomDiscount')->add(array('customid'=>$customId,'customname'=>$customName['u_name'],'productkindid'=>$kindId,'productkindname'=>$kindName['u_name'],'discount'=>$discount));
           /* if(!empty($isChildren))
            {
                foreach($isChildren as $key=>$val)
                {
                    $isfind =M()->table('CustomDiscount')->where(array('customid'=>$customId,'productkindid'=>$val['s_dullid']))->find();
                    if(!empty($isfind))
                    {
                        M()->table('CustomDiscount')->where(array('customid'=>$customId,'productkindid'=>$val['s_FullID']))->delete();
                    }
                    M()->table('CustomDiscount')->add(array('customid'=>$customId,'customname'=>$customName['u_name'],'productkindid'=>$val['s_fullid'],'productkindname'=>$val['u_name'],'discount'=>$discount));
                }
            }*/
            if($result)
            {
                echo 1;
            }
        }else
        {
            echo 0;
        }

    }
    public function discountSave(){
        $customId = I('customid');
        $kindId = I('productkindid');
        $discount = I('discount');
        $result =M()->table('CustomDiscount')->where(array('customid'=>$customId,'productkindid'=>$kindId))->save(array('discount'=>$discount));
        if($result)
        {
            echo 1;
        }else
        {
            echo 0;
        }
    }
    public function DiscountDelete(){
        $customid =I('customid');
        $productkindid = I('productkindid');
        $result=M()->table('CustomDiscount')->where(array('customid'=>$customid,'productkindid'=>$productkindid))->delete();
        if($result)
        {
            echo 1;
        }else
        {
            echo 0;
        }
    }
}