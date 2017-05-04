<?php

class ServiceProductRecommendViewPage extends InitActiveRecord
{

	public function tableName()
	{
		return 'service_product_recommend_view_page';
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('page',$this->page,true);
		$criteria->compare('recommend_type',$this->recommend_type,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
