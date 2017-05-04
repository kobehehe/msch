<?php
class SupplierProduct extends InitActiveRecord
{
	public function tableName()
	{
		return 'supplier_product';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('ori_id',$this->ori_id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('service_product_id',$this->service_product_id);
		$criteria->compare('supplier_type_id',$this->supplier_type_id);
		$criteria->compare('dish_type',$this->dish_type);
		$criteria->compare('decoration_tap',$this->decoration_tap);
		$criteria->compare('lss_tap',$this->decoration_tap);
		$criteria->compare('standard_id',$this->standard_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('unit_price',$this->unit_price);
		$criteria->compare('unit_cost',$this->unit_cost);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('service_charge_ratio',$this->service_charge_ratio);
		$criteria->compare('ref_pic_url',$this->ref_pic_url,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('product_show',$this->product_show,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
