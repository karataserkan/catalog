<?php

/**
 * This is the model class for table "httpservice".
 *
 * The followings are the available columns in table 'httpservice':
 * @property integer $http_service_id
 * @property string $http_service_name
 * @property string $https_service_secret_key
 */
class Httpservice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'httpservice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('http_service_name, https_service_secret_key', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('http_service_id, http_service_name, https_service_secret_key', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'http_service_id' => 'Http Service',
			'http_service_name' => 'Http Service Name',
			'https_service_secret_key' => 'Https Service Secret Key',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('http_service_id',$this->http_service_id);
		$criteria->compare('http_service_name',$this->http_service_name,true);
		$criteria->compare('https_service_secret_key',$this->https_service_secret_key,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Httpservice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
