<?php
class SchoolCourseVideo extends InitActiveRecord
{
	public function tableName()
	{
		return 'school_course_video';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('course_id',$this->name);
		$criteria->compare('video_url',$this->sort,true);
		$criteria->compare('sort',$this->icon_url,true);
		$criteria->compare('name',$this->icon_url,true);
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
