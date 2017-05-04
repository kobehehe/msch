<?php

class OrderShowIdeaColor extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_show_idea_color';
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->words);
		$criteria->compare('remark',$this->remark);
		$criteria->compare('main_color',$this->main_color);
		$criteria->compare('second_color',$this->second_color);
		$criteria->compare('third_color',$this->third_color);
		$criteria->compare('show',$this->show);
		$criteria->compare('staff_id',$this->staff_id);
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
