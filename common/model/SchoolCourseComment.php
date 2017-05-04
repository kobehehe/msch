<?php
class SchoolCourseComment extends InitActiveRecord
{
	public function tableName()
	{
		return 'school_course_comment';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('course_id',$this->course_id);
		$criteria->compare('staff_id',$this->staff_id,true);
		$criteria->compare('star_amount',$this->star_amount,true);
		$criteria->compare('comment',$this->comment,true);
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
