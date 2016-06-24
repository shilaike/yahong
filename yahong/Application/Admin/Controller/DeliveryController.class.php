<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class DeliveryController extends Controller {
    public $discount=100;
    public function index(){
        $this->display();
    }
    public function deliveryMainList(){

        $ck=A('CheckInput');
        $page=$ck->in('当前页','page','intval','1',0,0);
        $rows=$ck->in('每页记录数','rows','intval','',0,0);
        $uname =I('uname');
        $count=M()->table('MasterBill')->where(array('BillType'=>1))->count();
        $info=M()->table('MasterBill M')->join('Units U on M.Unit_ID = U.s_ID')->page($page.','.$rows)->where(array('M.BillType'=>1,'U.u_Name'=>array('like',"%$uname%")))->field('M.AutoID,M.BillDate,M.BillTime,M.BillSN,M.Abst,M.Unit_ID,U.u_Name')->select();
        foreach($info as $key=>$val)
        {
            $unitName = M()->table('Units')->where(array('s_ID'=>$info[$key]['unit_id']))->field('u_Name')->find();
            $info[$key]['unitName']=$unitName['u_name'];
        }
        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }

        $this->ajaxReturn($data);
    }
    public function deliveryDetail(){

        $deliveryId = I('id');
        $unitid =I('unitid');
        $info=M()->table('ListSale')->where(array('Bill_ID'=>$deliveryId))->select();
        $table_head ="<table cellpadding='0' cellspacing='0'  style='height: 25px;' width='100%'><tbody><tr class='datagrid-row-over' style='height: 25px;'><td>商品编码</td><td>商品名称</td><td>规格</td><td>型号</td>
        <td>条码</td><td>单位</td><td>数量</td><td>单价</td><td>金额</td><td>折扣</td><td>折扣单价</td>
        <td>折扣金额</td><td>折后金额</td></tr>";
        $table_body='';
        foreach($info as $val){
            $product = $this->getProductName($val['prod_id'],$unitid);
            $table_body.="<tr style='height: 25px;'><td>".$product['s_fullid']."</td><td>".$product['u_name']."</td><td>".$product['prodspec']."</td><td>".$product['prodtype']."</td>
        <td>".$product['barcode']."</td><td>".$product['proddw']."</td><td>".sprintf("%0.2f", $val['prod_number'])."</td><td>".sprintf("%0.2f",$val['prod_price'])."</td><td>".sprintf("%0.2f", $val['prod_money'])."</td><td>".$product['discount']."</td><td>".sprintf("%0.2f",($val['prod_price']*$product['discount']/100))."</td>
        <td>".sprintf("%0.2f", $val['prod_money']*(1-$product['discount']/100))."</td><td>".sprintf("%0.2f", $val['prod_money']*$product['discount']/100)."</td></tr>";
        }
        $table_foot="</tbody></table>";
        $table=$table_head.$table_body.$table_foot;
        echo $table;
    }
    public function getProductName($s_id,$customId){
        $productInfo = M()->table('Product')->where(array('s_ID'=>$s_id))->find();
        $pro_dw =M()->table('CommonInfo')->where(array('s_ID'=>$productInfo['proddw']))->field('u_name')->find();
        $productInfo['proddw'] = $pro_dw['u_name'];
        $isDiscount =M()->table('CustomDiscount')->where(array('customid'=>$customId,'productkindid'=>$productInfo['s_parentid']))->find();
        if(!empty($isDiscount))
        {
            $productInfo['discount'] = $isDiscount['discount'];
        }else
        {
            $productInfo['discount'] =$this->discount;
        }
        return $productInfo;
    }
    public function deliverySave(){
        $deliveryId = I('id');
        $unitid =I('unitid');
        $info=M()->table('ListSale')->where(array('Bill_ID'=>$deliveryId))->select();
        $M=M();
        $M->startTrans();
        $allMoney =0;
        foreach($info as $key=>$val){
            $product = $this->getProductName($val['prod_id'],$unitid);
            $info[$key]['discount'] =$product['discount'];
            $info[$key]['discountePrice'] = sprintf("%0.2f",($val['prod_price']*$product['discount']/100));
            $info[$key]['discountMoney'] = sprintf("%0.2f",($val['prod_money']*(1-$product['discount']/100)));
            $info[$key]['discountaPrice'] = sprintf("%0.2f", ($val['prod_money']*$product['discount']/100));
            $allMoney+=$info[$key]['discountaPrice'];
            $data =array('Discount'=>$info[$key]['discount'],'DisPrice'=>$info[$key]['discountePrice'],'DisMoney0'=> $info[$key]['discountMoney'],'DisMoney'=>$info[$key]['discountaPrice']);
            $result[$key]=M()->table('ListSale')->where(array('Bill_ID'=>$val['bill_id'],'Prod_ID'=>$val['prod_id']))->save($data);
        }
        $yh_money =M()->table('MasterBill')->where(array('s_ID'=>$deliveryId))->field('YH_Money')->find();
        $YSYF_money =sprintf("%0.2f", (floatval($allMoney)-floatval($yh_money['yh_money '])));
        $result['msterbill'] =M()->table('MasterBill')->where(array('Auto_ID'=>$deliveryId))->save(array('SumTaxMoney'=>$allMoney,'YSYF_money'=>$YSYF_money,'SumDisMoney'=>$allMoney));
        M()->commit();
        /*foreach($result as $resval)
        {
            if(!empty($resval))
            {
                M()->commit();
            }else
            {
                M()->rollback();
            }
        }*/
       echo 1;
    }
}