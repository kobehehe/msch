<?php
class SchoolCourseType extends InitActiveRecord
{
	public function tableName()
	{
		return 'school_course_type';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		$criteria->compare('sort',$this->sort,true);
		$criteria->compare('icon_url',$this->icon_url,true);
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
