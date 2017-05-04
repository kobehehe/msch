<?php
class LibraryQuery extends InitActiveRecord
{
	public function tableName()
	{
		return 'library_query';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('web_id',$this->web_id);
		$criteria->compare('start_id',$this->start_id);
		$criteria->compare('modify_time',$this->modify_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
