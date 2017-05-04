<?php

class ServiceProduct extends InitActiveRecord
{

	public function tableName()
	{
		return 'service_product';
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('service_person_id',$this->service_person_id);
		$criteria->compare('service_type',$this->service_type,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('decoration_tap',$this->decoration_tap,true);
		$criteria->compare('recommend',$this->recommend,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('unit',$this->unit);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('ref_pic_url',$this->ref_pic_url,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('product_show',$this->product_show,true);
		$criteria->compare('total_inventory',$this->total_inventory,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
