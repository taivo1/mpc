<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public $mpd = false;
	
	public function render($file, $params = array(), $data=array()) {
		
		//we don't want to include those scripts
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		//Yii::app()->clientScript->scriptMap['jquery.yiiactiveform.js'] = false;
		
		if(Yii::app()->request->isAjaxRequest){
                
                    echo parent::renderPartial($file, $params, true, true);
		    
		}else{
		    
		    echo parent::render($file, $params, true);	
		}	
	}
	
}