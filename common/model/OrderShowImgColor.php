<?php
class OrderShowImgColor extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_show_img_color';
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('show',$this->show);
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
