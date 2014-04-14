<?php
class HttpsFilter extends CFilter {
    protected function preFilter( $filterChain ) {
        if ( !Yii::app()->getRequest()->isSecureConnection ) {
            # Redirect to the secure version of the page.
            $url = 'https://' .
                Yii::app()->getRequest()->serverName .
                Yii::app()->getRequest()->requestUri;
                Yii::app()->request->redirect($url);
            return false;
        }
        return true;
    }
}
