<?php

class OrderShow extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_show';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type);
		$criteria->compare('img_url',$this->img_url,true);
		$criteria->compare('img_description',$this->img_description,true);
		$criteria->compare('order_product_id',$this->order_product_id,true);
		$criteria->compare('words',$this->words,true);
		$criteria->compare('theme_words',$this->theme_words,true);
		$criteria->compare('theme_remark',$this->theme_remark,true);
		$criteria->compare('wed_style',$this->wed_style,true);
		$criteria->compare('wed_color',$this->wed_color,true);
		$criteria->compare('vi_img_url',$this->vi_img_url,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('subarea',$this->subarea,true);
		$criteria->compare('area_sort',$this->area_sort,true);
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
