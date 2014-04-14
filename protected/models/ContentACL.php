<?php

/**
 * This is the model class for table "contentACL".
 *
 * The followings are the available columns in table 'contentACL':
 * @property string $contentId
 * @property string $aclId
 * @property string $aclName
 * @property string $aclComment
 * @property string $aclVal1
 * @property string $aclVal2
 * @property string $aclType
 *
 * The followings are the available model relations:
 * @property Content $content
 */
class ContentACL extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contentACL';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contentId, aclId, aclName, aclComment, aclType', 'required'),
			array('contentId', 'length', 'max'=>80),
			array('aclId, aclName', 'length', 'max'=>120),
			array('aclVal1, aclVal2', 'length', 'max'=>20),
			array('aclType', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('contentId, aclId, aclName, aclComment, aclVal1, aclVal2, aclType', 'safe', 'on'=>'search'),
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
			'content' => array(self::BELONGS_TO, 'Content', 'contentId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'contentId' => 'Content',
			'aclId' => 'Acl',
			'aclName' => 'Acl Name',
			'aclComment' => 'Acl Comment',
			'aclVal1' => 'Acl Val1',
			'aclVal2' => 'Acl Val2',
			'aclType' => 'Acl Type',
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

		$criteria->compare('contentId',$this->contentId,true);
		$criteria->compare('aclId',$this->aclId,true);
		$criteria->compare('aclName',$this->aclName,true);
		$criteria->compare('aclComment',$this->aclComment,true);
		$criteria->compare('aclVal1',$this->aclVal1,true);
		$criteria->compare('aclVal2',$this->aclVal2,true);
		$criteria->compare('aclType',$this->aclType,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContentACL the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
