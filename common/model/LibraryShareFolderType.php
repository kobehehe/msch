<?php
class LibraryShareFolderType extends InitActiveRecord
{
	public function tableName()
	{
		return 'library_share_folder_type';
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		$criteria->compare('img_url',$this->img_url);
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
