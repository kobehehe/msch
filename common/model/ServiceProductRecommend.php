<?php

class ServiceProductRecommend extends InitActiveRecord
{

	public function tableName()
	{
		return 'service_product_recommend';
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('service_product_id',$this->service_product_id,true);
		$criteria->compare('recommend_type',$this->recommend_type,true);
		$criteria->compare('supplier_type',$this->supplier_type,true);
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
