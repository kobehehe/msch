<?php

class OrderShowImg extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_show_img';
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('service_product_img_id',$this->service_product_img_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('recommend',$this->recommend);
		$criteria->compare('style_id',$this->style_id);
		$criteria->compare('subarea_id',$this->subarea_id);
		$criteria->compare('img_url',$this->img_url,true);
		$criteria->compare('staff_id',$this->staff_id,true);
		$criteria->compare('description',$this->description,true);
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
