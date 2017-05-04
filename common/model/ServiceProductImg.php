<?php

class ServiceProductImg extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_product_img';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('service_product_id',$this->service_product_id);
		$criteria->compare('order_show_img_style',$this->order_show_img_style);
		$criteria->compare('order_show_img_case',$this->order_show_img_case);
		$criteria->compare('order_show_img_color',$this->order_show_img_color);
		$criteria->compare('img_url',$this->img_url);
		$criteria->compare('psd_url',$this->psd_url);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('img_show',$this->img_show);
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
