<?php
class SchoolCourse extends InitActiveRecord
{
	public function tableName()
	{
		return 'school_course';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('service_product_id',$this->service_product_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('poster',$this->poster,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('short_summary',$this->short_summary,true);
		$criteria->compare('course_show',$this->course_show,true);
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
