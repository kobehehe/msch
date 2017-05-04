<?php

class OrderShowIdeaWords extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_show_idea_words';
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('words',$this->words);
		$criteria->compare('remark',$this->remark);
		$criteria->compare('subarea_id',$this->subarea_id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('show',$this->show);
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
