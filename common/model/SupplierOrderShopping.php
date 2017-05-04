<?php

class SupplierOrderShopping extends InitActiveRecord
{
	public function tableName()
	{
		return 'supplier_order_shopping';
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('staff_address_id',$this->staff_address_id);
		$criteria->compare('service_product_id',$this->service_product_id);
		$criteria->compare('amonut',$this->amonut);
		$criteria->compare('actual_price',$this->actual_price);
		$criteria->compare('actual_unit_cost',$this->actual_unit_cost);
		$criteria->compare('statua',$this->statua);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
