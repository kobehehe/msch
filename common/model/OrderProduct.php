<?php

class OrderProduct extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_product';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('product_type',$this->product_type);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('order_set_id',$this->order_set_id);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('actual_price',$this->actual_price);
		$criteria->compare('unit',$this->unit);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('actual_unit_cost',$this->actual_unit_cost);
		$criteria->compare('actual_service_ratio',$this->actual_service_ratio);
		$criteria->compare('remark',$this->remark);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
